<?php

namespace app\models\PartsRecognizer;


interface DetectorInterface
{
    public function detect($str);
}
