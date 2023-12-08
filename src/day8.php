<?php

/**
 * @throws Exception
 */
function run(string $input): string
{
    $list = file($input);
    $steps = [];
    $map = [];

    foreach ($list as $key => $item) {
        $item = trim($item);

        if (empty($item)) continue;

        if ($key === 0) $steps = str_split(str_replace(['L', 'R'], [0, 1], $item));
        else {
            $item = str_replace([' = (', ', ', ')'], '|', $item);
            list($node, $left, $right) = explode("|", $item);
            $map[$node] = [$left, $right];
        }
    }

    // part1
    $part1 = steps($map, $steps, 'AAA');

    $moves = [];
    foreach(getStartNodes($map) as $node) {
        $moves[] = steps($map, $steps, $node);
    }

    // part2
    $part2 = lcm($moves);

    return sprintf("Part1: %d, Part2: %d\n", $part1, $part2);
}

function steps(array $map, array $steps, string $node): int
{
    $current = $node;
    $moves = 0;
    $step = 0;

    while (!str_ends_with($current, 'Z')) {
        if (!isset($steps[$step])) $step = 0;
        $current = $map[$current][$steps[$step]];
        $moves++;
        $step++;
    }

    return $moves;
}

function lcm(array $moves): int
{
    return array_reduce($moves, function ($carry, $item) {
        return ($carry * $item) / gcd($carry, $item);
    }, 1);
}

function gcd($a, $b): int
{
    [$a, $b] = $a > $b ? [$b, $a] : [$a, $b];

    for ($gcd = 2; $gcd <= $b; $gcd++) {
        if ($a % $gcd === 0 && $b % $gcd === 0) return $gcd;
    }

    return 1;
}

function getStartNodes(array $map): array
{
    $nodes = [];
    foreach ($map as $node => $nodeData) {
        if (str_ends_with($node, 'A')) $nodes[] = $node;
    }
    return $nodes;
}