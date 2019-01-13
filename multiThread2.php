<?php
include './includes.php';
include './classes/WayFinder.php';
include './classes/WayCollection.php';

ini_set('memory_limit', '4048M');

$ways = (array) json_decode(file_get_contents('./data.json'));
$pointsCount = count($ways[0]);
$allPoints = [];
for ($i = 0; $i < $pointsCount; $i++){
    $allPoints[] = $i;
}

$pool = new Pool(11);
$countVariants = gmp_fact($pointsCount-1); //
$bestVariant = new \Volatile();

$wayCollection = new WayCollection($countVariants,$bestVariant);
$pool->submit($wayCollection);
for ($i=0; $i < 10; $i++){
    $pool->submit(new WayFinder($allPoints, $ways, $pointsCount, $wayCollection));
}



while ($pool->collect());
$pool->shutdown();
print_r($bestVariant);