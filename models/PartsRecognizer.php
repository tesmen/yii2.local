<?php

namespace app\models;


class PartsRecognizer
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
}
