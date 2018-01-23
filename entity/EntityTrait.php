<?php

namespace app\entity;

use yii\db\ActiveRecord;

trait EntityTrait
{
    /**
     * @param $id
     * @return TmPart
     */
    public static function findById($id)
    {
        /**
         * @var ActiveRecord $this
         */
        return static::findOne(['id' => $id]);
    }
}
