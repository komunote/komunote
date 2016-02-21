<?php

include(PATH_LIB.'/_shop/model.php');

/**
 *
 * @author david_chabrier
 */
class AdminMvcController extends MvcController {

  //private $sPath = "../komunote/pages/user/";

  public function __construct($mParams) {
    parent::__construct($mParams);
  }

  public function Error404() {
    return MvcView::Render();
  }

  public function Action() {

    return MvcView::Render();
  }

  public function Signin() {

    unset($_SESSION['submit']);

    return MvcView::Render();
  }

  public function SigninX() {

    $sEmail = Sql::GetSanitizeEmail('email');
    $sPseudo = Sql::GetSanitizeString('pseudo');
    $sP = Sql::GetSanitizeString('p');
     
    // vérification pseudo et email
    if ($sP === 'checkUser') {          

      if (AdminMvcModel::checkUser($sEmail)) {
        
        // erreur email déjà existant
        MvcView::set('result', 'email');
      }
      if (AdminMvcModel::checkPseudo($sPseudo)) {
        // erreur pseudo déjà existant
        MvcView::set('result', 'pseudo');
      }
    }

    return MvcView::Render();
  }

  public function SigninResult() {

    $sP = Sql::PostSanitizeString('p');

    MvcView::set('result', false);
    
    // insertion
    if ($sP === 'insert'){
        MvcView::set('result', AdminMvcModel::addNewUser());
            
        $message = '<html>'
            . '<head></head>'
            . '<body>'            
                . '<p><span style="font-size: 2em;">'
                . '<span style="color :#000099;">Komu</span>'
                . '<span style="color :#0099ff;">note</span>.'
                . '<span style="color :#000099;">eu</span>'
                . '</span>'
                . '</p><br />'
                . '<p>'._t("Félicitation").'&nbsp;'.Sql::PostSanitizeString('pseudo').',</p><br />'
                . '<p>'._t("Nous sommes heureux de vous compter parmi les membres de la communauté.").'</p>'
                . '<p>'._t("Vous pouvez dès à présent acheter et vendre gratuitement et en toute simplicité sur Komunote.eu.").'</p><br />'
                . '<p><a href="http://www.komunote.eu">www.komunote.eu</a></p>'
            . '</body>'
            . '</html>';
    
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: noreply@komunote.eu' . "\r\n" ;

        mail(Sql::PostSanitizeString('email'), _t('Inscription'), $message, $headers);
    }

    return MvcView::Render();
  }

  public function Account() {

    unset($_SESSION['submit']);

    if (!isset($_SESSION['user_id']))
      return false;

    if ($this->cacheEnable($_SESSION['user_id'], 10)) {
      $sHtml = $this->sCacheHtml;
    } else {

      if ($this->cacheEnable($_SESSION['user_id'], 1, IS_JSON)) {
        $aVars = $this->sCacheHtml;
      } else {
        MvcView::$aVars['account']['kredit']= rand(10, 1000);
        MvcView::$aVars['account']['user']=AdminMvcModel::getUserById($_SESSION['user_id']);
        MvcView::$aVars['account']['shopping_list']= AdminMvcModel::getShoppingListById($_SESSION['user_id']);
        MvcView::$aVars['account']['sales_list']  =AdminMvcModel::getSalesListById($_SESSION['user_id']);
        MvcView::$aVars['account']['items_list']  =AdminMvcModel::getItemsListById($_SESSION['user_id']);
        
        //$aVars['account']['rating_list']['user'] = AdminMvcModel::getRatingListByUserId($_SESSION['user_id']);
        //$aVars['account']['rating_list']['seller'] = AdminMvcModel::getRatingListBySellerId($_SESSION['user_id']);

        $this->cacheSet(MvcView::$aVars, IS_JSON);
      }


      $sHtml = MvcView::Render();
      $this->cacheSet($sHtml);
    }

    return $sHtml;
  }

  public function RateSeller() {

    unset($_SESSION['submit']);

    if (isset($_POST['item'])) {
      
      MvcView::$aVars = json_decode(html_entity_decode($_POST['item']), true);      
    }

    return MvcView::Render();
  }

  public function RateBuyer() {

    unset($_SESSION['submit']);

    if (isset($_POST['item'])) {
      MvcView::$aVars = json_decode(html_entity_decode($_POST['item']), true);
    }

    return MvcView::Render();
  }

  public function RateBuyerSuccess() {
    
    MvcView::set('result', 0);

    if (isset($_POST['order'])) {
      
      $aData = json_decode(html_entity_decode($_POST['order']), true);
      $sComment = xss_clean(Sql::PostSanitizeString('comment'));
      $iScore = Sql::PostSanitizeInt('score');
      
      MvcView::set('result', AdminMvcModel::updateRating(
              $aData['id'], $aData['id_user'], $aData['id_seller'], $sComment));
    }

    return MvcView::Render();
  }

  public function RateSellerSuccess() {
    
    MvcView::set('result', 0);

    if (isset($_POST['order'])) {
      
      $data = json_decode(html_entity_decode($_POST['order']), true);
      $sComment = xss_clean(Sql::PostSanitizeString('comment'));
      $iScore = Sql::PostSanitizeInt('score');
      MvcView::set('result', AdminMvcModel::addNewRating(
              $data['id'], $data['id_user'], $data['id_seller'], $sComment, $iScore));
    }

    return MvcView::Render();
  }

}