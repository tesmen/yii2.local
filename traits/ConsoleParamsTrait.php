<?php

namespace app\traits;

use app\util\DateTimeHelper;

trait ConsoleParamsTrait
{
    private $consoleRawInput;
    private $consoleArgs = [];
    private $consoleOptions = [];

    public function __construct($args)
    {
        $this->loadConsoleParams($args);
    }

    /**
     * @param string $msg
     */
    private function writeln($msg)
    {
        echo (new \DateTime())->format(DateTimeHelper::FMT_HIS) . ' ';
        echo $msg;
        echo PHP_EOL;
    }

    /**
     * @param $data
     */
    private function loadConsoleParams($data)
    {
        $this->consoleRawInput = $data;

        foreach ($data as $arg) {
            switch (substr_count($arg, '=')) {
                case 1:
                    list($key, $value) = explode('=', $arg);
                    $this->consoleArgs[$key] = $value;
                    break;
                case 0:
                    $this->consoleOptions[] = $arg;
                    break;
            }
        }
    }

    /**
     * @param int $num
     * Returns first option from console
     * Notice! Zero consoleOptions element is a script name;s
     */
    private function getConsoleOption($num = 1)
    {
        return $this->consoleOptions[$num];
    }

    /**
     * @param $name
     * @param $default
     * @return mixed
     * ./run_script.sh MyBarScript "" arg=1 bar=2
     */
    private function getConsoleArgument($name, $default = null)
    {
        return isset($this->consoleArgs[$name])
            ? $this->consoleArgs[$name]
            : $default;
    }
}
