<?php

if (count($argv) < 4) {
    die('Not enough arguments');
}

$composer = json_decode(file_get_contents($argv[1]), true);
$composer['autoload']['psr-' . $argv[2]][] = [$argv[3] => $argv[4]];

file_put_contents($argv[1], json_encode($composer, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
