<?php
use yii\helpers\Url;
use common\models\Languages;
use yii\helpers\Html;
$languages = Languages::find()->where(['is_deleted' => 0, 'status' => 1])->all();
$lang = Yii::$app->language;
$langId = 1;
if ($lang == 'ru') {
    $langId = 3;
} elseif ($lang == 'en') {
    $langId = 2;
}
?>

<div class="root-item">
    <div class="mb_head d-flex justify-content-between align-items-center">
        <div class="mb_head_left">
            <a href="<?= Url::to(['site/index']) ?>">
                <img src="/frontend/web/images/sarbon_wh_logo.svg" alt="">
            </a>
        </div>
        <div class="mb_head_right">
            <div class="translation cab_flag">
                <div class="dropdown">

                    <button class="dropdown-toggle link-hover" style="background: none;" type="button" data-bs-toggle="dropdown" aria-expanded="true">
                        <?php foreach ($languages as $language): ?>
                            <?php if ($language->id == $langId): ?>
                                <p style="color: #fff;"><?= $language['name_'.$lang] ?></p>
                                <?php if ($language->id == 1): ?>
                                    <img src="/frontend/web/images/uzb.png" alt="">
                                <?php elseif ($language->id == 2) : ?>
                                    <img src="/frontend/web/images/eng1.png" alt="">
                                <?php elseif ($language->id == 3) : ?>
                                    <img src="/frontend/web/images/rus.png" alt="">
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </button>

                    <ul class="dropdown-menu">
                        <ul class="drop_m_ul">
                            <?php foreach ($languages as $language): ?>
                                <?php if ($language->id != $langId): ?>
                                    <li>
                                        <a href="<?= Url::to(['site/lang' , 'id' => $language->id]) ?>">
                                            <span><?= $language['name_'.$lang] ?></span>
                                            <?php if ($language->id == 1): ?>
                                                <img src="/frontend/web/images/uzb.png" alt="">
                                            <?php elseif ($language->id == 2) : ?>
                                                <img src="/frontend/web/images/eng1.png" alt="">
                                            <?php elseif ($language->id == 3) : ?>
                                                <img src="/frontend/web/images/rus.png" alt="">
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </ul>

                </div>
            </div>
        </div>
    </div>

    <div class="mb_content">

        <div class="mb_menu_list2 ik_sarbon_listensy">
            <ul>
                <li>
                    <a target="_blank" href="https://document.licenses.uz/certificate/uuid/57076f62-07b1-496f-8b2b-64789c3e7345/pdf?language=oz">
                        <i class="fa-solid fa-award"></i>
                        <span><?= Yii::t("app" , "Universitet Litsenziyasi") ?></span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="mb_menu_list2">
            <p><?= Yii::t("app" , "a1") ?></p>
            <ul>

                <?php if (!Yii::$app->user->isGuest) : ?>
                    <?php $user = Yii::$app->user->identity;
                    $student = $user->student; ?>

                    <?php if ($user->step == 5) : ?>
                        <li>
                            <a href="<?= Url::to(['cabinet/index']) ?>">
                                <i class="bi bi-person-check-fill"></i>
                                <span><?= Yii::t("app" , "a40") ?></span>
                            </a>
                        </li>

                        <?php if ($student->edu_type_id == 1) : ?>
                            <li>
                                <a href="<?= Url::to(['cabinet/exam']) ?>">
                                    <i class="bi bi-card-checklist"></i>
                                    <span><?= Yii::t("app" , "a43") ?></span>
                                </a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?= Url::to(['cabinet/send-file']) ?>">
                                    <i class="bi bi-send"></i>
                                    <span><?= Yii::t("app" , "a44") ?></span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li>
                            <a href="<?= Url::to(['cabinet/download-file']) ?>">
                                <i class="bi bi-arrow-down-circle"></i>
                                <span><?= Yii::t("app" , "a46") ?></span>
                            </a>
                        </li>

                        <li>
                            <a href="<?= Url::to(['site/index#ik_connection']) ?>">
                                <i class="bi bi-telephone-forward"></i>
                                <span><?= Yii::t("app" , "a47") ?></span>
                            </a>
                        </li>

                        <li>
                            <?= Html::a('<i class="fa-solid fa-file-import"></i>
                                <span>'.Yii::t("app" , "a41").'</span>', ['site/logout'], [
                                'data' => [
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </li>

                    <?php else: ?>
                        <li>
                            <a href="<?= Url::to(['site/index#ik_connection']) ?>">
                                <i class="fa-solid fa-phone"></i>
                                <span><?= Yii::t("app" , "a2") ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="#ik_direc">
                                <i class="fa-solid fa-sitemap"></i>
                                <span><?= Yii::t("app" , "a3") ?></span>
                            </a>
                        </li>
                        <li>
                            <?= Html::a('<i class="fa-solid fa-file-import"></i>
                                <span>'.Yii::t("app" , "a41").'</span>', ['site/logout'], [
                                'data' => [
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </li>
                    <?php endif; ?>

                <?php else: ?>
                    <li>
                        <a href="<?= Url::to(['site/index#ik_connection']) ?>">
                            <i class="fa-solid fa-phone"></i>
                            <span><?= Yii::t("app" , "a2") ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#ik_direc">
                            <i class="fa-solid fa-sitemap"></i>
                            <span><?= Yii::t("app" , "a3") ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to(['site/login']) ?>">
                            <i class="fa-solid fa-file-import"></i>
                            <span><?= Yii::t("app" , "a4") ?></span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <img src="/frontend/web/images/sarbon_wh_logo_icon.svg" class="mb_vector_img">

    </div>
</div>