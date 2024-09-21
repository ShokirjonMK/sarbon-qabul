<?php
use yii\helpers\Url;
?>
<div class="head_mobile_index">
    <div class="head_mobile_index_item">
        <?= $this->render('_hd') ; ?>
        <div class="root-item">
            <div class="sarbon_text">
                <p>SARBON UNIVERSITETI</p>
                <h1>
                    Oʻzbekiston Respublikasi Vazirlar Mahkamasining tegishli qarori bilan tashkil etilgan hamda Oliy ta’lim, fan va innovatsiyalar vazirligi tomonidan berilgan litsenziyaga ega <span>Sarbon Universiteti</span> qabul jarayonlari boshlanganligini e'lon qiladi
                </h1>
                <div class="banner-link">
                    <a href="<?= Url::to(['#']) ?>">
                        <?= Yii::t("app" , "a4") ?>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" height=".9rem" fill="none" viewBox="0 0 17 12">
                                <path stroke="currentColor" stroke-miterlimit="10" d="M8.647 11.847S10.007 7.23 16 6.336M8.645.805S10.005 5.423 16 6.317M0 6.27h15.484"></path>
                            </svg>
                            <svg width="20px" xmlns="http://www.w3.org/2000/svg" height=".9rem" fill="none" viewBox="0 0 17 12">
                                <path stroke="currentColor" stroke-miterlimit="10" d="M8.647 11.847S10.007 7.23 16 6.336M8.645.805S10.005 5.423 16 6.317M0 6.27h15.484"></path>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>