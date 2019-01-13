<?php
$pointsCount = $argv[1];
$ways = [];
for ($i = 0; $i < $pointsCount; $i++){
    $ways[$i] = [];
    for ($k = 0; $k < $pointsCount; $k++) {
        $ways[$i][$k] = $i === $k ? 0 : rand(1, 100);
    }
}

echo json_encode($ways);
