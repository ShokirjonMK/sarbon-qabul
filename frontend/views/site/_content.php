<?php
use common\models\EduYearForm;
use common\models\Direction;
use common\models\Languages;
use yii\helpers\Url;

$eduYearForms = EduYearForm::find()
    ->where(['is_deleted' => 0, 'status' => 1])
    ->all();
$lang = Yii::$app->language;
?>



<div class="ik_content">
    <div class="ik_content_box">
        <div class="ik_content_direction">
            <div class="root-item">
                <div class="ik_main_title" id="ik_direc">
                    <p><?= Yii::t("app" , "a12") ?></p>
                    <h4><?= Yii::t("app" , "a13") ?></h4>
                </div>

                <div class="ik_nav_pills">
                    <div class="ik_nav_pills_item">
                        <ul class="nav nav-pills mb-4 view-tabs" id="pills-tab" role="tablist">
                            <?php $a = 1 ?>
                            <?php foreach ($eduYearForms as $eduYearForm) : ?>
                                <?php
                                $directionCount = (new \yii\db\Query())
                                    ->select('code')
                                    ->from('direction')
                                    ->where(['status' => 1, 'is_deleted' => 0, 'edu_year_form_id' => $eduYearForm->id])
                                    ->groupBy('code')
                                    ->count();
                                ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?php if ($a == 1) { echo "active";} ?>" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills_ik<?= $a ?>" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">
                                        <?= $eduYearForm->eduForm['name_'.$lang] ?> <?= Yii::t("app" , "a14") ?> &nbsp;&nbsp; <span class="btn-span"><?= $directionCount ?></span>
                                    </button>
                                </li>
                                <?php $a++; ?>
                            <?php endforeach; ?>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <?php $a = 1 ?>
                            <?php foreach ($eduYearForms as $eduYearForm) : ?>
                                <?php
                                $eduForm = $eduYearForm->eduForm;
                                $subQuery = (new \yii\db\Query())
                                    ->select(['MAX(id)'])
                                    ->from('direction')
                                    ->where([
                                        'status' => 1,
                                        'is_deleted' => 0,
                                        'edu_year_form_id' => $eduYearForm->id
                                    ])->groupBy('code');

                                $directions = Direction::find()
                                    ->where(['id' => $subQuery])
                                    ->all();
                                ?>
                                <div class="tab-pane fade <?php if ($a == 1) { echo "show active";} ?>" id="pills_ik<?= $a ?>" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                    <?php if (count($directions) > 0) : ?>
                                        <div class="grid-view">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>№</th>
                                                    <th><?= Yii::t("app" , "a15") ?></th>
                                                    <th><?= Yii::t("app" , "a16") ?></th>
                                                    <th><?= Yii::t("app" , "a17") ?></th>
                                                    <th><?= Yii::t("app" , "a18") ?></th>
                                                    <th><?= Yii::t("app" , "a19") ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $t = 1; ?>
                                                    <?php foreach ($directions as $direction) : ?>
                                                        <?php
                                                            $languages = Languages::find()
                                                                ->where(['in' , 'id' , Direction::find()
                                                                    ->select('language_id')
                                                                    ->where([
                                                                        'code' => $direction->code,
                                                                        'edu_year_form_id' => $eduYearForm->id,
                                                                        'status' => 1,
                                                                        'is_deleted' => 0
                                                                    ])
                                                                ])->all();
                                                        ?>
                                                        <tr>
                                                            <td date-label="№"><?= $t ?></td>
                                                            <td date-label="<?= Yii::t("app" , "a15") ?>"><?= '<span class="ik_color_red">'.$direction->code.'</span>'.' - '.$direction['name_'.$lang] ?></td>
                                                            <td date-label="<?= Yii::t("app" , "a16") ?>"><?= $eduForm['name_'.$lang]; ?></td>
                                                            <td date-label="<?= Yii::t("app" , "a17") ?>">
                                                                <?php if (count($languages) > 0): ?>
                                                                    <span class="ik_lang_table">
                                                                        <?php foreach ($languages as $language): ?>
                                                                             <span><?= $language['name_'.$lang] ?></span>
                                                                        <?php endforeach; ?>
                                                                    </span>
                                                                <?php else: ?>
                                                                    -----
                                                                <?php endif; ?>
                                                            </td>
                                                            <td date-label="<?= Yii::t("app" , "a18") ?>"><?= $direction->edu_duration ?> &nbsp; <?= Yii::t("app" , "a20") ?></td>
                                                            <td date-label="<?= Yii::t("app" , "a19") ?>"><?= number_format((int)$direction->contract, 0, '', ' '); ?> <?= Yii::t("app" , "a21") ?></td>
                                                        </tr>
                                                        <?php $t++; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                    <?php endif; ?>
                                </div>
                                <?php $a++; ?>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>


                <br>
                <div class="ik_main_title" id="ik_connection">
                    <p><?= Yii::t("app" , "a104") ?></p>
                    <h4><?= Yii::t("app" , "a104") ?></h4>
                </div>

                <div class="iik_box btop">
                    <div class="iik_box_item">
                        <div class="down_title">
                            <h6><i class="bi bi-telephone-forward"></i> &nbsp;&nbsp; <?= Yii::t("app" , "a104") ?></h6>
                        </div>

                        <div class="down_content">

                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <a href="tel:+998788882288" class="down_content_box">
                                        <div class="down_content_box_left">
                                            <i class="fa-solid fa-phone-volume"></i>
                                        </div>
                                        <div class="down_content_box_right">
                                            <p><?= Yii::t("app" , "a105") ?></p>
                                            <h6>+998(78) 888 22 88</h6>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <a href="https://www.instagram.com/sarbonuniversiteti?igsh=MWRodnB0eG03MG1oOQ==" target="_blank" class="down_content_box">
                                        <div class="down_content_box_left">
                                            <img src="/frontend/web/images/instagram.svg" alt="">
                                        </div>
                                        <div class="down_content_box_right">
                                            <p>Instagram</p>
                                            <h6><?= Yii::t("app" , "a108") ?></h6>
                                        </div>
                                    </a>
                                </div>

