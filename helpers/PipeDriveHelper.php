<?php
namespace app\helpers;

use \Yii;
use app\models\AppConfig;
use app\models\Candidates;

class PipeDriveHelper
{
    public static function getApiToken()
    {
        $config = AppConfig::findOne(['code' => 'PIPEDRIVE_API_KEY']);
        if (!isset($config) || empty($config->val)) {
            return false;
        }

        return $config->val;
    }

    public static function getUri()
    {
        return getenv('PIPEDRIVE_URL') . '/v1/';
    }

    public static function sendRequest($requestType = 'GET', $route = '/', $queryParams = null, $jsonPayload = null)
    {
        $apiToken = self::getApiToken();
        if (!$apiToken) {
            return false;
        }

        $client = new \GuzzleHttp\Client(['base_uri' => self::getUri()]);

        $body = [
            'query' => [
                'api_token' => $apiToken
            ]
        ];

        if (isset($queryParams)) {
            $body['query'] = array_merge($body['query'], $queryParams);
        }

        if (isset($jsonPayload)) {
            $body['json'] = $jsonPayload;
        }

        $resp = $client->request($requestType, $route, $body);

        return json_decode($resp->getBody()->getContents(), true)['data'];
    }

    public static function getStages()
    {
        return self::sendRequest('GET', 'stages');
    }

    public static function findOrCreatePerson($personArr)
    {
        $personData = self::sendRequest('GET', 'persons/search', [
            'term' => $personArr['name']
        ]);

        if (isset($personData) && isset($personData['items']) && !empty($personData['items'])) {
            $personId = $personData['items'][0]['item']['id'];
        } else {
            $personData = self::sendRequest('POST', 'persons', null, $personArr);
            $personId = $personData['id'];
        }
        
        return $personId;
    }

    public static function findOrCreateOrganization($candidate)
    {
        $companyData = self::sendRequest('GET', 'organizations/search', [
            'term' => $candidate->company_name
        ]);

        if (isset($companyData['items']) && !empty($companyData['items'])) {
            $companyId = $companyData['items'][0]['item']['id'];
        } else {
            $companyData = self::sendRequest('POST', 'organizations', null, [
                'name' => $candidate->company_name
            ]);
            $companyId = $companyData['id'];
        }

        return $companyId;
    }

    public static function findProduct($applicationType)
    {
        $productData = self::sendRequest('GET', 'products/search', [
            'term' => $applicationType->name
        ]);

        $productId = null;
        if (isset($productData['items']) && !empty($productData['items'])) {
            foreach ($productData['items'] as $product) {
                $productObj = $product['item'];
                if ($productObj['code'] === $applicationType->keyword) {
                    $productId = $productObj['id'];
                    $productData = self::sendRequest('PUT', 'products/' . $productId, null, [
                        'name' => $applicationType->name,
                        'code' => $applicationType->keyword,
                        'prices' => [
                            [
                                'currency' => 'USD',
                                'price' => $applicationType->price
                            ]
                        ]
                    ]);
                    break;
                }
            }
        }

        return [
            'productId' => $productId,
            'productData' => $productData
        ];
    }

    public static function createProduct($applicationType)
    {
        return self::sendRequest('POST', 'products', null, [
            'name' => $applicationType->name,
            'code' => $applicationType->keyword,
            'prices' => [
                [
                    'currency' => 'USD',
                    'price' => $applicationType->price
                ]
            ]
        ]);
    }

    public static function findOrCreateProduct($applicationType)
    {
        $data = self::findProduct($applicationType);
        $productId = $data['productId'];
        $productData = $data['productData'];

        if (!isset($productId)) {
            $productData = self::createProduct($applicationType);
        }

        if (isset($productData['prices']) && !empty($productData['prices'])) {
            $newPricesArray = [];
            foreach ($productData['prices'] as $priceData) {
                $newPricesArray[$priceData['currency']] = $priceData;
            }
            $productData['prices'] = $newPricesArray;
        }
        
        return $productData;
    }

    public static function addParticipantToDeal($dealId, $personId)
    {
        self::sendRequest('POST', "deals/$dealId/participants", null, [
            'id' => $dealId,
            'person_id' => $personId
        ]);

        return true;
    }

