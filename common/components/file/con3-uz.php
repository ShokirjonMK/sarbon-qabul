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
    $code = $cons->code.'-Q3';
    $date = date("Y-m-d H:i:s" , $contract->confirm_date);
    $link = $contract->contract_link;
    $con2 = $contract->contract_third;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 2) {
    $contract = StudentPerevot::findOne([
        'direction_id' => $direction->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = $cons->code.'-P3';
    $date = date("Y-m-d H:i:s" , $contract->confirm_date);
    $link = $contract->contract_link;
    $con2 = $contract->contract_third;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 3) {
    $contract = StudentDtm::findOne([
        'direction_id' => $direction->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = $cons->code.'-D3';
    $date = date("Y-m-d H:i:s" , $contract->confirm_date);
    $link = $contract->contract_link;
    $con2 = $contract->contract_third;
    $contract->down_time = time();
    $contract->save(false);
}

$qr = (new QrCode('https://qabul.sarbon.university/site/contract?key=' . $link.'&type=3'))
    ->setSize(120, 120)
    ->setMargin(10)
    ->setForegroundColor(1, 89, 101);
$img = $qr->writeDataUri();

$lqr = (new QrCode('https://document.licenses.uz/certificate/uuid/57076f62-07b1-496f-8b2b-64789c3e7345/pdf?language=oz'))->setSize(100, 100)
    ->setMargin(10)
    ->setForegroundColor(1, 89, 101);
$limg = $lqr->writeDataUri();

?>


<table width="100%" style="font-family: 'Times New Roman'; font-size: 14px; border-collapse: collapse;">


    <tr>
        <td colspan="4">
            <table width="100%" style="font-family: 'Times New Roman'; border-bottom: 2px solid #000000; padding-bottom: 5px; font-size: 14px; border-collapse: collapse;">
                <tr>
                    <td colspan="2">
                        <b>SARBON UNIVERSITETI</b>
                    </td>
                    <td colspan="2" style="font-style: italic; font-size: 11px; text-align: right;">
                        <?= $date ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr><td>&nbsp;</td></tr>

    <tr>
        <td colspan="4" style="text-align: center; line-height: 23px;">
            <b>
                To‘lov-kontrakt (Ikki tomonlama) asosida mutaxassis tayyorlashga <br>
                KONTRAKT № <?= $code ?>/<?= str_replace('.', '', $direction->code) ?>/<?= $contract->id ?>
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
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            <?= $joy ?> Vazirlar Mahkamasining “Oliy ta’lim muassasalariga o‘qishga qabul qilish, talabalar o‘qishini ko‘chirish,
            qayta tiklash va o‘qishdan chetlashtirish tartibi to‘g‘risidagi nizomlarni tasdiqlash haqida” 2017-yil 20-iyundagi 393-son qarori,
            O‘zbekiston Respublikasi oliy va o‘rta maxsus ta’lim vazirining 2012-yil 28-dekabrdagi 508-son buyrug‘i (ro‘yxat raqami 2431,
            2013-yil 26-fevral) bilan tasdiqlangan Oliy va o‘rta maxsus, kasb-hunar ta’limi muassasalarida o‘qitishning to‘lov-shartnoma
            shakli va undan tushgan mablag‘larni taqsimlash tartibi to‘g‘risidagi Nizomga muvofiq, <b>SARBON UNIVERSITETI</b> oliy ta’lim
            tashkiloti (keyingi o‘rinlarda “Ta’lim muassasasi”) nomidan Ustav asosida ish yurituvchi
            direktor <b>SOBIRJONOV NODIRJON QODIRJONOVICH</b> birinchi tomondan, (keyingi o‘rinlarda “<b>Buyurtmachi</b>”) nomidan
            ______ _______ _______ asosida ish yurituvchi ________ ________ ________ ________ ________ ________ ________ ________
            <b><?= $full_name ?></b> (keyingi o‘rinlarda “<b>Ta’lim oluvchi</b>”) ikkinchi tomondan, keyingi o‘rinlarda birgalikda “Tomonlar” deb ataluvchilar o‘rtasida mazkur shartnoma quyidagilar haqida tuzildi:
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
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            <?= $joy ?> 1.1. Ta’lim muassasasi ta’lim xizmatini ko‘rsatishni, Ta’lim oluvchi o‘qish uchun belgilangan to‘lovni o‘z vaqtida amalga oshirishni va tasdiqlangan o‘quv reja bo‘yicha darslarga to‘liq qatnashish va ta’lim olishni o‘z zimmalariga oladi. Ta’lim oluvchining ta’lim ma’lumotlari quyidagicha:
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="padding: 5px;">
            <table width="100%" style="font-family: 'Times New Roman'; font-size: 14px; border-collapse: collapse;">

                <tr>
                    <td colspan="2" style="padding-bottom: 13px;">Ta’lim bosqichi:<?= $joy ?></td>
                    <td colspan="2" style="padding-bottom: 13px;"><b>Bakalavr</b></td>
                </tr>

                <tr>
                    <td colspan="2"  style="padding-bottom: 13px;">Ta’lim shakli:<?= $joy ?></td>
                    <td colspan="2" style="padding-bottom: 13px;"><b><?= $direction->eduForm->name_uz ?></b></td>
                </tr>

                <tr>
                    <td colspan="2" style="padding-bottom: 13px;"  style="padding-bottom: 13px;">O‘qish muddati:<?= $joy ?></td>
                    <td colspan="2" style="padding-bottom: 13px;"><b><?= ikYear($direction->edu_duration) ?></b></td>
                </tr>

                <tr>
                    <td colspan="2"  style="padding-bottom: 13px;">O‘quv kursi:<?= $joy ?></td>
                    <?php if ($student->edu_type_id == 2) : ?>
                        <td colspan="2" style="padding-bottom: 13px;"><b><?= Course::findOne(['id' => ($student->course_id + 1)])->name_uz ?></b></td>
                    <?php else: ?>
                        <td colspan="2" style="padding-bottom: 13px;"><b>1 kurs</b></td>
                    <?php endif; ?>
                </tr>

                <tr>
                    <td colspan="2">Ta’lim yo‘nalishi:<?= $joy ?></td>
                    <td colspan="2"><b><?= str_replace('.', '', $direction->code).' - '.$direction->name_uz ?></b></td>
                </tr>

            </table>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
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
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            2.1. “Ta’lim muassasasi”da o‘qish davrida ta’lim xizmatini ko‘rsatish narxi Respublikada belgilangan Bazaviy hisoblash miqdori o’zgarishiga bog‘liq holda hisoblanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            2.2. Ushbu shartnoma bo‘yicha ta’lim oluvchini bir yillik o‘qitish uchun to‘lov 8,219,893.00 (sakkiz million ikki yuz o`n to`qqiz ming sakkiz yuz to`qson uch so'm) soʻmni tashkil etadi va quyidagi muddatlarda amalga oshiriladi:
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            Choraklarga bo‘lib to‘langanda quyidagi muddatlarda:
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	belgilangan to‘lov miqdorining kamida 25.00 foizini talabalikka tavsiya etilgan abiturientlar uchun 15.10.2024 gacha, ikkinchi va undan yuqori bosqich talabalar uchun 01.11.2024 gacha;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	belgilangan to‘lov miqdorining kamida 50.00 foizini 01.01.2025 gacha, 75.00 foizini 01.04.2025 gacha va 100.00 foizini 01.06.2025 gacha.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            2.3. Buyurtmachi tomonidan shartnoma to‘lovini to‘lashda shartnomaning tartib raqami va sanasi,  familiyasi, ismi va sharifi hamda o‘quv kursi to‘lov topshiriqnomasida to‘liq ko‘rsatiladi. To‘lov kuni Universitetning bank hisob raqamiga mablag‘ kelib tushgan kun hisoblanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            2.4. Shartnoma Universitet tashabbusi bilan Talaba uning hatti-harakatlari (harakatsizligi) sababli talabalar safidan chetlashtirilsa, shartnoma bo‘yicha to‘langan mablag‘lar qaytarilmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            2.5. Ushbu shartnomaning 2.2-bandida ko‘zda tutilgan to‘lov muddatlari Tomonlarning o’zaro kelishuvi bilan o’zgartrilishi mumkin.
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
            <?= $joy ?> 3.1. <b>Ta’lim muassasasi majburiyatlari:</b>
        </td>
    </tr>

    <tr>
        <td  colspan="4" style="text-align: justify; line-height: 23px;">
            •	O‘qitish uchun belgilangan to‘lov o‘z vaqtida amalga oshirgandan so‘ng, “Ta’lim oluvchi”ni buyruq asosida talabalikka qabul qilish.
        </td>
    </tr>

    <tr>
        <td  colspan="4" style="text-align: justify; line-height: 23px;">
            •	Ta’lim oluvchiga o‘qishi uchun O‘zbekiston Respublikasining “Ta’lim to‘g‘risida”gi Qonuni va “Ta’lim muassasasi” Ustavida nazarda tutilgan zarur shart-sharoitlarga muvofiq sharoitlarni yaratib berish.
        </td>
    </tr>

    <tr>
        <td  colspan="4" style="text-align: justify; line-height: 23px;">
            •	Ta’lim oluvchining huquq va erkinliklari, qonuniy manfaatlari hamda ta’lim muassasasi Ustaviga muvofiq professor-o‘qituvchilar tomonidan o‘zlarining funksional vazifalarini to‘laqonli bajarishini ta’minlash.
        </td>
    </tr>

    <tr>
        <td  colspan="4" style="text-align: justify; line-height: 23px;">
            •	Ta’lim oluvchini tahsil olayotgan ta’lim yo‘nalishi (mutaxassisligi) bo‘yicha tasdiqlangan o‘quv rejasi va dasturlariga muvofiq davlat ta’lim standarti talablari darajasida tayyorlash.
        </td>
    </tr>

    <tr>
        <td  colspan="4" style="text-align: justify; line-height: 23px;">
            •	O‘quv yili boshlanishida ta’lim oluvchini yangi o‘quv yili uchun belgilangan to‘lov miqdori to‘g‘risida o‘quv jarayoni boshlanishidan oldin xabardor qilish.
        </td>
    </tr>

    <tr>
        <td  colspan="4" style="text-align: justify; line-height: 23px;">
            •	Respublikada belgilangan Mehnatga haq to‘lashning eng kam miqdori yoki tariflar o‘zgarishi natijasida o‘qitish uchun belgilangan to‘lov miqdori o‘zgargan taqdirda ta’lim oluvchiga ta’limning qolgan muddati uchun to‘lov miqdori haqida xabar berish.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 3.2. <b>Ta’lim oluvchining majburiyatlari:</b>
        </td>
    </tr>

    <tr>
        <td  colspan="4" style="text-align: justify; line-height: 23px;">
            •	Shartnomaning 2.2. bandida belgilangan to‘lov summasini shu bandda ko‘rsatilgan muddatlarda to‘lab borish.
        </td>
    </tr>

    <tr>
        <td  colspan="4" style="text-align: justify; line-height: 23px;">
            •	Respublikada belgilangan Mehnatga haq to‘lashning eng kam miqdori yoki tariflar o‘zgarishi natijasida o‘qitish uchun belgilangan to‘lov miqdori o‘zgargan taqdirda, o‘qishning qolgan muddati uchun ta’lim muassasasiga haq to‘lash bo‘yicha bir oy muddat ichida shartnomaga qo‘shimcha bitim rasmiylashtirish va to‘lov farqini to‘lash.
        </td>
    </tr>

    <tr>
        <td  colspan="4" style="text-align: justify; line-height: 23px;">
            •	Ta’lim oluvchi o‘qitish uchun belgilangan to‘lov miqdorini to‘laganlik to‘g‘risidagi bank tasdiqnomasi va shartnomaning bir nusxasini o‘z vaqtida hujjatlarni rasmiylashtirish uchun ta’lim muassasasiga topshirish.
        </td>
    </tr>

    <tr>
        <td  colspan="4" style="text-align: justify; line-height: 23px;">
            •	Tahsil olayotgan ta’lim yo‘nalishining (mutaxassisligining) tegishli malaka tavsifnomasiga muvofiq kelajakda mustaqil faoliyat yuritishga zarur bo‘lgan barcha bilimlarni egallash, dars va mashg‘ulotlarga to‘liq qatnashish.
        </td>
    </tr>

    <tr>
        <td  colspan="4" style="text-align: justify; line-height: 23px;">
            •	Ta’lim muassasasi va talabalar turar joyining ichki nizomlariga qa’tiy rioya qilish, professoro‘ qituvchilar va xodimlarga hurmat bilan qarash, “Ta’lim muassasasi” obro‘siga putur yetkazadigan harakatlar qilmaslik, moddiy bazasini asrash, ziyon keltirmaslik, ziyon keltirganda o‘rnini qoplash.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 3.3. <b>Buyurtmachining majburiyatlari:</b>
        </td>
    </tr>

    <tr>
        <td  colspan="4" style="text-align: justify; line-height: 23px;">
            •	Shartnoma to‘lovini mazkur shartnomada belgilangan muddatlarda to‘lash.
        </td>
    </tr>
    <tr>
        <td  colspan="4" style="text-align: justify; line-height: 23px;">
            •	Ta’lim muassasasi Ustavi va ichki tartib-qoidalariga qat’iy rioya qilishni hamda o‘quv reja va dasturlarga muvofiq ta’lim olishni Ta’lim oluvchidan talab qilish.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>IV. TOMONLARNING HUQUQLARI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 4.1. <b>Ta’lim muassasasi huquqlari:</b>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	O‘quv jarayonini mustaqil ravishda amalga oshirish, “Ta’lim oluvchi”ning oraliq va yakuniy nazoratlarni topshirish, qayta topshirish tartibi hamda vaqtlarini belgilash.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	O‘zbekiston Respublikasi qonunlari, “Ta’lim muassasasi” nizomi hamda mahalliy normativ-huquqiy hujjatlarga muvofiq “Ta’lim oluvchi”ga rag‘batlantiruvchi yoki intizomiy choralarni qo‘llash.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	Agar “Ta’lim oluvchi” o‘quv yili semestrlarida yakuniy nazoratlarni topshirish, qayta topshirish natijalariga ko‘ra akademik qarzdor bo‘lib qolsa uni kursdan-kursga qoldirish huquqiga ega.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	“Ta’lim muassasasi” “Ta’lim oluvchi”ning qobiliyati, darslarga sababsiz 36 akademik soat qatnashmaslik, intizomni buzish, “Ta’lim muassasasi”ning ichki tartib qoidalariga amal qilmaganda, respublikaning normativ-huquqiy hujjatlarida nazarda tutilgan boshqa sabablarga ko‘ra hamda o‘qitish uchun belgilangan to‘lov o‘z vaqtida amalga oshirilmaganda “Ta’lim oluvchi”ni talabalar safidan chetlashtirish huquqiga ega.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 4.2. <b>Ta’lim oluvchining huquqlari:</b>
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	O‘quv yili uchun shartnoma summasini semestrlarga yoki choraklarga bo‘lmasdan bir yo‘la to‘liqligicha to‘lash mumkin.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	Ta’lim oluvchi mazkur shartnoma bo‘yicha naqd pul, bank plastik kartasi, bankdagi omonat hisob raqami orqali, ish joyidan arizasiga asosan oylik maoshini o‘tkazishi yoki banklardan ta’lim krediti olish orqali to‘lovni amalga oshirishi mumkin.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	Professor-o‘qituvchilarning o‘z funksional vazifalarini bajarishidan yoki ta’lim muassasasidagi shart-sharoitlardan norozi bo‘lgan taqdirda ta’lim muassasasi rahbariyatiga yozma shaklda murojaat qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	Quyidagi hollarda Ta’lim muassasasi ruxsati bilan 1 (bir) yilgacha akademik ta’til olish:
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            a) salomatlik holati davlat sog‘liqni saqlash tizimiga kiruvchi tibbiyot muassasalarining davolovchi shifokorlari tomonidan hujjatlar bilan tasdiqlangan sezilarli darajada yomonlashganda;
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            b) homiladorlik va tug‘ish, shuningdek bola ikki yoshga to‘lgunga qadar parvarishlash bo‘yicha ta’tilga bog‘liq hollarda;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            v) yaqin qarindoshining vafoti munosabati bilan bu holda akademik ta’til berish Ta’lim muassasasi rahbariyati tomonidan har bir holat alohida ko‘rib chiqiladi va qaror qabul qilinadi;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            g) harbiy xizmatni o‘tash uchun safarbar etilishi munosabati bilan;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            d) boshqa hollarda Ta’lim muassasasi rahbariyatining qaroriga ko‘ra.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 4.3. <b>Buyurtmachining huquqlari:</b>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	Ta’lim oluvchi va Ta’lim muassasasidan shartnoma majburiyatlari bajarilishini talab qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	Ta’lim oluvchining Ta’lim muassasasi o‘quv reja va dasturlariga muvofiq ta’lim olishini nazorat qilish
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	Ta’lim muassasasi ta’lim jarayonlarini yaxshilashga doir takliflar berish
        </td>
    </tr>


    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>V. SHARTNOMANING AMAL QILISH MUDDATI, UNGA O‘ZGARTIRISH VA QO‘SHIMCHALAR KIRITISH HAMDA BEKOR QILISH TARTIBI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            5.1. Ushbu shartnoma ikki tomonlama imzolangandan so‘ng kuchga kiradi hamda ta’lim xizmatlarini taqdim etish o‘quv yili tugagunga qadar amalda bo‘ladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            5.2. Ushbu shartnoma shartlariga ikkala tomon kelishuviga asosan tuzatish, o‘zgartirish va qo‘shimchalar kiritilishi  mumkin
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            5.3. Shartnomaga tuzatish, o‘zgartirish va qo‘shimchalar faqat yozma ravishda “Shartnomaga qo‘shimcha bitim” tarzida kiritiladi va imzolanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            Shartnoma quyidagi hollarda bekor qilinishi mumkin:
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	Tomonlarning o‘zaro kelishuviga binoan.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	“Ta’lim oluvchi” talabalar safidan chetlashtirilganda “Ta’lim muassasasi” tashabbusi bilan bir tomonlama bekor qilinishi mumkin.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	Ta’lim muassasasi  tomonidan Ta’lim oluvchi tomonidan to’lov o’z vaqtida va to’liq amalga oshirilmaganda;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            •	Tomonlardan biri o‘z majburiyatlarini bajarmaganda yoki lozim darajada bajarmaganda sud qarori asosida
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            5.5. Ta’lim muassasasi tugatilganda, ta’lim oluvchi bilan o‘zaro qayta hisob-kitob qilinadi.
        </td>
    </tr>


    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>VI. YAKUNIY QOIDALAR VA NIZOLARNI HAL QILISH TARTIBI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            6.1. Ta’lim muassasasining Ta’lim oluvchini o‘qishga qabul qilish buyrug‘i Ta’lim oluvchi tomonidan barcha kerakli hujjatlarni taqdim etish va to‘lovni amalga oshirish sharti bilan chiqariladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            6.2. Ta’lim oluvchiga Ta’lim muassasasi tomonidan stipendiya to‘lanmaydi va Ta’lim muassasasi Ta’lim oluvchini ishga joylashtirish majburiyatini o‘z zimmasiga olmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            6.3. Ushbu shartnomani bajarish jarayonida kelib chiqishi mumkin bo‘lgan nizo va ziddiyatlar tomonlar o‘rtasida  muzokaralar olib borish yo‘li bilan hal etiladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            6.4. Muzokaralar olib borish yo‘li bilan nizoni hal etish imkoniyati bo‘lmagan taqdirda, tomonlar nizolarni hal etish uchun amaldagi qonunchilikka muvofiq Ta’lim muassasasi joylashgan yerdagi sudga murojaat etishlari mumkin.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            6.5. Ta’lim muassasasi tomonidan ushbu shartnoma bo‘yicha mablag‘lar qaytarilishi lozim bo‘lgan hollarda mazkur mablag‘lar tegishli hujjat o‘z kuchiga kirgan paytdan boshlab 30 (o‘ttiz) kun ichida qaytariladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            6.6. Ushbu Shartnomaga kiritilgan har qanday o‘zgartirish va/yoki qo‘shimchalar, agar ular tomonlar tomonidan yozma shaklda rasmiylashtirilgan, imzolangan/muhrlangan bo‘lsagina amal qiladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            6.7. “Ta’lim muassasasi” axborotlar va xabarnomalarni internetdagi veb-saytida, axborot tizimida yoki e’lonlar taxtasida e’lon joylashtirish orqali xabar berishi mumkin.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            6.8. Shartnoma 2 (ikki)  nusxada, tomonlarning har biri  uchun bir nusxadan tuzildi va ikkala nusxa ham  bir xil huquqiy kuchga ega.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; line-height: 23px;">
            6.9. Ushbu Shartnomaga qo‘shimcha bitim kiritilgan taqdirda ushbu barcha kiritilgan qo‘shimcha bitimlar shartnomaning ajralmas qismi hisoblanadi.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <div>
                <table width="100%" style=" font-family: 'Times New Roman'; font-size: 14px; border-collapse: collapse;">
                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="4" style="text-align: center;">
                            <b>VII.	TOMONLARNING REKVIZITLARI VA IMZOLARI</b>
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
                            <b>7.1. Ta’lim muassasasi:</b>
                        </td>
                        <td colspan="2">
                            <b>7.2. Ta’lim oluvchi:</b>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="vertical-align: top; line-height: 25px;">
                            <b>“SARBON UNIVERSITETI” oliy ta’lim tashkiloti</b> <br>
                            <b>Manzil:</b> Toshkent shahar, Olmazor tumani, Paxta MFY, Sag'bon ko'chasi. <br>
                            <b>H/R:</b> <?= $cons->h_r ?> <br>
                            <b>Bank:</b> “Trastbank” xususiy aksiyadorlik bankining “Raqamli” bank xizmatlari ofisi <br>
                            <b>Bank kodi (MFO):</b> 00491  <br>
                            <b>IFUT (OKED):</b> 64190  <br>
                            <b>STIR (INN):</b> 309341614 <br>
                            <b>Tel:</b> +998 78 888 22 88 <br>
                        </td>
                        <td colspan="2" style="vertical-align: top; line-height: 23px;">
                            <b>F.I.Sh.:</b> <?= $full_name ?> <br>
                            <b>Pasport ma’lumotlari:</b> <?= $student->passport_serial.' '.$student->passport_number ?> <br>
                            <b>JShShIR:</b> <?= $student->passport_pin ?> <br>
                            <b>Tеlefon raqami: </b> <?= $student->user->username ?> <br>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="vertical-align: top;">
                            <img src="<?= $img ?>" width="120px">
                        </td>
                        <td colspan="2" style="vertical-align: top;">
                            <div style="width: 100%; text-align: right;">
                                <img src="<?= $limg ?>" width="120px">
                            </div>
                            <b>Litsenziya berilgan sana va raqami</b> <br>
                            14.09.2024 <b>№ 397374</b>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="padding-bottom: 13px;">
                            <b>7.3. Buyurtmachi:</b>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="1" style="padding-bottom: 13px;">
                            <b>Yuridik shaxs:</b>
                        </td>
                        <td colspan="3" style="padding-bottom: 13px;">
                            ________ _______ _______ _______
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1" style="padding-bottom: 13px;">
                            <b>Manzil:</b>
                        </td>
                        <td colspan="3" style="padding-bottom: 13px;">
                            ________ _______ _______ _______
                        </td>
                    </tr>

                    <tr>
                        <td colspan="1" style="padding-bottom: 13px;">
                            <b>H/r:</b>
                        </td>
                        <td colspan="3" style="padding-bottom: 13px;">
                            ________ _______ _______ _______
                        </td>
                    </tr>

                    <tr>
                        <td colspan="1" style="padding-bottom: 13px;">
                            <b>Bank:</b>
                        </td>
                        <td colspan="3" style="padding-bottom: 13px;">
                            ________ _______ _______ _______
                        </td>
                    </tr>

                    <tr>
                        <td colspan="1" style="padding-bottom: 13px;">
                            <b>MFO:</b>
                        </td>
                        <td colspan="3" style="padding-bottom: 13px;">
                            ________ _______ _______ _______
                        </td>
                    </tr>

                    <tr>
                        <td colspan="1" style="padding-bottom: 13px;">
                            <b>STIR:</b>
                        </td>
                        <td colspan="3" style="padding-bottom: 13px;">
                            ________ _______ _______ _______
                        </td>
                    </tr>

                    <tr>
                        <td colspan="1" style="padding-bottom: 13px;">
                            <b>Tel:</b>
                        </td>
                        <td colspan="3" style="padding-bottom: 13px;">
                            ________ _______ _______ _______
                        </td>
                    </tr>

                    <tr>
                        <td colspan="1" style="padding-bottom: 13px;">
                            <b>E-mail:</b>
                        </td>
                        <td colspan="3" style="padding-bottom: 13px;">
                            ________ _______ _______ _______
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="1" style="padding-bottom: 13px;">
                            <b>Rahbar:</b>
                        </td>
                        <td colspan="3" style="padding-bottom: 13px;">
                            ________ _______ _______ _______
                        </td>
                    </tr>



                </table>
            </div>
        </td>
    </tr>


    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            <table width="100%" style="font-family: 'Times New Roman'; font-size: 14px; border-collapse: collapse;">
                <tr>
                    <td colspan="4" style="border: 1px solid #000000; padding: 10px;">
                        <b>SANA: </b> &nbsp; <?= $date ?> <br>
                        <b>INVOYS RAQAMI: &nbsp; </b> <?= $con2 ?> <br>
                        <b>KONTRAKT TO‘LOV MIQDORI: &nbsp; </b> <?= number_format((int)$contract->contract_price, 0, '', ' ') . ' (' . Contract::numUzStr($contract->contract_price) . ')' ?> <br>
                        <table width="100%" style="font-family: 'Times New Roman';">
                            <tr>
                                <td colspan="4">To‘lovni amalga oshirish usullari:</td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: justify; line-height: 23px;">
                                    <?= $joy ?> Yuridik shaxslar va bank kassalari orqali. Bunda To‘lov maqsadida - Invoys raqam. JSHSHIR. Talabaning
                                    FISH tartibida yozilgan bo‘lishi talab etiladi
                                </td>
                            </tr>

                            <tr>
                                <td>&nbsp;</td>
                            </tr>

                            <tr>
                                <td colspan="4" style="font-family: 'Times New Roman'; padding: 5px; border: 2px solid red;">
                                    <table width="100%" style="font-family: 'Times New Roman';">
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
                                <td colspan="4" style="text-align: justify; line-height: 23px;">
                                    <?= $joy ?> To‘lov maqsadi belgilangan tartibda to‘ldirilmagan taqdirda to‘lovni qabul qilishga doir muammolar kelib chiqishi
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
                                <td colspan="4" style="text-align: justify; line-height: 23px;"><b>To‘lovlarni amalgi oshirish uchun Universitetning bank hisob ma’lumotlari:</b></td>
                            </tr>

                            <tr>
                                <td>&nbsp;</td>
                            </tr>

                            <tr>
                                <td colspan="4">
                                    <table width="100%" style="font-family: 'Times New Roman'; border-collapse: collapse; border: 1px solid;">
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
                                            <td colspan="2" style="padding: 5px; border: 1px solid;"><b>00491</b></td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding: 5px; border: 1px solid;"><b>Bank nomi:</b></td>
                                            <td colspan="2" style="padding: 5px; border: 1px solid;"><b>“Trastbank” xususiy aksiyadorlik bankining “Raqamli” bank xizmatlari ofisi</b></td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding: 5px; border: 1px solid;"><b>STIR (INN):</b></td>
                                            <td colspan="2" style="padding: 5px; border: 1px solid;"><b>309341614</b></td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding: 5px; border: 1px solid;"><b>IFUT (OKED):</b></td>
                                            <td colspan="2" style="padding: 5px; border: 1px solid;"><b>64190</b></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

</table>