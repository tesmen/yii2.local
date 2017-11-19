<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\TmPart;
use yii\console\Controller;
use yii\log\Logger;
use Yii;


class InsertTmPartsController extends Controller
{
    public function actionIndex($filename)
    {
        $data = $this->parseCsvFile($filename);

        foreach($data as $row){
            $rec = new TmPart();
            $rec->name = iconv('UTF8', 'UTF8', mb_strtolower($row[7]));
            $rec->save();
        }
    }

    public function parseCsvFile($file)
    {
        return array_map('str_getcsv', file($file));
    }

}