    public static function addProductToDeal($dealId, $product)
    {
        self::sendRequest('POST', "deals/$dealId/products", null, [
            'id' => $dealId,
            'product_id' => $product['id'],
            'item_price' => $product['prices']['USD']['price'],
            'quantity' => 1
        ]);

        return true;
    }

    public static function postDeal($candidate)
    {
        $product = self::findOrCreateProduct($candidate->applicationType);
        $productId = $product['id'];

        $candidateArr = [
            'name' => $candidate->fullName,
            'email' => $candidate->email,
            'phone' => $candidate->phone
        ];

        $personId = self::findOrCreatePerson($candidateArr);
        $contactPersonId = null;

        $contactPersonSearchable = isset($candidate->contact_person) && $candidate->contact_person !== '' && trim($candidate->contact_person) !== '';

        if ($contactPersonSearchable) {
            $contactPersonId = self::findOrCreatePerson([
                'name' => $candidate->contact_person,
                'email' => $candidate->contactEmail,
                'phone' => $candidate->company_phone
            ]);
        }

        $orgId = null;

        $title = $candidate->fullName . ' deal';

        $payload = [];

        if (isset($contactPersonId)) {
            $payload['person_id'] = $contactPersonId;
        }

        if ($candidate->company_name) {
            $orgId = self::findOrCreateOrganization($candidate);
            $title = $candidate->company_name . ' deal';
            $payload['org_id'] = $orgId;
        }

        $payload['title'] = $title;

        $config = AppConfig::findOne(['code' => 'PIPEDRIVE_API_KEY']);
        if (!isset($config)) {
            return false;
        }
        $apiToken = $config->val;

        $stageId = null;
        $stageIdConfig = AppConfig::findOne(['code' => 'PIPEDRIVE_INITIAL_STAGE']);
        if (isset($stageIdConfig)) {
            $stageId = $stageIdConfig->val ?? null;
        }

        $dealData = self::sendRequest('GET', 'deals/search', [
            'term' => $title,
            'org_id' => $orgId
        ]);

        if (!isset($dealData) || !isset($dealData['items']) || empty($dealData['items'])) {
            if (isset($stageId)) {
                $payload['stage_id'] = $stageId;
            }
            
            $dealData = self::sendRequest('POST', 'deals', null, $payload);
        } else {
            $dealId = $dealData['items'][0]['item']['id'];
            $dealData = self::sendRequest('PUT', 'deals/' . $dealId, null, $payload);
        }

        self::addParticipantToDeal($dealData['id'], $personId);
        self::addProductToDeal($dealData['id'], $product);

        return true;
    }
    public static function callDeal($data)
    {
        $api_token = self::getApiToken();
        $company_domain = 'americancraneschool';

        $candidateArr = [
            'name' => $data['name'],
            'email' =>$data['email'],
           // 'phone' => trim($data['phone'])
        ];

        $personId = self::findOrCreatePerson($candidateArr);

       $arr = array(
           "phone"=> array(
               array(
                   "label"=> "work",
                   "value"=>trim($data['phone']),
                   "primary"=>true
               )

           ),
           "job_title"=>array(
               "label"=> "Call",
               "value"=>$data['date'].' '.$data['time'],
               "primary"=>true
           )
        );

        $url = self::getUri() . 'persons/' . $personId . '?api_token=' . $api_token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arr));

       // echo 'Sending request...' . PHP_EOL;

        $output = curl_exec($ch);
        curl_close($ch);
//        $person_id = strtotime(date('Y-m-d h:i:s'));
//
//        $url = 'https://' . $company_domain . '.pipedrive.com/api/v1/personFields?api_token=' . $api_token;
//        $deal = array(
//
//            'name' =>$data['name'],
//          // "options"=> ["email"=>$data['email']],
//          //  'field_type'=>['phone'=>$data['phone']]
//
//        );
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $deal);
//
//        $output = curl_exec($ch);
//        curl_close($ch);

        $deal = array(
            'title' => "Call back ".$data['phone'],
            'person_id' =>$personId,
           // 'options'=>json_encode(['date'=>$data['date']],true)

        );
        $url = self::getUri() . 'deals?api_token=' . $api_token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $deal);

        $output = curl_exec($ch);
        curl_close($ch);
        
        if($output){
            return 'ok';
        }

        return 'wrong';
    }
}
