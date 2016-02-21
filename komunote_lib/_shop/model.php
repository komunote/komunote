<?php

/**
 *
 */
class ShopMvcModel extends MvcModel {

    public function __construct() {
        parent::__construct();
    }

    static public function getItems($aKeywords, $iOffset = 0, $aFilters = array()) {
        
        $skeywords = '1';
        foreach ($aKeywords as $sKeyword) {
            $skeywords .=" AND `keywords` like '%" . $sKeyword . "%'";
        }

        $aParams = self::generateItemFilters($aFilters);

        $sRq = "SELECT * 
                FROM " . PREFIX . "shop.`item` as i
                where i.id NOT IN(
                        SELECT o.id_item 
                        FROM " . PREFIX . "sales.`order` as o 
                        WHERE o.is_validated = 1	
                ) AND " . $skeywords . $aParams['filters'] . $aParams['orderby'] . " 
                LIMIT " . $iOffset . ",10;";
        
        $aItems = Sql::Get($sRq, 1, 'shop-getItems-' . $skeywords, 900);        

        return $aItems;
        //}
    }

    /**
     * Retourne le nombre d'enregistrement
     * 
     * @param array $aKeywords
     * @param array $aFilters
     * @return int 
     */
    static public function getItemsCount($aKeywords, $aFilters = array()) {
        
        $sKeywords = '';
        $i = 0;
        foreach ($aKeywords as $sKeyword) {
            if ($i > 0)
                $sKeywords .=" AND ";
            $sKeywords .="`keywords` like '%" . $sKeyword . "%'";
            ++$i;
        }

        $aParams = self::generateItemFilters($aFilters);

        $sRq = "SELECT COUNT(*) FROM " . PREFIX . "shop.`item` 
          WHERE " . $sKeywords . $aParams['filters'] . $aParams['orderby'];

        $aItems = Sql::Get($sRq, 0, 'shop-getItemsCount-' . $sKeywords);
        
        return (int) $aItems[0][0];        
    }

    /**
     * Retourne un tableau de clé/valeur contenant les filtres et les orderby
     * utile à  la construction de la requàªte SQL
     * @param array $aFilters
     * @return array 
     */
    static private function generateItemFilters($aFilters) {

        if (is_array($aFilters) && count($aFilters) > 0) {

            $sFilter = '';
            $aOrderby = array();
            foreach ($aFilters as $sKey => $sValue) {
                switch ($sKey) {
                    /* price greater than */
                    case 'priceGt': $sFilter.=" AND unit_price > " . (int) $sValue;
                        break;
                    /* price greater than equal */
                    case 'priceGte': $sFilter.=" AND unit_price >= " . (int) $sValue;
                        break;
                    /* price less than */
                    case 'priceLt': $sFilter.=" AND unit_price < " . (int) $sValue;
                        break;
                    /* price less than equal */
                    case 'priceLte': $sFilter.=" AND unit_price <= " . (int) $sValue;
                        break;

                    case 'price':
                        if (in_array($sValue, array('ASC', 'DESC'))) {
                            $aOrderby[] = "unit_price " . $sValue;
                        }break;
                    case 'date':
                        if (in_array($sValue, array('ASC', 'DESC'))) {
                            $aOrderby[] = "last_modified " . $sValue;
                        }break;

                    default: break;
                }
            }

            $sOrderby = "";
            if (count($aOrderby) > 0) {
                $sOrderby = " ORDER BY " . implode(',', $aOrderby);
            }

            return array('filters' => $sFilter, 'orderby' => $sOrderby);
        }

        return array('filters' => '', 'orderby' => '');
    }

    /**
     * Retourne un item en fonction de son Id
     * 
     * @param string $id
     * @return array 
     */
    static public function getItemById($id) {
        $rq = "SELECT * FROM " . PREFIX . "shop.`item` 
            WHERE `id` =" . $id . " LIMIT 1;";

        $aItem = Sql::Get($rq, 1);
        // MemCacheManager::Set($sKey, $aItems, 900); //900s = 15 min

        return $aItem[0];
    }

