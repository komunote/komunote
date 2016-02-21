<?php

include (PATH_LIB.'/_admin/model.php');

/**
 *
 * @author david_chabrier
 */
class ShopMvcController extends MvcController {

  //private $path = "../komunote/pages/shop/";

  public function __construct($params) {

    parent::__construct($params);
  }

  public function Action() {

    MvcView::set('users', AdminMvcModel::getUsers());

    return MvcView::render();
  }

  public function Sell() {
    unset($_SESSION['submit']);

    return MvcView::render();
  }

  public function Search() {

    unset($_SESSION['submit']);
    
    MvcView::set('items', ShopMvcModel::getLast20NewItems());

    return MvcView::render();
  }

  /**
   * Insère un nouvel article dans le système
   * @return type 
   */
  public function SellSuccess() {

    MvcView::set('result', ShopMvcModel::addNewItem());
    
    $message = '<html>'
        . '<head></head>'
        . '<body>'            
            . '<p><span style="font-size: 2em;">'
            . '<span style="color :#000099;">Komu</span>'
            . '<span style="color :#0099ff;">note</span>.'
            . '<span style="color :#000099;">eu</span>'
            . '</span>'
            . '</p><br />'
            . '<p>'._t("Félicitation").'&nbsp;'.$_SESSION['user_pseudo'].',</p><br />'
            . '<p>'._t("Votre article a été mis en ligne avec succès et immédiatement accessible.").'</p><br />'                
            . '<p><a href="http://www.komunote.eu">www.komunote.eu</a></p>'
        . '</body>'
        . '</html>';

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= 'From: noreply@komunote.eu' . "\r\n" ;

    mail($_SESSION['user_email'], _t("Creation d'un article"), $message, $headers);

    return MvcView::render();
  }

  /**
   * Page produit
   * @return type 
   */
  public function Show() {

    unset($_SESSION['submit']);

    $sItemId = strtolower(Sql::GetSanitizeString('itemId'));

    if ($this->cacheEnable($sItemId)) {

      $html = $this->sCacheHtml;
    } else {

      MvcView::set('item', ShopMvcModel::getItemById($sItemId));
      MvcView::set('user', AdminMvcModel::getUserById(MvcView::$aVars['item']['id_user']));

      $html = MvcView::render();
      $this->cacheSet($html);
    }

    return $html;
  }

  /**
   *
   * @return type 
   */
  public function SearchItem() {

    $sKeywords = strtolower(Sql::GetSanitizeString('keywords'));
    $iOffset = Sql::GetSanitizeInt('offset');

    $filters = self::getFilters(Sql::GetSanitizeString('filters'));
    $sFilters = Sql::GetSanitizeString('filters');

    if ($this->cacheEnable($sKeywords . '_' . $iOffset . '_' . $sFilters, 10)) {

      $html = $this->sCacheHtml;
    } else {

      if ($this->cacheEnable($sKeywords . '_' . $iOffset . '_' . $sFilters, 1, IS_JSON)) {

        MvcView::$aVars = $this->sCacheHtml;
      } else {

        $aKeywords = explode(' ', $sKeywords);
        //$keywordsCount = count($aKeywords);

        $aResultMatchingFiles = array();
        $records = ShopMvcModel::getItemsCount($aKeywords, $filters);
        $items = ShopMvcModel::getItems($aKeywords, $iOffset, $filters);

        if ($items !== null) {

          foreach ($items as $item) {

            $shop = ShopMvcModel::getItemById($item['id']);

            if ($shop !== null) {
              $aResultMatchingFiles[] = $shop;
            }
          }
        }

        MvcView::set('filters', $sFilters);
        MvcView::set('offset', $iOffset);

        if (count($aResultMatchingFiles) > 0) {

          MvcView::set('records', $records);
          MvcView::set('matchingFiles', $aResultMatchingFiles);

          $this->cacheSet(MvcView::$aVars, IS_JSON);
        }
      }

      MvcView::set('keywords', $sKeywords);

      $html = MvcView::render();
      $this->cacheSet($html);
    }

    return $html;
  }

  /**
   * Retourne dans un tableau les filtres d'une URL en
   * paramètres GET
   * @param string $_filters
   * @return array 
   */
  static public function getFilters($_filters) {

    $aItems = explode('__', $_filters);

    $aFilters = array();
    foreach ($aItems as $aItem) {
      $ex = explode('_', $aItem);
      if (count($ex) > 1) {
        $aFilters[$ex[0]] = $ex[1];
      }
    }

    return $aFilters;
  }

