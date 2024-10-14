<?php

namespace app\modules\api\controllers;

use app\models\Candidates;
use app\models\TestSession;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;

class ReportController extends ActiveController
{
    public function init()
    {
        parent::init();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => [getenv('CRANETRX_URL')],
                    'Access-Control-Request-Headers' => ['*'],
                ],
            ],
        ];
    }

    public $modelClass = 'app\models\Candidates';

    public function actionGenerate()
    {
        $request = \Yii::$app->request;

        if (!$request->isPost) {
            throw new \yii\web\MethodNotAllowedHttpException();
        }

        $postData = $request->post();

        $dateRangeFrom = $postData['from'];
        $dateRangeTo = $postData['to'];
        $row = $postData['row'];
        $columns = $postData['columns'];

        $columnHeadings = $this->getReportHeading($row, $columns);

        if ($row['category'] === 'candidate') {
            $candidates = Candidates::find()->where(['between', 'date_created', $dateRangeFrom, $dateRangeTo])->orderBy(['last_name' => SORT_ASC])->all();

            $result = [];

            foreach ($candidates as $candidate) {
                $columnValues = [];

                foreach ($columns as $column) {
                    $columnValues[] = $this->getValueFromObject($column['category'], $column['value'], $candidate);
                }

                $result[] = [
                    'rowStart' => $this->getValueFromObject('candidate', $row['value'], $candidate),
                    'columnValues' => $columnValues
                ];
            }

            return [
                'columnHeadings' => $columnHeadings,
                'rowValues' => $result
            ];
        }

        if ($row['category'] === 'testSession') {
            $testSessions = TestSession::find()->where(['between', 'start_date', $dateRangeFrom, $dateRangeTo])->orderBy(['start_date' => SORT_ASC])->all();

            $result = [];

            foreach ($testSessions as $testSession) {
                $columnValues = [];

                foreach ($columns as $column) {
                    if ($column['category'] === 'candidate') {
                        if ($column['value'] === 'count') {
                            $classStats = $testSession->classStats;

                            $columnValues[] = $classStats['totalCandidates'];
                        }

                        if ($column['value'] === 'countRegular') {
                            $classStats = $testSession->classStats;

                            $columnValues[] = $classStats['totalRegular'];
                        }

                        if ($column['value'] === 'countPracticalOnly') {
                            $classStats = $testSession->classStats;

                            $columnValues[] = $classStats['practicalOnly'];
                        }

                        if ($column['value'] === 'countSw') {
                            $classStats = $testSession->classStats;

                            $columnValues[] = $classStats['sw'];
                        }

                        if ($column['value'] === 'countFx') {
                            $classStats = $testSession->classStats;

                            $columnValues[] = $classStats['fx'];
                        }

                        if ($column['value'] === 'countCoreExamTotal') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['W_EXAM_CORE']['total'];
                        }

                        if ($column['value'] === 'countWrittenFxTotal') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['W_EXAM_TSS']['total'];
                        }

                        if ($column['value'] === 'countWrittenSwTotal') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['W_EXAM_TLL']['total'];
                        }

                        if ($column['value'] === 'countPracticalFxTotal') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['P_TELESCOPIC_TSS']['total'];
                        }

                        if ($column['value'] === 'countPracticalSwTotal') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['P_TELESCOPIC_TLL']['total'];
                        }

                        if ($column['value'] === 'countCoreExamPass') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['W_EXAM_CORE']['pass'];
                        }

                        if ($column['value'] === 'countWrittenFxPass') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['W_EXAM_TSS']['pass'];
                        }

                        if ($column['value'] === 'countWrittenSwPass') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['W_EXAM_TLL']['pass'];
                        }

                        if ($column['value'] === 'countPracticalFxPass') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['P_TELESCOPIC_TSS']['pass'];
                        }

                        if ($column['value'] === 'countPracticalSwPass') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['P_TELESCOPIC_TLL']['pass'];
                        }

                        if ($column['value'] === 'countCoreExamFail') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['W_EXAM_CORE']['fail'];
                        }

                        if ($column['value'] === 'countWrittenFxFail') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['W_EXAM_TSS']['fail'];
                        }

                        if ($column['value'] === 'countWrittenSwFail') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['W_EXAM_TLL']['fail'];
                        }

                        if ($column['value'] === 'countPracticalFxFail') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['P_TELESCOPIC_TSS']['fail'];
                        }

                        if ($column['value'] === 'countPracticalSwFail') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['P_TELESCOPIC_TLL']['fail'];
                        }

                        if ($column['value'] === 'countPracticalFxDecline') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['P_TELESCOPIC_TSS']['decline'];
                        }

                        if ($column['value'] === 'countPracticalSwDecline') {
                            $gradeStats = $testSession->gradeStats;

                            $columnValues[] = $gradeStats['P_TELESCOPIC_TLL']['decline'];
                        }
                    }

                    if ($column['category'] === 'user') {
                        if ($column['value'] === 'instructor') {
                            $columnValues[] = $testSession->instructorName;
                        }

                        if ($column['value'] === 'proctor') {
                            $columnValues[] = $testSession->proctorName;
                        }

                        if ($column['value'] === 'testCoordinator') {
                            $columnValues[] = $testSession->testCoordinatorName;
                        }

                        if ($column['value'] === 'practicalExaminer') {
                            $columnValues[] = $testSession->staffName;
                        }
                    }

                    if ($column['category'] === 'testSession') {
                        if ($column['value'] === 'name') {
                            $columnValues[] = $testSession->getFullTestSessionDescription(true);
                        }
                    }

                    if ($column['category'] === 'testSite') {
                        if ($column['value'] === 'name') {
                            $columnValues[] = $testSession->testSite->testSiteName;
                        }
                    }
                }

                $result[] = [
                    'rowStart' => $this->getValueFromObject('testSession', $row['value'], $testSession),
                    'columnValues' => $columnValues
                ];
            }

            return [
                'columnHeadings' => $columnHeadings,
                'rowValues' => $result
            ];
        }

        return [];
    }

    private function getColumnDesc($category, $value)
    {
        if ($category === 'candidate') {
            if ($value === 'fullName') {
                return 'Full Name';
            }

            if ($value === 'email') {
                return 'Email';
            }

            if ($value === 'phone') {
                return 'Phone';
            }

            if ($value === 'mobilePhone') {
                return 'Mobile Phone';
            }

            if ($value === 'customerCharges') {
                return 'Customer Charges';
            }

            if ($value === 'totalPayment') {
                return 'Amount Paid';
            }

            if ($value === 'totalAmountOwed') {
                return 'Amount Owed';
            }

            if ($value === 'count') {
                return 'No. of Candidates (Total)';
            }

            if ($value === 'countRegular') {
                return 'No. of Candidates (Regular Only)';
            }

            if ($value === 'countPracticalOnly') {
                return 'No. of Candidates (Practical Only)';
            }

            if ($value === 'countSw') {
                return 'No. of Candidates (SW Cab)';
            }

            if ($value === 'countFx') {
                return 'No. of Candidates (FX Cab)';
            }

            if ($value === 'countCoreExamTotal') {
                return 'Count (Total Core Exam)';
            }

            if ($value === 'countWrittenFxTotal') {
                return 'Count (Total Written FX Exam)';
            }

            if ($value === 'countWrittenxSwPass') {
                return 'Count (Total Written SW Exam)';
            }

            if ($value === 'countPracticalFxTotal') {
                return 'Count (Total Practical FX Exam)';
            }

            if ($value === 'countPracticalSwTotal') {
                return 'Count (Total Practical SW Exam)';
            }
            
            if ($value === 'countCoreExamPass') {
                return 'Count (Passed Core Exam)';
            }

            if ($value === 'countWrittenFxPass') {
                return 'Count (Passed Written FX Exam)';
            }

            if ($value === 'countWrittenxSwPass') {
                return 'Count (Passed Written SW Exam)';
            }

            if ($value === 'countPracticalFxPass') {
                return 'Count (Passed Practical FX Exam)';
            }

            if ($value === 'countPracticalSwPass') {
                return 'Count (Passed Practical SW Exam)';
            }

            if ($value === 'countCoreExamFail') {
                return 'Count (Failed Core Exam)';
            }

            if ($value === 'countWrittenFxFail') {
                return 'Count (Failed Written FX Exam)';
            }

            if ($value === 'countWrittenxSwFail') {
                return 'Count (Failed Written SW Exam)';
            }

            if ($value === 'countPracticalFxFail') {
                return 'Count (Failed Practical FX Exam)';
            }

            if ($value === 'countPracticalSwFail') {
                return 'Count (Failed Practical SW Exam)';
            }

            if ($value === 'countPracticalFxDecline') {
                return 'Count (Declined Practical FX Exam)';
            }

            if ($value === 'countPracticalSwDecline') {
                return 'Count (Declined Practical SW Exam)';
            }

            if ($value === 'gradeCoreExam') {
                return 'Grade Core Exam';
            }

            if ($value === 'gradeWrittenFx') {
                return 'Grade Written FX';
            }

            if ($value === 'gradeWrittenSw') {
                return 'Grade Written SW';
            }

            if ($value === 'gradePracticalFx') {
                return 'Grade Practical FX';
            }

            if ($value === 'gradePracticalSw') {
                return 'Grade Practical SW';
            }
        }

        if ($category === 'testSession') {
            if ($value === 'name') {
                return 'Class Date & Location';
            }
        }

        if ($category === 'testSite') {
            if ($value === 'name') {
                return 'Test Site';
            }
        }

        if ($category === 'user') {
            if ($value === 'instructor') {
                return 'Instructor';
            }

            if ($value === 'testCoordinator') {
                return 'Test Coordinator';
            }

            if ($value === 'proctor') {
                return 'Proctor';
            }

            if ($value === 'practicalExaminer') {
                return 'Practical Examiner';
            }
        }
    }

    private function getReportHeading($row, $columns)
    {
        $result = [$this->getColumnDesc($row['category'], $row['value'])];

        foreach ($columns as $column) {
            $result[] = $this->getColumnDesc($column['category'], $column['value']);
        }

        return $result;
    }

    private function getValueFromObject($category, $valueStr, $obj)
    {

        $gradeValues = [
            '0' => 'Fail',
            '1' => 'Pass',
            '2' => 'Did Not Test',
            '3' => 'SD'
        ];

        if ($category === 'candidate') {
            if ($valueStr === 'fullName') {
                return $obj->last_name . ', ' . $obj->first_name;
            }

            if ($valueStr === 'email') {
                return $obj->email;
            }

            if ($valueStr === 'phone') {
                return $obj->phone;
            }

            if ($valueStr === 'mobilePhone') {
                return $obj->cellNumber;
            }
    
            if ($valueStr === 'customerCharges') {
                $transactionTotals = $obj->transactionTotals;
    
                return $transactionTotals['totalNetPayable'];
            }

            if ($valueStr === 'totalPayment') {
                $transactionTotals = $obj->transactionTotals;
    
                return $transactionTotals['totalPayment'];
            }

            if ($valueStr === 'totalAmountOwed') {
                $transactionTotals = $obj->transactionTotals;

                return $transactionTotals['totalAmountOwed'];
            }

            if ($valueStr === 'gradeCoreExam') {
                $grades = $obj->previousGrades;

                if (isset($grades['W_EXAM_CORE'])) {
                    return $gradeValues[$grades['W_EXAM_CORE']];
                }
                return null;
            }

            if ($valueStr === 'gradeWrittenFx') {
                $grades = $obj->previousGrades;

                if (isset($grades['W_EXAM_TLL'])) {
                    return $gradeValues[$grades['W_EXAM_TLL']];
                }
                return null;
            }

            if ($valueStr === 'gradeWrittenSw') {
                $grades = $obj->previousGrades;

                if (isset($grades['W_EXAM_TLL'])) {
                    return $gradeValues[$grades['W_EXAM_TLL']];
                }
                return null;
            }

            if ($valueStr === 'gradePracticalFx') {
                $grades = $obj->previousGrades;

                if (isset($grades['P_TELESCOPIC_TSS'])) {
                    return $gradeValues[$grades['P_TELESCOPIC_TSS']];
                }
                return null;
            }

            if ($valueStr === 'gradePracticalSw') {
                $grades = $obj->previousGrades;

                if (isset($grades['P_TELESCOPIC_TLL'])) {
                    return $gradeValues[$grades['P_TELESCOPIC_TLL']];
                }
                return null;
            }
        }

        if ($category === 'testSession') {
            if ($valueStr === 'name') {
                return $obj->getFullTestSessionDescription(true);
            }
        }

        return null;
    }
}
