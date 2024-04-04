<?php
/* Retrieve the package config file */
$configFile = __DIR__."/../config/encoded-ids.php";
$content = file_get_contents($configFile);

/* Find the pointer to the start and end of the alphabet string */
$start = strpos($content, "'alphabet' => '");
$end = strpos($content, "'", $start);

/* Generate a random alphabet */
$alphabet = str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
// Append the newly generated alphabet to the config file
$content = substr_replace($content, "{$alphabet}", $end, 0);

// Update the config file
file_put_contents($configFile, $content);

echo "Generated new alphabet: {$alphabet}\n";
