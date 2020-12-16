<?php

use yii\grid\GridView;
use yii\helpers\Html;
use app\assets\AppAsset;
AppAsset::register($this);
?>

<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout' => '{items}{pager}',
    'columns' => [
        [
            'attribute' => 'day.date',
            'label' => 'Дата',
            'value' => function ($model) {
                return date('d.m', strtotime($model->day->date));
            }
        ],
        [
            'attribute' => 'time',
            'label' => 'Время',
            'filter' => false
        ],
        [
            'label' => 'Клиент',
            'value' => function ($model) {
                return $model->name . ' (' . $model->phone . ') ';
            }
        ],
        [
            'header' => 'Статус брони',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'content' => function ($model) {
                $checkbox = "<div class='checkbox table_checkbox'>" . Html::checkbox('selection[]', ($model->is_reserved) ? true : false ?? false, ['value' => $model->id, 'id' => $model->id, 'class' => 'is_reserved']) . "<label for='$model->id' style='padding: unset'></label></div>";
                return $checkbox;
            },
        ],
    ]
])
?>

<?php
$js = <<<JS
$('.is_reserved').change(function(){
     var id = $(this).val();
     $.ajax({
            url: '/site/change-status',
            type: 'POST',
            data: {id: id},
            success: function(res){
                console.log(res);
            },
            error: function(xhr, status, error){
                var err = eval("(" + JSON.parse(xhr.responseText) + ")");
                console.log(value + ' ' + err.Message);
            }
        });
     return false;
    });
JS;
$this->registerJS($js, yii\web\View::POS_READY);
?>

