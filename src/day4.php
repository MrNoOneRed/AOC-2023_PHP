<?php

function run(string $input): string
{
    $list = file($input);

    $part1 = 0;
    $part2 = 0;
    $cards = [];

    foreach ($list as $item) {
        if (empty($item)) continue;

        $item = trim($item);

        list($card, $boards) = explode(': ', $item);
        $card = trim(str_replace(['Card', ' '], '', $card));

        list($winningBoard, $numberBoard) = explode('|', $boards);

        preg_match_all('/(\d+)/', $winningBoard, $matches);
        $winning = $matches[0];

        preg_match_all('/(\d+)/', $numberBoard, $matches);
        $numbers = $matches[0];

        $matches = array_intersect($winning, $numbers);

        $points = 0;
        if($matches) {
            $points = 1;
            for($i = 0; $i < count($matches) - 1; $i++) $points += $points;
        }

        $part1 += $points;

        $cards[$card] = ['matches' => count($matches), 'copy' => 1];
    }

    // Part 2
    foreach($cards as $number => &$card) {
        $part2 += $card['copy'];

        for($a = 0; $a < $card['copy']; $a++) {
            if($card['matches'] > 0) {
                $start = $number + 1;
                $end = $number + $card['matches'];

                for($i = $start; $i <= $end; $i++) {
                    if(isset($cards[$i])) $cards[$i]['copy'] += 1;
                }
            }
        }

    }

    //$part2 += $part1;
    print_r($cards);
//3762400
    //127939848258
    //127939870746
    //1457569
    //1435081
    //7013204
    return sprintf("Part1: %d, Part2: %d\n", $part1, $part2);
}
