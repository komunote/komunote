<?php

include(PATH_LIB.'/_admin/model.php');

/**
 *
 * @author david_chabrier
 */
class IndexMvcController extends MvcController {

  public function __construct($mParams) {
    parent::__construct($mParams);
  }

  public function LogIn() {
    if (isset($_POST['cx'])) {
      $sEmail = Sql::PostSanitizeEmail('_email');
      $sPassword = Sql::PostSanitizeString('_password');

      $user = AdminMvcModel::checkUserExists($sEmail, $sPassword);
      if (isset($user[0])) {

        session_regenerate_id();

        $_SESSION['logged'] = 1;
        $_SESSION['user_pseudo'] = $user[0]['pseudo'];
        $_SESSION['user_id'] = $user[0]['id'];
        $_SESSION['user_email'] = $user[0]['email'];
        $_SESSION['DB_USER'] = PREFIX_USER . "user";
        $_SESSION['date_connection'] = $_SERVER['REQUEST_TIME'];

        if (isset($_GET['mobile'])) {

          MvcView::set('mobile', json_encode($_SESSION));
          return MvcView::render();
        }
      }

      if (isset($_GET['mobile'])) {

        MvcView::set('mobile', '');
        json_encode($_SESSION);
        return MvcView::render();
      }
    }

//        $vars['session']=json_encode($_SESSION);
//        return MvcView::render($vars);
    return MvcView::render();
  }

  public function LogOut() {
    // déconnexion
    unset($_SESSION['logged']);
    unset($_SESSION['user_pseudo']);
    unset($_SESSION['user_id']);
    unset($_SESSION['user_email']);
    unset($_SESSION['date_connection']);

    $_SESSION['DB_USER'] = PREFIX_USER . "guest";

    return MvcView::render();
  }

  public function Action() {
    
    if ($this->cacheEnable(null, 1)) {
      
      $sHtml = $this->sCacheHtml;
    } else {
      
      //MvcView::setFilename('index', 'Concept');
      $sHtml = MvcView::render();
      $this->cacheSet($sHtml);
    }
    return $sHtml;
  }

  public function ActionX() {
    
    return MvcView::render();
  }

  public function About() {
    
    if ($this->cacheEnable()) {
      
      $sHtml = $this->sCacheHtml;
    } else {
      
      $sHtml = MvcView::render();
      $this->cacheSet($sHtml);
    }
    return $sHtml;
  }

  public function Concept() {
    
    if ($this->cacheEnable()) {
      
      $sHtml = $this->sCacheHtml;
    } else {
      
      $sHtml = MvcView::render();
      $this->cacheSet($sHtml);
    }
        
    $message = '<html>'
            . '<head></head>'
            . '<body>'            
                . '<p><span style="font-size: 2em;">'
                . '<span style="color :#000099;">Komu</span>'
                . '<span style="color :#0099ff;">note</span>.'
                . '<span style="color :#000099;">eu</span>'
                . '</span>'
                . '</p><br />'
                . '<p>'._t("Félicitation").'&nbsp;'.@$_SESSION['user_pseudo'].',</p><br />'
                . '<p>'._t("Nous sommes heureux de vous compter parmi les membres de la communauté.").'</p>'
                . '<p>'._t("Vous pouvez dès à présent acheter et vendre gratuitement et en toute simplicité sur Komunote.eu.").'</p><br />'
                . '<p><a href="http://www.komunote.eu">www.komunote.eu</a></p>'
            . '</body>'
            . '</html>';
    
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= 'From: noreply@komunote.eu' . "\r\n" ;

    mail('david_chabrier@hotmail.com', _t('Inscription'), $message, $headers);
    
    return $sHtml;
  }

  public function Expired() {


    return MvcView::render();
  }

}

?>