<?php

//error_reporting(0);
$files = File::GetFilesFromDir('cache/');
var_dump($files);

foreach (array_keys($files) as $key) {

    echo "Fichier supprimé : ", $files[$key], " ", filemtime($files[$key]), " < ", ($_SERVER['REQUEST_TIME'] - 3600), "<br />";
    unlink($files[$key]);
}