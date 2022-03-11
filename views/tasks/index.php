<?php

/** @var yii\web\View $this */
/** @var array $period_values */
/** @var app\models\Task[] $tasks */
/** @var app\models\Category[] $categories */
/** @var app\models\forms\SearchForm $model */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Новые задания';

?>
<div class="left-column">
    <h3 class="head-main head-task">Новые задания</h3>

    <?php foreach ($tasks as $task): ?>

        <?= $this->render('_task', ['task' => $task]) ?>

    <?php endforeach; ?>

    <div class="pagination-wrapper">
        <ul class="pagination-list">
            <li class="pagination-item mark">
                <a href="#" class="link link--page"></a>
            </li>
            <li class="pagination-item">
                <a href="#" class="link link--page">1</a>
            </li>
            <li class="pagination-item pagination-item--active">
                <a href="#" class="link link--page">2</a>
            </li>
            <li class="pagination-item">
                <a href="#" class="link link--page">3</a>
            </li>
            <li class="pagination-item mark">
                <a href="#" class="link link--page"></a>
            </li>
        </ul>
    </div>
</div>
<div class="right-column">
    <div class="right-card black">
        <div class="search-form">

            <?php $form = ActiveForm::begin([
                'options' => ['autocomplete' => 'off'],
            ]) ?>

                <?= Html::tag('h4', 'Категории', ['class' => 'head-card']) ?>
                <?= $form->field($model, 'categories[]', ['template' => '{input}'])->checkboxList(
                    ArrayHelper::map($categories, 'id', 'name'),
                    [
                        'separator' => '<br>',
                        'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                            settype($model->categories, 'array');
                            $checked = in_array($value, $model->categories) ? ' checked' : '';
                            $html = "<input type=\"checkbox\" name=\"{$name}\" value=\"{$value}\"{$checked}>";

                            return "<label>{$html}{$label}</label>";
                        }
                    ]
                ); ?>

                <?= Html::tag('h4', 'Дополнительно', ['class' => 'head-card']) ?>
                <?= $form
                    ->field($model, 'isTelework', ['template' => "{input}\n{label}"])
                    ->checkbox(enclosedByLabel: false) ?>

                <?= $form
                    ->field($model, 'no_response', ['template' => "{input}\n{label}"])
                    ->checkbox(enclosedByLabel: false) ?>

                <?= Html::tag('h4', 'Период', ['class' => 'head-card']) ?>
                <?= $form->field($model, 'period_value', ['template' => '{input}'])->dropDownList($period_values) ?>

                <?= Html::submitButton('Искать', ['class' => 'button button--blue']) ?>

            <?php ActiveForm::end() ?>

        </div>
    </div>
</div>
