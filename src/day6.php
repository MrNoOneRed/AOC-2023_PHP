<?php

function run(string $input): string
{
    $list = file($input);

    preg_match_all('/(\d+)/', $list[0], $matches);
    $times = $matches[0];

    preg_match_all('/(\d+)/', $list[1], $matches);
    $distances = $matches[0];

    // Part1
    $wins = [];
    foreach($times as $index => $time) {
        $distance = $distances[$index];
        $wins[$index] = 0;

        for($t = 0; $t <= $time; $t++) {
            $travel = ($time - $t) * $t;

            if($travel > $distance) $wins[$index]++;
        }
    }
    $part1 = array_product($wins);

    // Part2
    $time = intval(implode("", $times));
    $distance = intval(implode("", $distances));

    $min = ceil(($time - sqrt(pow($time, 2) - 4 * $distance)) / 2);
    $max = $time - $min;

    $part2 = $max - $min + 1;

    return sprintf("Part1: %d, Part2: %d\n", $part1, $part2);
}
