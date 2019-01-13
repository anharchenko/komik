<?php

class WayFinder extends Threaded
{
    /**
     * @var array
     */
    private $allPoints;
    /**
     * @var array
     */
    private $ways;
    /**
     * @var integer
     */
    private $pointsCount;
    /**
     * @var WayCollection
     */
    private $wayCollection;

    /**
     * WayFinder constructor.
     * @param array $allPoints
     * @param array $ways
     * @param int $pointsCount
     * @param WayCollection $wayCollection
     */
    public function __construct(array $allPoints, array $ways, int $pointsCount, WayCollection $wayCollection)
    {
        $this->allPoints = $allPoints;
        $this->ways = $ways;
        $this->pointsCount = $pointsCount;
        $this->wayCollection = $wayCollection;
    }

    private function getWay()
    {
        $jumps = [0];
        $currentPoint = 0;
        $wayCost = 0;
        do{
            $availableJumps = array_diff((array) $this->allPoints, $jumps);
            $nextPoint = array_rand($availableJumps);

            $jumps[] = $nextPoint;
            $wayCost += $this->ways[$currentPoint][$nextPoint];
            $currentPoint = $nextPoint;

        }while(count($jumps) < $this->pointsCount);

        return $response =  [
            'jumps' => $jumps,
            'hash' => md5(implode('|', $jumps)),
            'wayCost' => $wayCost,
        ];
    }

    public function run()
    {
        $iWaitCollection = 0;
        $iLookForWays = 0;
        do
        {
            $start = microtime_float();
            $variants = [];
            for($i = 0; $i < 300000;)
            {
                $variant = $this->getWay();

                if (isset($variants[$variant['hash']])){
                    continue;
                }

                if ($this->wayCollection->iGotEnogh())
                {
                    break;
                }

                $variants[$variant['hash']] = $variant;

                $i++;
            }
            $iLookForWays += microtime_float() - $start;

            $start = microtime_float();
            $this->wayCollection->takeWay($variants);
            $iWaitCollection += microtime_float() - $start;
            unset($variants, $variant);

        }while(!$this->wayCollection->iGotEnogh());

        echo 'i wait collection: '.sprintf('%f', $iWaitCollection).PHP_EOL;
        echo 'i look for ways: '.sprintf('%f', $iLookForWays).PHP_EOL;
    }
}