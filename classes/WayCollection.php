<?php
class WayCollection extends Threaded
{
    /**
     * @var int
     */
    public $countVariantsToFind;
    public $dirtyWays = [];
    public $cleanWays = [];
    public $dirtyCheckedInd = 0;
    public $countCleanWays = 0;
    public $bestVariant = 0;

    /**
     * WayCollection constructor.
     * @param int $countVariantsToFind
     */
    public function __construct(int $countVariantsToFind, $bestVariant)
    {
        $this->countVariantsToFind = $countVariantsToFind;
        $this->bestVariant = $bestVariant;
    }

    public function takeWay(array $ways)
    {
        $tWays = [];
        foreach ($ways as $way) {
            $tWays[] = [
                'jumps' => (array)$way['jumps'],
                'wayCost' => (int) $way['wayCost'],
                'hash' => (string) $way['hash'],
            ];
        }

        $this->dirtyWays[] = $tWays;

//        $this->dirtyWays->synchronized(function ($dirtyWays) use ($ways){
//            foreach ($ways as $way){
//                $dirtyWays[] = [
//                    'jumps' => (array) $way['jumps'],
//                    'wayCost' => (int) $way['wayCost'],
//                ];
//            }
//            $dirtyWays->notify();
//        }, $this->dirtyWays);
    }

    public function iGotEnogh():bool
    {
        return $this->countCleanWays >= $this->countVariantsToFind;
    }

    public function run()
    {
        $cleanWays = [];
        while (!$this->iGotEnogh()){
            $variantHeaps = array_slice((array) $this->dirtyWays, $this->dirtyCheckedInd);

            foreach ($variantHeaps as $variantHeap)
            {
                foreach ($variantHeap as $variant)
                {
                    $vHash = $variant['hash'];
                    if (!isset($this->cleanWays[$vHash])){
                        $this->cleanWays[$vHash] = 1;
                        $cleanWays[] = [
                            'jumps' => (array) $variant['jumps'],
                            'wayCost' => (int) $variant['wayCost'],
                        ];
                        $this->countCleanWays ++;
//                        echo $this->countCleanWays.PHP_EOL;
                    }
                }

                $this->dirtyWays[$this->dirtyCheckedInd] = '-1';
                $this->dirtyCheckedInd ++;
                echo $this->dirtyCheckedInd.'- ';
                echo ''.$this->countCleanWays.PHP_EOL;
            }

        }

        echo 'Looking for best way'.PHP_EOL;

        $bestVariant = [];
        foreach ($cleanWays as $variant)
        {
            if (!$bestVariant || $variant['wayCost'] < $bestVariant['wayCost']){
                $bestVariant = $variant;
                echo $variant['wayCost'].PHP_EOL;
            }
        }

        print_r($bestVariant);
        die;

        return $this->bestVariant;
    }
}