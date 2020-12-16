<?php

namespace app\components\jobs;

use yii\queue\Queue;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class TableJob  extends BaseObject implements JobInterface
{
    public $model;

    public function execute($queue)
    {
        $this->model->save();
    }
}
