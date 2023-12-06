<?php

function run(string $input): string
{
    $list = file($input);

    $seeds = [];
    $phases = [];
    $phase = 0;

    foreach ($list as $item) {
        $item = trim($item);

        if (empty($item)) continue;

        if(str_contains($item, 'seeds: ')) $seeds = explode(' ', trim(str_replace('seeds: ', '', $item)));
        if(str_contains($item, 'map:')) {
            $phase++;
            continue;
        }

        if($phase > 0) {
            list($dest, $source, $range) = explode(' ', $item);
            $phases[$phase][] = ['dest' => $dest, 'source' => $source, 'range' => $range];
        }
    }

    // Part1
    $locations = [];
    foreach ($seeds as $seed) {
        $locations[] = getLocation($seed, $phases);
    }
    $part1 = min($locations);

    // Part2
    $ranges = [];
    for($i = 0; $i < count($seeds); $i += 2) {
        $start = $seeds[$i];
        $end = $start + $seeds[$i + 1];
        $ranges[] = [$start, $end];
    }

    foreach($phases as $phase) {
        $newRanges = [];

        while (count($ranges) > 0) {
            list($start, $end) = array_pop($ranges);

            $found = false;
            foreach($phase as $data) {
                $oStart = max($start, $data['source']);
                $oEnd = min($end, $data['source'] + $data['range']);

                if($oStart < $oEnd) {
                    $nStart = $oStart - $data['source'] + $data['dest'];
                    $nEnd = $oEnd - $data['source'] + $data['dest'];
                    $newRanges[] = [$nStart, $nEnd];

                    if($oStart > $start) $ranges[] = [$start, $oStart];
                    if($end > $oEnd) $ranges[] = [$oEnd, $end];

                    $found = true;
                }

                if($found) break;
            }

            if(!$found) $newRanges[] = [$start, $end];
        }

        $ranges = $newRanges;
    }

    $part2 = min($ranges)[0];

    return sprintf("Part1: %d, Part2: %d\n", $part1, $part2);
}

function getLocation(int $number, array $phases, int $phase = 1): int {
    $output = $number;

    if(isset($phases[$phase])) {
        foreach ($phases[$phase] as $data) {
            $left = $data['source'];
            $right = $data['source'] + $data['range'] - 1;

            if($left <= $number && $right >= $number) {
                $output = $data['dest'] + $number - $left;
                break;
            }
        }

        if(isset($phases[$phase + 1])) return getLocation($output, $phases, $phase + 1);
    }

    return $output;
}
