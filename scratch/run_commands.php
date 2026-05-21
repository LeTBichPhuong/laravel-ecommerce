<?php
echo "--- RUNNING COMMANDS ---\n";

$commands = [
    "php artisan storage:link",
    "php crawl_products.php",
    "php artisan migrate:fresh --seed"
];

foreach ($commands as $cmd) {
    echo "\n> $cmd\n";
    system($cmd, $retval);
    if ($retval !== 0) {
        echo "FAILED with status $retval\n";
    } else {
        echo "SUCCESS\n";
    }
}
echo "\n--- DONE ---\n";
