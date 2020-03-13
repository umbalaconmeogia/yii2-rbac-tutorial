<?php

use app\models\Post;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title:ntext',
            [
                'attribute' => 'content',
                'value' => function(Post $model) {
                    return StringHelper::truncate($model->content, 45, '…');
                },
            ],
            [
                'header' => 'Author',
                'value' => 'author.username',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'update' => function ($model, $key, $index) {
                        return Yii::$app->user->can('updatePost', ['post' => $model]);
                    },
                    'delete' => function ($model, $key, $index) {
                        return Yii::$app->user->can('updatePost', ['post' => $model]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
