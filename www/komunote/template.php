<?php
header('Content-Type: text/html; charset=utf-8');
?>

<h2>Génération des templates</h2>

<hr />

<?php 
$path = '../../komunote_lib/';
define('CRON', false);
require($path . "class/MvcClasses.class.php");


include($path . '_index/view/About.tpl.php');
 
?>