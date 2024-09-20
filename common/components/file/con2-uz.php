<?php
use common\models\Student;
use common\models\Direction;
use common\models\Exam;
use common\models\StudentPerevot;
use common\models\StudentDtm;
use common\models\Course;
use Da\QrCode\QrCode;
use frontend\models\Contract;
use common\models\User;
use common\models\Constalting;

/** @var Student $student */
/** @var Direction $direction */
/** @var User $user */

function ikYear($number) {
    $years = floor($number);

    $months = round(($number - $years) * 12);

    if ($months == 12) {
        $years++;
        $months = 0;
    }

    return "$years yil $months oy";
}
$user = $student->user;
$cons = Constalting::findOne($user->cons_id);
$direction = $student->direction;
$full_name = $student->last_name.' '.$student->first_name.' '.$student->middle_name;
$code = '';
$joy = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$date = '';
$link = '';
$con2 = '';
if ($student->edu_type_id == 1) {
    $contract = Exam::findOne([
        'direction_id' => $direction->id,
        'student_id' => $student->id,
        'status' => 3,
        'is_deleted' => 0
    ]);
    $code = $cons->code.'-Q2UZ';
    $date = date("Y-m-d H:i:s" , $contract->confirm_date);
    $link = $contract->contract_link;
    $con2 = $contract->contract_second;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 2) {
    $contract = StudentPerevot::findOne([
        'direction_id' => $direction->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = $cons->code.'-P2UZ';
    $date = date("Y-m-d H:i:s" , $contract->confirm_date);
    $link = $contract->contract_link;
    $con2 = $contract->contract_second;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 3) {
    $contract = StudentDtm::findOne([
        'direction_id' => $direction->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = $cons->code.'-D2UZ';
    $date = date("Y-m-d H:i:s" , $contract->confirm_date);
    $link = $contract->contract_link;
    $con2 = $contract->contract_second;
    $contract->down_time = time();
    $contract->save(false);
}

$qr = (new QrCode('https://qabul.tpu.uz/site/contract?key=' . $link.'&type=2'))->setSize(120, 120)
    ->setMargin(10);
$img = $qr->writeDataUri();

$lqr = (new QrCode('https://license.gov.uz/registry/da127cfb-12a8-4dd6-b3f8-7516c1e9dd82'))->setSize(100, 100)
    ->setMargin(10);
$limg = $lqr->writeDataUri();


?>


<table width="100%" style="font-family: 'Times New Roman'; font-size: 12px; border-collapse: collapse;">

    <tr>
        <td colspan="4" style="text-align: center">
            <b>
                To‘lov-kontrakt (Ikki tomonlama) asosida mutaxassis tayyorlashga <br>
                <?= $code ?>  &nbsp;  <?= str_replace('.', '', $direction->code) ?> &nbsp; | &nbsp; <?= $contract->id ?> – sonli SHARTNOMA
            </b>
        </td>
    </tr>

    <tr><td>&nbsp;</td></tr>

    <tr>
        <td colspan="2"><?= $date ?></td>
        <td colspan="2" style="text-align: right">Toshkent shahri</td>
    </tr>

    <tr><td>&nbsp;</td></tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            <?= $joy ?> Vazirlar Mahkamasining “Oliy ta’lim muassasalariga o‘qishga qabul qilish, talabalar o‘qishini ko‘chirish, qayta tiklash va o‘qishdan chetlashtirish tartibi to‘g‘risidagi nizomlarni tasdiqlash haqida” 2017-yil 20-iyundagi 393-son qarori, O‘zbekiston Respublikasi oliy va o‘rta maxsus ta’lim vazirining 2012-yil 28-dekabrdagi 508-son buyrug‘i (ro‘yxat raqami 2431, 2013-yil 26-fevral) bilan tasdiqlangan Oliy va o‘rta maxsus, kasb-hunar ta’limi muassasalarida o‘qitishning to‘lov-shartnoma shakli va undan tushgan mablag‘larni taqsimlash tartibi to‘g‘risidagi Nizomga muvofiq, <b>SARBON UNIVERSITETI</b> oliy ta’lim tashkiloti (keyingi o‘rinlarda “Ta’lim muassasasi”) nomidan Ustav asosida ish yurituvchi direktor <b>SOBIRJONOV NODIRJON QODIRJONOVICH</b> birinchi tomondan, <b><?= $full_name ?></b> (keyingi o‘rinlarda “Ta’lim oluvchi”) ikkinchi tomondan, keyingi o‘rinlarda birgalikda “Tomonlar” deb ataluvchilar o‘rtasida mazkur shartnoma quyidagilar haqida tuzildi:
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: center">
            <b>I. SHARTNOMA PREDMETI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 1.1. Ta’lim muassasasi ta’lim xizmatini ko‘rsatishni, Ta’lim oluvchi o‘qish uchun belgilangan to‘lovni o‘z vaqtida amalga oshirishni va tasdiqlangan o‘quv reja bo‘yicha darslarga to‘liq qatnashish va ta’lim olishni o‘z zimmalariga oladi. Ta’lim oluvchining ta’lim ma’lumotlari quyidagicha:
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="padding: 5px;">
            <table width="100%">

                <tr>
                    <td>Ta’lim bosqichi:<?= $joy ?></td>
                    <td><b>Bakalavr</b></td>
                </tr>

                <tr>
                    <td>Ta’lim shakli:<?= $joy ?></td>
                    <td><b><?= $direction->eduForm->name_uz ?></b></td>
                </tr>

                <tr>
                    <td>O‘qish muddati:<?= $joy ?></td>
                    <td><b><?= ikYear($direction->edu_duration) ?></b></td>
                </tr>

                <tr>
                    <td>O‘quv kursi:<?= $joy ?></td>
                    <?php if ($student->edu_type_id == 2) : ?>
                        <td><b><?= Course::findOne(['id' => ($student->course_id + 1)])->name_uz ?></b></td>
                    <?php else: ?>
                        <td><b>1 kurs</b></td>
                    <?php endif; ?>
                </tr>

                <tr>
                    <td>Ta’lim yo‘nalishi:<?= $joy ?></td>
                    <td><b><?= str_replace('.', '', $direction->code).' '.$direction->name_uz ?></b></td>
                </tr>

            </table>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            <?= $joy ?> 1.2. “Ta’lim muassasasi”ga o‘qishga qabul qilingan “Ta’lim oluvchi”lar O‘zbekiston Respublikasining “Ta’lim to‘g‘risida”gi Qonuni va davlat ta’lim standartlarga muvofiq ishlab chiqilgan o‘quv rejalar va fan dasturlari asosida ta’lim oladilar.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>II. TA’LIM XIZMATINI KO‘RSATISH NARXI, TO‘LASH MUDDATI VA TARTIBI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            <?= $joy ?> 2.1. “Ta’lim muassasasi”da o‘qish davrida ta’lim xizmatini ko‘rsatish narxi Respublikada belgilangan Bazaviy hisoblash miqdori o’zgarishiga bog‘liq holda hisoblanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            <?= $joy ?> 2.2. Ushbu shartnoma bo‘yicha ta’lim oluvchini bir yillik o‘qitish uchun to‘lov 8,219,893.00 (sakkiz million ikki yuz o`n to`qqiz ming sakkiz yuz to`qson uch so'm) soʻmni tashkil etadi va quyidagi muddatlarda amalga oshiriladi:
            <br><?= $joy ?> Choraklarga bo‘lib to‘langanda quyidagi muddatlarda:
            <br>
            <ul style="padding-left: 40px;">
                <li style="text-align: justify">
                    belgilangan to‘lov miqdorining kamida 25.00 foizini talabalikka tavsiya etilgan abiturientlar uchun 15.10.2024 gacha, ikkinchi va undan yuqori bosqich talabalar uchun 01.11.2024 gacha;
                </li>
                <li style="text-align: justify">
                    belgilangan to‘lov miqdorining kamida 50.00 foizini 01.01.2025 gacha, 75.00 foizini 01.04.2025 gacha va
                    100.00 foizini 01.06.2025 gacha.
                </li>
            </ul>
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.3. Ushbu shartnomaning 2.2-bandida ko‘zda tutilgan to‘lov muddatlari Tomonlarning o’zaro kelishuvi bilan o’zgartrilishi mumkin.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>III. TA’LIM TO‘LOVINING MIQDORI, TARTIBI VA TO‘LOV SHARTLARI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 3.1. 2024-2025 o‘quv yili uchun shartnoma to‘lovi <?= number_format((int)$contract->contract_price, 0, '', ' ') .' so‘m'?> so‘mni tashkil etadi
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 3.2. Universitet xizmatlarning narxini o‘zgartirish huquqini o‘zida saqlab qoladi. Bunday holatda qo‘shimcha
            kelishuv tuziladi va Tomonlar yangi qiymatni hisobga olgan holda o‘zaro hisob-kitoblarni amalga oshirish majburiyatini
            oladi.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 3.3. O‘qish uchun to‘lov quyidagi tartibda amalga oshiriladi:
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 3.3.1. 2024-yil 1-oktabrga qadar – 25 foizidan kam bo‘lmagan miqdorda.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 3.3.2. 2024-yil 15-dekabrga qadar – 50 foizidan kam bo‘lmagan miqdorda.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 3.3.3. 2025-yil 15-fevralga qadar – 75 foizidan kam bo‘lmagan miqdorda.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  3.3.4. 2025-yil 15-aprelga qadar – 3.1-bandda nazarda tutilgan ta’lim to‘lovining amalga oshirilmagan qismi
            miqdorda.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 3.4. Buyurtmachi tomonidan shartnoma to‘lovini to‘lashda shartnomaning tartib raqami va sanasi, familiyasi, ismi
            va sharifi hamda o‘quv kursi to‘lov topshiriqnomasida to‘liq ko‘rsatiladi. To‘lov kuni Universitetning bank hisob
            raqamiga mablag‘ kelib tushgan kun hisoblanadi.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 3.5. Talaba tegishli fanlar bo‘yicha akademik qarzdorlikni qayta topshirish sharti bilan keyingi kurs (semestr)ga o‘tkazilgan taqdirda, keyingi semestr uchun shartnoma to‘lovi Talaba tomonidan akademik qarzdorlik belgilangan muddatda topshirilishiga qadar amalga oshiriladi.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 3.6. Talaba ushbu Shartnomaning amal qilish muddati davomida o‘quv darslarini o‘zlashtira olmagani, ichki tartib qoidalarini, odob-axloq qoidalarini yoki o‘quv jarayonini buzgani va unga nisbatan o‘qishini to‘xtatish yoki o‘qishdan chetlatish chorasi ko‘rilganligi, uni o‘qish uchun haq to‘lash bo‘yicha moliyaviy majburiyatlardan ozod etmaydi.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 3.7. Shartnoma Universitet tashabbusi bilan Talaba uning hatti-harakatlari (harakatsizligi) sababli talabalar safidan chetlashtirilsa, shartnoma bo‘yicha to‘langan mablag‘lar qaytarilmaydi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>IV. SHARTNOMAGA O‘ZGARTIRISH KIRITISH VA UNI BEKOR QILISH TARTIBI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 4.1. Ushbu Shartnoma shartlari Tomonlar kelishuvi bilan yoki O‘zbekiston Respublikasi qonunchiligiga muvofiq o‘zgartirilishi mumkin. Shartnomaga kiritilgan barcha o‘zgartirish va qo‘shimchalar, agar ular yozma ravishda tuzilgan va Tomonlar yoki ularning vakolatli vakillari tomonidan imzolangan bo‘lsa, haqiqiy hisoblanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  4.2. Ushbu Shartnoma quyidagi hollarda bekor qilinishi mumkin:
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 4.2.1. Tomonlarning kelishuviga binoan;
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 4.2.2. Universitetning tashabbusiga ko‘ra bir tomonlama (2.1.6-bandda nazarda tutilgan hollarda);
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 4.2.3. sudning qonuniy kuchga kirgan qarori asosida;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  4.2.4. shartnoma muddati tugashi munosabati bilan;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 4.2.5. Talaba o‘qishni muvaffaqiyatli tamomlaganligi munosabati bilan;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  4.2.6. Universitet faoliyati tugatilgan taqdirda.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 4.3. Shartnomani Universitetning tashabbusiga ko‘ra bir tomonlama tartibda bekor qilinganida Buyurtmachining yuridik yoki elektron pochta manziliga tegishli xabar yuboriladi va shu bilan Buyurtmachi xabardor qilingan hisoblanadi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>V. FORS-MAJOR</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 5.1. Tomonlardan biri tarafidan shartnomani to‘liq yoki qisman bajarishni imkonsiz qiladigan holatlar, xususan, yong‘in, tabiiy ofat, urush, har qanday harbiy harakatlar, mavjud huquqiy hujjatlarni almashtirish va boshqa mumkin bo‘lgan tomonlarga bog‘liq bo‘lmagan fors-major holatlari shartnoma bo‘yicha majburiyatlarni bajarish muddatlari ushbu holatlarning amal qilish muddatiga mos ravishda uzaytiriladi.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 5.2. Ushbu shartnoma bo‘yicha o‘z majburiyatlarini bajarishga qodir bo‘lmagan tomon ikkinchi tomonni ushbu holatlarni bajarishiga to‘sqinlik qiladigan holatlar yuzaga kelganligi yoki bekor qilinganligi to‘g‘risida darhol xabardor qilishi shart.
            <br>
            <?= $joy ?> Xabarnoma shartnomada ko‘rsatilgan yuridik manzilga yuboriladi va jo‘natuvchi pochta bo‘limi tomonidan tasdiqlanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 5.3. Agar shartnoma tomonlariga bog‘liq bo‘lmagan tarzda sodir bo‘lgan har qanday hodisa, tabiiy ofatlar, urush yoki mamlakatdagi favqulodda holat, davlat hokimiyati organi tomonidan qabul qilingan qaror, uning ijrosi, uning yuzasidan amalga oshirilgan harakatlar (shular bilan cheklanmagan hodisalar) tufayli yuzaga kelgan bo‘lsa, bir tomon ikkinchi tomon oldida ushbu shartnomani bajarmaslik yoki bajarishni kechiktirish oqibatlari uchun javobgar bo‘lmaydi. Ijrosi shu tarzda to‘xtatilgan tomon bunday majburiyatlarni bajarish muddatini tegishli ravishda uzaytirish huquqiga ega bo‘ladi.
        </td>
    </tr>


    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>VI. TOMONLARNING JAVOBGARLIGI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 6.1. Talaba mol-mulk, jihozlar, o‘quv qurollari va hokazoga moddiy zarar yetkazgan taqdirda Universitet oldida to‘liq moddiy javobgarlikni o‘z zimmasiga oladi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 6.2. Ushbu shartnomaning 3.3-bandga muvofiq o‘qish uchun to‘lov kechiktirilgan taqdirda:
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 6.2.1. Talabaning Universitetga kirishi cheklanadi;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 6.2.2. Quyidagilar to‘xtatiladi:
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> - Universitet tomonidan akademik xizmatlar ko‘rsatilishi;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> - Talabani imtixonlarga kiritilishi;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> - har qanday akademik ma’lumotnomalar/sertifikatlar berish;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 6.2.3. Talaba 2.1.6-bandga muvofiq talabalar safidan chiqarilishi mumkin.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  6.3. Universitet shartnoma to‘lovi manbalari uchun javobgarlikni o‘z zimmasiga olmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  6.4. Universitet shartnoma to‘lovini amalga oshirishda yo‘l qo‘yilgan xatolar uchun javobgar bo‘lmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  6.5. Talabaning o‘qishdan chetlashtirilishi yoki talabalar safidan chiqarilishi Buyurtmachi va Talabani ushbu shartnoma bo‘yicha Talabaga ko‘rsatilgan ta’lim xizmatlari uchun haq to‘lash hamda boshqa majburiyatlardan ozod etmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 6.6. Tomonlarning ushbu Shartnomada nazarda tutilmagan javobgarlik choralari O‘zbekiston Respublikasining amaldagi qonunchiligi bilan belgilanadi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>VII. QO‘SHIMCHA SHARTLAR</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 7.1. Universitetning Talabani o‘qishga qabul qilish buyrug‘i Talaba tomonidan barcha kerakli hujjatlarni taqdim etish va shartnomaning 3.3.1-bandiga muvofiq to‘lovni amalga oshirish sharti bilan chiqariladi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 7.2. Talabaga Universitet tomonidan stipendiya to‘lanmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  7.3. Mazkur Shartnomaning 1-bandida nazarda tutilgan majburiyatlar O‘zbekiston Respublikasining amaldagi qonunchiligi talablariga muvofiq, bevosita yoki onlayn tarzda taqdim etilishi mumkin. Akademik ta’lim xizmatlari onlayn tarzda taqdim etilgan taqdirda, Talaba texnik va telekommunikatsiya aloqalari holatining sifati uchun shaxsan javobgardir.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 7.4. Ushbu Shartnoma Tomonlar bir o‘quv yili uchun uning predmetida ko‘rsatilgan maqsadlar uchun o‘z majburiyatlarini to‘liq bajarguniga qadar, lekin 2025-yil 1-iyuldan kechikmagan muddatga qadar tuziladi. Shartnomaning amal qilish muddati tugaganligi qarzdor Tomonlarni o‘z zimmasidagi majburiyatlarini bajarishdan ozod qilmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 7.5. O‘qish davrida Talaba yoki boshqa shaxsga rasmiy hujjatlarning asl nusxalari, shu jumladan o‘rta yoki o‘rta maxsus ta’lim muassasasining bitiruv hujjatlari (attestat/diplom/sertifikat) berilmaydi.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 7.6. Universitet Talabani ishga joylashtirish majburiyatini o‘z zimmasiga olmaydi.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 7.7. Shartnoma to‘lovlari va ularni qaytarish bilan bog‘liq barcha bank xizmatlari Buyurtmachi yoki Talaba tomonidan to‘lanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 7.8. Universitet tomonidan ushbu shartnoma bo‘yicha mablag‘lar qaytarilishi lozim bo‘lgan hollarda mazkur mablag‘lar tegishli hujjat o‘z kuchiga kirgan paytdan boshlab 30 (o‘ttiz) kun ichida qaytariladi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 7.9. Ushbu Shartnomaga kiritilgan har qanday o‘zgartirish va/yoki qo‘shimchalar, agar ular tomonlar tomonidan yozma shaklda rasmiylashtirilgan, imzolangan/muhrlangan bo‘lsagina amal qiladi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 7.10. Tomonlar shartnomada Universitet faksimilesini tegishli imzo sifatida tan olishga kelishib oldilar.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 7.11. Ushbu shartnomadan kelib chiqadigan har qanday nizo yoki kelishmovchiliklarni tomonlar muzokaralar yo‘li bilan hal qilishga intiladi. Kelishuvga erishilmagan taqdirda, nizolar O‘zbekiston Respublikasi qonun hujjatlarida belgilangan tartibda Universitet joylashgan yerdagi sud tomonidan ko‘rib chiqiladi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>VIII. YAKUNIY QOIDALAR</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 8.1. Ushbu shartnoma Tomonlar tomonidan imzolangan paytdan boshlab kuchga kiradi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 8.2. Buyurtmachi va Talaba shartnoma shartlaridan norozi bo‘lgan taqdirda 2024-yil 30-noyabrdan kechiktirmay murojaat qilishi lozim, bunda mazkur sanaga qadar Universitet bilan shartnoma tuzmagan Talaba o‘qishga qabul qilinmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  8.3. O‘zbekiston Respublikasi Prezidentining tegishli farmoniga muvofiq mehnatga haq to‘lashning eng kam miqdori yoki bazaviy hisoblash miqdori o‘zgarganda, shartnoma to‘lovi miqdori navbatdagi semestr boshidan oshiriladi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 8.3. Mazkur shartnomani imzolanishi, o‘zgartirilishi, ijro etilishi, bekor qilinishi yuzasidan Tomonlar o‘rtasida yozishmalar shartnomada ko‘rsatilgan Tomonlarning rasmiy elektron pochta manzillari orqali amalga oshirilishi mumkin va Tomonlar bu tartibda yuborilgan xabarlarning yuridik kuchga ega ekanligini tan oladilar. Elektron pochta manzili o‘zgarganligi to‘g‘risida boshqa tomonni yozma ravishda xabardor qilmagan tomon bu bilan bog‘liq barcha xavflarni o‘z zimmasiga oladi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  8.4. Ushbu Shartnoma o‘zbek tilida, uch asl nusxada, teng yuridik kuchga ega, har bir tomon uchun bir nusxadan tuzildi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <div>
                <table width="100%">

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="4" style="text-align: center;">
                            <b>IX. TOMONLARNING YURIDIK MANZILLARI VA BANK REKVIZITLARI</b>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <b>Universitet</b>
                        </td>
                        <td colspan="2">
                            <b>Talaba</b>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="vertical-align: top">
                            <b>“SARBON UNIVERSITETI” oliy ta’lim tashkiloti</b> <br>
                            <b>Manzil:</b> Toshkent shahri, Yunusobod tumani, Posira MFY, Bog'ishamol ko'chasi, 220-uy <br>
                            <b>H/R:</b> <?= $cons->h_r ?> <br>
                            <b>Bank:</b> “KAPITALBANK” ATB Sirg’ali filiali <br>
                            <b>Bank kodi (MFO):</b> 01042  <br>
                            <b>IFUT (OKED):</b> 85420  <br>
                            <b>STIR (INN):</b> 309477784 <br>
                            <b>Tel:</b> +998 77 129-29-29 <br>
                            <b>Tel:</b> +998 55 500-02-50 <br>
                        </td>
                        <td colspan="2" style="vertical-align: top">
                            <b>F.I.Sh.:</b> <?= $full_name ?> <br>
                            <b>Pasport ma’lumotlari:</b> <?= $student->passport_serial.' '.$student->passport_number ?> <br>
                            <b>JShShIR:</b> <?= $student->passport_pin ?> <br>
                            <b>Ro‘yxatdan o‘tgan tеlefon raqami: </b> <?= $student->user->username ?> <br>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="padding-right: 20px">
                            M.O‘.
                        </td>
                        <td style="text-align: right">
                            <?= $full_name ?>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            Direktor _________________________ SHARIPOV M.T.
                        </td>
                        <td colspan="2">
                            Imzo: _________________________________________
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="vertical-align: top; padding-left: 40px">
                            <img src="<?= $img ?>" width="120px">
                        </td>
                        <td colspan="2" style="vertical-align: top">
                            <img src="<?= $limg ?>" width="120px"> <br>
                            <b>Litsenziya berilgan sana va raqami</b> <br>
                            19.10.2022 <b>№ 043951</b>
                        </td>
                    </tr>



                </table>
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="border: 1px solid #000000; padding: 10px;">
            <b>SANA: </b> &nbsp; <?= $date ?> <br>
            <b>INVOYS RAQAMI: &nbsp; </b> <?= $con2 ?> <br>
            <b>KONTRAKT TO‘LOV MIQDORI: &nbsp; </b> <?= number_format((int)$contract->contract_price, 0, '', ' ') . ' (' . Contract::numUzStr($contract->contract_price) . ')' ?> <br>
            <table width="100%">
                <tr>
                    <td colspan="4">To‘lovni amalga oshirish usullari:</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <?= $joy ?> Yuridik shaxslar va bank kassalari orqali. Bunda To‘lov maqsadida - Invoys raqam. JSHSHIR. Talabaning
                        FISH tartibida yozilgan bo‘lishi talab etiladi
                    </td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="4" style="padding: 5px; border: 2px solid red;">
                        <table width="100%">
                            <tr>
                                <td><?= $con2 ?> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;</td>
                                <td><?= $student->passport_pin ?> &nbsp;&nbsp;&nbsp; |</td>
                                <td class="2" style="text-align: center;"><?= $full_name ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>
                        To‘lov maqsadi belgilangan tartibda to‘ldirilmagan taqdirda to‘lovni qabul qilishga doir muammolar kelib chiqishi
                        mumkin. Shu sababli to‘lov qilish jarayonida to‘lov maqsadini belgilangan tartibda ko‘rsatilishi shart!
                    </td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="2" style="border-top: 2px double #000;">&nbsp;</td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="4"><b>To‘lovlarni amalgi oshirish uchun Universitetning bank hisob ma’lumotlari:</b></td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="4">

                        <table width="100%" style="border-collapse: collapse; border: 1px solid;">
                            <tr>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>Qabul qiluvchi tashkilot nomi:</b></td>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>“SARBON UNIVERSITETI” MCHJ</b></td>
                            </tr>

                            <tr>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>Hisob raqami:</b></td>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b><?= $cons->h_r ?></b></td>
                            </tr>

                            <tr>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>Bank kodi (MFO):</b></td>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>01042</b></td>
                            </tr>

                            <tr>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>Bank nomi:</b></td>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>“KAPITALBANK” ATB Sirg’ali filiali</b></td>
                            </tr>

                            <tr>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>STIR (INN):</b></td>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>309477784</b></td>
                            </tr>

                            <tr>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>IFUT (OKED):</b></td>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>85420</b></td>
                            </tr>
                        </table>

                    </td>
                </tr>

            </table>
        </td>
    </tr>

</table>