    /**
     * Ajoute une nouvel item en base
     * 
     * @return bool 
     */
    static public function addNewItem() {

        // exit si on a déjà  soumis le formulaire
        if (isset($_SESSION['submit']))
            return false;

        if (isset($_FILES['uploaded_image'])) {
            $aExtensions = array('.jpg', '.jpeg');
            // récupère la partie de la chaine à partir du dernier . pour connaî®tre l'extension.
            $sExtension = strrchr($_FILES['uploaded_image']['name'], '.');
            //Ensuite on teste
            if (!in_array($sExtension, $aExtensions)) { //Si l'extension n'est pas dans le tableau        
                return false;
            }
        }

        $sKeywords = strtolower(str_replace("'", '', Sql::PostSanitizeString('_keywords')));
        $sDescription = Sql::PostSanitizeString('_description');
        $fPrice = Sql::PostSanitizeFloat('_price');
        $fShipping = Sql::PostSanitizeFloat('_shipping');
        $iShippingType = Sql::PostSanitizeInt('_shipping_type');
        $iState = Sql::PostSanitizeInt('_state');

        // vérification qu'au moins un mot clé de plus de 3 lettres est présent
        $aEx = explode(' ', $sKeywords);
        $aKeywordList = array();

        foreach ($aEx as $sKey) {
            if (strlen($sKey) < 3) {
                continue;
            } else {
                $aKeywordList[] = $sKey;
            }
        }

        if (count($aKeywordList) > 0) {

            $sRq = "INSERT INTO " . PREFIX . "shop.`item`
              (`id_user`,`keywords`,`description`,`unit_price`,`unit_shipping_price`,
              `shipping_type`,`state`, `date_created`, `last_modified`)
              VALUES('" .
                    $_SESSION['user_id'] . "','" .
                    $sKeywords . "','" .
                    mysql_real_escape_string($sDescription) . "','" .
                    $fPrice . "','" .
                    $fShipping . "'," .
                    $iShippingType . "," .
                    $iState . ",
              NOW(),
              NOW());";

            if (Sql::Set($sRq)) {

                $iId = mysql_insert_id();

                if ($iId == 0)
                    return false;
               
                // chaque mot clé est ajouté dans la table KEYWORD
                foreach ($aKeywordList as $sKey) {
                    $aKeyword = self::getKeyword($sKey);

                    // le mot clé existe, on le met à  jour
                    if (isset($aKeyword) && count($aKeyword) > 1) {
                        self::setKeyword($key, $aKeyword["hit"]);
                    }
                    // le mot clé est nouveau, on l'ajoute
                    else {
                        self::addNewKeyword($sKey);
                    }                    
                }               

                // ajout de l'image        
                if (isset($_FILES['uploaded_image'])) {
                    $aExtensions = array('.jpg', '.jpeg');

                    // récupà¨re la partie de la chaine à  partir du dernier . pour connaà®tre l'extension.
                    $sExtension = strrchr($_FILES['uploaded_image']['name'], '.');

                    //Ensuite on teste Si l'extension n'est pas dans le tableau
                    if (!in_array($sExtension, $aExtensions)) {
                        return false;
                    }

                    $oImage = new SimpleImage();

                    if ($oImage->load($_FILES['uploaded_image']['tmp_name'])) {
                        // si plus grand on redimensionne
                        if ($oImage->getWidth() > 480) {
                            $oImage->resizeToWidth(480);
                        }

                        if (File::CreatePath(PATH_WWW . 'images/shop/' . $iId . '/')) {
                            $oImage->save(PATH_WWW . 'images/shop/' . $iId . '/' . $iId . '.jpg'); //$image->output();    
                        } else {
                            return false;
                        }
                    }
                }

                $_SESSION['submit'] = 1;

                return true;
            }
        }

        return false;
    }

    /**
     * Ajoute un mot clé en base
     * 
     * @param type $sKeyword
     * @return bool 
     */
    static private function addNewKeyword($sKeyword) {
        $sRq = "INSERT INTO " . PREFIX . "keyword.`keyword`
            (`keyword`, `last_modified`, `hit`)VALUES
            ('" . $sKeyword . "', NOW(), 1);";

        return Sql::Set($sRq);
    }

