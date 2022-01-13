<?php

/* @var $this \yii\web\View */
/* @var $cities \app\models\City[] */
/* @var $model \app\models\forms\SignupForm */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '';

?>
<div class="center-block">
    <div class="registration-form regular-form">

        <?php $form = ActiveForm::begin([
            'id' => 'registration-form',
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

        <!-- <form>
            <h3 class="head-main head-task">Регистрация нового пользователя</h3>
            <div class="form-group">
                <label class="control-label" for="username">Ваше имя</label>
                <input id="username" type="text">
            </div>
            <div class="half-wrapper">
                <div class="form-group">
                    <label class="control-label" for="email-user">Email</label>
                    <input id="email-user" type="email">
                </div>
                <div class="form-group">
                    <label class="control-label" for="town-user">Город</label>
                    <select id="town-user">
                        <option>Москва</option>
                        <option>Санкт-Петербург</option>
                        <option>Владивосток</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="password-user">Пароль</label>
                <input id="password-user" type="password">
            </div>
            <div class="form-group">
                <label class="control-label" for="password-repeat-user">Повтор пароля</label>
                <input id="password-repeat-user" type="password">
            </div>
            <div class="form-group">
                <input id="response-user" type="checkbox" checked>
                <label class="control-label" for="response-user">я собираюсь откликаться на заказы</label>
            </div>
            <input type="button" class="button button--blue" value="Создать аккаунт">
        </form> -->
    </div>
</div>
