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


<div class="head_mobile">
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
</div>


<div class="modal fade" id="connectionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="ikmodel aloqa_model">
                <div class="ikmodel_item">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modalBody">
                        <img src="/frontend/web/images/wh_logo.png" alt="">
                        <div class="ik_connection">
                            <h5><?= Yii::t("app" , "a6") ?></h5>
                            <ul>
                                <li><p><?= Yii::t("app" , "a7") ?></p></li>
                                <li>
                                    <a href="tel:+998771292929">+998 (77) 129-29-29</a>
                                </li>
                            </ul>

                            <ul>
                                <li><p><?= Yii::t("app" , "a8") ?></p></li>
                                <li>
                                    <a href="tel:+998555000250">+998 (55) 500-02-50</a>
                                </li>
                            </ul>

                            <ul>
                                <li><p><?= Yii::t("app" , "a9") ?></p></li>
                                <li>
                                    <a href="https://maps.app.goo.gl/1aK5espkYi5Hvjde8">
                                        <?= Yii::t("app" , "a10") ?>
                                    </a>
                                </li>
                            </ul>

                            <div class="modal_vector_img">
                                <img src="/frontend/web/images/logo-vector.svg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>