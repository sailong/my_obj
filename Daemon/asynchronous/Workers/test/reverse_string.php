<?php

function reverse_string($job, &$log) {

    $workload = $job->workload();

    $result = strrev($workload);
    
    file_put_contents('/tmp/gearman-test.log', date('Y-m-d H:i:s') .':'. $result . "\n", FILE_APPEND );

    $log[] = "Success";

    return $result;

}

?>