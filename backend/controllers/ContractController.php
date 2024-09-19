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
        $student = Student::findOne(['id' => $id]);
        $user = $student->user;

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
        }

        $content = $this->renderPartial($action, [
            'student' => $student,
            'type' => $type,
            'user' => $user
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'marginLeft' => 25,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_DOWNLOAD,
            'content' => $content,
            'cssInline' => 'body { font-family: Times, "Times New Roman", serif; }',
            'filename' => date('YmdHis') . ".pdf",
            'options' => [
                'title' => 'Contract',
                'subject' => 'Student Contract',
                'keywords' => 'pdf, contract, student',
            ],
        ]);

        if ($student->lead_id != null) {
            try {
                $amoCrmClient = Yii::$app->ikAmoCrm;
                $leadId = $student->lead_id;
                $tags = [];
                $message = '';
                $customFields = [];

                $updatedFields = [
                    'pipelineId' => $student->pipeline_id,
                    'statusId' => User::STEP_STATUS_7
                ];

                $updatedLead = $amoCrmClient->updateLead($leadId, $updatedFields, $tags, $message, $customFields);
            } catch (\Exception $e) {
                $errors[] = ['Ma\'lumot uzatishda xatolik STEP 2: ' . $e->getMessage()];
                Yii::$app->session->setFlash('error' , $errors);
                return $this->redirect(['site/index']);
            }
        }

        return $pdf->render();
    }

   public function actionIk177()
   {
        $directions = Direction::find()
            ->where([
                'edu_type_id' => 1,
                'status' => 1,
                'is_deleted' => 0
            ])->all();
        foreach ($directions as $direction) {
            $new = new Direction();
            $new->name_uz = $direction->name_uz;
            $new->name_ru = $direction->name_ru;
            $new->name_en = $direction->name_en;
            $new->edu_year_id = $direction->edu_year_id;
            $new->language_id = $direction->language_id;
            $new->edu_year_type_id = 3;
            $new->edu_year_form_id = $direction->edu_year_form_id;
            $new->edu_form_id = $direction->edu_form_id;
            $new->edu_type_id = 3;
            $new->contract = $direction->contract;
            $new->code = $direction->code;
            $new->course_json = $direction->course_json;
            $new->oferta = $direction->oferta;
            $new->edu_duration = $direction->edu_duration;
            $new->status = 1;
            $new->save(false);
        }
   }

}
