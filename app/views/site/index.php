<div class="jumbotron">
    <p class="lead"><?=$username?></p>
</div>

<?php

use yii\widgets\ListView;

echo ListView::widget([
    'dataProvider' => $listDataProvider,
    'itemView' => 'user/_list',

    'options' => [
        'tag' => 'div',
        'class' => 'users-list',
        'id' => 'users-list',
    ],

]);
