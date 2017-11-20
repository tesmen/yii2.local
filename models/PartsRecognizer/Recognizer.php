<?php

namespace app\models\PartsRecognizer;


use app\entity\TmPartType;

class Recognizer
{
    /**
     * PartsRecognizer constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function recognize()
    {
    }

    /**
     * @param $str
     */
    public static function parseDetail($str)
    {
        $partTypes = TmPartType::find()->all();

        var_export($partTypes);
        die;
    }
}
