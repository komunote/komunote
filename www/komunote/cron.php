<?php
header('Content-Type: text/html; charset=utf-8');

define('DEV', true);
define('CRON', true);
define('MEMCACHE_HOST', '127.0.0.1');
define('MEMCACHE_PORT', '11211');

if (DEV) {

    define('PATH_LIB', '../../lib/komunote_lib/');
    define('PATH_WWW', '../komunote/');
    define('DB', 'admin');
    define('DB_HOST', 'localhost/komunote');
    define('PREFIX', 'komu7dh_komu');   // pour les tables
    define('PREFIX_USER', 'komu7dh_');  // pour les utilisateurs
} else {
    define('MEMCACHE_ENABLED', true);
    define('CACHE_ENABLED', false);
    define('PATH_LIB', '/var/lib/komunote_lib/');
    define('PATH_WWW', '/var/www/');
    define('DB', 'admin');
    define('DB_HOST', 'www.komunote.eu');
    define('PREFIX', 'komu7dh_komu');
    define('PREFIX_USER', 'komu7dh_');
}

require(PATH_LIB . "class/MvcClasses.class.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1)
    die('Non autorisé');
?>

<h2>Liste des tâches CRON</h2>
<ul>
    <li><a href="./cron.php?a=cc">Clear cache</a></li>
    <li><a href="./cron.php?a=cac">Clear all cache </a></li>
    <li><a href="./cron.php?a=trad">Generate default translation </a></li>
    <li><a href="./cron.php?a=statsBdd">Statistiques BDD </a></li>
</ul>
<hr />

<?php
// charge le bon fichier CRON et l'exécute
if (isset($_GET['a'])) {

    $file = '';

    switch ($_GET['a']) {

        // génération des entrées de traduction
        case 'generateDefaultTranslation':
        case 'genTrad':
        case 'trad':
            $file = 'generateDefaultTranslation';
            break;

        // effacement des fichiers de cache
        case 'clearCache':
        case 'cc':
            $file = 'clearCache';
            break;

        // Statistiques base de données
        case 'statsBdd':
        case 'sbdd':
            $file = 'statsBdd';
            break;

        // effacement des fichiers de cache
        case 'clearAllCache':
        case 'cac':
        default:
            $file = 'clearAllCache';
            break;
    }
    include(PATH_LIB . 'cron/' . $file . '.php');
}
?>