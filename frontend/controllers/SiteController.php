<?php

namespace frontend\controllers;

use common\components\AmoCrmClient;
use common\components\AmoCrmSettings;
use common\models\Exam;
use common\models\Flayer;
use common\models\Languages;
use common\models\Std;
use common\models\Student;
use common\models\StudentDtm;
use common\models\StudentPerevot;
use common\models\Target;
use common\models\User;
use common\models\Verify;
use frontend\models\ContractSearch;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\ResetPassword;
use frontend\models\StepOne;
use frontend\models\StepSecond;
use frontend\models\StepThree;
use frontend\models\VerifyEmailForm;
use kartik\mpdf\Pdf;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function beforeAction($action)
    {
        $domen = $_SERVER['HTTP_HOST'];
        if ($domen == "shartnoma.tpu.uz") {
            return $this->redirect('https://shartnoma.tpu.uz/std/index');
        }
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'bot' => ['post'],
                ],
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex($id = null)
    {
        Yii::$app->session->remove('redirected_from_flayer');
        if ($id !== null) {
            $target = Target::findOne($id);
            if ($target) {
                $session = Yii::$app->session;
                $session->set('target_id', $id);
            }
        }
        return $this->render('index');
    }

    public function actionFlayer()
    {
        $session = Yii::$app->session;
        if ($session->has('redirected_from_flayer')) {
            return $this->redirect(['site/index']);
        }
        Flayer::updateItem();
        $session->set('redirected_from_flayer', true);
        return $this->redirect(['site/index']);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['cabinet/index']);
        }

        $this->layout = 'main';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['cabinet/index']);
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }


    public function actionSignUp($id = null)
    {
        $this->layout = 'main';

        if ($id !== null) {
            $target = Target::findOne($id);
            if ($target) {
                $session = Yii::$app->session;
                $session->set('target_id', $id);
            }
        }

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            $result = $model->signup();
            if ($result['is_ok']) {
                return $this->redirect(['verify' , 'id' => $result['user']->get_token]);
            } else {
                Yii::$app->session->setFlash('error' , $result['errors']);
            }
        }

        return $this->render('sign-up', [
            'model' => $model
        ]);
    }

    public function actionResetPassword()
    {
        $this->layout = 'main';

        $model = new ResetPassword();
        if ($model->load(Yii::$app->request->post())) {
            $result = $model->reset();
            if ($result['is_ok']) {
                return $this->redirect(['password-verify' , 'id' => $result['user']->get_token]);
            } else {
                Yii::$app->session->setFlash('error' , $result['errors']);
            }
        }

        return $this->render('reset-password', [
            'model' => $model
        ]);
    }

    public function actionPasswordVerify($id)
    {
        $this->layout = 'main';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $user = $this->findUserActive($id);

        $model = new Verify();
        if ($model->load(Yii::$app->request->post())) {
            $result = Verify::password($user , $model);
            if ($result['is_ok']) {
                Yii::$app->session->setFlash('success');
                return $this->redirect(['cabinet/index']);
            }
            Yii::$app->session->setFlash('error' , $result['errors']);
            return $this->redirect(['password-verify' , 'id' => $result['user']->get_token]);
        }

        return $this->render('password-verify', [
            'model' => $model,
            'user' => $user
        ]);
    }


    public function actionVerify($id)
    {
        $this->layout = 'main';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $user = $this->findUser($id);
        $model = new Verify();
        if ($model->load(Yii::$app->request->post())) {
            $result = Verify::confirm($user , $model);
            if ($result['is_ok']) {
                Yii::$app->session->setFlash('success');
                return $this->redirect(['cabinet/index']);
            }
            Yii::$app->session->setFlash('error' , $result['errors']);
            return $this->redirect(['verify' , 'id' => $result['user']->get_token]);
        }

        return $this->render('verify', [
            'model' => $model,
            'user' => $user
        ]);
    }

    public function actionSendSms($id)
    {
        $this->layout = 'main';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $user = $this->findUser($id);

        $result = Verify::sendSms($user);
        if ($result['is_ok']) {
            Yii::$app->session->setFlash('success');
        } else {
            Yii::$app->session->setFlash('error' , $result['errors']);
        }
        return $this->redirect(['verify' , 'id' => $result['user']->get_token]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    protected function findUser($id)
    {
        $model = User::findOne(['get_token' => $id, 'user_role' => 'student' , 'status' => 9]);
        if ($model) {
            return $model;
        }
        throw new NotFoundHttpException();
    }

    protected function findUserActive($id)
    {
        $model = User::findOne(['get_token' => $id, 'user_role' => 'student' , 'status' => 10]);
        if ($model) {
            return $model;
        }
        throw new NotFoundHttpException();
    }

    public function actionLang($id)
    {
        $model = $this->findLang($id);
        $lang = 'uz';
        if ($model->id == 2) {
            $lang = 'en';
        } elseif ($model->id == 3) {
            $lang = 'ru';
        }
        Yii::$app->session->set("lang" , $lang);
        Yii::$app->language = $lang;
        return $this->redirect(Yii::$app->request->referrer);
    }

    protected function findLang($id)
    {
        $model = Languages::findOne(['id' => $id]);
        if ($model) {
            return $model;
        }
        throw new NotFoundHttpException();
    }

    public function actionContract($key , $type)
    {
        $student = null;
        $query = Exam::findOne(['contract_link' => $key , 'is_deleted' => 0]);
        if (!$query) {
            $query = StudentDtm::findOne(['contract_link' => $key , 'is_deleted' => 0]);
            if (!$query) {
                $query = StudentPerevot::findOne(['contract_link' => $key , 'is_deleted' => 0]);
            }
        }

        if ($query) {
            $student = $query->student;

            $action = '';
            if ($type == 2) {
                $action = 'con2-uz';
            } elseif ($type == 3) {
                $action = 'con3-uz';
            } else {
                $errors[] = ['Type not\'g\'ri tanlandi!'];
                Yii::$app->session->setFlash('error' , $errors);
                return $this->redirect(['site/index']);
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
        } else {
            $errors[] = ['Shartnoma topilmadi.'];
            Yii::$app->session->setFlash('error' , $errors);
            return $this->redirect(['site/index']);
        }
    }


}
