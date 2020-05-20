<?php

if (!isset($argv[1]) || !is_file($argv[1])) {
    die("csv file doesn't exists\n");
}

$fp = fopen($argv[1], 'r');

$nameList = $fileSize = [];
$totalSize = 0;

if (!is_dir('result')) {
    mkdir(__DIR__ . '/result');
}

while (($file = fgetcsv($fp, 0, ',')) !== false) {
    $nameList =
        [
            'name' => trim(strtolower(str_replace(' ', '_', $file[0]))),
            'image' => $file[1]
        ];
    $image = new Imagick("resources/{$nameList['image']}");
    $image->cropThumbnailImage(150, 150);
    $image->writeImage("result/{$nameList['name']}.jpg");
    $fileSize = filesize("result/{$nameList['name']}.jpg");
    $totalSize += $fileSize;
    $resultSize[] = [
        'name' => $nameList['name'],
        'size' => $fileSize
    ];
}

$fp = fopen("result/result.csv", "w");
$filesSize = '';

for ($i = 0; $i < count($resultSize); $i++) {
    $filesSize .= "{$resultSize[$i]['name']} = {$resultSize[$i]['size']} Bytes\n";
}

fwrite($fp, $filesSize);
fwrite($fp, "\nTotal Image Size (Bytes): " . $totalSize);
fclose($fp);
