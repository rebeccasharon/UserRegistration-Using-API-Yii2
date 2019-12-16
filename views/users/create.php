<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
?>
<div class="users-create">

    <h2><?= "Add User : Step 1" ?></h2>

    <?= $this->render('_step1', [
        'model' => $model,
    ]) ?>

</div>
