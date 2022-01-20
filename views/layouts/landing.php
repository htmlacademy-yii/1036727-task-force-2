<?php

/* @var $this yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\LandingAsset;
use app\models\forms\LoginForm;

LandingAsset::register($this);

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
<body class="landing">
<?php $this->beginBody() ?>

<div class="table-layout">

    <?= $this->render('_landing-header') ?>

    <main>
        <?= $content ?>
    </main>

    <?= $this->render('_landing-footer') ?>
    <?= $this->render('//modals/_login-form', ['model' => new LoginForm]) ?>

</div>
<div class="overlay"></div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
