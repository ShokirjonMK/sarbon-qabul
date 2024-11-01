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
            $action = 'con2';
        } elseif ($type == 3) {
            $action = 'con3';
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
                body {
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


    public function actionIk11()
    {
        $student = Student::find()
            ->andWhere([
                'in' ,
                'user_id' ,
                User::find()
                    ->select('id')
                    ->where(['step' => 5 , 'status' => 10])
                    ->andWhere(['user_role' => 'student'])
            ])
            ->andWhere([
                'in' ,
                'id' ,
                Exam::find()->select('student_id')
                    ->where([
                        'edu_form_id' => 2,
                        'status' => 3,
                    ])
                    ->andWhere(['>' , 'down_time' , 0])
            ])
            ->all();

        dd(count($student));
    }
}
