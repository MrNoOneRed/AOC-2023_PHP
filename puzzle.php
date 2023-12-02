<?php

$options = getopt('d:', ['day:']);

try{
    if(!isset($options['d']) && !isset($options['day'])) throw new Exception('You need to provide number of day ex. "-d 5" or "--day=5"');

    $day = $options['d'] ?? $options['day'];
    $dayScript = sprintf("./src/day%d.php", $day);
    $input = sprintf("./input/puzzle%d.txt", $day);

    if(!file_exists($dayScript)) throw new Exception(sprintf("There is no script for day %d puzzle", $day));
    if(!file_exists($input)) throw new Exception(sprintf("There is no input for day %d puzzle", $day));

    require $dayScript;

    echo run($input);
}
catch (Exception $e) {
    die($e->getMessage());
}