  /**
   * Finalisation de l'achat
   * Si la commande n'existe pas déjà, elle sera créée et affectée à l'acheteur
   * 
   * @return string 
   */
  public function PaypalForm() {

    if (isset($_POST['item'])) {
      $aData = json_decode(html_entity_decode($_POST['item']), true);

      $aData['order']['id'] = ShopMvcModel::checkAlreadOrderedBySomeUserAndNotValidated(
                      $_SESSION['user_id'], $aData['item']['id']);

      // si l'item n'est pas déjà en cours de commande ou annulé, 
      // ou si on n'a pas déjà soumis le formulaire
      if (!$aData['order']['id'] && !isset($_SESSION['submit'])) {

        // on récupère le numéro de la commande s'il existe
        $aData['order']['id'] = ShopMvcModel::checkAlreadOrderedByUserAndNotValidated(
                        $_SESSION['user_id'], $aData['item']['id']);

        // sinon on crée la commande
        if (!$aData['order']['id']) {

            $aData['order']['id'] = ShopMvcModel::addNewOrder(
                            $aData['user']['id'], $_SESSION['user_id'], $aData['item']['id'], $aData['item']['unit_price'], $aData['item']['unit_shipping_price']);
            $_SESSION['submit'] = 1;
          
            // envoi de mail à l'acheteur
            $message = '<html>'
                . '<head></head>'
                . '<body>'            
                    . '<p><span style="font-size: 2em;">'
                    . '<span style="color :#000099;">Komu</span>'
                    . '<span style="color :#0099ff;">note</span>.'
                    . '<span style="color :#000099;">eu</span>'
                    . '</span>'
                    . '</p><br />'
                    . '<p>'._t("Félicitation").'&nbsp;'.$_SESSION['user_pseudo'].',</p><br />'
                    . '<p>'._t("Votre achat est confirmé.").'</p><br />'
                    . '<p>'._t("Vous devez à présent effectuer le paiement sur Komunote.eu pour finaliser la transaction.").'</p><br /><br />'
                    . '<p><a href="http://www.komunote.eu">www.komunote.eu</a></p>'
                . '</body>'
                . '</html>';

            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'From: noreply@komunote.eu' . "\r\n" ;

            mail($_SESSION['user_email'], _t('Confirmation de votre achat'), $message, $headers);                        
        } else {

          $aData['order']['id'] = 1;
        }
      } else {

        $aData['order']['id'] = 2;
      }
    }

    MvcView::$aVars = $aData;

    return MvcView::render();
  }

