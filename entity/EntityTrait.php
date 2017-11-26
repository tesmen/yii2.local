<?php

namespace app\entity;

use yii\db\ActiveRecord;

trait EntityTrait
{
    /**
     * @param $id
     * @return ActiveRecord
     */
    public static function findById($id)
    {
        /**
         * @var ActiveRecord $this
         */
        return static::findOne(['id' => $id]);
    }
}
