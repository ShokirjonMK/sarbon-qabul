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
            <p><?= $joy ?>•	belgilangan to‘lov miqdorining kamida 25.00 foizini talabalikka tavsiya etilgan abiturientlar uchun 15.10.2024 gacha, ikkinchi va undan yuqori bosqich talabalar uchun 01.11.2024 gacha;</p>
            <p><?= $joy ?>•	belgilangan to‘lov miqdorining kamida 50.00 foizini 01.01.2025 gacha, 75.00 foizini 01.04.2025 gacha va 100.00 foizini 01.06.2025 gacha.</p>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            <?= $joy ?> 2.3. Ushbu shartnomaning 2.2-bandida ko‘zda tutilgan to‘lov muddatlari Tomonlarning o’zaro kelishuvi bilan o’zgartrilishi mumkin.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <div>
        <table width="100%">
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
                <td  colspan="4" style="text-align: justify">
                    •	O‘qitish uchun belgilangan to‘lov o‘z vaqtida amalga oshirgandan so‘ng, “Ta’lim oluvchi”ni buyruq asosida talabalikka qabul qilish.
                </td>
            </tr>

            <tr>
                <td  colspan="4" style="text-align: justify">
                    •	Ta’lim oluvchiga o‘qishi uchun O‘zbekiston Respublikasining “Ta’lim to‘g‘risida”gi Qonuni va “Ta’lim muassasasi” Ustavida nazarda tutilgan zarur shart-sharoitlarga muvofiq sharoitlarni yaratib berish.
                </td>
            </tr>

            <tr>
                <td  colspan="4" style="text-align: justify">
                    •	Ta’lim oluvchining huquq va erkinliklari, qonuniy manfaatlari hamda ta’lim muassasasi Ustaviga muvofiq professor-o‘qituvchilar tomonidan o‘zlarining funksional vazifalarini to‘laqonli bajarishini ta’minlash.
                </td>
            </tr>

            <tr>
                <td  colspan="4" style="text-align: justify">
                    •	Ta’lim oluvchini tahsil olayotgan ta’lim yo‘nalishi (mutaxassisligi) bo‘yicha tasdiqlangan o‘quv rejasi va dasturlariga muvofiq davlat ta’lim standarti talablari darajasida tayyorlash.
                </td>
            </tr>

            <tr>
                <td  colspan="4" style="text-align: justify">
                    •	O‘quv yili boshlanishida ta’lim oluvchini yangi o‘quv yili uchun belgilangan to‘lov miqdori to‘g‘risida o‘quv jarayoni boshlanishidan oldin xabardor qilish.
                </td>
            </tr>

            <tr>
                <td  colspan="4" style="text-align: justify">
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
                <td  colspan="4" style="text-align: justify">
                    •	Shartnomaning 2.2. bandida belgilangan to‘lov summasini shu bandda ko‘rsatilgan muddatlarda to‘lab borish.
                </td>
            </tr>

            <tr>
                <td  colspan="4" style="text-align: justify">
                    •	Respublikada belgilangan Mehnatga haq to‘lashning eng kam miqdori yoki tariflar o‘zgarishi natijasida o‘qitish uchun belgilangan to‘lov miqdori o‘zgargan taqdirda, o‘qishning qolgan muddati uchun ta’lim muassasasiga haq to‘lash bo‘yicha bir oy muddat ichida shartnomaga qo‘shimcha bitim rasmiylashtirish va to‘lov farqini to‘lash.
                </td>
            </tr>

            <tr>
                <td  colspan="4" style="text-align: justify">
                    •	Ta’lim oluvchi o‘qitish uchun belgilangan to‘lov miqdorini to‘laganlik to‘g‘risidagi bank tasdiqnomasi va shartnomaning bir nusxasini o‘z vaqtida hujjatlarni rasmiylashtirish uchun ta’lim muassasasiga topshirish.
                </td>
            </tr>

            <tr>
                <td  colspan="4" style="text-align: justify">
                    •	Tahsil olayotgan ta’lim yo‘nalishining (mutaxassisligining) tegishli malaka tavsifnomasiga muvofiq kelajakda mustaqil faoliyat yuritishga zarur bo‘lgan barcha bilimlarni egallash, dars va mashg‘ulotlarga to‘liq qatnashish.
                </td>
            </tr>

            <tr>
                <td  colspan="4" style="text-align: justify">
                    •	Ta’lim muassasasi va talabalar turar joyining ichki nizomlariga qa’tiy rioya qilish, professoro‘ qituvchilar va xodimlarga hurmat bilan qarash, “Ta’lim muassasasi” obro‘siga putur yetkazadigan harakatlar qilmaslik, moddiy bazasini asrash, ziyon keltirmaslik, ziyon keltirganda o‘rnini qoplash.
                </td>
            </tr>
        </table>
    </div>




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