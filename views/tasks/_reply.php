<?php

/** @var yii\web\View $this */
/** @var bool $isActualTask */
/** @var app\models\Reply $reply */

use yii\helpers\Html;
use yii\helpers\Url;
use anatolev\helpers\FormatHelper;
use anatolev\helpers\TaskHelper;
use anatolev\helpers\UserHelper;
use anatolev\service\Task;

UserHelper::getAvatar($reply->user);

?>
<div class="response-card">

    <img
        class="customer-photo"
        src="<?= Html::encode(UserHelper::getAvatar($reply->user)) ?>"
        width="146"
        height="156"
        alt="Фото заказчиков"
    >

    <div class="feedback-wrapper">
        <a
            href="<?= $reply->user->is_executor ? Url::to(['profile/view', 'id' => $reply->user->id]) : '#' ?>"
            class="link link--block link--big"
        ><?= Html::encode($reply->user->name) ?></a>
        <div class="response-wrapper">
            <div class="stars-rating small">

                <?php for ($i = 1; $i <= Yii::$app->params['maxUserRating']; $i++): ?>
                    <span class="<?= $i <= $reply->user->profile->current_rate ? 'fill-star' : '' ?>">&nbsp;</span>
                <?php endfor; ?>

            </div>
            <?php

            $reviews = array_filter($reply->user->tasks0, fn($task) => $task?->review);
            $review_count = count($reviews);
            $review_word = FormatHelper::getNounPluralForm($review_count, 'отзыв', 'отзыва', 'отзывов');

            ?>
            <p class="reviews"><?= "{$review_count} {$review_word}" ?></p>
        </div>

        <?php if (isset($reply->comment)): ?>
            <p class="response-message"><?= Html::encode($reply->comment) ?></p>
        <?php endif; ?>

    </div>
    <div class="feedback-wrapper">
        <p class="info-text">
            <span class="current-time"><?= FormatHelper::getRelativeTime($reply->dt_add) ?> </span>назад
        </p>

        <?php if (isset($reply->payment)): ?>
            <p class="price price--small"><?= Html::encode($reply->payment) ?> &#8381;</p>
        <?php endif; ?>

    </div>

    <?php if (
        $isActualTask
        && !$reply->denied
        && $reply->task->customer_id === Yii::$app->user->id
        && $reply->task->status->inner_name === Task::STATUS_NEW
    ): ?>
        <div class="button-popup">
            <a
                href="<?= Url::to(['reply/accept', 'reply_id' => $reply->id]) ?>"
                class="button button--blue button--small"
            >Принять</a>

            <a
                href="<?= Url::to(['reply/refuse', 'reply_id' => $reply->id]) ?>"
                class="button button--orange button--small"
            >Отказать</a>
        </div>
    <?php endif; ?>

</div>
