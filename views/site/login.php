<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вход';
?>
<div class="col-md-12 col-xs-12 main_block">
    <div class="row">
        <h1 style="text-align: center; margin-bottom: 20px"><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="contact-form">
    <?php $form = ActiveForm::begin([

    ]); ?>
        <div class="row">
        <?= $form->field($model, 'username')->textInput(['class' => 'form-input', 'autofocus' => true])->label(false) ?>
        </div>
            <div class="row">
        <?= $form->field($model, 'password')->passwordInput(['class' => 'form-input'])->label(false) ?>
            </div>
        <div class="row">
        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"form-input\">{input} {label}</div>\n<div class=\"\">{error}</div>",
        ])->label('Запомнить меня') ?>
        </div>

        <div class="row">
                <?= Html::submitButton('Войти', ['class' => 'btn contact-submit', 'name' => 'login-button']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
</div>
