<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<section style="display: none;" class="modal form-modal refusal-form" id="cancel-form">
    <h2>Отмена задания</h2>

    <!-- <p>
      Вы собираетесь отказаться от выполнения задания.
      Это действие приведёт к снижению вашего рейтинга.
      Вы уверены?
    </p> -->

    <!-- <button class="button__form-modal button" id="close-modal" type="button">Отмена</button> -->
    <a
        style="float: right;"
        href="<?= Url::to(['tasks/cancel', 'id' => Yii::$app->request->get('id')]) ?>"
        class="button__form-modal refusal-button button"
    >Отменить</a>
    <button class="form-modal-close" type="button">Закрыть</button>

</section>
