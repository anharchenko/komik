<?php
if (!function_exists('gmp_fact')){
    function gmp_fact($fact){
        $ffact = 1;
        while($fact >= 1)
        {
            $ffact = $fact * $ffact;
            $fact--;
        }

        return $ffact;
    }
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}