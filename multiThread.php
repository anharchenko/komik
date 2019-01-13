<?php
include './includes.php';

$ways = (array) json_decode(file_get_contents('./data.json'));
$pointsCount = count($ways[0]);
$allPoints = [];
for ($i = 0; $i < $pointsCount; $i++){
    $allPoints[] = $i;
}


class Task extends Threaded
{
    public $pointsCount, $allPoints, $ways, $findedVariants, $bestVariant, $bestCost, $countVariants;

    /**
     * Task constructor.
     * @param $pointsCount
     * @param $allPoints
     * @param $ways
     * @param $findedVariants
     */
    public function __construct($pointsCount, $allPoints, $ways, $findedVariants, $bestVariant, $bestCost, $countVariants)
    {
        $this->pointsCount = $pointsCount;
        $this->allPoints = (array) $allPoints->value;
        $this->ways = (array) $ways->value;
        $this->findedVariants = $findedVariants;
        $this->bestVariant = $bestVariant;
        $this->bestCost = $bestCost;
        $this->countVariants = $countVariants;
    }

    private function getWay()
    {
        $jumps = [0];
        $currentPoint = 0;
        $wayCost = 0;
        do{
            $availableJumps = array_diff($this->allPoints, $jumps);
            $nextPoint = array_rand($availableJumps);

            $jumps[] = $nextPoint;
            $wayCost += $this->ways[$currentPoint][$nextPoint];
            $currentPoint = $nextPoint;

        }while(count($jumps) < $this->pointsCount);

        return $response =  [
            'jumps' => $jumps,
            'wayCost' => $wayCost,
        ];
    }


    public function run()
    {
        $allFounded = false;
        $variants = [];
        $countCollectVariants = 0;
        do{
            $countCollectVariants ++;
            for($i = 0; $i < 30000; $i++)
            {
                $variant = $this->getWay();
                $variants[] = $variant;
            }

            $wayUsed = false;

            $goodVariants = [];
            $this->findedVariants->synchronized(function ($findedVariants) use (&$wayUsed, $variants, &$allFounded, &$goodVariants){
                $tFindedVariants = (array) $findedVariants;

                if (count($tFindedVariants) >= $this->countVariants)
                {
                    $allFounded = true;
                }else{
                    foreach ($variants as $variant)
                    {
                        $vHash = md5(json_encode($variant['jumps']));

                        if (!$wayUsed = in_array($vHash, $tFindedVariants)){
                            $findedVariants[] = $vHash;
                            $goodVariants[] = $variant;
                            //echo count($findedVariants).PHP_EOL;
                        }
                    }
                }
                $findedVariants->notify();
            }, $this->findedVariants);

            if (!$goodVariants){
                continue;
            }

            $this->bestVariant->synchronized(function ($bestVariant) use ($goodVariants){
                foreach ($goodVariants as $variant)
                {
                    if (!isset($bestVariant[0]) || $variant['wayCost'] < $bestVariant[0]['wayCost']){
                        $bestVariant[0] = (array) $variant;
                        $bestVariant->notify();
                    }
                }
            }, $this->bestVariant);



        }while(!$allFounded);

        echo $countCollectVariants.': sayonara!'.PHP_EOL;
    }
}

class Atomic extends Threaded {

    public function __construct($value = 0) {
        $this->value = $value;
    }

    public function dump()
    {
        print_r($this->value);
    }

    public $value;
}

# Create a pool of 4 threads
$pool = new Pool(11);
$allPoints = new Atomic($allPoints);
$ways = new Atomic($ways);
$findedVariants = new \Volatile();
$bestVariant = new \Volatile();
$bestCost = new \Volatile();
$countVariants = gmp_fact($pointsCount-1); //
for ($i = 0; $i < 11; ++$i)
{
    $task = new Task($pointsCount, $allPoints, $ways, $findedVariants, $bestVariant, $bestCost, $countVariants);
    $pool->submit($task);
}
//sleep(5);
//while ($pool->collect());
$pool->shutdown();
//print_r($findedVariants);
print_r($bestVariant);
