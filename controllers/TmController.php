<?php

namespace app\controllers;

use app\entity\TmPartSynonym;
use app\entity\TmPartType;
use app\models\Search\SynonymsSearch;
use app\models\TmPartSynonymModel;
use app\traits\ControllerTrait;
use yii\web\Controller;

class TmController extends Controller
{
    use ControllerTrait;

    public function actionPartTypes()
    {
        return $this->render('tm-part-types');
    }

    public function actionPartSynonyms()
    {
        return $this->render('tm-part-synonyms');
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
