<?php

namespace app\controllers;

use app\entity\TmPart;
use app\entity\TmPartSynonym;
use app\entity\TmPartType;
use app\models\FileProcessor\SmartFileProcessor;
use app\models\FileProcessor\SynonymFileProcessor;
use app\models\FileProcessor\XslxFileProcessor;
use app\models\Search\SynonymsSearch;
use app\models\TmPartModel;
use app\models\TmPartSynonymModel;
use app\traits\ControllerTrait;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\services\FileService;

class TestController extends Controller
{
    use ControllerTrait;

    public $enableCsrfValidation = false;
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file'],
        ];
    }

    public function actionFoo()
    {
        $a = TmPart::findBySql(
            "SELECT * FROM tm_parts WHERE trim(LOWER(obez)) = trim(LOWER(:str))",
            ['str' => 111]
        )->one();

        var_export($a);
    }


}
