<?php

//error_reporting(0);
$aRq = array();
$aItems = array();
$aRq[] = "SELECT count(*) as nb_items FROM " . PREFIX . "shop.`item`;";
$aRq[] = "SELECT count(*) as nb_users FROM " . PREFIX . "admin.`user`;";
$aRq[] = "SELECT count(*) as nb_orders FROM " . PREFIX . "sales.`order`;";
//$aRq[] = "SELECT count(*) FROM " . PREFIX . "shop.`item`;";

foreach($aRq as $sRq) {
  $aItems[] = Sql::Get($sRq, 1);
}

        
var_dump($aItems);

/*foreach(array_keys($files) as $key) {
    if(filemtime($files[$key]) < ($_SERVER['REQUEST_TIME'] - 3600)) {
        echo "Fichier supprimé : ", $files[$key], " ",filemtime($files[$key]), " < ", ($_SERVER['REQUEST_TIME'] - 3600),"<br />";
        unlink($files[$key]);
    }
}*/