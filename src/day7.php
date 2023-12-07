<?php

const CARDS = ['A' => 14, 'K' => 13, 'Q' => 12, 'J' => 11, 'T' => 10];

function run(string $input): string
{
    $list = file($input);
    $part1 = 0;
    $part2 = 0;
    $hands = [];

    foreach ($list as $item) {
        if (empty($item)) continue;

        $item = trim($item);

        list($hand, $score) = explode(" ", $item);

        $jHand = getJockerHand($hand);

        $hands[] = ['hand' => $hand, 'jHand' => $jHand, 'score' => $score, 'weight' => getWeight($hand), 'jWeight' => getWeight($jHand)];
    }

    // Part1
    usort($hands, "sortingHands");
    krsort($hands);

    $multiple = 1;
    foreach($hands as $hand) {
        $part1 += $multiple * $hand['score'];
        $multiple++;
    }

    // Part2
    usort($hands, "sortingJockerHands");
    krsort($hands);

    $multiple = 1;
    foreach($hands as $hand) {
        $part2 += $multiple * $hand['score'];
        $multiple++;
    }


    return sprintf("Part1: %d, Part2: %d\n", $part1, $part2);
}

function sortingHands($a, $b): int
{
    if($a['weight'] == $b['weight']) return sortingCards($a['hand'], $b['hand']);

    return ($a['weight'] > $b['weight']) ? -1 : 1;
}

function sortingJockerHands($a, $b): int
{
    if($a['jWeight'] == $b['jWeight']) return sortingCards($a['hand'], $b['hand'], true);

    return ($a['jWeight'] > $b['jWeight']) ? -1 : 1;
}

function sortingCards($a, $b, $jocker = false): int
{
    for($c = 0; $c < strlen($a); $c++) {
        $left = substr($a, $c, 1);
        $right = substr($b, $c, 1);

        if(!is_numeric($left)) {
            if($jocker && $left == 'J') $left = 1;
            else $left = CARDS[$left];
        }

        if(!is_numeric($right)) {
            if($jocker && $right == 'J') $right = 1;
            else $right = CARDS[$right];
        }

        if($left > $right) return -1;
        else if($left < $right) return 1;
    }

    return 0;
}

function getWeight($hand): int
{
    $counts = count_chars($hand,1);
    rsort($counts);

    if($counts[0] == 5) return 7;
    else if($counts[0] == 4) return 6;
    else if($counts[0] == 3 && isset($counts[1]) && $counts[1] == 2) return 5;
    else if($counts[0] == 3) return 4;
    else if($counts[0] == 2 && isset($counts[1]) && $counts[1] == 2) return 3;
    else if($counts[0] == 2) return 2;
    else return 1;
}

function getJockerHand($hand) {
    $jockerCount = substr_count($hand, 'J');

    if($jockerCount > 0) {
        $jockerHand = str_replace('J', '', $hand);

        if($jockerCount >= 3) $card = getHighestCard($jockerHand);
        else if($jockerCount == 2) {
            $weight = getWeight($jockerHand);

            if($weight == 2) {
                $charCount = array_flip(count_chars($jockerHand,1));
                krsort($charCount);
                $card = chr(reset($charCount));
            }
            else $card = getHighestCard($jockerHand);
        }
        else {
            $weight = getWeight($jockerHand);

            if($weight == 2 || $weight == 4) {
                $charCount = array_flip(count_chars($jockerHand,1));
                krsort($charCount);
                $card = chr(reset($charCount));
            }
            else if($weight == 3) {
                $charCount = count_chars($jockerHand,1);
                $left = chr(array_key_first($charCount));
                $right = chr(array_key_last($charCount));
                $card = (getCardWeight($left) > getCardWeight($right)) ? $left : $right;
            }
            else $card = getHighestCard($jockerHand);
        }

        $hand = str_replace('J', $card, $hand);
    }

    return $hand;
}

function getCardWeight($card): int
{
    return is_numeric($card) ? $card : CARDS[$card];
}

function getHighestCard($hand): int|string
{
    $result = 0;
    for($c = 0; $c < strlen($hand); $c++) {
        $card = substr($hand, $c, 1);

        if(getCardWeight($card) > getCardWeight($result)) $result = $card;
    }

    return $result;
}

