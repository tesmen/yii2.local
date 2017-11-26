<?php

namespace app\models\Search;

/**
 * Class Search
 * @package app\models\Seach
 *
 * @property $code
 * @property $syn_name
 * @property $ved_name
 */
class SynonymsSearch extends Search
{
    protected function getDefaults()
    {
        $defaults = [
            'ved_name' => '',
            'syn_name' => '',
            'code'     => '',
        ];

        return array_merge(parent::getDefaults(), $defaults);
    }


}
