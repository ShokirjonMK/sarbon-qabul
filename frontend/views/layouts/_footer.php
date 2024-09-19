<?php
use yii\helpers\Url;
?>

<div class="ik_footer">
    <div class="root-item">
        <div class="ik_footer_box">
            <div class="mb_head d-flex justify-content-between align-items-center">
                <div class="mb_head_left">
                    <a href="<?= Url::to(['site/index']) ?>">
                        <img src="/frontend/web/images/sarbon_wh_logo.svg" alt="">
                    </a>
                </div>
            </div>

            <div class="mb_content">

                <div class="mb_menu_list">
                    <p><?= Yii::t("app" , "a5") ?></p>
                    <ul>
                        <li><a href="https://www.instagram.com/sarbonuniversiteti?igsh=MWRodnB0eG03MG1oOQ=="><i class="fa-brands fa-instagram"></i></a></li>
                        <li><a href="https://t.me/sarbonuniversity"><i class="fa-brands fa-telegram"></i></a></li>
                        <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
                    </ul>
                </div>


                <div class="mb_menu_list2">
                    <ul>
                        <li>
                            <a href="https://t.me/globalsoftline">
                                <span><?= Yii::t("app" , "a22") ?> &nbsp;&nbsp; <b>GLOBAL SOFTLINE GROUP</b></span>
                            </a>
                        </li>
                    </ul>
                </div>

                <img src="/frontend/web/images/sarbon_wh_logo_icon.svg" class="mb_vector_img">
            </div>
        </div>
    </div>
</div>