<?php

header('Content-Type: text/html; charset=utf-8');

define('DEV', true);

if (DEV) {  
    
  define('PATH_LIB', '../../lib/komunote_lib/');
  
} else {    
    
  define('PATH_LIB', '/var/lib/komunote_lib/');  
  
}

class _t {

    public static $_t = array(); // local trad
    public static $t = array(); // global trad

}

$folders = array('_default', '_admin', '_index', '_shop');
$languages = array('EN', 'JP'); // le FR est par défaut pas besoin de l'ajouter
// on parcourt chaque dossier
foreach ($folders as $folder) {
    echo '<h2>', $folder, '</h2>';
    include(PATH_LIB . $folder . '/trad.php');
    //echo '<h3>Entrées existantes</h3>';    

    $files = File::GetFileList(PATH_LIB . $folder . '/view/');

    var_dump($files);
    //var_dump(Vars::$t);
    // pour chaque template trouvé
    foreach ($files as $file) {
        //echo $file, '<br />';
        // on exécute le template afin de récupérer les entrées de traduction
        ob_start();
        include(PATH_LIB . $folder . '/view/' . $file);
        ob_end_clean();

        // on ajoute les nouvelles entrées trouvées dans les templates avec celles
        // déjà existantes
        foreach (_t::$_t as $key => $value) {
            foreach ($languages as $language) {
                // on écrase pas l'entrée existante
                if (!isset(Vars::$t[$language][$key])) {
                    Vars::$t[$language][$key] = '';
                }
                if (!isset(_t::$t[$language][$key])) {
                    // copie dans le tableau pour la trad globale
                    _t::$t[$language][$key] = Vars::$t[$language][$key];
                }
            }
        }
    }

    echo '<h3>Totalité des entrées</h3>';
    var_dump(Vars::$t);

    $filename = PATH_LIB . $folder . '/trad.php';

    if (copy($filename,  PATH_LIB . 'backup/'.date('Y-m-d-H-i-s').'_trad'.$folder.'.php')) {
        // on écrase le fichier existant avec toutes les entrées
        $h = fopen($filename, 'w');
        // en-téte fichier php    
        fwrite($h, "<?php\n");

        foreach ($languages as $language) {
            $keys = array_keys(Vars::$t[$language]);
            natcasesort($keys);

            foreach ($keys as $key) {
                fwrite($h, 'Vars::$t["' . $language . '"]["' . $key . '"]="' . Vars::$t[$language][$key] . '";' . "\n");
            }

            fwrite($h, "\n");
        }
        fwrite($h, "\n");
        fclose($h);
    }

    // on vide les tableaux sauf le global trad
    Vars::$t = array();
    _t::$_t = array();
}


// écriture dans le fichier de trad général
$h = fopen(PATH_LIB . 'globalTrad.php', 'w');
fwrite($h, "<?php\n");
foreach ($languages as $language) {
    $keys = array_keys(_t::$t[$language]);
    natcasesort($keys);

    foreach ($keys as $key) {
        fwrite($h, 'Vars::$t["' . $language . '"]["' . $key . '"]="' . _t::$t[$language][$key] . '";' . "\n");
    }
    fwrite($h, "\n");
}

fwrite($h, "\n");
fclose($h);
?>
