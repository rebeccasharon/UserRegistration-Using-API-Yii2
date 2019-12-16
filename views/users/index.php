<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if (Yii::$app->session->hasFlash('Success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <h4><i class="icon fa fa-check"></i>Success!</h4>
         <?= Yii::$app->session->getFlash('Success') ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('Failed')): ?>
    <div class="alert alert-danger alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <h4><i class="icon fa fa-check"></i>Error!</h4>
         <?= Yii::$app->session->getFlash('Failed') ?>
    </div>
<?php endif; ?>

<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Add Users', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'UserId',
            'FirstName',
            'LastName',
            'Telephone',
            'Status:boolean',
            ['label' => 'Payment Data ID',
             'value'     => function ($data) {
                return $data->relation_tableUserPaymentDetails->Response;
            },
             ], 
        ],
    ]); ?>


</div>
