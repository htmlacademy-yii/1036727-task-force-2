<?php

/** @var yii\web\View $this */
/** @var app\models\City[] $cities */
/** @var app\models\forms\SignupForm $model */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация';

?>
<div class="center-block">
    <div class="registration-form regular-form">

        <?php $form = ActiveForm::begin([
            'options' => ['autocomplete' => 'off']
        ]); ?>

            <?= Html::tag('h3', 'Регистрация нового пользователя', ['class' => 'head-main head-task']) ?>

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
