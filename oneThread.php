<?php
include './includes.php';

$ways = (array) json_decode(file_get_contents('./data.json'));
$pointsCount = count($ways[0]);
$allPoints = [];
for ($i = 0; $i < $pointsCount; $i++){
    $allPoints[] = $i;
}


function getWay()
{
    global $pointsCount, $allPoints, $ways;

    $jumps = [0];
    $currentPoint = 0;
    $wayCost = 0;
    do{
        $availableJumps = array_diff($allPoints, $jumps);
        $nextPoint = array_rand($availableJumps);

        $jumps[] = $nextPoint;
        $wayCost += $ways[$currentPoint][$nextPoint];
        $currentPoint = $nextPoint;

    }while(count($jumps) < $pointsCount);

    return [
        'jumps' => $jumps,
        'wayCost' => $wayCost,
    ];
}

$countVariants = gmp_fact($pointsCount-1);
$findedVariants = [];
$bestVariant = [];
$bestCost = 0;
$countRandmFails = 0;
$countRuns = 0;
do{
    $countRuns++;
    $variant = getWay();
    $vHash = md5(json_encode($variant['jumps']));

    if (in_array($vHash, $findedVariants)){
        $countRandmFails++;
        continue;
    }

    $findedVariants[] = $vHash;

    if ($bestCost == 0 || $variant['wayCost'] < $bestCost){
        $bestVariant = $variant;
        $bestCost = $variant['wayCost'];
    }

}while(count($findedVariants) < $countVariants);


printf('Counts: runs %d succeess %d fails %d(%d)'.PHP_EOL, $countRuns, count($findedVariants), $countRandmFails, 100 * ($countRandmFails/$countRuns));

print_r($bestVariant);