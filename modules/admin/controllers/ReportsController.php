<?php

namespace app\modules\admin\controllers;

use yii\filters\AccessControl;

use PhpOffice\PhpSpreadsheet;

use app\models\Candidates;
use app\models\TestSession;
use app\models\TestSite;
use app\models\CandidatePreviousSession;

class ReportsController extends CController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'custom', 'generate'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCustom()
    {
        $apiUrl = getenv('API_HOST_INFO') . getenv('API_URL');

        $this->layout = 'main-react';

        return $this->render('custom', [
            'apiUrl' => $apiUrl
        ]);
    }

    public function actionGenerate($type)
    {
        $params = \Yii::$app->request->queryParams;

        if ($type === 'END_OF_YEAR_ENROLLMENTS') {
            $year = $params['options']['year'];
            $startDate = date_format(date_create_from_format('Y-m-d H:i:s', "$year-01-01 00:00:00"), 'Y-m-d H:i:s');
            $endDate = date_format(date_create_from_format('Y-m-d H:i:s', "$year-12-31 23:59:59"), 'Y-m-d H:i:s');

            $candidates = Candidates::find()->where(['between', 'date_created', $startDate, $endDate])->all();
            $candidatesEnhanced = array_map(function($candidate) {
                return [
                    'id' => $candidate->id,
                    'applicationType' => $candidate->applicationType->name,
                    'mergedFormSetup' => $candidate->mergedFormSetup
                ];
            }, $candidates);

            $result = array_reduce($candidatesEnhanced, function($acc, $candidate) {
                $partialResult = $acc;

                $partialResult['totalCandidates'] = $partialResult['totalCandidates'] + 1;

                if ($candidate['applicationType'] === 'Recert') {
                    $partialResult['totalRecert'] = $partialResult['totalRecert'] + 1;
                }

                $hasFxWritten = isset($candidate['mergedFormSetup']['W_EXAM_TSS']) && $candidate['mergedFormSetup']['W_EXAM_TSS'] === 'on';
                $hasSwWritten = isset($candidate['mergedFormSetup']['W_EXAM_TLL']) && $candidate['mergedFormSetup']['W_EXAM_TLL'] === 'on';
                $hasFxPractical = isset($candidate['mergedFormSetup']['P_EXAM_TSS']) && $candidate['mergedFormSetup']['P_EXAM_TSS'] === 'on';
                $hasSwPractical = isset($candidate['mergedFormSetup']['P_EXAM_TLL']) && $candidate['mergedFormSetup']['P_EXAM_TLL'] === 'on';

                $hasFx = $hasFxWritten || $hasFxPractical;
                $hasSw = $hasSwWritten || $hasSwPractical;

                if ($hasFx && $hasSw) {
                    $partialResult['totalBoth'] = $partialResult['totalBoth'] + 1;
                } elseif ($hasFx) {
                    $partialResult['totalFxOnly'] = $partialResult['totalFxOnly'] + 1;
                } elseif ($hasSw) {
                    $partialResult['totalSwOnly'] = $partialResult['totalSwOnly'] + 1;
                }

                return $partialResult;
            }, [
                'totalCandidates' => 0,
                'totalRecert' => 0,
                'totalFxOnly' => 0,
                'totalSwOnly' => 0,
                'totalBoth' => 0
            ]);

            $result['percentRecert'] = number_format(($result['totalRecert'] / $result['totalCandidates']) * 100, 2) . '%';
            $result['percentFxOnly'] = number_format(($result['totalFxOnly'] / $result['totalCandidates']) * 100, 2) . '%';
            $result['percentSwOnly'] = number_format(($result['totalSwOnly'] / $result['totalCandidates']) * 100, 2) . '%';
            $result['percentBoth'] = number_format(($result['totalBoth'] / $result['totalCandidates']) * 100, 2) . '%';

            $titleRow = ["End of the Year Report - Enrollments (${year})"];
            $columnNamesRow = ['', 'Total Recert Candidates', 'Total FX Cab-only Test Takers', 'Total SW Cab-only Test Takers', 'Total FX & SW Cab Test Takers', 'Total Candidates'];
            $valuesRow = ['Total:', $result['totalRecert'], $result['totalFxOnly'], $result['totalSwOnly'], $result['totalBoth'], $result['totalCandidates']];
            $percentagesRow = ['Percent', $result['percentRecert'], $result['percentFxOnly'], $result['percentSwOnly'], $result['percentBoth'], ''];

            $spreadSheetArr = [$titleRow, $columnNamesRow, $valuesRow, $percentagesRow];

            $filename = '/tmp/year-end-report-enrollment.xlsx';
            file_exists($filename) && unlink($filename);

            $spreadsheet = new PhpSpreadsheet\Spreadsheet();
            $spreadsheet->removeSheetByIndex(0);

            $worksheet = new PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $year);
            $worksheet->fromArray($spreadSheetArr);

            $spreadsheet->addSheet($worksheet, 0);
            $spreadsheet->setActiveSheetIndex(0);
            $spreadsheet->getActiveSheet()->mergeCells('A1:F1');
            $spreadsheet->getActiveSheet()->getStyle('A1:F2')->applyFromArray([
                'font' => [
                    'bold' => true
                ]
            ]);

            $spreadsheet->getActiveSheet()->getStyle('A3:A4')->applyFromArray([
                'font' => [
                    'bold' => true
                ]
            ]);

            $spreadsheet->getActiveSheet()->getStyle('B4:F4')->applyFromArray([
                'alignment' => [
                    'horizontal' => PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
                ]
            ]);

            $usedColumns = ['A', 'B', 'C', 'D', 'E', 'F'];
            foreach ($usedColumns as $column) {
                $spreadsheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
            }

            $writer = new PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($filename);

            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);

            return \Yii::$app->response->sendFile($filename);
        }

        if ($type === 'END_OF_YEAR_REVIEW') {
            $year = $params['options']['year'];
            $startDate = date_format(date_create_from_format('Y-m-d H:i:s', "$year-01-01 00:00:00"), 'Y-m-d H:i:s');
            $endDate = date_format(date_create_from_format('Y-m-d H:i:s', "$year-12-31 23:59:59"), 'Y-m-d H:i:s');

            $aliases = [
                'Humble ' => 'Humble',
                'Selma ' => 'Selma',
                'La Mirada' => 'La Palma',
                'West Sacramento' => 'Sacramento'
            ];

            $testSites = TestSite::find()->all();
            $citiesById = [];

            foreach ($testSites as $testSite) {
                $city = $testSite->city;

                if (isset($aliases[$city])) {
                    $city = $aliases[$city];
                }

                if (isset($citiesById[$city])) {
                    $citiesById[$city][] = $testSite->id;
                } else {
                    $citiesById[$city] = [$testSite->id];
                }
            }

            $testSessionsByCityByMonth = [];
            $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

            foreach ($citiesById as $city => $testSiteIds) {
                $testSessionsByMonth = [
                    1 => [],
                    2 => [],
                    3 => [],
                    4 => [],
                    5 => [],
                    6 => [],
                    7 => [],
                    8 => [],
                    9 => [],
                    10 => [],
                    11 => [],
                    12 => []
                ];

                $cityTotals = [
                    'W_EXAM_CORE' => [
                        'total' => 0,
                        'pass' => 0,
                        'fail' => 0
                    ],
                    'W_EXAM_TLL' => [
                        'total' => 0,
                        'pass' => 0,
                        'fail' => 0
                    ],
                    'W_EXAM_TSS' => [
                        'total' => 0,
                        'pass' => 0,
                        'fail' => 0
                    ],
                    'P_TELESCOPIC_TLL' => [
                        'total' => 0,
                        'pass' => 0,
                        'fail' => 0,
                        'decline' => 0
                    ],
                    'P_TELESCOPIC_TSS' => [
                        'total' => 0,
                        'pass' => 0,
                        'fail' => 0,
                        'decline' => 0
                    ]
                ];

                foreach ($months as $month) {
                    $testSessions = TestSession::find()->select(['id'])->where(['test_site_id' => $testSiteIds])->andWhere(['>=', 'start_date', $startDate])->andWhere(['<=', 'start_date', $endDate])->andWhere("MONTH(start_date) = $month")->asArray()->all();

                    $testSessionIds = array_map(function($testSession) {
                        return $testSession['id'];
                    }, $testSessions);

                    $candidateRawGrades = CandidatePreviousSession::find()->select(['craneStatus'])->where(['test_session_id' => $testSessionIds])->asArray()->all();

                    $candidateGrades = array_map(function($rawGrade) {
                        $gradeArr = json_decode($rawGrade['craneStatus'], true);
                        return array_reduce($gradeArr, function($acc, $test) {
                            $newAcc = $acc;
                            $newAcc[$test['key']] = $test['val'];

                            return $newAcc;
                        }, []);
                    }, $candidateRawGrades);

                    $gradesByExam = [
                        'W_EXAM_CORE' => [
                            'total' => 0,
                            'pass' => 0,
                            'fail' => 0
                        ],
                        'W_EXAM_TLL' => [
                            'total' => 0,
                            'pass' => 0,
                            'fail' => 0
                        ],
                        'W_EXAM_TSS' => [
                            'total' => 0,
                            'pass' => 0,
                            'fail' => 0
                        ],
                        'P_TELESCOPIC_TLL' => [
                            'total' => 0,
                            'pass' => 0,
                            'fail' => 0,
                            'decline' => 0
                        ],
                        'P_TELESCOPIC_TSS' => [
                            'total' => 0,
                            'pass' => 0,
                            'fail' => 0,
                            'decline' => 0
                        ]
                    ];

                    foreach($candidateGrades as $candidateGrade) {
                        foreach($candidateGrade as $test => $grade) {
                            if (array_key_exists($test, $gradesByExam)) {
                                $gradesByExam[$test]['total'] += 1;
                                $cityTotals[$test]['total'] += 1;
    
                                if ($grade === '1') {
                                    $gradesByExam[$test]['pass'] += 1;
                                    $cityTotals[$test]['pass'] += 1;
                                } elseif ($grade === '2' && substr($test, 0, 2) === 'P_') {
                                    $gradesByExam[$test]['decline'] += 1;
                                    $cityTotals[$test]['decline'] += 1;
                                } else {
                                    $gradesByExam[$test]['fail'] += 1;
                                    $cityTotals[$test]['fail'] += 1;
                                }
                            }
                        }
                    }

                    foreach($gradesByExam as $test => $stats) {
                        if ($stats['total'] === 0) {
                            $gradesByExam[$test]['passRate'] = 0;
                        } elseif (isset($stats['decline'])) {
                            $gradesByExam[$test]['passRate'] = (($stats['pass'] + $stats['decline']) / $stats['total']) * 100;
                        } else {
                            $gradesByExam[$test]['passRate'] = ($stats['pass'] / $stats['total']) * 100;
                        }
                    }

                    $testSessionsByMonth[$month] = $gradesByExam;
                }

                foreach($cityTotals as $test => $stats) {
                    if ($stats['total'] === 0) {
                        $cityTotals[$test]['passRate'] = 0;
                    } elseif (isset($stats['decline'])) {
                        $cityTotals[$test]['passRate'] = (($stats['pass'] + $stats['decline']) / $stats['total']) * 100;
                    } else {
                        $cityTotals[$test]['passRate'] = ($stats['pass'] / $stats['total']) * 100;
                    }
                }

                if (isset($testSessionsByCityByMonth[$city])) {
                    array_merge($testSessionsByCityByMonth[$city], $testSessionsByMonth);
                } else {
                    $testSessionsByCityByMonth[$city] = $testSessionsByMonth;
                }

                $testSessionsByCityByMonth[$city]['cityTotals'] = $cityTotals;
            }

            $spreadSheetArr = [];

            foreach($testSessionsByCityByMonth as $city => $results) {
                $cityArr = [
                    [$city],
                    ['Month', 'Total Written Core', 'Pass Written Core', 'Fail Written Core', 'Pass Rate Written Core', 'Total Written FX', 'Pass Written FX', 'Fail Written FX', 'Pass Rate Written FX', 'Total Written SW', 'Pass Written SW', 'Fail Written SW', 'Pass Rate Written SW', 'Total Practical FX', 'Pass Practical FX', 'Fail Practical FX', 'Decline Practical FX', 'Pass Rate Practical FX', 'Total Practical SW', 'Pass Practical SW', 'Fail Practical SW', 'Decline Practical SW', 'Pass Rate Practical SW']
                ];

                foreach($months as $month) {
                    $monthResults = $results[$month];
                    $cityArr[] = [
                        $month,
                        $monthResults['W_EXAM_CORE']['total'],
                        $monthResults['W_EXAM_CORE']['pass'],
                        $monthResults['W_EXAM_CORE']['fail'],
                        $monthResults['W_EXAM_CORE']['passRate'],
                        $monthResults['W_EXAM_TLL']['total'],
                        $monthResults['W_EXAM_TLL']['pass'],
                        $monthResults['W_EXAM_TLL']['fail'],
                        $monthResults['W_EXAM_TLL']['passRate'],
                        $monthResults['W_EXAM_TSS']['total'],
                        $monthResults['W_EXAM_TSS']['pass'],
                        $monthResults['W_EXAM_TSS']['fail'],
                        $monthResults['W_EXAM_TSS']['passRate'],
                        $monthResults['P_TELESCOPIC_TLL']['total'],
                        $monthResults['P_TELESCOPIC_TLL']['pass'],
                        $monthResults['P_TELESCOPIC_TLL']['fail'],
                        $monthResults['P_TELESCOPIC_TLL']['decline'],
                        $monthResults['P_TELESCOPIC_TLL']['passRate'],
                        $monthResults['P_TELESCOPIC_TSS']['total'],
                        $monthResults['P_TELESCOPIC_TSS']['pass'],
                        $monthResults['P_TELESCOPIC_TSS']['fail'],
                        $monthResults['P_TELESCOPIC_TSS']['decline'],
                        $monthResults['P_TELESCOPIC_TSS']['passRate']
                    ];
                }

                $cityTotals = $results['cityTotals'];

                $cityArr[] = [
                    'Totals',
                    $cityTotals['W_EXAM_CORE']['total'],
                    $cityTotals['W_EXAM_CORE']['pass'],
                    $cityTotals['W_EXAM_CORE']['fail'],
                    $cityTotals['W_EXAM_CORE']['passRate'],
                    $cityTotals['W_EXAM_TLL']['total'],
                    $cityTotals['W_EXAM_TLL']['pass'],
                    $cityTotals['W_EXAM_TLL']['fail'],
                    $cityTotals['W_EXAM_TLL']['passRate'],
                    $cityTotals['W_EXAM_TSS']['total'],
                    $cityTotals['W_EXAM_TSS']['pass'],
                    $cityTotals['W_EXAM_TSS']['fail'],
                    $cityTotals['W_EXAM_TSS']['passRate'],
                    $cityTotals['P_TELESCOPIC_TLL']['total'],
                    $cityTotals['P_TELESCOPIC_TLL']['pass'],
                    $cityTotals['P_TELESCOPIC_TLL']['fail'],
                    $cityTotals['P_TELESCOPIC_TLL']['decline'],
                    $cityTotals['P_TELESCOPIC_TLL']['passRate'],
                    $cityTotals['P_TELESCOPIC_TSS']['total'],
                    $cityTotals['P_TELESCOPIC_TSS']['pass'],
                    $cityTotals['P_TELESCOPIC_TSS']['fail'],
                    $cityTotals['P_TELESCOPIC_TSS']['decline'],
                    $cityTotals['P_TELESCOPIC_TSS']['passRate']
                ];

                if ($cityTotals['W_EXAM_CORE']['total'] > 0) {
                    $spreadSheetArr = array_merge($spreadSheetArr, $cityArr);
                }
            }

            $filename = '/tmp/year-end-report.xlsx';
            file_exists($filename) && unlink($filename);

            $spreadsheet = new PhpSpreadsheet\Spreadsheet();
            $spreadsheet->removeSheetByIndex(0);

            $worksheet = new PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $year);
            $worksheet->fromArray($spreadSheetArr);

            $spreadsheet->addSheet($worksheet, 0);
            $spreadsheet->setActiveSheetIndex(0);

            $writer = new PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($filename);

            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);

            return \Yii::$app->response->sendFile($filename);
        }

        return $this->redirect('/admin/reports');
    }
}
