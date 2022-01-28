<?php

/* @var $this yii\web\View */
/* @var $model app\models\forms\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<section class="modal enter-form form-modal" id="enter-form">
    <h2>Вход на сайт</h2>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'action' => 'login',
        'enableAjaxValidation' => true,
        'options' => ['autocomplete' => 'off'],
        'fieldConfig' => [
            'labelOptions' => ['class' => 'form-modal-description'],
            'inputOptions' => ['class' => 'enter-form-email input input-middle']
        ]
    ]); ?>

        <?= $form->field($model, 'email')->input('email') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= Html::submitButton('Войти', ['class' => 'button']) ?>

    <?php ActiveForm::end(); ?>

    <?= Html::button('Закрыть', ['class' => 'form-modal-close']) ?>

</section>
