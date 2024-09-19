<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use yii\bootstrap5\Html;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">

    <?= $this->render('_meta'); ?>
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?= $this->render('_css'); ?>
    <link href="/frontend/web/images/sarbon_icon.svg" rel="icon">
    <link href="/frontend/web/images/sarbon_icon.svg" rel="apple-touch-icon">
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <div class="pageLoading">
        <div class="ik_loader">
            <div class="ik_load"></div>
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"  xml:space="preserve" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 68.95 68.95">
             <defs>
                 <style type="text/css">
                     .fil0 {fill:#fff}
                 </style>
             </defs>
                <g id="Слой_x0020_1">
                    <metadata id="CorelCorpID_0Corel-Layer"/>
                    <path class="fil0" d="M68.95 0l-68.95 0 0 0c0,37.92 31.03,68.95 68.95,68.95l0 0 0 -68.95zm-68.95 52.51l0 0 0 16.44 16.45 0 0 0c0,-9.04 -7.4,-16.44 -16.45,-16.44z"/>
                </g>
            </svg>
        </div>
        <h5>SARBON <br> UNIVERSITY </h5>
    </div>

    <div class="root">

        <?= $this->render('_sidebar'); ?>

        <div class="root_right">

            <?= $this->render('_header'); ?>

            <div class="content left-260">
                <div class="main-content">
                    <?= $content ?>
                </div>
            </div>

        </div>

    </div>

    <?= $this->render('_script'); ?>
    <?php $this->endBody() ?>
    <?= Alert::widget() ?>
</body>

</html>
<?php $this->endPage();