  /**
   * Paypal valide définitivement en base le paiement.
   * On estime à ce niveau que le paiement s'est effectué correctement.
   * 
   * @return string 
   */
  public function PaymentSuccess() {

    // exit si on a déjà soumis le formulaire
    if (isset($_SESSION['submit'])) {

      MvcView::set('result', false);
    } else {

      $sKey = Sql::GetSanitizeString('key');
      $sOrderId = Sql::GetSanitizeString('id');

      if ($sKey === sha256_crypt($sOrderId . $_SESSION['user_id'])) {

        // mise à jour de la table achat                
        MvcView::set('result', ShopMvcModel::validateOrder($sOrderId, $_SESSION['user_id']));
        
        $aOrder = ShopMvcModel::getOrderById($sOrderId);
        $aSeller = AdminMvcModel::getUserById($aOrder['id_seller']);
        
        // envoi de mail au vendeur
        $message = '<html>'
            . '<head></head>'
            . '<body>'            
                . '<p><span style="font-size: 2em;">'
                . '<span style="color :#000099;">Komu</span>'
                . '<span style="color :#0099ff;">note</span>.'
                . '<span style="color :#000099;">eu</span>'
                . '</span>'
                . '</p><br />'
                . '<p>'._t("Félicitation").'&nbsp;'.$aSeller['pseudo'].',</p><br />'
                . '<p>'.$_SESSION['user_pseudo'].',&nbsp;'._t("membre de Komunote.eu vient de confirmer le paiement de votre article.").'</p><br />'
                . '<p>'._t("Vous pouvez dès à présent procéder à son expédition.").'</p><br /><br />'
                . '<p><a href="http://www.komunote.eu">www.komunote.eu</a></p>'
            . '</body>'
            . '</html>';

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: noreply@komunote.eu' . "\r\n" ;               

        mail($aSeller['email'], _t("Commande a expedier"), $message, $headers);
        
        // envoi de mail à l'acheteur
        $message = '<html>'
            . '<head></head>'
            . '<body>'            
                . '<p><span style="font-size: 2em;">'
                . '<span style="color :#000099;">Komu</span>'
                . '<span style="color :#0099ff;">note</span>.'
                . '<span style="color :#000099;">eu</span>'
                . '</span>'
                . '</p><br />'
                . '<p>'._t("Félicitation").'&nbsp;'.$_SESSION['user_pseudo'].',</p><br />'
                . '<p>'._t("Votre achat est confirmé.").'</p><br />'
                . '<p>'._t("Votre paiement a été confirmé par Paypal.")."&nbps;".$aSeller['pseudo']."&nbsp;"._t("va procéder à l'expédition de votre article.").'</p><br /><br />'
                . '<p><a href="http://www.komunote.eu">www.komunote.eu</a></p>'
            . '</body>'
            . '</html>';

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: noreply@komunote.eu' . "\r\n" ;

        mail($_SESSION['user_email'], _t('Confirmation de votre paiement'), $message, $headers);   
            
      } else {

        MvcView::set('result', false);
      }

      $_SESSION['submit'] = 1;
    }

    return MvcView::render();
  }

  /**
   * Paiement validé par le vendeur. Ce dernier confirme manuellement qu'il a bien reçule paiement.
   * On estime à ce niveau que le paiement a été réalisé mais qu'il y a eu un problème mineur lors de la validation du paiement.
   * On estime que le vendeur a constaté le paiement et on l'autorise à le valider manuellement.
   * 
   * @return string 
   */
  public function PaymentSuccessSeller() {

    // exit si on a déjà soumis le formulaire
    if (isset($_SESSION['submit'])) {

      MvcView::set('result', false);
    } else {

      //$sKey = Sql::GetSanitizeString('key');
      $sOrderId = Sql::GetSanitizeString('id');

      //if ($sKey === sha256_crypt($sOrderId . $_SESSION['user_id'])) {
      if (isset($_SESSION['user_id'])) {

        // mise à jour de la table achat                
        MvcView::set('result', ShopMvcModel::validateOrderBySeller($sOrderId, $_SESSION['user_id']));
        
        $aOrder = ShopMvcModel::getOrderById($sOrderId);
        $aSeller = AdminMvcModel::getUserById($aOrder['id_seller']);
        $aBuyer = AdminMvcModel::getUserById($aOrder['id_user']);
        
        // envoi de mail au vendeur
        $message = '<html>'
            . '<head></head>'
            . '<body>'            
                . '<p><span style="font-size: 2em;">'
                . '<span style="color :#000099;">Komu</span>'
                . '<span style="color :#0099ff;">note</span>.'
                . '<span style="color :#000099;">eu</span>'
                . '</span>'
                . '</p><br />'
                . '<p>'._t("Félicitation").'&nbsp;'.$aSeller['pseudo'].',</p><br />'
                . '<p>'._t("Vous venez de confirmer manuellement le paiement de votre article par l'acheteur ").$aBuyer['pseudo'].'.</p><br />'
                . '<p>'._t("Vous pouvez dès à présent procéder à son expédition.").'</p><br /><br />'
                . '<p><a href="http://www.komunote.eu">www.komunote.eu</a></p>'
            . '</body>'
            . '</html>';

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: noreply@komunote.eu' . "\r\n" ;               

        mail($aSeller['email'], _t("Commande à expédier"), $message, $headers);
        
        // envoi de mail à l'acheteur
        $message = '<html>'
            . '<head></head>'
            . '<body>'            
                . '<p><span style="font-size: 2em;">'
                . '<span style="color :#000099;">Komu</span>'
                . '<span style="color :#0099ff;">note</span>.'
                . '<span style="color :#000099;">eu</span>'
                . '</span>'
                . '</p><br />'
                . '<p>'._t("Félicitation").'&nbsp;'.$_SESSION['user_pseudo'].',</p><br /><br />'
                . '<p>'._t("Votre achat est confirmé.").'</p><br />'
                . '<p>'._t("Votre paiement a été confirmé par le vendeur qui va procéder à l'expédition de votre article.").'</p><br /><br />'
                . '<p><a href="http://www.komunote.eu">www.komunote.eu</a></p>'
            . '</body>'
            . '</html>';

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: noreply@komunote.eu' . "\r\n" ;

        mail($aBuyer['email'], _t('Confirmation de votre paiement'), $message, $headers);       
  } else {

        MvcView::set('result', false);
      }

      $_SESSION['submit'] = 1;
    }

    return MvcView::render();
  }

