<?php
/* @var $times app\models\Time[] */

?>
<div class="col-md-12 col-xs-12 main_block">
    <?php foreach ($times as $time) : ?>
        <a href="/reserve/order?id=<?=$time->id?>"><div class="day-block <?= ($time->is_reserved != 0) ? 'reserved' : ''  ?>"><?= $time->time?></div></a>
    <?php endforeach; ?>
</div>
<?php
$js = <<<JS
$('.reserved').click(function(e) {
    e.preventDefault();
    alert('Извините, это время уже занято.')
});
JS;
$this->registerJS($js);