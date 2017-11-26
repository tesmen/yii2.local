<?php

namespace app\models\Search;

/**
 * Class Search
 * @package app\models\Seach
 *
 * @property $code
 * @property $synonym
 * @property $ved_name
 */
class SynonymsSearch extends Search
{
    protected function getDefaults()
    {
        $defaults = [
            'ved_name' => '',
            'synonym' => '',
            'code'     => '',
            'limit'     => 10,
        ];

        return array_merge(parent::getDefaults(), $defaults);
    }


}
