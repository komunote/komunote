<?php

//error_reporting(0);

/*
 * @author David Chabrier
 */

$path = '../../komunote_lib/';
define('CRON', false);
include($path . "class/MvcClasses.class.php");

//define('DB_HOST', '172.17.11.70');

header('P3P: CP="CAO PSA OUR"');
header('Content-type: application/json');
header('Cache-Control: public, max-age=3600, must-revalidate');
header('Expires: ' . gmdate("D, d M Y H:i:", $_SERVER['REQUEST_TIME'] + 3600));

if (GlobalCache::Check()) {
    echo GlobalCache::Get();
} else {
    $_SESSION['c'] = Sql::GetSanitizeString('c');
    if ($_SESSION['c'] == '')
        $_SESSION['c'] = 'index';

    $_SESSION['a'] = Sql::GetSanitizeString('a');
    if ($_SESSION['a'] == '')
        $_SESSION['a'] = 'Action';

    $page = new MvcPage($path);
    $render = $page->Execute();        
    echo $render;
    GlobalCache::Set($render);
}
?>