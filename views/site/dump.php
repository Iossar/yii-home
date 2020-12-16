<?php
$quotes_ru = Yii::$app->db->createCommand('SELECT * FROM quotes_ru')->queryAll();
$category_ru = Yii::$app->db->createCommand('SELECT id, title_ru FROM category')->queryAll();
$category_en = Yii::$app->db->createCommand('SELECT id, title_en FROM category')->queryAll();
$quotes_data = [];
foreach ($quotes_ru as $quote) {
    $quotes_data[] = $quote;
}
$encoded = json_encode($quotes_data,JSON_UNESCAPED_UNICODE);
$path = rand(1, 100);
mkdir(__DIR__ . '/../../web/' . $path, 0775);
$filename = __DIR__ . '/../../web/' . $path . '/results.json';
$fp = fopen($filename, 'w');
fwrite($fp, $encoded);
header('X-Sendfile: ' . $filename);
//header("Content-Type: image/png");
//header("Content-Length: " . filesize($filename));
header("Content-Disposition: attachment; filename =" . 'json.json' . ";charset=UTF-8");
readfile($filename);
