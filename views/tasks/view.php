<?php

/* @var $this yii\web\View */
/* @var $task app\models\Task */

use yii\helpers\Html;
use yii\helpers\Url;
use anatolev\helpers\FileHelper;
use anatolev\helpers\FormatHelper;
use anatolev\helpers\TaskHelper;
use anatolev\helpers\UserHelper;

?>
<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= Html::encode($task->name) ?></h3>
        <p class="price price--big"><?= Html::encode($task->budget ?? '') ?> &#8381;</p>
    </div>
    <p class="task-description"><?= Html::encode($task->description) ?></p>
    <a href="#" class="button button--blue">Откликнуться на задание</a>
    <div class="task-map">
        <img class="map" src="/img/map.png" width="725" height="346" alt="Новый арбат, 23, к. 1">
        <p class="map-address town"><?= Html::encode($task->city->name ?? '') ?></p>
        <p class="map-address"><?= Html::encode($task->location ?? '') ?></p>
    </div>

    <?php if ($replies = TaskHelper::getTaskReplies($task)): ?>
        <h4 class="head-regular">Отклики на задание</h4>

        <?php
        foreach ($replies as $reply):

            echo $this->render('_reply', ['reply' => $reply]);

        endforeach;
        ?>

    <?php endif; ?>

</div>

<div class="right-column">
    <div class="right-card black info-card">
        <h4 class="head-card">Информация о задании</h4>
        <dl class="black-list">
            <dt>Категория</dt>
            <dd><?= Html::encode($task->category->name) ?></dd>

            <dt>Дата публикации</dt>
            <dd><?= FormatHelper::getRelativeTime($task->dt_add) ?> назад</dd>

            <?php if (isset($task->expire)): ?>
                <dt>Срок выполнения</dt>
                <dd><?= date('j F, H:i', strtotime($task->expire)) ?></dd>
            <?php endif; ?>

        </dl>
    </div>

    <?php if ($files = FileHelper::getExist($task->files)): ?>
        <div class="right-card white file-card">
            <ul class="enumeration-list">

                <?php foreach ($files as $file): ?>

                    <li class="enumeration-item">
                        <a
                            href="<?= Url::to([Yii::getAlias('@files') . '/' . $file->path]) ?>"
                            class="link link--block link--clip"
                            download
                        ><?= Html::encode($file->path) ?></a>
                        <p class="file-size"><?= FileHelper::getSize($file->path) ?> Кб</p>
                    </li>

                <?php endforeach; ?>

            </ul>
        </div>
    <?php endif; ?>

</div>
