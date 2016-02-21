<?php

/**
 * @author : David Chabrier
 * @copyright : David Chabrier 2010
 * @category : class
 * @version : 0.5
 * @see : http://www.komunote.com
 */
class Vars {

    /**
     * Traduction
     * @var array 
     */
    public static $t = array();

}

// c'est ici qu'on charge toutes les variables de sessions.
//session_set_cookie_params(10);
session_start();

// protection des sessions d'un navigateur à l'autre
if (!isset($_GET['mobile'])) {
    if (!isset($_SESSION['initiated']) ||
            isset($_SESSION['HTTP_USER_AGENT'])) {
        if (!isset($_SESSION['initiated']) ||
                $_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])) {

            session_regenerate_id();
            $_SESSION['initiated'] = true;
        }
    } else {

        $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
    }
}

// si définie en paramètre GET par l'utilisateur
if (isset($_GET['language'])) {
    $_SESSION['language'] = $_GET['language'];
}

// si non définie
if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = strtoupper(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
}

// anglais par défaut
if (!in_array($_SESSION['language'], array('FR', 'EN', 'JP'))) {
    $_SESSION['language'] = 'EN';
}

// gestion des dates
if ($_SESSION['language'] === 'FR') {
    define('DATETIME_FORMAT', 'd/m/Y H:i:s');
    define('DATE_FORMAT', 'd/m/Y');
    define('TIME_FORMAT', 'H:i:s');
} else {
    define('DATETIME_FORMAT', 'Y/m/d h:i:s');
    define('DATE_FORMAT', 'Y/m/d');
    define('TIME_FORMAT', 'h:i:s');
}

setlocale(LC_ALL, strtolower($_SESSION['language']) . '_' . $_SESSION['language']);

if (isset($_SESSION['date_connection'])) {
    if (($_SERVER['REQUEST_TIME'] - $_SESSION['date_connection']) > 9000) {
        $_SESSION['date_connection'] = $_SERVER['REQUEST_TIME'];
        header('Location: ' . _t('./Session-expiree'));
    }
}

/**
 * Classe SQL permettant de réaliser toutes sortes de requêtes
 */
class Sql {

    static public $user = "";
//static private $pass = "";
    static public $cx = null;
    static public $db = null;

    /**
     * Retourne un jeu d'enregistrement SQL sous forme de tableau
     * numérique ou assiociatif
     *
     * @param string $rq
     * @param int $type
     * @return array
     */
    static public function Get($rq = "", $type = 0, $sMemcacheKey = null, $iMemcacheTime=900) {

        $aItems = null;
        if (MEMCACHE_ENABLED && null != $sMemcacheKey) {

            $sKey = md5($sMemcacheKey);
            $aItems = MemCacheManager::Get($sKey);
        }

        if ($aItems && count($aItems) > 0) {
            
            return $aItems;
            
        } else {
            if (self::Login()) {
                //mysql_query("SET NAMES 'utf8'");

                $q = mysql_query($rq, self::$cx) or die(mysql_error());

                if ($type === 0) {
                    $type = MYSQL_NUM;
                } else {
                    $type = MYSQL_ASSOC;
                }

                $a = array();

                while ($row = mysql_fetch_array($q, $type)) {
                    $a[] = $row;
                }

                mysql_free_result($q);
                
                if (MEMCACHE_ENABLED && null != $sMemcacheKey) {
                    MemCacheManager::Set($sKey, $a, $iMemcacheTime); //900s = 15 min
                }

                return $a;
            }
        }
        return null;
    }

    /**
     * Effectue un ajout ou une modification SQL
     *
     * @param string $rq
     * @param int $type
     * @return boolean
     */
    static public function Set($rq = "") {
        if (self::Login()) {

            //mysql_query("SET NAMES 'utf8'");

            return mysql_query($rq, self::$cx) or false; // or
            //die(MvcException::showError(mysql_errno(), mysql_error(),null));
        }
        return false;
    }

