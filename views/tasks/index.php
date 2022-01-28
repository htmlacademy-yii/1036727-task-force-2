<?php

/* @var $this yii\web\View */
/* @var $period_values array */
/* @var $tasks app\models\Task[] */
/* @var $categories app\models\Category[] */
/* @var $model app\models\forms\SearchForm */

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
                'id' => 'search-form',
                'fieldConfig' => [
                    'template' => "{input}"
                ]
            ]) ?>

                <h4 class="head-card">Категории</h4>
                <?= $form->field($model, 'categories[]')->checkboxList(
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
                ) ?>

                <h4 class="head-card">Дополнительно</h4>
                <?= $form
                    ->field($model, 'without_performer', ['template' => "{input}\n{label}"])
                    ->checkbox(enclosedByLabel: false) ?>

                <h4 class="head-card">Период</h4>
                <?= $form->field($model, 'period_value')->dropDownList($period_values) ?>

                <?= Html::submitButton('Искать', ['class' => 'button button--blue']) ?>

            <?php ActiveForm::end() ?>

        </div>
    </div>
</div>
