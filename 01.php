<?php

$input = 'Блок управления с встроенной панелью управления MS PSU5, РМРС';
$prepared = mb_strtolower(trim($input));
$partTypes = ['блок', 'панель'] ;

foreach ($partTypes as $k=>$partType) {
    $variants = [];
    $tester = mb_strtolower(trim($partType));
    $position = mb_strpos($prepared, $tester);

    if ($position !== false) {
        $variants[$position] = $k;
    };
}


ksort($variants);
var_export($variants);
var_export( intval(reset($variants)));
