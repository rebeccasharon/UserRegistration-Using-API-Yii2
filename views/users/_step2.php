<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Step 2';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index'] ];
$this->params['breadcrumbs'][] = ['label' => 'Step 1', 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;


?>

<h3><?= "Address" ?></h3>

<div class="address-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($detailsmodel, 'UserId')->hiddenInput(['value'=> $userid])->label(false) ?>

    <?= $form->field($detailsmodel, 'House_Number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($detailsmodel, 'Street')->textInput(['maxlength' => true]) ?>

    <?= $form->field($detailsmodel, 'City')->textInput() ?>

    <?= $form->field($detailsmodel, 'Zipcode')->textInput() ?>

    <!--<?#= $form->field($model, 'Status')->checkbox() ?>-->

    <div class="form-group">
        
        <?= Html::submitButton('Next', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
