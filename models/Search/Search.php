<?php

namespace app\models\Search;

/**
 * Class Search
 * @package app\models\Seach
 *
 * @property int $limit
 * @property int $offset
 * @property string $orderBy
 * @property string $dir
 * @property int $page
 */
class Search
{
    protected $data = [];
    protected $limit = 15;

    /**
     * @param array|string $data - search data as array or json
     */
    public function __construct($data)
    {
        $this->data = is_array($data)
            ? $data
            : (json_decode($data, true)
                ?: []);
    }

    /**
     * @return array
     */
    protected function getDefaults()
    {
        return [
            'limit'  => $this->limit,
            'offset' => 0,
            'orderBy'  => false,
            'dir'    => false,
            'page'    => 1,
        ];
    }

    public function __get($name)
    {
        return isset($this->data[$name])
            ? $this->data[$name]
            : (isset($this->getDefaults()[$name])
                ? $this->getDefaults()[$name]
                : '');
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->limit * ($this->page - 1);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getDefault($name)
    {
        $defaults = $this->getDefaults();

        return isset($defaults[$name])
            ? $defaults[$name]
            : null;
    }


}