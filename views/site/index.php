<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this);

$this->title = 'My Yii Application';
?>
    <div class="site-index">

        <div class="jumbotron">
            <?php $form = ActiveForm::begin([
                    'action' => '/site/data',
                //'id' => 'policy-form',
                'fieldConfig' => [
                    'template' => "{beginLabel}<span>{labelTitle}</span>{endLabel}\n{input}\n{hint}\n{error}\n",
                ]
            ]); ?>
            <col-md-6>
                <?= Html::textInput('city', '', ['class' => 'city', 'id' => 'city']); ?>
            </col-md-6>
            <?php ActiveForm::end(); ?>
        </div>

        <div class="body-content">

            <div class="row">
            </div>

        </div>
    </div>
<?php
$js = <<<JS
let datatoken = '3fdeada8b8129a920f359433176047d0905e3a0e';
$("#city").suggestions({
        token: datatoken,
        type: "ADDRESS",
        count: 5,
        onSelect: function (suggestion) {
            let city = suggestion.data.city;
            $(this).val(city);
            if (city != null) {
    $.ajax({
    type: 'POST',
    url: '/site/data',
    dataType: 'json',
    data: {city : city},
    success: function(res){
        console.log(res);
    },
    error: function(xhr, status, error, res){
        var err = eval("(" + JSON.parse(xhr.responseText) + ")");
        console.log(value + ' ' + err.Message);
    }
    });
    return false;
    }
        }
    });
JS;
$this->registerJS($js, yii\web\View::POS_READY);

