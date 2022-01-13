<?php

/* @var $this \yii\web\View */
/* @var $cities \app\models\City[] */
/* @var $model \app\models\forms\SignupForm */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="center-block">
    <div class="registration-form regular-form">

        <?php $form = ActiveForm::begin([
            'id' => 'signup-form',
            'options' => ['autocomplete' => 'off']
        ]); ?>

            <h3 class="head-main head-task">Регистрация нового пользователя</h3>

            <?= $form->field($model, 'name')->textInput(); ?>

            <div class="half-wrapper">
                <?= $form->field($model, 'email')->input('email'); ?>
                <?= $form->field($model, 'city_id')->dropDownList(ArrayHelper::map($cities, 'id', 'name')); ?>
            </div>

            <?= $form->field($model, 'password')->passwordInput(); ?>
            <?= $form->field($model, 'password_repeat')->passwordInput(); ?>
            <?= $form->field($model, 'is_executor')->checkbox(enclosedByLabel: false) ?>

            <?= Html::submitInput('Создать аккаунт', [
                'class' => 'button button--blue',
                'style' => 'width: 642px; float: initial;'
            ]); ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>
