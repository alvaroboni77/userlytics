<?php

$array = range(1, 111);
$lastElement = end($array);
foreach ($array as $number) {
    if ($lastElement == $number) {
        echo "\033[0;32m$number\033[0m". PHP_EOL;
    } else if ($number % 3 == 0 && $number % 5 == 0) {
        echo "\033[0;35m$number\033[0m". PHP_EOL;
    } else if ($number % 5 == 0 && $number % 3 != 0) {
        echo "\033[0;34m$number\033[0m". PHP_EOL;
    } else if ($number % 3 == 0 && $number % 5 != 0) {
        echo "\033[0;31m$number\033[0m". PHP_EOL;
    } else {
        echo $number . PHP_EOL;
    }
}