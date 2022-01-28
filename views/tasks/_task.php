<?php

/* @var $this yii\web\View */
/* @var $task app\models\Task */

use yii\helpers\Html;
use yii\helpers\Url;
use anatolev\helpers\FormatHelper;

?>
<div class="task-card">
    <div class="header-task">
        <?php $href = Url::to(['tasks/view', 'id' => $task->id]); ?>
        <a href="<?= $href ?>" class="link link--block link--big"><?= Html::encode($task->name) ?></a>
        <p class="price price--task"><?= Html::encode($task->budget) ?></p>
    </div>
    <p class="info-text">
        <span class="current-time"><?= FormatHelper::getRelativeTime($task->dt_add) ?> </span>назад
    </p>
    <p class="task-text"><?= Html::encode($task->description) ?></p>
    <div class="footer-task">
        <p class="info-text town-text"><?= Html::encode($task->location ?? '') ?></p>
        <p class="info-text category-text"><?= Html::encode($task->category->name) ?></p>
        <a href="<?= $href ?>" class="button button--black">Смотреть Задание</a>
    </div>
</div>
