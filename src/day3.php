<?php

function run(string $input): string
{
    $list = file($input);

    $part1 = 0;
    $part2 = 0;

    $numbers = [];
    $symbols = [];
    $y = 0;

    foreach ($list as $item) {
        if (empty($item)) continue;

        $item = trim($item);

        preg_match_all('/(\d+)|(\D)/', $item, $matches);

        if ($matches[0]) {
            $x = 0;

            foreach($matches[0] as $match) {
                if(is_numeric($match)) {
                    $len = strlen($match);
                    $numbers[$y][$x] = ['number' => $match, 'endX' => $x + $len - 1, 'used' => false];
                    $x += $len;
                    continue;
                }

                if($match !== '.') $symbols[$y][$x] = $match;

                $x++;
            }
        }

        $y++;
    }

    foreach($symbols as $y => $row) {
        foreach($row as $x => $symbol) {
            $gears = [];

            foreach ([-1, 0, 1] as $v) {
                foreach ([-1, 0, 1] as $h) {
                    $checkY = $y + $v;
                    $checkX = $x + $h;

                    if(isset($numbers[$checkY])) {
                        foreach ($numbers[$checkY] as $startX => $data) {
                            $endX = $data['endX'];

                            if($checkX >= $startX && $checkX <= $endX && !$data['used']) {
                                $part1 += $data['number'];
                                $numbers[$checkY][$startX]['used'] = true;

                                if($symbol === '*') $gears[] = $data['number'];
                            }
                        }
                    }
                }
            }

            if(count($gears) === 2) $part2 += array_product($gears);
        }
    }

    return sprintf("Part1: %d, Part2: %d\n", $part1, $part2);
}
