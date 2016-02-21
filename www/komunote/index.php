<?php

/*
 * @author David Chabrier
 * @info :  chmod 777 /var/www
 *          chmod 777 /var/www/images
 *          chmod 777 /var/www/images/shop
 *          chmod 777 /var/www/cache/
 *          chmod 777 /var/www/cache/admin
 *          chmod 777 /var/www/cache/global
 *          chmod 777 /var/www/cache/index
 *          chmod 777 /var/www/cache/shop
 */
error_reporting(E_ALL);

define('DEV', true);
define('IS_JSON', true);
define('CRON', false);

define('MEMCACHE_HOST', '127.0.0.1');
define('MEMCACHE_PORT', '11211');

if (DEV) {
  
  define('PATH_LIB', '../../lib/komunote_lib/');
  define('PATH_WWW', '../komunote/');
  define('MEMCACHE_ENABLED', false);
  define('CACHE_ENABLED', false);  
  define('DB', 'admin');
  define('DB_HOST', 'localhost/komunote');
  define('PREFIX', 'komu7dh_komu');   // pour les tables
  define('PREFIX_USER', 'komu7dh_');  // pour les utilisateurs
} else {
    
  define('PATH_LIB', '/var/lib/komunote_lib/');
  define('PATH_WWW', '/var/www/');
  define('MEMCACHE_ENABLED', true);
  define('CACHE_ENABLED', true);  
  define('DB', 'admin');
  define('DB_HOST', 'www.komunote.eu');
  define('PREFIX', 'komu7dh_komu');
  define('PREFIX_USER', 'komu7dh_');
}
include(PATH_LIB . "class/MvcClasses.class.php");


$bench = new Bench();
$bench->start();

header('Cache-Control: public, max-age=3600, must-revalidate');
header('Expires: ' . gmdate("D, d M Y H:i:", $_SERVER['REQUEST_TIME'] + 3600));

if (GlobalCache::Check()) {

  echo GlobalCache::Get();
  $bench->stop();
  echo "<br /><p align='center'>Global Page en cache ", GlobalCache::$sCacheFile;
  echo "<br />Page exécutée en : ", $bench->getResultInSeconds(), " secondes</p>";
  
} else {

  $_SESSION['c'] = Sql::GetSanitizeString('c');
  if ($_SESSION['c'] == '')
    $_SESSION['c'] = 'index';

  $_SESSION['a'] = Sql::GetSanitizeString('a');
  if ($_SESSION['a'] == '')
    $_SESSION['a'] = 'Action';

  // contenu principal        
  $page = new MvcPage(PATH_LIB);
  MvcView::set('result', $page->Execute());

  $render = MvcView::Render(PATH_LIB . "_default/view/masterpage.view.php");
  echo $render;
  GlobalCache::Set($render);
  $bench->stop();

  if (!$page->controller->bCacheFileIsExpired)
    echo "<br /><p align='center'>Page en cache : ", $page->controller->sCacheFile, "</p>";

  echo "<br /><p align='center'>Page ", $_SESSION['c'], $_SESSION['a'], " exécutée en : ", 
          $bench->getResultInSeconds(),
  " secondes<br />Memoire : ", memory_get_usage(), "</p>";
}