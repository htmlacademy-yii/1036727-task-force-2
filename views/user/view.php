<?php

/* @var $this \yii\web\View */
/* @var $user \app\models\User */

use yii\helpers\Html;
use yii\helpers\Url;
use anatolev\helpers\FormatHelper;
use anatolev\helpers\UserHelper;

?>
<div class="left-column">
    <h3 class="head-main"><?= Html::encode($user->name) ?></h3>
    <div class="user-card">
        <div class="photo-rate">

            <img
                class="card-photo"
                src="<?= Html::encode(UserHelper::getAvatar($user)) ?>"
                width="191"
                height="190"
                alt="Фото пользователя"
            >

            <div class="card-rate">
                <div class="stars-rating big">

                    <?php for ($i = 1; $i <= \Yii::$app->params['maxUserRating']; $i++): ?>
                        <span class="<?= $i <= $user->profile->current_rate ? 'fill-star' : '' ?>">&nbsp;</span>
                    <?php endfor; ?>

                </div>
                <span class="current-rate"><?= Html::encode($user->profile->current_rate) ?></span>
            </div>
        </div>
        <p class="user-description"><?= Html::encode($user->profile->about ?? '') ?></p>
    </div>
    <div class="specialization-bio">
        <div class="specialization">
            <p class="head-info">Специализации</p>
            <ul class="special-list">

                <?php foreach ($user->categories as $category): ?>
                    <li class="special-item">
                        <a
                            href="<?= Url::to(['tasks/index', 'category' => $category->inner_name]) ?>"
                            class="link link--regular"
                        ><?= Html::encode($category->name) ?></a>
                    </li>
                <?php endforeach; ?>

            </ul>
        </div>
        <div class="bio">
            <p class="head-info">Био</p>
            <p class="bio-info">
                <span class="country-info">Россия</span>,
                <span class="town-info"><?= Html::encode($user->city->name) ?></span>,

                <?php if (isset($user->profile->birthday)): ?>
                    <?php $age_info = explode(' ', FormatHelper::getRelativeTime($user->profile->birthday)); ?>
                    <span class="age-info"><?= $age_info[0] ?></span> <?= $age_info[1] ?>
                <?php endif; ?>

            </p>
        </div>
    </div>

    <?php if ($tasks = array_filter($user->tasks0, fn($task) => $task?->review)): ?>
        <h4 class="head-regular">Отзывы заказчиков</h4>

        <?php foreach ($tasks as $task): ?>
            <div class="response-card">

                <img
                    class="customer-photo"
                    src="<?= Html::encode(UserHelper::getAvatar($task->customer)) ?>"
                    width="120"
                    height="127"
                    alt="Фото заказчиков"
                >

                <div class="feedback-wrapper">
                    <p class="feedback"><?= Html::encode($task->review->comment) ?></p>
                    <p class="task">
                        Задание «<a
                            href="<?= Url::to(['tasks/view', 'id' => $task->id]) ?>"
                            class="link link--small"
                        ><?= Html::encode($task->name) ?></a>»
                        <?= $task->done ? 'выполнено' : 'провалено' ?>
                    </p>
                </div>
                <div class="feedback-wrapper">
                    <div class="stars-rating small">

                        <?php for ($i = 1; $i <= \Yii::$app->params['maxUserRating']; $i++): ?>
                            <span class="<?= $i <= $task->review->rate ? 'fill-star' : '' ?>">&nbsp;</span>
                        <?php endfor; ?>

                    </div>
                    <p class="info-text">
                        <span class="current-time"><?= FormatHelper::getRelativeTime($task->review->dt_add) ?> </span>назад
                    </p>
                </div>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>
<div class="right-column">
    <div class="right-card black">
        <h4 class="head-card">Статистика исполнителя</h4>
        <dl class="black-list">
            <dt>Всего заказов</dt>
            <dd>
                <?= Html::encode($user->profile->done_task_count) ?> выполнено,
                <?= Html::encode($user->profile->failed_task_count) ?> провалено
            </dd>

            <dt>Место в рейтинге</dt>
            <dd><?= Html::encode($user->place_in_rating) ?> место</dd>

            <dt>Дата регистрации</dt>
            <dd><?= date('j F, H:i', strtotime($user->dt_add)) ?></dd>

            <dt>Статус</dt>
            <dd><?= Html::encode($user->busy_status) ?></dd>
        </dl>
    </div>
    <div class="right-card white">
        <h4 class="head-card">Контакты</h4>
        <ul class="enumeration-list">

            <?php

            $contacts = [
                [
                    'property' => 'contact_phone',
                    'protocol' => 'tel:',
                    'class_modifier' => 'phone'
                ],
                [
                    'property' => 'email',
                    'protocol' => 'mailto:',
                    'class_modifier' => 'email'
                ],
                [
                    'property' => 'contact_tg',
                    'protocol' => 'https://t.me/',
                    'class_modifier' => 'tg'
                ]
            ];

            ?>

            <?php foreach ($contacts as $contact): ?>

                <?php if ($value = $user->profile->{$contact['property']} ?? $user->{$contact['property']} ?? null): ?>
                    <li class="enumeration-item">
                        <a
                            href="<?= $contact['protocol'] ?><?= Html::encode($value) ?>"
                            class="link link--block link--<?= $contact['class_modifier'] ?>"
                        ><?= Html::encode($value) ?></a>
                    </li>
                <?php endif; ?>

            <?php endforeach; ?>

        </ul>
    </div>
</div>
