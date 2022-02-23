<?php

/** @var yii\web\View $this */
/** @var app\models\forms\RefuseForm $model */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<section style="display: none;" class="modal form-modal refusal-form" id="refuse-form">
    <h2>Отказ от задания</h2>

    <p>
      Вы собираетесь отказаться от выполнения задания.
      Это действие приведёт к снижению вашего рейтинга.
      Вы уверены?
    </p>

    <button class="button__form-modal button" id="close-modal" type="button">Отмена</button>
    <a
        style="float: right; border: 1px solid transparent;"
        href="<?= Url::to(['tasks/refuse', 'task_id' => Yii::$app->request->get('id')]) ?>"
        class="button__form-modal refusal-button button"
    >Отказаться</a>
    <button class="form-modal-close" type="button">Закрыть</button>

</section>
