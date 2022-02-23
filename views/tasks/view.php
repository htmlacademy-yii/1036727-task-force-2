<?php

/** @var yii\web\View $this */
/** @var app\models\Task $task */
/** @var app\models\forms\CompleteForm $completeForm */
/** @var app\models\forms\ResponseForm $responseForm */
/** @var anatolev\service\TaskAction[] $availableActions */

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\ModalFormAsset;
use app\widgets\ModalForm;
use anatolev\helpers\FileHelper;
use anatolev\helpers\FormatHelper;
use anatolev\helpers\TaskHelper;

ModalFormAsset::register($this);

$this->title = Html::encode($task->name);

?>
<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= Html::encode($task->name) ?></h3>

        <?php if (isset($task->budget)): ?>
            <p class="price price--big"><?= Html::encode($task->budget) ?> &#8381;</p>
        <?php endif; ?>

    </div>
    <p class="task-description"><?= Html::encode($task->description) ?></p>

    <?php foreach ($availableActions as $action): ?>
        <a
            href="#"
            class="button button--blue open-modal"
            data-for="<?= Html::encode($action::FORM_TYPE) ?>"
        ><?= Html::encode($action->getName()) ?></a>
    <?php endforeach; ?>

    <div class="task-map">
        <img class="map" src="/img/map.png" width="725" height="346" alt="Новый арбат, 23, к. 1">
        <p class="map-address town"><?= Html::encode($task->city->name ?? '') ?></p>
        <p class="map-address"><?= Html::encode($task->location ?? '') ?></p>
    </div>

    <?php if ($replies = TaskHelper::getTaskReplies($task)): ?>

        <h4 class="head-regular"><?= TaskHelper::getRepliesHeader($task, count($replies)) ?></h4>
        <?php $isActualTask = TaskHelper::isActual($task); ?>

        <?php foreach ($replies as $reply): ?>

            <?= $this->render('_reply', ['reply' => $reply, 'isActualTask' => $isActualTask]) ?>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<div class="right-column">
    <div class="right-card black info-card">
        <h4 class="head-card">Информация о задании</h4>
        <dl class="black-list">
            <dt>Статус</dt>
            <dd><?= Html::encode($task->status->name) ?></dd>

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
            <h4 class="head-card">Файлы задания</h4>
            <ul class="enumeration-list">

                <?php foreach ($files as $file): ?>

                    <li class="enumeration-item">
                        <a
                            href="<?= Url::to([Yii::getAlias('@files') . '/' . $file->path]) ?>"
                            class="link link--block link--clip"
                            download
                        ><?= Html::encode(FileHelper::getName($file->path)) ?></a>
                        <p class="file-size"><?= FileHelper::getSize($file->path) ?> Кб</p>
                    </li>

                <?php endforeach; ?>

            </ul>
        </div>
    <?php endif; ?>

</div>

<?= ModalForm::widget(['formType' => 'cancel']) ?>
<?= ModalForm::widget(['formType' => 'refuse']) ?>
<?= ModalForm::widget(['formType' => 'complete', 'model' => $completeForm]) ?>
<?= ModalForm::widget(['formType' => 'response', 'model' => $responseForm]) ?>

<div style="display: none;" class="overlay"></div>
