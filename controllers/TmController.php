<?php

namespace app\controllers;

use app\entity\TmPart;
use app\entity\TmPartSynonym;
use app\entity\TmPartType;
use app\models\FileProcessor\SmartFileProcessor;
use app\models\FileProcessor\SynonymFileProcessor;
use app\models\FileProcessor\XslxFileProcessor;
use app\models\Search\SynonymsSearch;
use app\models\TmPartSynonymModel;
use app\traits\ControllerTrait;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\services\FileService;

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
            $uploadedPath = FileService::getUploadDir(md5($file->tempName) . '.' . $file->getExtension());
            move_uploaded_file($file->tempName, $uploadedPath);

            $data = XslxFileProcessor::instance($uploadedPath)
                ->setCodeColumn(4)
                ->setNameColumn(3)
                ->processFile();

            return $this->csvFileResponse($file->name . '.csv', $data);
        }

        return $this->render('tm-parse-file');
    }

    public function actionPartSynonyms()
    {
        return $this->render('tm-parts');
    }

    public function actionCreatePart()
    {
        $name = $this->getQueryParams('name');
        $code = $this->getQueryParams('code');
        $part = new TmPart;

        try {
            $part->raw_name = trim($name);
            $part->code = $code;
            $part->save();
            return $this->jsonSuccessResponse();
        } catch (\Exception $e) {
            return $this->jsonFailureResponse($e->getMessage());
        }
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