<!--                                <div class="col-lg-4 col-md-6 col-sm-12">-->
<!--                                    <a href="https://www.facebook.com/perfectuniversity.uz" target="_blank" class="down_content_box">-->
<!--                                        <div class="down_content_box_left">-->
<!--                                            <img src="/frontend/web/images/facebook.svg" alt="">-->
<!--                                        </div>-->
<!--                                        <div class="down_content_box_right">-->
<!--                                            <p>Facebook</p>-->
<!--                                            <h6>--><?php //= Yii::t("app" , "a108") ?><!--</h6>-->
<!--                                        </div>-->
<!--                                    </a>-->
<!--                                </div>-->

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <a href="https://t.me/sarbonuniversity" target="_blank" class="down_content_box">
                                        <div class="down_content_box_left">
                                            <img src="/frontend/web/images/telegram.svg" alt="">
                                        </div>
                                        <div class="down_content_box_right">
                                            <p>Telegram</p>
                                            <h6><?= Yii::t("app" , "a108") ?></h6>
                                        </div>
                                    </a>
                                </div>

<!--                                <div class="col-lg-4 col-md-6 col-sm-12">-->
<!--                                    <a href="https://www.youtube.com/@perfectuniversity4471" target="_blank" class="down_content_box">-->
<!--                                        <div class="down_content_box_left">-->
<!--                                            <img src="/frontend/web/images/youtube.svg" alt="">-->
<!--                                        </div>-->
<!--                                        <div class="down_content_box_right">-->
<!--                                            <p>YouTube</p>-->
<!--                                            <h6>--><?php //= Yii::t("app" , "a108") ?><!--</h6>-->
<!--                                        </div>-->
<!--                                    </a>-->
<!--                                </div>-->

                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <a href="https://maps.app.goo.gl/9KPyvDf1pYwWpaYeA" target="_blank" class="down_content_box">
                                        <div class="down_content_box_left">
                                            <i class="fa-solid fa-location-dot"></i>
                                        </div>
                                        <div class="down_content_box_right">
                                            <p><?= Yii::t("app" , "a9") ?></p>
                                            <h6><?= Yii::t("app" , "a10") ?></h6>
                                        </div>
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
