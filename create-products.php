<?php
$dir = "data/";
$files = glob($dir . "*.json");

@mkdir("products/");

$index = 1;

foreach ($files as $file_item) {
    $data = file_get_contents($file_item);
    $obj = json_decode($data, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        continue;
    }

    foreach ($obj["products"] as $product) {
        file_put_contents("products/$index.json", json_encode($product, JSON_UNESCAPED_UNICODE));
        
        $index++;
    }
}

print "Done.\n";