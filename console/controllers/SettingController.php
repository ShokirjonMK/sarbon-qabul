<?php

namespace console\controllers;

use common\models\AuthAssignment;
use common\models\Direction;
use common\models\DirectionSubject;
use common\models\Drift;
use common\models\DriftCourse;
use common\models\DriftForm;
use common\models\Exam;
use common\models\ExamSubject;
use common\models\Message;
use common\models\Options;
use common\models\Questions;
use common\models\SendMessage;
use common\models\Std;
use common\models\Student;
use common\models\StudentDtm;
use common\models\StudentGroup;
use common\models\StudentOferta;
use common\models\StudentPerevot;
use common\models\User;
use Yii;
use yii\console\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\httpclient\Client;

class SettingController extends Controller
{

    public function actionIk0()
    {
        $text = 'Hurmatli abituriyent! \n\t\n Sizni “SARBON UNIVERSITETI”ga talabalikka tavsiya etilganingiz bilan tabriklaymiz! \n\t\n  To\'lov shartnomasini https://qabul.sarbon.university qabul tizimi orqali yuklab olishingiz mumkin. Shoshiling! bizda o\'quv jarayonlari kunduzgi va kechki taʼlim shakli talabalari uchun 9-sentyabrdan, sirtqi taʼlim shakli talabalari uchun 16-sentyabrdan boshlanadi.  \n\t\n Manzil: Toshkent sh., Olmazor t., Xastimom MFY, Zarqaynar ko\'chasi, 10-uy. \n Aloqa markazi: 78 888 22 88 \n So\'ngi yangiliklar rasmiy telegram kanalimizda: https://t.me/perfect_university';
        $phone = '+998 (94) 505-52-50';
        Message::sendedSms($phone , $text);
    }

    public function actionIk1()
    {
        $students = Student::find()
            ->andWhere(['in' , 'id' , StudentPerevot::find()
                ->select('student_id')
                ->andWhere(['is_deleted' => 0])
                ->andWhere(['<>' , 'file_status' , 3])
            ])->all();

        $text = 'Hurmatli abituriyent! \n\t\n Sizni “SARBON UNIVERSITETI”ga talabalikka tavsiya etilganingiz bilan tabriklaymiz! \n\t\n  To\'lov shartnomasini https://qabul.sarbon.university qabul tizimi orqali yuklab olishingiz mumkin. Shoshiling! bizda o\'quv jarayonlari kunduzgi va kechki taʼlim shakli talabalari uchun 9-sentyabrdan, sirtqi taʼlim shakli talabalari uchun 16-sentyabrdan boshlanadi.  \n\t\n Manzil: Toshkent sh., Olmazor t., Xastimom MFY, Zarqaynar ko\'chasi, 10-uy. \n Aloqa markazi: 78 888 22 88 \n So\'ngi yangiliklar rasmiy telegram kanalimizda: https://t.me/perfect_university';

        if (count($students)) {
            $i = 0;
            foreach ($students as $student) {
                $phone = $student->username;
                Message::sendedSms($phone , $text);
                echo $i++."\n";
            }
        }
    }

    public function actionIk2()
    {
        $students = Student::find()
            ->andWhere(['in' , 'id' , StudentDtm::find()
                ->select('student_id')
                ->andWhere(['is_deleted' => 0])
                ->andWhere(['<>' , 'file_status' , 3])
            ])->all();

        $text = 'Hurmatli abituriyent! \n\t\n Sizni “SARBON UNIVERSITETI”ga talabalikka tavsiya etilganingiz bilan tabriklaymiz! \n\t\n  To\'lov shartnomasini https://qabul.sarbon.university qabul tizimi orqali yuklab olishingiz mumkin. Shoshiling! bizda o\'quv jarayonlari kunduzgi va kechki taʼlim shakli talabalari uchun 9-sentyabrdan, sirtqi taʼlim shakli talabalari uchun 16-sentyabrdan boshlanadi.  \n\t\n Manzil: Toshkent sh., Olmazor t., Xastimom MFY, Zarqaynar ko\'chasi, 10-uy. \n Aloqa markazi: 78 888 22 88 \n So\'ngi yangiliklar rasmiy telegram kanalimizda: https://t.me/perfect_university';

        if (count($students)) {
            $i = 0;
            foreach ($students as $student) {
                $phone = $student->username;
                Message::sendedSms($phone , $text);
                echo $i++."\n";
            }
        }
    }

