<?php

const NAMES = [
    'one' => "1",
    'two' => "2",
    'three' => "3",
    'four' => "4",
    'five' => "5",
    'six' => "6",
    'seven' => "7",
    'eight' => "8",
    'nine' => "9",
];

/**
 * @throws Exception
 */
function run(string $input): string
{
    $list = file($input);

    $part1 = 0;
    $part2 = 0;

    foreach ($list as $item) {
        if (empty($item)) continue;

        $pair1 = "";
        $pair2 = "";

        // Part1
        preg_match_all('/(\d)/', $item, $matches);
        if ($matches && $matches[0]) {
            $first = $matches[0][0];
            $last = end($matches[0]);
            $pair1 = $first . $last;
        }

        // Part2
        $searching = array_merge(array_keys(NAMES), array_values(NAMES));
        $found = [];

        foreach ($searching as $search) {
            $index = 0;

            while (($index = strpos($item, $search, $index)) !== false) {
                $found[$index] = $search;
                $index++;
            }
        }

        if ($found) {
            ksort($found);
            $first = reset($found);
            $last = end($found);
            $pair2 .= is_numeric($first) ? $first : NAMES[$first];
            $pair2 .= is_numeric($last) ? $last : NAMES[$last];
        }

        // Summary
        if (strlen($pair1) == 2 && strlen($pair2) == 2) {
            $part1 += intval($pair1);
            $part2 += intval($pair2);
        } else throw new Exception("Something is wrong. There should be numeric or spelled number");
    }

    return sprintf("Part1: %d, Part2: %d\n", $part1, $part2);
}
