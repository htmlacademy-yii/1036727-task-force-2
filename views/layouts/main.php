<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\MainAsset;

MainAsset::register($this);

$this->registerMetaTag([
    'name' => 'description',
    'content' => Yii::$app->params['description']
]);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> | Taskforce</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?= $this->render('_main-header') ?>

<main class="main-content container">
    <?= $content ?>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
