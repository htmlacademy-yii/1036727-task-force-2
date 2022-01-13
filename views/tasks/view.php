<?php

/* @var $this \yii\web\View */
/* @var $task \app\models\Task */

use yii\helpers\Html;
use yii\helpers\Url;
use anatolev\helpers\FileHelper;
use anatolev\helpers\FormatHelper;
use anatolev\helpers\UserHelper;

?>
<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= Html::encode($task->name) ?></h3>
        <p class="price price--big"><?= Html::encode($task->budget ?? '') ?></p>
    </div>
    <p class="task-description"><?= Html::encode($task->description) ?></p>
    <a href="#" class="button button--blue">Откликнуться на задание</a>
    <div class="task-map">
        <img class="map" src="/img/map.png" width="725" height="346" alt="Новый арбат, 23, к. 1">
        <p class="map-address town"><?= Html::encode($task->city->name ?? '') ?></p>
        <p class="map-address"><?= Html::encode($task->address ?? '') ?></p>
    </div>

    <?php if (!empty($task->replies)): ?>
        <h4 class="head-regular">Отклики на задание</h4>

        <?php foreach ($task->replies as $reply): ?>
            <div class="response-card">

                <img
                    class="customer-photo"
                    src="<?= Html::encode(UserHelper::getAvatar($reply->author)) ?>"
                    width="146"
                    height="156"
                    alt="Фото заказчиков"
                >

                <div class="feedback-wrapper">
                    <a
                        href="<?= Url::to(['user/view', 'id' => $reply->author->id]) ?>"
                        class="link link--block link--big"
                    ><?= Html::encode($reply->author->name) ?></a>
                    <div class="response-wrapper">
                        <div class="stars-rating small">

                            <?php for ($i = 1; $i <= Yii::$app->params['maxUserRating']; $i++): ?>
                                <span class="<?= $i <= $reply->author->profile->current_rate ? 'fill-star' : '' ?>">&nbsp;</span>
                            <?php endfor; ?>

                        </div>
                        <?php

                        $reviews = array_filter($reply->author->tasks0, fn($task) => $task?->review);
                        $review_count = count($reviews);
                        $review_word = FormatHelper::getNounPluralForm($review_count, 'отзыв', 'отзыва', 'отзывов');

                        ?>
                        <p class="reviews"><?= "{$review_count} {$review_word}" ?></p>
                    </div>
                    <p class="response-message"><?= Html::encode($reply->comment ?? '') ?></p>
                </div>
                <div class="feedback-wrapper">
                    <p class="info-text">
                        <span class="current-time"><?= FormatHelper::getRelativeTime($reply->dt_add) ?> </span>назад
                    </p>
                    <p class="price price--small"><?= Html::encode($reply->price ?? '') ?></p>
                </div>
                <div class="button-popup">
                    <a href="#" class="button button--blue button--small">Принять</a>
                    <a href="#" class="button button--orange button--small">Отказать</a>
                </div>
            </div>
        <?php endforeach; ?>

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
                            href="<?= Url::to([FileHelper::FILES_UPLOAD_DIR . '/' . $file->path]) ?>"
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
