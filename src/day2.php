<?php

const MAX_RED = 12;
const MAX_GREEN = 13;
const MAX_BLUE = 14;

function run(string $input): string
{
    $list = file($input);

    $part1 = 0;
    $part2 = 0;

    foreach ($list as $item) {
        if (empty($item)) continue;

        preg_match_all('/(Game \d+)|(\d+ red)|(\d+ green)|(\d+ blue)/', $item, $matches);

        if ($matches[0]) {
            $game = 0;
            $red = 0;
            $green = 0;
            $blue = 0;

            foreach ($matches[0] as $entry) {
                if (str_contains($entry, 'Game')) $game = intval(str_replace(['Game', ' '], '', $entry));

                foreach (['red', 'green', 'blue'] as $color) {
                    if (strpos($entry, $color)) {
                        $number = str_replace([$color, ' '], '', $entry);
                        $$color = max($$color, $number);
                    }
                }
            }

            if ($red <= MAX_RED && $green <= MAX_GREEN && $blue <= MAX_BLUE) $part1 += $game;

            $part2 += $red * $green * $blue;
        }
    }

    return sprintf("Part1: %d, Part2: %d\n", $part1, $part2);
}
