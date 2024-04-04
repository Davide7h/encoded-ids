<?php

/* Retrieve Laravel config file */
$configFile = __DIR__.'/../../../config/app.php';
$content = file_get_contents($configFile);

// Package provider name
$provider = 'Davide7h\EncodedIds\EncodedIdsProvider::class';



// Find pointers to start and end of the "providers" array
$start = strpos($content, "'providers' => [");
$end = strpos($content, ']', $start);

// Check if the provider is already present
if (strpos($content, $provider, $start) === false) {
    // Append package provider to the array
    $content = substr_replace($content, "\n\t\t{$provider},\n", $end, 0);

    // Update config file
    file_put_contents($configFile, $content);

    echo "ServiceProvider successfully published to config/app.php\n";
} else {
    echo "ServiceProvider already published to config/app.php\n";
}
