<?php

/** @var yii\web\View $this */
/** @var ?string $filter */
/** @var bool $isExecutor */
/** @var app\models\Task[] $tasks */

use yii\helpers\Url;
use yii\widgets\Menu;
use anatolev\helpers\TaskHelper;

?>
<div class="left-menu">
    <h3 class="head-main head-task">Мои задания</h3>
    <ul class="side-menu-list">

        <?php
        if ($isExecutor):
            $items = [
                ['label' => 'В процессе', 'url' => ['tasks/user-tasks', 'filter' => 'progress']],
                ['label' => 'Просрочено','url' => ['tasks/user-tasks', 'filter' => 'overdue']],
                ['label' => 'Закрытые', 'url' => ['tasks/user-tasks', 'filter' => 'closed']]
            ];
        else:
            $items = [
                ['label' => 'Новые', 'url' => ['tasks/user-tasks', 'filter' => 'new']],
                ['label' => 'В процессе','url' => ['tasks/user-tasks', 'filter' => 'progress']],
                ['label' => 'Закрытые', 'url' => ['tasks/user-tasks', 'filter' => 'closed']]
            ];
        endif;
        ?>

        <?= Menu::widget([
            'items' => $items,
            'activeCssClass' => 'side-menu-item--active',
            'itemOptions' => ['class' => 'side-menu-item'],
            'labelTemplate' => '<a class="link link--nav">{label}</a>',
            'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
            'options' => ['class' => 'side-menu-list']
        ]); ?>

    </ul>
</div>
<div class="left-column left-column--task">
    <h3 class="head-main head-regular"><?= TaskHelper::getFilterDesc($filter) ?></h3>

    <?php foreach ($tasks as $task): ?>

        <?= $this->render('_task', ['task' => $task]) ?>

    <?php endforeach; ?>

</div>
