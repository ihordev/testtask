<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>

<div class="users-item">
    <h2><?= Html::encode($model->username) ?></h2>
    <?= HtmlPurifier::process($model->country_code . $model->phone) ?>
</div>