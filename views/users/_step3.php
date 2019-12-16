<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Step 3';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Step 1', 'url' => ['create']];
$this->params['breadcrumbs'][] = ['label' => 'Step 2', 'url' => ['renderstep2']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h3><?= "Payment" ?></h3>

<div class="payment-form">

    <?php $form = ActiveForm::begin([
        'action' => ['users/renderstep3'],
        //'enableAjaxValidation' => true,
        //'validationUrl' => 'https://37f32cl571.execute-api.eu-central-1.amazonaws.com/default/wunderfleet-recruiting-backend-dev-save-payment-data',
        'options' => [
            'class' => 'comment-form'
        ]

    ]); ?>

    <?= $form->field($paymentmodel, 'customerId')->hiddenInput(['value'=> $userid])->label(false) ?>

    <?= $form->field($paymentmodel, 'owner')->textInput(['maxlength' => true]) ?>

    <?= $form->field($paymentmodel, 'iban')->textInput() ?>

    <?= $form->field($paymentmodel, 'Response')->hiddenInput()->label(false) ?>

    <!--<?#= $form->field($model, 'Status')->checkbox() ?>-->

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
