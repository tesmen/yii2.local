<?php

namespace app\models\PartsRecognizer;


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

    public static function parseDetail($str)
    {
        $obj = new RecognizedDetail();

        $dn = preg_match();

        $obj->dn = mb_strpos($str);

    }
}