    /**
     * Se connecte à la BDD avec les droits adaptés (guest ou user)
     *
     * @return bool
     */
    static public function Login() {

        if ((isset($_SESSION['logged']) && $_SESSION['logged'] === 1)
                // lors de l'inscription
                || (isset($_POST['validation']) && $_POST['validation'] === '666')) {
            $_SESSION['DB_USER'] = PREFIX_USER . "user";
        } else {
            $_SESSION['DB_USER'] = PREFIX_USER . "guest";
        }

        return ((self::$cx = mysql_connect('localhost', $_SESSION['DB_USER'], $_SERVER['DB_PASS'])) &&
                //, MYSQL_CLIENT_COMPRESS )) &&
                (self::$db = mysql_select_db(PREFIX . DB)));
    }

    /**
     * Retourne un identifiant unique crypté en hmac sha256 avec une clé secrète
     * 
     * @return string 
     */
    static public function GetUniqueId() {
        return hash_hmac('sha256', uniqid(rand(), true), 'KoMuNoTe');
    }

    static public function PostSanitizeString($field) {
        return xss_clean(filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING));
    }

    static public function GetSanitizeString($field) {
        return xss_clean(filter_input(INPUT_GET, $field, FILTER_SANITIZE_STRING));
    }

    static public function PostSanitizeFloat($field) {
        return filter_input(INPUT_POST, $field, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    static public function GetSanitizeFloat($field) {
        return filter_input(INPUT_GET, $field, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    static public function PostSanitizeInt($field) {
        return (int) filter_input(INPUT_POST, $field, FILTER_SANITIZE_NUMBER_INT);
    }

    static public function GetSanitizeInt($field) {
        return (int) filter_input(INPUT_GET, $field, FILTER_SANITIZE_NUMBER_INT);
    }

    static public function PostSanitizeEmail($field) {
        return xss_clean(filter_input(INPUT_POST, $field, FILTER_SANITIZE_EMAIL));
    }

    static public function GetSanitizeEmail($field) {
        return xss_clean(filter_input(INPUT_GET, $field, FILTER_SANITIZE_EMAIL));
    }

}

/**
 * Gère les actions et les appels aux traitements métiers (Modèles).
 * Il peut appeler une classe métier externe.
 * Il communique avec le modèle de données afin de
 * réaliser des traitements ainsi qu'avec la vue
 * Il retourne ensuite le résultat final à l'objet MvcPage
 * qui lui se charge d'afficher le flux HTML
 *
 * @author david_chabrier
 */
abstract class MvcController {

    public $model = null;
    public $view = null;
    public $sCacheFile = '';
    private $iCacheExpire = 0;
    protected $bCacheFileExists = false;
    public $bCacheFileIsExpired = false;
    private $sCacheFileParameters = 'index';
    protected $sCacheHtml = '';

    public function __construct(MvcModel $model) {

        $this->model = $model;
    }

    /**
     * Active et charge le cache
     * 
     * @global string $sCategory
     * @global string $sAction
     * @param string $sParams
     * @param int $iExpire
     * @param bool $bIsJson
     * @return bool retourne true si le cache existe et qu'il n'a pas expiré
     */
    public function cacheEnable($sParams = 'index', $iExpire = 900, $bIsJson = false) {

        // cache désactivé
        if (!CACHE_ENABLED)
            return false;

        $this->sCacheFileParameters = $sParams;
        $this->sCacheFile = PATH_WWW . "cache/" . $_SESSION['c'] . "/" . $_SESSION['a'] . $_SESSION['language'] . "-" . $sParams . ".cache";
        $sFilename = $this->sCacheFile . ($bIsJson ? '.js' : '.php');

        if (file_exists($sFilename)) {

            $this->iCacheExpire = $_SERVER['REQUEST_TIME'] - $iExpire;
            $this->bCacheFileExists = true;
            $this->bCacheFileIsExpired = (filemtime($sFilename) < $this->iCacheExpire);

            if (!$this->bCacheFileIsExpired) {

                if (!$bIsJson) {
                    $this->sCacheHtml = file_get_contents($sFilename);
                } else {
                    $this->sCacheHtml = json_decode(file_get_contents($sFilename), true);
                }
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * Met en cache le html généré
     * @param string $sValue 
     * @param bool $bIsJson 
     */
    public function cacheSet($sValue, $bIsJson = false) {
        if (CACHE_ENABLED) {

            if (!$bIsJson) {
                file_put_contents($this->sCacheFile . '.php', $sValue);
            } else {
                file_put_contents($this->sCacheFile . '.js', json_encode($sValue));
            }
        }
    }

    public function execute() {

        $aVars = array();
        $aVars['body'] = $this->model->execute();
        return MvcView::Render($aVars);
    }

}

/**
 * 
 */
class GlobalCache {

    static protected $sCachePath = '';
    static public $sCacheFile = '';
    //protected $bCacheFileExists = false;
    //public $bCacheFileIsExpired = false;
    static protected $sCacheHtml = '';

    static public function Check() {

        // cache désactivé
        if (!CACHE_ENABLED || isset($_GET['nocache']))
            return false;

        $sCacheFileParameters = implode('-', $_GET);

        if ((isset($_SESSION['logged']))) {

            $sCacheFileParameters.='-online';
        } else {

            $sCacheFileParameters.='-offline';
        }

        self::$sCachePath = PATH_WWW . 'cache/global/';
        self::$sCacheFile = PATH_WWW . 'cache/global/' . $sCacheFileParameters . ".cache.php";

        if (file_exists(self::$sCacheFile)) {

            $iCacheExpire = $_SERVER['REQUEST_TIME'] - 3600;
            $bCacheFileIsExpired = (filemtime(self::$sCacheFile) < $iCacheExpire);

            if (!$bCacheFileIsExpired) {
                self::$sCacheHtml = file_get_contents(self::$sCacheFile);
                return true;
            }

            return false;
        } else {

            return false;
        }

        return false;
    }

    /**
     * Met en cache
     * @param string $sHtml
     * @return boolean 
     */
    static public function Set($sHtml) {

        if (self::$sCacheFile == '')
            return false;

        if (CACHE_ENABLED) {

            if (!file_exists(self::$sCachePath)) {

                File::CreatePath(self::$sCachePath);
            }

            file_put_contents(self::$sCacheFile, $sHtml);

            return true;
        }
        return false;
    }

    /**
     * Renvoie le cache
     * 
     * @return boolean 
     */
    static public function Get() {

        if (CACHE_ENABLED) {
            return self::$sCacheHtml;
        }
        return false;
    }

}

/**
 * Gère les connexions à la base de données (ou d'un fichier).
 * Il réalise les requêtes, les demandes d'informations, ainsi
 * que toutes les mises à jour et suppression de données.
 *
 * @author david_chabrier
 */
abstract class MvcModel {

    public function __construct($connection = null) {
        
    }

}

/**
 * Gère une page complète ou des parties de page (appels Ajax) faisant
 * appel à des controlleurs, modèles et vues adaptés en fonction
 * d'une catégorie et d'une action
 *
 * @author david_chabrier
 */
class MvcPage {

    /**
     * Chemin de la page. Doit toujours finir par '/'
     * 
     * @var string
     */
    protected $sPath = "";

    /**
     * Crée une page ou une partie d'une page web
     * @param <string> $sCategorie
     * @param <string> $sAction
     */
    public function __construct($sPath = '') {
        MvcView::$sPath = $sPath;

        require(MvcView::$sPath . '_' . $_SESSION['c'] . '/controller.php');
        require(MvcView::$sPath . '_' . $_SESSION['c'] . '/model.php');
        require(MvcView::$sPath . 'globalTrad.php');
    }

    /**
     * Création de la page.
     * @return <string> retourne un flux HTML
     */
    public function execute() {
        // initialisation de la classe statique MvcView    
        MvcView::setFilename($_SESSION['c'], $_SESSION['a']);

        // création de l'objet dynamique MvcController, le contrèleur reèoit en paramètre un model
        $this->controller = $this->getDynamicClass($_SESSION['c'] . "MvcController", $this->getDynamicClass($_SESSION['c'] . "MvcModel"));

        // exécute l'action et retourne le Html
        //$sHtml = $this->controller->{'execute' . $_SESSION['a']}();
        $sHtml = $this->controller->{$_SESSION['a']}();
        return $sHtml;
    }

    /**
     * Instancie dynamiquement une classe à partir de son nom donné
     * en paramètre. Son nom est formé généralement de la catégorie et
     * de l'action. Cependant l'action peut ètre vide, ce qui induit
     * implicitement de créer une classe formée uniquement de la catégorie
     * @param string $sName
     * @return object
     */
    private function getDynamicClass($sName, $params = null) {

        if ($sName != null && $sName != '')
            $oClass = new $sName($params);
        else
            throw new MvcException('"sName" non définie dans "lib/class/MvcClasses.class.php"', 483);

        return $oClass;
    }

}

/*
 * Interface IHM
 * Elle parse le code HTML de la page avec des donnèes passèes en paramètre
 *
 * @author david_chabrier
 */

class MvcView {

// chemin du template
    public static $sFilename = "";
    public static $sPath = "";
    public static $aVars = array();

    /**
     * Calcule, parse et renvoie un flux HTML
     * 
     * @param string $_filename
     * @param int $prefix
     * @return string 
     */
    public static function render($_sFilename = null, $cPrefix = EXTR_PREFIX_ALL) {

        if (!is_null($_sFilename))
            self::$sFilename = $_sFilename;

        if (self::$sFilename === null || empty(self::$sFilename))
            throw new MvcException('"Filename" non défini dans "komunote_lib/class/MvcClasses.class.php"', 517);

        // démarre la temporisation
        ob_start(); //ob_start("ob_gzhandler_no_errors"); ob_start("ob_gzhandler");
        // exporte les variables dans le template en les préfixants de 'var'
        if (!is_null(self::$aVars)) {
            extract(self::$aVars, $cPrefix, 'var');
        }

        // exécute le template
        include(self::$sFilename);

        // récupère le flux Html et vide le tampon
        $sStr = ob_get_contents();

        ob_end_clean();

        return $sStr;
    }

    /**
     * Ajoute une variable au template
     * 
     * @param string $sKey
     * @param object $oValue 
     */
    public static function set($sKey, $oValue) {
        self::$aVars[$sKey] = $oValue;
    }

    /**
     * Retourne une valeur ajoutée au template
     * 
     * @param string $sKey
     * @return object 
     */
    public static function get($sKey) {

        if (isset(self::$aVars[$sKey])) {
            return self::$aVar[$sKey];
        }

        return null;
    }

    /**
     * Définit une autre vue que celle prévue par le framework.
     * @param type $sControler
     * @param type $sAction 
     */
    public static function setFilename($sControler, $sAction) {
        self::$sFilename = self::$sPath . '_' . $sControler . '/view/' . $sAction . ".view.php";
    }

}

/**
 * Gestionnaire de cache
 * pour les variables et les requêtes SQL.
 * 
 */
class MemCacheManager {

    static private $oInstance;

    /**
     * 
     */
    static private function check() {

        if (!isset(self::$oInstance)) {
            self::$oInstance = new Memcache();
            self::$oInstance->connect(MEMCACHE_HOST, MEMCACHE_PORT);
        }
    }

    /**
     *
     * @param type $sKey
     * @return type 
     */
    static public function Get($sKey) {
        self::check();

        return self::$oInstance->get($sKey);
    }

    static public function Set($sKey, $mValue, $iExpire = 0) {
        self::check();

        $bCompress = is_bool($mValue) || is_int($mValue) || is_float($mValue) ? false : MEMCACHE_COMPRESSED;

        return self::$oInstance->set($sKey, $mValue, $bCompress, $iExpire);
    }

}

/**
 * Classe permettant de tester l'exècution d'une partie d'un script
 */
class Bench {

    private $iStart;
    private $iEnd;

    public function __construct() {
        
    }

    public function start() {

        $this->iStart = microtime(true);
    }

    public function stop() {

        $this->iEnd = microtime(true);
    }

    public function getResultInSeconds() {
        return $this->iEnd - $this->iStart;
    }

}

/**
 * File: SimpleImage.php
 * Author: Simon Jarvis
 * Copyright: 2006 Simon Jarvis
 * Date: 08/11/06
 * Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details:
 * http://www.gnu.org/licenses/gpl.html
 *
 */
class SimpleImage {

    var $image;
    var $image_type;

    function load($sFilename) {

        $retour = false;
        $image_info = getimagesize($sFilename);
        $this->image_type = $image_info[2];

        if ($this->image_type == IMAGETYPE_JPEG) {

            $this->image = imagecreatefromjpeg($sFilename);
            $retour = true;
        } elseif ($this->image_type == IMAGETYPE_GIF) {

            $this->image = imagecreatefromgif($sFilename);
            $retour = true;
        } elseif ($this->image_type == IMAGETYPE_PNG) {

            $this->image = imagecreatefrompng($sFilename);
            $retour = true;
        }

        return $retour;
    }

    function save($sFilename, $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = null) {

        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $sFilename, $compression);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image, $sFilename);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image, $sFilename);
        }
        if ($permissions != null) {
            chmod($sFilename, $permissions);
        }
    }

    function output($image_type = IMAGETYPE_JPEG) {

        if ($image_type == IMAGETYPE_JPEG) {

            imagejpeg($this->image);
        } elseif ($image_type == IMAGETYPE_GIF) {

            imagegif($this->image);
        } elseif ($image_type == IMAGETYPE_PNG) {

            imagepng($this->image);
        }
    }

    function getWidth() {

        return imagesx($this->image);
    }

    function getHeight() {

        return imagesy($this->image);
    }

    function resizeToHeight($height) {

        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    function resizeToWidth($width) {

        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
    }

    function scale($scale) {

        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    function resize($width, $height) {

        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }

}

/**
 * Gère la création et la modification des fichiers
 * 
 */
class File {

    /**
     * Crée le chemin avec ses répertoires enfants si inexistants
     * @param string $path
     * @return bool
     */
    static public function CreatePath($sPath) {
        if (!file_exists($sPath)) {
            return mkdir($sPath, 0777, true/* recursive */);
        }
        return true;
    }

    /**
     * Crée ou modifie un fichier
     * 
     * @param string $path
     * @param string $sFilename
     * @param string $data 
     */
    static public function Set($sPath, $sFilename, $sData) {
        if (!file_exists($sPath)) {
            mkdir($sPath, 0777, true/* recursive */);
        }

        file_put_contents($sPath . $sFilename, utf8_encode($sData));
    }

    /**
     * Ouvre un fichier et en retourne le contenu.
     * Retourne false si le fichier est introuvable.
     *
     * @param string $sFilename
     * @return string
     */
    static public function Get($sFilename) {

        if (file_exists($sFilename)) {

            return file_get_contents($sFilename);
        }
        return false;
    }

    /**
     * Retourne une liste de fichiers d'un rèpertoire donnè en paramètre.
     * 
     * @param string $path
     * @param int $max
     * @return array 
     */
    static public function GetFileList($sPath, $iMax = 0) {

        $aFiles = array();
        if ($rHandle = opendir($sPath)) {

            $i = 0;
            while (false !== ($sFile = readdir($rHandle))) {

                if (strlen($sFile) > 2 && ($iMax < 1 || $i < $iMax)) {

                    $aFiles[] = $sFile;
                    ++$i;
                }
            }
            closedir($rHandle);
        }
        return $aFiles;
    }

    /**
     * Retourne de façon récursive tous les fichiers du chemin donné en paramètre
     * @param string $dir
     * @return array 
     */
    static public function GetFilesFromDir($sDir) {

        $aFiles = array();
        if ($rHandle = opendir($sDir)) {

            while (false !== ($sFile = readdir($rHandle))) {

                if ($sFile != "." && $sFile != "..") {
                    if (is_dir($sDir . '/' . $sFile)) {
                        $sDir2 = $sDir . '/' . $sFile;
                        $aFiles[] = self::GetFilesFromDir($sDir2);
                    } else {
                        $aFiles[] = $sDir . '/' . $sFile;
                    }
                }
            }
            closedir($rHandle);
        }

        return self::array_flat($aFiles);
    }

    static private function array_flat($aArray) {
        $aTmp = array();

        foreach ($aArray as $mA) {

            if (is_array($mA)) {

                $aTmp = array_merge($aTmp, self::array_flat($mA));
            } else {

                $aTmp[] = $mA;
            }
        }

        return $aTmp;
    }

}

/**
 * Affiche le contenu intègral d'un objet, d'un tableau ou d'une variable.
 *
 * @param $variant
 */
function Debug($variant) {
    echo "<pre>";
    var_dump($variant);
    echo "</pre>";
}

/**
 * Crypte une chaine en hmac sha256 via une clè secrète
 * 
 * @param type $string
 * @return string 64 caractères
 */
function sha256_crypt($string) {
    return hash_hmac('sha256', $string, 'KoMuNoTe');
}

/**
 * protection xss
 */
function xss_clean($data) {
// Fix &entity\n;
    $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

// supprime tout attribut commençant par "on" ou xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

// supprime tout javascript: et vbscript: protocoles
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

// uniquement avec IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

// supprime les éléments avec un namespace
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

    do {
// supprime réellement les tags non désirés
        $old_data = $data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    } while ($old_data !== $data);

    return $data;
}

/**
 * Variante du var_dump
 * @param type $var
 * @param type $var_name
 * @param type $indent
 * @param string $reference 
 */
function do_dump(&$var, $var_name = NULL, $indent = NULL, $reference = NULL) {
    $do_dump_indent = "<span style='color:#666666;'>|</span> &nbsp;&nbsp; ";
    $reference = $reference . $var_name;
    $keyvar = 'the_do_dump_recursion_protection_scheme';
    $keyname = 'referenced_object_name';

    // So this is always visible and always left justified and readable
    echo "<div style='text-align:left; background-color:white; font: 100% monospace; color:black;'>";

    if (is_array($var) && isset($var[$keyvar])) {
        $real_var = &$var[$keyvar];
        $real_name = &$var[$keyname];
        $type = ucfirst(gettype($real_var));
        echo "$indent$var_name <span style='color:#666666'>$type</span> = <span style='color:#e87800;'>&amp;$real_name</span><br>";
    } else {
        $var = array($keyvar => $var, $keyname => $reference);
        $avar = &$var[$keyvar];

        $type = ucfirst(gettype($avar));
        if ($type == "String")
            $type_color = "<span style='color:#FF00FF'>";
        elseif ($type == "Integer")
            $type_color = "<span style='color:red'>";
        elseif ($type == "Double") {
            $type_color = "<span style='color:#0099c5'>";
            $type = "Float";
        } elseif ($type == "Boolean")
            $type_color = "<span style='color:#92008d'>";
        elseif ($type == "NULL")
            $type_color = "<span style='color:black'>";

        if (is_array($avar)) {
            $count = count($avar);
            echo "$indent" . ($var_name ? "$var_name => " : "") . "<span style='color:#666666'>$type ($count)</span><br>$indent(<br>";
            $keys = array_keys($avar);
            foreach ($keys as $name) {
                $value = &$avar[$name];
                do_dump($value, "[$name]", $indent . $do_dump_indent, $reference);
            }
            echo "$indent)<br>";
        } elseif (is_object($avar)) {
            echo "$indent$var_name <span style='color:#666666'>$type</span><br>$indent(<br>";
            foreach ($avar as $name => $value)
                do_dump($value, "$name", $indent . $do_dump_indent, $reference);
            echo "$indent)<br>";
        } elseif (is_int($avar))
            echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> $type_color" . htmlentities($avar) . "</span><br>";
        elseif (is_string($avar))
            echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> $type_color\"" . htmlentities($avar) . "\"</span><br>";
        elseif (is_float($avar))
            echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> $type_color" . htmlentities($avar) . "</span><br>";
        elseif (is_bool($avar))
            echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> $type_color" . ($avar == 1 ? "TRUE" : "FALSE") . "</span><br>";
        elseif (is_null($avar))
            echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> {$type_color}NULL</span><br>";
        else
            echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> " . htmlentities($avar) . "<br>";

        $var = $var[$keyvar];
    }

    echo "</div>";
}

/**
 * Chainage d'objet
 * @param type $oObject
 * @return type 
 */
function get($oObject) {
    return $oObject;
}

/**
 * Fonction de traduction pour la tÃ¢che CRON
 * @global array $t
 * @param string $key chaÃ®ne à traduire
 * @return void 
 */
function t($key) {
    if (CRON) {

        _t::$_t[$key] = $key;
    } else {

        if (isset(Vars::$t[$_SESSION['language']][$key]) &&
                Vars::$t[$_SESSION['language']][$key] != '') {

            echo Vars::$t[$_SESSION['language']][$key];
        } else {
            echo $key;
        }
    }
}

/**
 * Fonction de traduction pour la tÃ¢che CRON
 * @global type $t
 * @param string $key chaÃ®ne à traduire
 * @return string 
 */
function _t($key) {
    if (CRON) {

        _t::$_t[$key] = $key;
    } else {

        if (isset(Vars::$t[$_SESSION['language']][$key]) &&
                Vars::$t[$_SESSION['language']][$key] != '') {

            return Vars::$t[$_SESSION['language']][$key];
        } else {

            return $key;
        }
    }
}
