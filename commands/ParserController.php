<?php


namespace app\commands;


use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class ParserController extends Controller
{
    public function actionParse($filename, $category_id, $subcategory_id)
    {
        if (file_exists(__DIR__ . '/parse/' . $filename . '.json'))
        {
            $json_content = file_get_contents(__DIR__ . '/parse/' . $filename . '.json');
            $quotes = json_decode($json_content, true);
            if (!empty($quotes))
            {
                foreach ($quotes as $key => $quote)
                {
                    if (empty($quote['text']))
                    {
                        continue;
                    } elseif (!empty($quote['text']) && stristr($quote['text'], chr(160)) != false)
                    {
                        $text = str_replace([chr(0xC2) . chr(0xA0)], ' ', $quote['text']);
                    } else
                    {
                        $text = $quote['text'];
                    }
                    $author = $quote['author'] ?? null;
                    Yii::$app->db->createCommand()
                        ->insert('quotes_ru', [
                            'category_id' => (int)$category_id,
                            'subcategory_id' => (int)$subcategory_id,
                            'text' => $text,
                            'author' => $author
                        ])->execute();
                }
                Console::output('Done!');
            } else
            {
                Console::output('Content array is empty');
            }
        } else {
            Console::output('File not found');
        }
    }

    public function actionDump() {
        $quotes_ru = Yii::$app->db->createCommand('SELECT * FROM quotes_ru')->queryAll();
        $quotes_en = Yii::$app->db->createCommand('SELECT * FROM quotes_en')->queryAll();
        $categories = Yii::$app->db->createCommand('SELECT * FROM category')->queryAll();
        $subcategories = Yii::$app->db->createCommand('SELECT * FROM subcategory')->queryAll();
        $quotes_ru_data = [];
        $quotes_en_data = [];
        $category_ru_data = [];
        $category_en_data = [];
        $subcategory_ru_data = [];
        $subcategory_en_data = [];

        foreach ($quotes_ru as $quote) {
            $quotes_ru_data[] = $quote;
        }
        $encoded = json_encode($quotes_ru_data,JSON_UNESCAPED_UNICODE);
        $filename = __DIR__ . '/dump/quotes_ru.json';
        $fp = fopen($filename, 'w');
        fwrite($fp, $encoded);
        fclose($fp);

        foreach ($quotes_en as $quote) {
            $quotes_en_data[] = $quote;
        }
        $encoded = json_encode($quotes_en_data,JSON_UNESCAPED_UNICODE);
        $filename = __DIR__ . '/dump/quotes_en.json';
        $fp = fopen($filename, 'w');
        fwrite($fp, $encoded);
        fclose($fp);

        foreach ($categories as $category) {
            $category_ru_data[] = ['id' => $category['id'], 'title_ru' => $category['title_ru']];
            $category_en_data[] = ['id' => $category['id'], 'title_en' => $category['title_en']];
        }
        $encoded = json_encode($category_ru_data,JSON_UNESCAPED_UNICODE);
        $filename = __DIR__ . '/dump/category_ru.json';
        $fp = fopen($filename, 'w');
        fwrite($fp, $encoded);
        fclose($fp);
        $encoded = json_encode($category_en_data,JSON_UNESCAPED_UNICODE);
        $filename = __DIR__ . '/dump/category_en.json';
        $fp = fopen($filename, 'w');
        fwrite($fp, $encoded);
        fclose($fp);

        foreach ($subcategories as $subcategory) {
            $subcategory_ru_data[] = ['id' => $subcategory['id'], 'title_ru' => $subcategory['title_ru']];
            $subcategory_en_data[] = ['id' => $subcategory['id'], 'title_en' => $subcategory['title_en']];
        }
        $encoded = json_encode($subcategory_ru_data,JSON_UNESCAPED_UNICODE);
        $filename = __DIR__ . '/dump/subcategory_ru.json';
        $fp = fopen($filename, 'w');
        fwrite($fp, $encoded);
        fclose($fp);
        $encoded = json_encode($subcategory_en_data,JSON_UNESCAPED_UNICODE);
        $filename = __DIR__ . '/dump/subcategory_en.json';
        $fp = fopen($filename, 'w');
        fwrite($fp, $encoded);
        fclose($fp);

        Console::output('Done!');
    }

    public function actionParseDirectory()
    {
        $files = array_filter(scandir(__DIR__ . '/parse/'), function($item) {
            return is_file(__DIR__ . '/parse/' . $item);
        });
        foreach ($files as $file) {
            $filename  = explode('_', $file);
            $category_id = (int)$filename[0];
            $subcategory_id = (int)$filename[1];
            $json_content = file_get_contents(__DIR__ . '/parse/' . $file);
            $quotes = json_decode($json_content, true);
            if (!empty($quotes))
            {
                foreach ($quotes as $key => $quote)
                {
                    if (empty($quote['text']))
                    {
                        continue;
                    } elseif (!empty($quote['text']) && stristr($quote['text'], chr(160)) != false)
                    {
                        $text = str_replace([chr(0xC2) . chr(0xA0)], ' ', $quote['text']);
                    } else
                    {
                        $text = $quote['text'];
                    }
                    $author = $quote['author'] ?? null;
                    Yii::$app->db->createCommand()
                        ->insert('quotes_ru', [
                            'category_id' => $category_id,
                            'subcategory_id' => $subcategory_id,
                            'text' => $text,
                            'author' => $author
                        ])->execute();
                }
                Console::output($file . ' was parsed');
            } else
            {
                Console::output('Content array is empty');
            }
        }
        Console::output('Done!');
    }
}