    public function actionIk3()
    {
        $students = Student::find()
            ->andWhere(['in' , 'id' , Exam::find()
                ->select('student_id')
                ->andWhere(['is_deleted' => 0])
            ])->all();

        $text = 'Hurmatli abituriyent! \n\t\n Sizni “SARBON UNIVERSITETI”ga talabalikka tavsiya etilganingiz bilan tabriklaymiz! \n\t\n  To\'lov shartnomasini https://qabul.sarbon.university qabul tizimi orqali yuklab olishingiz mumkin. Shoshiling! bizda o\'quv jarayonlari kunduzgi va kechki taʼlim shakli talabalari uchun 9-sentyabrdan, sirtqi taʼlim shakli talabalari uchun 16-sentyabrdan boshlanadi.  \n\t\n Manzil: Toshkent sh., Olmazor t., Xastimom MFY, Zarqaynar ko\'chasi, 10-uy. \n Aloqa markazi: 78 888 22 88 \n So\'ngi yangiliklar rasmiy telegram kanalimizda: https://t.me/perfect_university';

        if (count($students)) {
            $i = 0;
            foreach ($students as $student) {
                $phone = $student->username;
                Message::sendedSms($phone , $text);
                echo $i++."\n";
            }
        }
    }


    public function actionPhone()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $inputFileName = __DIR__ . '/excels/sms.xlsx';
        $spreadsheet = IOFactory::load($inputFileName);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $bt = 0;
        foreach ($data as $key => $row) {
            if ($key != 0) {
                $phone = $row[0];

                $new = new SendMessage();
                $new->phone = $phone;
                $new->status = 0;
                $new->save(false);

                $bt++;
                echo $bt."\n";
            }
        }


        if (count($errors) == 0) {
            $transaction->commit();
            echo "tugadi.";
        } else {
            $transaction->rollBack();
            dd($errors);
        }
    }


    public function actionSendMessage()
    {
        $text2 = 'Hurmatli abituriyent! \n\t\n Siz Sarbon universitetiga hujjat topshirishni boshlab qo\'ydingiz. Lekin, yakuniga yetkazmadingiz. Arizani 15-oktabrga qadar https://qabul.sarbon.university tizimi orqali yakuniga yetkazing va ta\'lim shakli va yo\'nalishingizni tanlang.\n\t\n Muhim ma\'lumotlardan bexabar qolmaslik uchun universitet rasmiy telegram kanaliga obuna bo\'lishni unutmang: https://t.me/sarbonuniversity, \n Qabul: https://qabul.sarbon.university, \n Aloqa markazi: 78 888 22 88';

//        $text2 = 'Hurmatli abituriyent! \n\t\n Siz Sarbon universitetiga hujjat topshirdingiz. \n Muhim ma\'lumotlardan bexabar qolmaslik uchun universitet rasmiy telegram kanaliga obuna bo\'lishni unutmang: https://t.me/sarbonuniversity, \n Qabul: https://qabul.sarbon.university , \n Aloqa markazi: 78 888 22 88';

        $text1 = 'Hurmatli abituriyent! \n\t\n Toshkent shahrida yangi tashkil etilgan Sarbon universiteti oʻz faoliyatini boshladi. \n Qabul davom etmoqda. Quyida yoʻnalishlar bilan tanishing: \n https://telegra.ph/Bakalavriat-talim-yonalishlar-09-25 \n\t\n Qabul: https://qabul.sarbon.university \n Aloqa markazi: 78 888 22 88';

        $query = SendMessage::find()
            ->where(['status' => 0])
            ->all();
        foreach ($query as $item) {
            $phone = '+'.$item->phone;
            $result = Message::sendedSms($phone , $text1);
            if ($result == 'Request is received') {
                echo $item->id."\n";
                $item->status = 1;
                $item->push_time = time();
                $item->save(false);
            }
        }
    }

    public function actionDel()
    {
        $text1 = 'Hurmatli abituriyent! \n\t\n Toshkent shahrida yangi tashkil etilgan Sarbon universiteti oʻz faoliyatini boshladi. \n Qabul davom etmoqda. Quyida yoʻnalishlar bilan tanishing: \n https://telegra.ph/Bakalavriat-talim-yonalishlar-09-25 \n\t\n Qabul: https://qabul.sarbon.university \n Aloqa markazi: 78 888 22 88';

        $phone = '+998945055250';
        $result = Message::sendedSms($phone , $text1);
        if ($result == 'Request is received') {
            echo 121212;
        }
    }



    public function actionS1()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $inputFileName = __DIR__ . '/excels/10.10.2024 (2).xlsx';
        $spreadsheet = IOFactory::load($inputFileName);
        $data = $spreadsheet->getActiveSheet()->toArray();

