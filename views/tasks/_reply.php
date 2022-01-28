<?php

/* @var $this yii\web\View */
/* @var $reply app\models\Reply */

use yii\helpers\Html;
use yii\helpers\Url;
use anatolev\helpers\FormatHelper;
use anatolev\helpers\UserHelper;

?>
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
            href="<?= Url::to(['profile/view', 'id' => $reply->author->id]) ?>"
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

    <?php if ($reply->task->customer_id === Yii::$app->user->id): ?>
        <div class="button-popup">
            <a href="#" class="button button--blue button--small">Принять</a>
            <a href="#" class="button button--orange button--small">Отказать</a>
        </div>
    <?php endif; ?>

</div>
