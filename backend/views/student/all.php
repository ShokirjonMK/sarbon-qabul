<?php

use common\models\StudentDtm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\models\Status;
use kartik\export\ExportMenu;
use yii\widgets\LinkPager;


/** @var yii\web\View $this */
/** @var common\models\StudentDtmSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var \common\models\EduYearType $edu_type */

$this->title = Yii::t('app', 'Students');
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$user = Yii::$app->user->identity;
?>
<div class="student-dtm-index">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <?php
            foreach ($breadcrumbs['item'] as $item) {
                echo "<li class='breadcrumb-item'><a href='". Url::to($item['url']) ."'>". $item['label'] ."</a></li>";
            }
            ?>
            <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
        </ol>
    </nav>

    <?php echo $this->render('_all-search', ['model' => $searchModel]); ?>

    <?php $data = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'F.I.O',
            'contentOptions' => ['date-label' => 'F.I.O'],
            'format' => 'raw',
            'value' => function($model) {
                if ($model->user->step == 1) {
                    return "---- ---- ----";
                }
                return $model->last_name.' '.$model->first_name.' '.$model->middle_name. " | ".$model->passport_serial.' '.$model->passport_number;
            },
        ],
        [
            'attribute' => 'Pasport ma\'lumoti',
            'contentOptions' => ['date-label' => 'F.I.O'],
            'format' => 'raw',
            'value' => function($model) {
                if ($model->user->step == 1) {
                    return "-- -------";
                }
                return $model->passport_serial.' | '.$model->passport_number;
            },
        ],
        [
            'attribute' => 'Pasport pin',
            'contentOptions' => ['date-label' => 'pin'],
            'format' => 'raw',
            'value' => function($model) {
                return $model->passport_pin;
            },
        ],
        [
            'attribute' => 'Tel raqam',
            'contentOptions' => ['date-label' => 'Tel raqam'],
            'format' => 'raw',
            'value' => function($model) {
                return $model->user->username;
            },
        ],
        [
            'attribute' => 'Ro\'yxatga olingan sana',
            'contentOptions' => ['date-label' => 'Ro\'yxatga olingan sana'],
            'format' => 'raw',
            'value' => function($model) {
                $target = $model->user->target;
                $text = '';
                if ($target) {
                    $text = "<div class='badge-table-div active mt-2'>".$model->user->target->name."</div>";
                }
                return "<div><div>". date("Y-m-d H:i:s" , $model->user->created_at) ."</div>$text</div>";
            },
        ],
        [
            'attribute' => 'Ariza tasdiqlagan vaqti',
            'contentOptions' => ['date-label' => 'Ro\'yxatga olingan sana'],
            'format' => 'raw',
            'value' => function($model) {
                $user = $model->user;
                if ($user->step_confirm_time == null) {
                    return "-------";
                }
                return "<div><div>". date("Y-m-d H:i:s" , $model->user->step_confirm_time) ."</div></div>";
            },
        ],
        [
            'attribute' => 'DOMEN',
            'contentOptions' => ['date-label' => 'DOMEN'],
            'format' => 'raw',
            'value' => function($model) {
                $consId = $model->user->cons_id;
                if ($consId == 5) {
                    $domen = 'edu';
                } elseif ($consId == 4) {
                    $domen = 'tashkent';
                } elseif ($consId == 6) {
                    $domen = 'talim';
                } elseif ($consId == 7) {
                    $domen = 'uz';
                } elseif ($consId == 8) {
                    $domen = 'sam';
                } elseif ($consId == 9) {
                    $domen = 'uzedu';
                } elseif ($consId == 10) {
                    $domen = 'mahalla';
                } elseif ($consId == 11) {
                    $domen = 'c-cons';
                } elseif ($consId == 12) {
                    $domen = 's-lion';
                } else {
                    $domen = 'qabul';
                }
                $text = $domen.".sarbon.university";
                return "<a href='https://".$text."' class='badge-table-div active'> ".$text." </a>";
            },
        ],
        [
            'attribute' => 'Batafsil',
            'contentOptions' => ['date-label' => 'Batafsil'],
            'format' => 'raw',
            'value' => function($model) {
                return "<a href='". Url::to(['view' , 'id' => $model->id]) ."' class='badge-table-div active'><span>Batafsil</span></a>";
            },
        ],
    ]; ?>

    <div class="form-section">
        <div class="form-section_item">
            <div class="d-flex justify-content-between align-items-center">
                <p><b>Jami soni: &nbsp; <?= $dataProvider->totalCount ?></b></p>

<!--                --><?php //if ($user->id != 1) : ?>
                    <div class="page_export">
                        <?php echo ExportMenu::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => $data,
                            'asDropdown' => false,
                        ]); ?>
                    </div>
<!--                --><?php //endif; ?>

            </div>
        </div>
    </div>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $data,
        'pager' => [
            'class' => LinkPager::class,
            'pagination' => $dataProvider->getPagination(),
            'firstPageLabel' => 'First',
            'lastPageLabel' => 'Last',
            'nextPageLabel' => false,
            'prevPageLabel' => false,
            'maxButtonCount' => 10,
        ],
    ]); ?>

</div>
