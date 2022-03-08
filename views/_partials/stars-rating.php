<?php

/** @var yii\web\View $this */
/** @var int $rating */

?>
<?php for ($i = 1; $i <= Yii::$app->params['maxUserRating']; $i++): ?>
    <span class="<?= $i <= intval($rating) ? 'fill-star' : '' ?>">&nbsp;</span>
<?php endfor; ?>
