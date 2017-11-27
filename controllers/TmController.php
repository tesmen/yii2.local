<?php

namespace app\controllers;

use app\entity\TmPartSynonym;
use app\entity\TmPartType;
use app\models\FileProcessor\TmCsvFileProcessor;
use app\models\Search\SynonymsSearch;
use app\models\TmPartSynonymModel;
use app\traits\ControllerTrait;
use yii\web\Controller;
use yii\web\UploadedFile;

class TmController extends Controller
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

    public function actionParseFile()
    {
        if (\Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName('file');
            move_uploaded_file($file->tempName, \app\services\FileService::getBatchDir($file->name));

            $stat = TmCsvFileProcessor::instance($file->name)
                ->setBatch(true)
                ->processFile();
            var_export($stat);
        }

        return $this->render('tm-parse-file');
    }

    public function actionPartSynonyms()
    {
        return $this->render('tm-parts');
    }

    public function actionGetSynonyms()
    {
        $get = $this->getQueryParams();
        $search = new SynonymsSearch($get);

        return $this->asJson(
            [
                'items' => TmPartSynonymModel::search($search),
                'total' => TmPartSynonymModel::getCount($search),
            ]
        );
    }

    public function actionGetPartData()
    {
        $id = $this->getQueryParams('id');

        return $this->asJson(TmPartSynonymModel::getPartData($id));
    }

    public function actionCreatePartSynonym()
    {
        $name = $this->getQueryParams('name');
        $partId = $this->getQueryParams('id');

        return $this->asJson(TmPartSynonymModel::createSafe($name, $partId));
    }

    public function actionDeletePartSynonym()
    {
        $synId = $this->getQueryParams('id');

        return $this->asJson(TmPartSynonym::findById($synId)->delete());
    }

    public function actionUpdatePartSynonym()
    {
        $name = $this->getQueryParams('name');
        $partId = $this->getQueryParams('id');

        return $this->asJson(TmPartSynonymModel::update($name, $partId));
    }

    public function actionSaveSynonym()
    {

        return $this->asJson(TmPartSynonymModel::getPartData('id'));
    }

    public function actionGetPartTypes()
    {
        return $this->asJson(
            ['data' => TmPartType::find()->all()]
        );
    }

    public function actionCreatePartType()
    {
        $name = $this->getRequestParams('name');

        return $this->asJson(TmPartType::create($name));
    }

    public function actionTogglePartTypeActive()
    {
        $name = $this->getRequestParams('id');

        return $this->asJson(TmPartType::create($name));
    }


}
