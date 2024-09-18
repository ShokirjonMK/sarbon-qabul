<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;

/** @var $model */
/** @var \common\models\Student $student */
/** @var \common\models\Student $user */
/** @var $id */
?>


<div class="ikb_border">
    <div class="ikb_border_item">
        <div class="ikb_border_item_box">

            <div class="step_bar">
                <div class="step_bar_abs">
                    <div class="step_bar_abs_animate"></div>
                </div>
                <div class="step_bar_ul">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <li class="<?php if ($i == $id) { echo "active";}  ?>">
                            <a href="<?php if ($i <= $user->step) { echo Url::to(['cabinet/step' , 'id' => $i]);} else { echo "javascript: void(0);";}  ?> ">
                                <?php if ($i < $user->step) : ?>
                                    <i class="fa-solid fa-check"></i>
                                <?php else: ?>
                                    <?= $i ?>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="mainBoxPage">
    <div class="<?php if ($id == 4) { echo "mainBoxPageItem2";} else { echo "mainBoxPageItem";}  ?>">
        <div class="mainBoxPageItemBoder">

            <div class="login_p">
                <img src="/frontend/web/images/sarbon_icon.svg" alt="">
                <?php if ($id == 1) :  ?>
                    <h4><?= Yii::t("app" , "a139") ?></h4>
                <?php elseif ($id == 2) :  ?>
                    <h4><?= Yii::t("app" , "a140") ?></h4>
                <?php elseif ($id == 3) :  ?>
                    <h4><?= Yii::t("app" , "a3") ?></h4>
                <?php elseif ($id == 4) :  ?>
                    <h4><?= Yii::t("app" , "a37") ?></h4>
                <?php else: ?>
                    <h4>-----------</h4>
                <?php endif; ?>

                <p><?= $id ?> - <?= Yii::t("app" , "a138") ?></p>
            </div>


            <?php if ($id == 1) : ?>
                <?= $this->render('_form-step1' , [
                    'model' => $model,
                    'student' => $student
                ]) ?>
            <?php elseif ($id == 2): ?>
                <?= $this->render('_form-step2' , [
                    'model' => $model,
                    'student' => $student
                ]) ?>
            <?php elseif ($id == 3): ?>
                <?php if ($student->edu_type_id == 1) :  ?>
                    <?= $this->render('_form-step3' , [
                        'model' => $model,
                        'student' => $student
                    ]) ?>
                <?php elseif ($student->edu_type_id == 2) :  ?>
                    <?= $this->render('_form-step32' , [
                        'model' => $model,
                        'student' => $student
                    ]) ?>
                <?php elseif ($student->edu_type_id == 3) :  ?>
                    <?= $this->render('_form-step33' , [
                        'model' => $model,
                        'student' => $student
                    ]) ?>
            <?php endif;  ?>



            <?php elseif ($id == 4): ?>
                <?= $this->render('_form-step4' , [
                    'model' => $model,
                    'student' => $student
                ]) ?>
            <?php endif; ?>

        </div>
    </div>
</div>