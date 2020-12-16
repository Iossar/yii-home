<?php
/* @var $days app\models\Day[] */
?>
<div class="col-md-12 col-xs-12 main_block">
<?php foreach ($days as $day) : ?>
    <a href="/reserve/time?id=<?=$day->id?>"><div class="day-block"><?= date('d.m', strtotime($day->date))?></div></a>
<?php endforeach; ?>
</div>