<?php

namespace backend\controllers;

use common\models\Direction;
use common\models\Exam;
use common\models\Message;
use common\models\StudentPerevot;
use common\models\StudentPerevotSearch;
use common\models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Student;
use kartik\mpdf\Pdf;
use Yii;

/**
 * StudentPerevotController implements the CRUD actions for StudentPerevot model.
 */
class ContractController extends Controller
{
    use ActionTrait;
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex($id , $type)
    {
        $errors = [];
        $student = Student::findOne(['id' => $id]);
        $action = '';
        if ($type == 2) {
            if ($student->language_id == 1) {
                $action = 'con2-uz';
            } elseif ($student->language_id == 3) {
                $action = 'con2-ru';
            }
        } elseif ($type == 3) {
            if ($student->language_id == 1) {
                $action = 'con3-uz';
            } elseif ($student->language_id == 3) {
                $action = 'con3-ru';
            }
        } else {
            $errors[] = ['Type not\'g\'ri tanlandi!'];
            Yii::$app->session->setFlash('error' , $errors);
            return $this->redirect(Yii::$app->request->referrer);
        }

        $pdf = Yii::$app->ikPdf;
        $content = $pdf->contract($student , $action);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'marginLeft' => 25,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_DOWNLOAD,
            'content' => $content,
            'cssInline' => '
                 @font-face {
                    font-family: "Times New Roman";
                    src: url("/path/to/times-new-roman.ttf") format("truetype");
                }
                body { 
                    font-family: "Times New Roman"; 
                    color: #000000;
                }
            ',
            'filename' => date('YmdHis') . ".pdf",
            'options' => [
                'title' => 'Contract',
                'subject' => 'Student Contract',
                'keywords' => 'pdf, contract, student',
            ],
        ]);

        return $pdf->render();
    }
}
