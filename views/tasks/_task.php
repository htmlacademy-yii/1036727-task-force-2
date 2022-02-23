<?php

/** @var yii\web\View $this */
/** @var app\models\Task $task */

use yii\helpers\Html;
use yii\helpers\Url;
use anatolev\helpers\FormatHelper;
use anatolev\helpers\TaskHelper;

?>
<div class="task-card">
    <div class="header-task">
        <?php $href = Url::to(['tasks/view', 'id' => $task->id]); ?>
        <a href="<?= $href ?>" class="link link--block link--big"><?= Html::encode($task->name) ?></a>

        <?php if (isset($task->budget)): ?>
            <p class="price price--task"><?= Html::encode($task->budget) ?> &#8381;</p>
        <?php endif; ?>

    </div>
    <p class="info-text">
        <span class="current-time"><?= FormatHelper::getRelativeTime($task->dt_add) ?> </span>назад
    </p>
    <p class="task-text"><?= Html::encode($task->description) ?></p>
    <div class="footer-task">

        <?php if (isset($task->location)): ?>
            <p class="info-text town-text"><?= Html::encode($task->location) ?></p>
        <?php endif; ?>

        <p class="info-text category-text"><?= TaskHelper::category($task) ?></p>
        <a href="<?= $href ?>" class="button button--black">Смотреть Задание</a>
    </div>
</div>
