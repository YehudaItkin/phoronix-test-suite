<?php

$BENCHMARK_RESULTS = substr($argv[1], strrpos($argv[1], "Render Time:") + 12);
echo trim(substr($BENCHMARK_RESULTS, 0, strpos($BENCHMARK_RESULTS, "Seconds")));
?>
