<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;
use anatolev\helpers\UserHelper;

$user = $this->context->user;

?>
<header class="page-header">
    <nav class="main-nav">
        <a href="<?= Yii::$app->homeUrl ?>" class="header-logo">
            <img class="logo-image" src="/img/logotype.png" width="227" height="60" alt="taskforce">
        </a>

        <?php if (!Yii::$app->user->isGuest): ?>
            <div class="nav-wrapper">

                <?= Menu::widget([
                    'items' => [
                        ['label' => 'Новое', 'url' => ['#']],
                        ['label' => 'Новые задания','url' => ['tasks/index']],
                        ['label' => 'Создать задание', 'url' => ['#']],
                        ['label' => 'Настройки', 'url' => ['#']]
                    ],
                    'activeCssClass' => 'list-item--active',
                    'itemOptions' => ['class' => 'list-item'],
                    'labelTemplate' => '<a class="link link--nav">{label}</a>',
                    'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
                    'options' => ['class' => 'nav-list']
                ]); ?>

            </div>
        <?php endif; ?>

    </nav>

    <?php if (!Yii::$app->user->isGuest): ?>
        <div class="user-block">
            <a href="<?= $user->is_executor ? Url::to(['user/view', 'id' => $user->id]) : '#' ?>">
                <img
                    class="user-photo"
                    src="<?= Html::encode(UserHelper::getAvatar($user)) ?>"
                    width="55"
                    height="55"
                    alt="Аватар"
                >
            </a>
            <div class="user-menu">
                <p class="user-name"><?= Html::encode($user->name) ?></p>
                <div class="popup-head">

                    <?= Menu::widget([
                        'items' => [
                            ['label' => 'Настройки', 'url' => ['#']],
                            ['label' => 'Связаться с нами', 'url' => ['#']],
                            ['label' => 'Выход из системы', 'url' => ['user/logout']]
                        ],
                        'itemOptions' => ['class' => 'menu-item'],
                        'linkTemplate' => '<a href="{url}" class="link">{label}</a>',
                        'options' => ['class' => 'popup-menu']
                    ]); ?>

                </div>
            </div>
        </div>
    <?php endif; ?>

</header>