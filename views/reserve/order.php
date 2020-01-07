<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Бронирование времени';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12 col-xs-12 main_block">
    <div class="row">
        <h1 style="text-align: center; margin-bottom: 20px"><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="contact-form">
    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            Спасибо за заявку!
        </div>

    <?php else: ?>

                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                <?= Html::hiddenInput('time', $time->id, ['placeholder' => 'Ваше имя']) ?>
        <div class="row">
                <?= Html::textInput('name', '', ['placeholder' => 'Ваше имя', 'class' => 'form-input']) ?>
        </div>
            <div class="row">
                <?= Html::textInput('phone', '', ['placeholder' => 'Номер для связи', 'class' => 'form-input']) ?>
            </div>
                <div class="row">
                    <?= Html::submitButton('Подтвердить', ['class' => 'btn contact-submit', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>


    <?php endif; ?>
    </div>
</div>