//        $direction = 'Psixologiya';
//        $text = 'Hurmatli abituriyent! \n\t\n Siz Sarbon unversiteti '.$direction.' yo\'nalishi talabasi bo\'lish imkoniyati bor! \n\t\n Imkoniyatni qo’ldan boy bermang! \n Quyidagi havola orqali ro‘yxatdan o‘ting. \n http://tashkent.sarbon.university \n\t\n Ma’lumot uchun: +998788882288';

        $text1 = 'Hurmatli abituriyent! \n\t\n Toshkent shahrida yangi tashkil etilgan Sarbon universiteti oʻz faoliyatini boshladi. \n Qabul davom etmoqda. Quyida yoʻnalishlar bilan tanishing: \n https://telegra.ph/Yonalishlar-10-12 \n\t\n Qabul: https://t.me/SarbonUzQabulBot \n Aloqa markazi: 99 232 80 20';

        $phone = '+998945055250';
        $result = Message::sendedSms($phone , $text1);

        dd($result);
//        $bt = 0;
//        foreach ($data as $key => $row) {
//            if ($row[0] > 0) {
//                $phone = $row[0];
//                $result = Message::sendedSms($phone , $text1);
//                $bt++;
//                echo $result." - ".$bt."\n";
//            } else {
//                break;
//            }
//        }


        if (count($errors) == 0) {
            $transaction->commit();
            echo "tugadi.";
        } else {
            $transaction->rollBack();
            dd($errors);
        }
    }


    public function actionS2()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $inputFileName = __DIR__ . '/excels/10.10.2024 (3).xlsx';
        $spreadsheet = IOFactory::load($inputFileName);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $direction = 'Iqtisodiyot';
        $text = 'Hurmatli abituriyent! \n\t\n Siz Sarbon unversiteti '.$direction.' yo\'nalishi talabasi bo\'lish imkoniyati bor! \n\t\n Imkoniyatni qo’ldan boy bermang! \n Quyidagi havola orqali ro‘yxatdan o‘ting. \n https://t.me/SarbonUzQabulBot \n\t\n Ma’lumot uchun: +998992328020';


        $bt = 0;
        foreach ($data as $key => $row) {
            if ($key != 0) {
                $phone = '+998'.$row[0];
                $result = Message::sendedSms($phone , $text);
                $bt++;
                echo $result." - ".$bt."\n";
            }
        }


        if (count($errors) == 0) {
            $transaction->commit();
            echo "tugadi.";
        } else {
            $transaction->rollBack();
            dd($errors);
        }
    }
}
