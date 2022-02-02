<?php

/** @var yii\web\View $this */
/** @var app\models\forms\CompleteForm $model */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use anatolev\service\Task;

?>
<section style="display: none;" class="modal form-modal completion-form" id="complete-form">
    <?= Html::tag('h2', 'Завершение задания') ?>
    
    <?php $form = ActiveForm::begin([
        'action' => Url::to(['tasks/complete']),
        'options' => ['autocomplete' => false],
    ]); ?>

        <?= Html::tag('p', 'Задание выполнено?', ['class' => 'form-modal-description']) ?>
        <?= $form->field($model, 'task_status', ['template' => '{input}{error}'])->radioList(
            [Task::STATUS_DONE_ID => 'Да', Task::STATUS_FAILED_ID => 'Возникли проблемы'],
            [
                'item' => function ($index, $label, $name, $checked, $value) {
                    $classModifier = $value == Task::STATUS_DONE_ID ? 'yes' : 'difficult';
                    $inputClass = "visually-hidden completion-input completion-input--{$classModifier}";
                    $input = "<input class=\"{$inputClass}\" type=\"radio\" id=\"completion-radio--{$classModifier}\" name=\"{$name}\" value=\"{$value}\">";

                    $labelClass = "completion-label completion-label--{$classModifier}";
                    $label = "<label class=\"{$labelClass}\" for=\"completion-radio--{$classModifier}\">{$label}</label>";

                    return $input . $label;
                }
        ]); ?>

        <?= $form->field($model, 'comment', ['labelOptions' => ['class' => 'form-modal-description']])
            ->textarea(['class' => 'input textarea', 'rows' => '4', 'placeholder' => 'Place your text']) ?>
        <?= $form->field($model, 'task_id', ['template' => '{input}'])->hiddenInput(['value' => Yii::$app->request->get('id')]) ?>

        <?= Html::tag('p', 'Оценка', ['class' => 'form-modal-description']) ?>
        <div class="feedback-card__top--name completion-form-star">
            <span class="star-disabled" data-rating="1"></span>
            <span class="star-disabled" data-rating="2"></span>
            <span class="star-disabled" data-rating="3"></span>
            <span class="star-disabled" data-rating="4"></span>
            <span class="star-disabled" data-rating="5"></span>
        </div>
        <p></p>

        <?= $form->field($model, 'rating', ['template' => '{input}{error}'])->hiddenInput(['value' => '0', 'id' => 'rating']) ?>

        <?= Html::submitButton('Отправить', ['class' => 'button modal-button']) ?>

    <?php ActiveForm::end(); ?>

    <?= Html::button('Закрыть', ['class' => 'form-modal-close']) ?>

</section>