  /**
   * Annule définitivement en base le paiement.
   * Le paiement n'ayant pas été effectué, il peut être annulé par l'acheteur.
   * 
   * @return string 
   */
  public function PaymentCancelled() {

    // exit si on a déjà soumis le formulaire
    if (isset($_SESSION['submit'])) {

      MvcView::set('result', false);
    } else {

      $iOrderId = Sql::GetSanitizeInt('idOrder');
      $iUserId = Sql::GetSanitizeInt('idUser');
//die("test :".$iUserId);
      if (isset($_SESSION['user_id']) && ($iOrderId >0 && $iUserId != $_SESSION['user_id'])) {

        // mise à jour de la table achat                
        MvcView::set('result', ShopMvcModel::cancelOrder($iOrderId, $_SESSION['user_id']));
      } else {

        MvcView::set('result', false);
      }

      $_SESSION['submit'] = 1;
    }

    return MvcView::render();
  }
  /**
   * Annule définitivement en base le paiement.
   * Le paiement n'ayant pas été effectué, il peut être annulé par l'acheteur.
   * 
   * @return string 
   */
  public function PaymentPaypalCancelled() {

    // exit si on a déjà soumis le formulaire
    if (isset($_SESSION['submit'])) {
//unset($_SESSION['submit']);
      MvcView::set('result', false);
    } else {

      $sKey = Sql::GetSanitizeString('key');
      $iOrderId = Sql::GetSanitizeString('id');

      if (isset($_SESSION['user_id']) && ($sKey === sha256_crypt($iOrderId . $_SESSION['user_id']))) {
 
        // mise à jour de la table achat                
        MvcView::set('result', ShopMvcModel::cancelOrder($iOrderId, $_SESSION['user_id']));
      } else {

        MvcView::set('result', false);
      }
//die($sOrderId.'/'.$sKey.'/'.$_SESSION['user_id'].'/'.sha256_crypt($sOrderId . $_SESSION['user_id']));      
      $_SESSION['submit'] = 1;
    }

    return MvcView::render();
  }
  

  /**
   * Le paiement est annulé par le vendeur pour une raison quelconque (défaut de paiement, défaut de stock etc.)
   * 
   * @return string 
   */
  public function PaymentCancelledSeller() {

    // exit si on a déjà soumis le formulaire
    if (isset($_SESSION['submit'])) {

      MvcView::set('result', false);
    } else {
      
      //$iUserId = Sql::GetSanitizeString('idUser');
      $iOrderId = Sql::GetSanitizeString('idOrder');

      if (isset($_SESSION['user_id'])) {      

        // mise à jour de la table achat                
        MvcView::set('result', ShopMvcModel::cancelOrderBySeller($iOrderId, $_SESSION['user_id']));
      } else {

        MvcView::set('result', false);
      }

      $_SESSION['submit'] = 1;
    }

    return MvcView::render();
  }
  
  /**
   * Supprime définitivement en base l'article.   
   * 
   * @return string 
   */
  public function SellDelete() {

    // exit si on a déjà soumis le formulaire
    if (isset($_SESSION['submit'])) {

      MvcView::set('result', false);
    } else {

      $iId = Sql::GetSanitizeInt('id');
      $iUser = Sql::GetSanitizeInt('key');

      if (isset($_SESSION['user_id']) && ($iId >0 && $iUser == $_SESSION['user_id'])) {
 
        // mise à jour de la table achat                
        MvcView::set('result', ShopMvcModel::deleteItem($iId, $_SESSION['user_id']));
      } else {

        MvcView::set('result', false);
      }

      $_SESSION['submit'] = 1;
    }

    return MvcView::render();
  }

}