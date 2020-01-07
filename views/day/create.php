<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Day */

$this->title = 'Create Day';
$this->params['breadcrumbs'][] = ['label' => 'Days', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="day-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