    /**
     * Met à  jour la date de modification et le nombre de hit d'un mot clé
     * 
     * @param type $sKeyword
     * @param type $iHit
     * @return type 
     */
    static private function setKeyword($sKeyword, $iHit) {
        $sRq = "UPDATE " . PREFIX . "keyword.`keyword`
          SET last_modified = NOW(),
                hit = " . ++$iHit . "
          WHERE `keyword` = '" . $sKeyword . "' LIMIT 1;";

        return Sql::Set($sRq);
    }

    /**
     * Retourne les propriétés d'un mot clé
     * 
     * @param type $keyword
     * @return type 
     */
    static private function getKeyword($sKeyword) {
        $rq = "SELECT * FROM " . PREFIX . "keyword.`keyword` 
            WHERE `keyword` = '" . $sKeyword . "' LIMIT 1;";

        return Sql::Get($rq, 1);
    }

    /**
     * Vérifie si un utilisateur a déjà  une commande en cours (non validée)
     * Retourne le numéro de commande s'il existe
     * sinon retourne false
     * 
     * @param string $userId
     * @param string $itemId
     * @return string 
     */
    static public function checkAlreadOrderedByUserAndNotValidated($iUserId, $iItemId) {

        $sRq = "SELECT id FROM " . PREFIX . "sales.`order` 
            WHERE id_user=" . (int) $iUserId . " 
                AND id_item=" . (int) $iItemId . " 
                AND is_validated =0 
                AND is_cancelled =0 
            LIMIT 1;";

        $aResult = Sql::Get($sRq, 1);

        if (count($aResult) > 0) {
            return $aResult[0]['id'];
        } else {
            return false;
        }
    }

    /**
     * Vérifie si l'item est déjà  commandé par quelqu'un
     * Retourne le numéro de commande s'il existe
     * sinon retourne false
     * 
     * @param int $itemId
     * @return string 
     */
    static public function checkAlreadOrderedBySomeUserAndNotValidated($iItemId) {

        $sRq = "SELECT id FROM " . PREFIX . "sales.`order` 
            WHERE id_item=" . (int) $iItemId . " 
                AND is_validated =0 
                AND is_cancelled =0 
            LIMIT 1;";

        $aResult = Sql::Get($sRq, 1);

        if (count($aResult) > 0) {
            return $aResult[0]['id'];
        } else {
            return false;
        }
    }

    /**
     * Crée une nouvelle commande en attente de paiement Paypal.
     * Toute commande créée a le statut 'is_validated' à false.
     * Retourne le numéro de commande sinon false
     * 
     * @param string $id_seller
     * @param string $id_user
     * @param string $id_item
     * @param float $unit_price
     * @param float $unit_shipping_price
     * @param int $quantity
     * @param int $shipping_type
     * @return string 
     */
    static public function addNewOrder($iIdSeller, $iIdUser, $iIdItem, $fUnitPrice, $fUnitShippingPrice, $iQuantity = 1, $iShippingType = 0) {

        // exit si on a déjà  soumis le formulaire
        if (isset($_SESSION['submit']))
            return false;

        $sRq = "INSERT INTO " . PREFIX . "sales.`order`
            (`id_seller`,`id_user`,`id_item`,`unit_price`,`unit_shipping_price`, 
            `quantity`,`shipping_type`,`date_created`,`is_validated`,`is_cancelled`)
	  VALUES(
		
		" . (int) $iIdSeller . ",
                " . (int) $iIdUser . ",
                " . (int) $iIdItem . ",                      
                " . $fUnitPrice . ",
                " . $fUnitShippingPrice . ",
		" . (int) $iQuantity . ",
		" . (int) $iShippingType . ",		
		NOW(),		
                0,0
            );";
//echo "<!--".$sRq."-->";
        if (Sql::Set($sRq)) {
            $_SESSION['submit'] = 1;
            return mysql_insert_id();
        }

        return false;
    }

