<?php

namespace app\traits;

use app\util\DateTimeHelper;

trait ConsoleParamsTrait
{
    private $consoleRawInput;
    private $consoleArgs = [];
    private $consoleOptions = [];

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
