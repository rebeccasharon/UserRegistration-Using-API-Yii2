<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
//print_r($_REQUEST); exit;
$userid = isset($session['userid']) ?  $session['userid'] :isset($_REQUEST['userid']) ? $_REQUEST['userid'] : null;
?>
<div class="users-details">

    <h2><?= "Add User : Step 2" ?></h2>
    <?= $this->render('_step2', [
        'userid' => $userid,
        'detailsmodel' => $detailsmodel,
    ]) ?>

</div>