    /**
     * Valide une commande pour un item et un utilisateur donnés.
     *      
     * @param string $id_item
     * @param string $id_user
     * @return bool 
     */
    static public function validateOrder($iIdOrder, $iIdUser) {

        $sRq = "UPDATE " . PREFIX . "sales.`order` 
            SET `is_validated`=1,
                `date_updated` = NOW(), 
                `is_cancelled`=0 
            WHERE `id`=" . (int) $iIdOrder . " 
                AND `id_user`=" . (int) $iIdUser . " 
                AND `is_validated`=0 
                AND `is_cancelled`=0 
            LIMIT 1";

        if (Sql::Set($sRq)) {
            $_SESSION['submit'] = 1;
            if (mysql_affected_rows() < 1) {
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Validation d'une commande par le vendeur
     *      
     * @param string $id_item
     * @param string $id_user
     * @return bool 
     */
    static public function validateOrderBySeller($iIdOrder, $iIdSeller) {

        $sRq = "UPDATE " . PREFIX . "sales.`order` 
            SET `is_validated`=1,
                `date_updated` = NOW(), 
                `is_cancelled`=0 
            WHERE `id`=" . (int) $iIdOrder . " 
                AND `id_seller`=" . (int) $iIdSeller . " 
                AND `is_validated`=0 
                AND `is_cancelled`=0 
            LIMIT 1";

        if (Sql::Set($sRq)) {
            $_SESSION['submit'] = 1;
            if (mysql_affected_rows() < 1) {
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Annule la commande de l'utilisateur
     *      
     * @param string $id_item
     * @param string $id_user
     * @return bool 
     */
    static public function cancelOrder($iIdOrder, $iIdUser) {

        $sRq = "UPDATE " . PREFIX . "sales.`order` 
            SET `is_validated`=0,
                `date_updated` = NOW(), 
                `is_cancelled`=1 
            WHERE `id`=" . (int) $iIdOrder . " 
                AND `id_user`=" . (int) $iIdUser . " 
                
            LIMIT 1";
//die ($sRq);
        if (Sql::Set($sRq)) {
            $_SESSION['submit'] = 1;
            if (mysql_affected_rows() < 1) {
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Annulation d'une commande par le vendeur
     *      
     * @param string $id_item
     * @param string $id_user
     * @return bool 
     */
    static public function cancelOrderBySeller($iIdOrder, $iIdSeller) {

        $sRq = "UPDATE " . PREFIX . "sales.`order` 
            SET `is_validated`=0,
                `date_updated`= NOW(), 
                `is_cancelled`=1 
            WHERE `id`=" . (int) $iIdOrder . " 
                AND `id_seller`=" . (int) $iIdSeller . "                  
            LIMIT 1";

        if (Sql::Set($sRq)) {
            $_SESSION['submit'] = 1;
            if (mysql_affected_rows() < 1) {
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     *
     * @return type 
     */
    static public function getLast20NewItems() {

        $sRq = "SELECT * 
                FROM " . PREFIX . "shop.`item` as i
                where i.id NOT IN(
                        SELECT o.id_item 
                        FROM " . PREFIX . "sales.`order` as o 
                        WHERE o.is_validated = 1	
                )
                Order by i.`date_created` DESC
                LIMIT 20;";

        $aItems = Sql::Get($sRq, 1, 'getLast20NewItems', 900);

        return $aItems;

        /* $oMongo = new \Mongo();$oTable = $oMongo->komunote;$oUsers = $oTable->user;$aCursor = $oUsers->find();//var_dump($aCursor);foreach( $aCursor as $aRow){echo $aRow['name'],' ', $aRow['age'], ' ans <br />';} */
    }

    /**
     * Supprime l'article de l'utilisateur
     *      
     * @param string $iId
     * @param string $iIdUser
     * @return bool 
     */
    static public function deleteItem($iId, $iIdUser) {

        $sRq = "DELETE FROM " . PREFIX . "shop.`item`             
            WHERE `id`=" . (int) $iId . " 
                AND `id_user`=" . (int) $iIdUser . "                 
            LIMIT 1";

        if (Sql::Set($sRq) &&
                unlink(PATH_WWW . 'images/shop/' . $iId . '/' . $iId . '.jpg')) {
            if (mysql_affected_rows() < 1) {
                return false;
            }
            $_SESSION['submit'] = 1;
            return true;
        }

        return false;
    }

    /**
     * Retourne une order en fonction de son Id
     * 
     * @param string $id
     * @return array 
     */
    static public function getOrderById($id) {
        $rq = "SELECT * FROM " . PREFIX . "sales.`order` 
            WHERE `id` =" . $id . " LIMIT 1;";

        $aItem = Sql::Get($rq, 1);
        
        return $aItem[0];
    }

}
