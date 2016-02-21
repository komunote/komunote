<?php

/**
 *
 */
class AdminMvcModel extends MvcModel {

  public function __construct() {
    parent::__construct();
  }

  static public function execute() {
    $rq = "SELECT * FROM " . PREFIX . "admin.user;";

    return Sql::Get($rq);
  }

  static public function getUsers() {
    $rq = "SELECT * FROM " . PREFIX . "admin.`user`;";

    return Sql::Get($rq);
  }

  static public function getUserById($id) {
    $rq = "SELECT * FROM " . PREFIX . "admin.`user` WHERE id=" . $id . " LIMIT 1;";

    $user = Sql::Get($rq, 1);

    if (isset($user[0])) {
      return $user[0];
    }

    return false;
  }

  static public function addNewUser() {
    // exit si on a déjà soumis le formulaire
    if (isset($_SESSION['submit']))
      return false;

    $sEmail = Sql::PostSanitizeEmail('email');
    $sPseudo = Sql::PostSanitizeString('pseudo');
    $sPass = Sql::PostSanitizeString('password');
    $sQuestion = Sql::PostSanitizeString('question');
    $sAnswer = strtolower(Sql::PostSanitizeString('answer'));

    $sRq = "INSERT INTO " . PREFIX . "admin.`user`
	  (`email`,`pseudo`,`password`,`question`,`answer`,`date`,`dateConnection`,`active`)
	  VALUES(		
            '" . $sEmail . "',
            '" . $sPseudo . "',
            '" . sha256_crypt($sPass) . "',
            '" . mysql_real_escape_string($sQuestion) . "',
            '" . mysql_real_escape_string($sAnswer) . "',
            NOW(),
            NOW(),
	  1);";

    if (Sql::Set($sRq)) {
      $_SESSION['submit'] = 1;
      return true;
    }

    return false;
  }

  static public function checkUser($sEmail) {
    $sRq = "SELECT 1 FROM " . PREFIX . "admin.`user` as u 
      WHERE u.email='".$sEmail."' LIMIT 1;";

    return Sql::Get($sRq);
  }

  static public function checkPseudo($sPseudo) {
    $sRq = "SELECT 1 FROM " . PREFIX . "admin.`user` as u 
            WHERE u.pseudo='".$sPseudo."' LIMIT 1;";

    return Sql::Get($sRq);
  }

  static public function checkUserExists($sEmail, $sPassword) {
    $sRq = "SELECT HIGH_PRIORITY * FROM " . PREFIX . "admin.`user` as u 
      WHERE u.email='".xss_clean($sEmail)."' AND 
        u.password='" . sha256_crypt($sPassword) . "' LIMIT 1;";

    return Sql::Get($sRq, 1);
  }

  /**
   * Retourne la liste des achats d'un utilisateur donné
   * @param type $id_user
   * @return type 
   */
  static public function getShoppingListById($iIdUser) {

    $sRq = "SELECT 0 as is_seller, 
              o.id_user as id_user, o.id_seller as id_seller, 
              u.pseudo, u.email as email_user, 
              o.id, o.date_created, o.date_updated, o.quantity, o.is_validated, o.is_cancelled, 
              i.id as id_item,i.unit_price, i.unit_shipping_price,i.description,i.keywords, 
              r.score, r.comment, r.answer, r.date_comment, r.date_answer 
            FROM komu7dh_komuadmin.`user` AS u
            INNER JOIN komu7dh_komusales.`order` AS o ON(o.id_seller=u.id)
            INNER JOIN komu7dh_komushop.`item` AS i ON(i.id=o.id_item)
            LEFT JOIN komu7dh_komurate.`rating` AS r ON(r.id_order = o.id)
            WHERE o.id IN(
              SELECT  id
                FROM komu7dh_komusales.`order`
                WHERE id_user=$iIdUser                 
            )
            ORDER BY date_created DESC";

    return Sql::Get($sRq, 1);
  }

  /**
   * Retourne la liste des ventes d'un utlisateur donné
   * 
   * @param type $id_seller
   * @return type 
   */
  static public function getSalesListById($iIdSeller) {

    $sRq = "SELECT 1 as is_seller, o.id_user as id_user, o.id_seller as id_seller, 
              u.pseudo, u.email as email_user, 
              o.id, o.date_created, o.date_updated, o.quantity, o.is_validated, o.is_cancelled, 
              i.id as id_item,i.unit_price, i.unit_shipping_price,i.description,i.keywords, 
              r.score, r.comment, r.answer, r.date_comment, r.date_answer 
            FROM komu7dh_komuadmin.`user` AS u
            INNER JOIN komu7dh_komusales.`order` AS o ON(o.id_user=u.id)
            INNER JOIN komu7dh_komushop.`item` AS i ON(i.id=o.id_item)
            LEFT JOIN komu7dh_komurate.`rating` AS r ON(r.id_order = o.id)
            WHERE o.id IN(
               SELECT  id
                  FROM komu7dh_komusales.`order`
                  WHERE id_seller=$iIdSeller                   
            )
            ORDER BY date_created DESC";

    return Sql::Get($sRq, 1);
  }
  
  /**
   * Retourne la liste des articles en cours de vente d'un utilisateur donné
   * 
   * @param type $id_seller
   * @return type 
   */
  static public function getItemsListById($iIdSeller) {

    $sRq = "SELECT i.id as id_item,i.unit_price, i.unit_shipping_price, i.description, i.id_user as id_user  
            FROM komu7dh_komushop.`item` AS i             
            WHERE i.id_user=$iIdSeller AND 
            i.id NOT IN(
              SELECT  id_item
              FROM komu7dh_komusales.`order`
              WHERE id_seller=$iIdSeller                   
            )
            ORDER BY date_created DESC;";

    return Sql::Get($sRq, 1);
  }

  static public function getOrdersByUserId($iIdUser) {
    $sRq = "SELECT HIGH_PRIORITY * 
            FROM " . PREFIX . "sales.`order`             
            WHERE id_user=$iIdUser 
            ORDER BY date_created DESC;";

    return Sql::Get($sRq, 1);
  }

  static public function getOrdersBySellerId($iIdSeller) {
    $sRq = "SELECT HIGH_PRIORITY * 
            FROM " . PREFIX . "sales.`order`             
            WHERE id_seller=$iIdSeller 
            ORDER BY date_created DESC;";

    return Sql::Get($sRq, 1);
  }

  /**
   * Retourne l
   * @param type $id_order
   * @return type 
   */
  static public function getRatingByOrderId($iIdOrder, $iIdUser, $iIdSeller) {
    $sRq = "SELECT *             
            FROM " . PREFIX . "rate.`rating`             
            WHERE id_order=$iIdOrder AND 
                id_user='$id_user' AND 
                id_seller='$id_seller'
            ORDER BY date_comment DESC LIMIT 1;";

    $rating = Sql::Get($sRq, 1);

    if (isset($rating[0])) {
      return $rating[0];
    }

    return null;
  }

  /**
   * Insère une nouvelle évaluation
   * 
   * @param string $id_order
   * @param string $id_user
   * @param string $id_seller
   * @param string $comment
   * @param int $score
   * @return int 
   */
  static public function addNewRating($iIdOrder, $iIdUser, $iIdSeller, $sComment, $iScore) {

    // exit si on a déjà soumis le formulaire
    if (isset($_SESSION['submit']))
      return false;

    if (self::checkIfRatingExists((int)$iIdOrder, (int)$iIdUser, (int)$iIdSeller)) {
      return 2;
    } else {

      $sRq = "INSERT INTO " . PREFIX . "rate.`rating`
	  (`id_order`,`id_user`,`id_seller`,`comment`,`score`,`date_comment`)
	  VALUES(
		" . $iIdOrder . ",
		" . $iIdUser . ",
                " . $iIdSeller . ",
		'" . mysql_real_escape_string($sComment) . "',
		" . $iScore . ",		
		NOW());";

      if (Sql::Set($sRq)) {
        $_SESSION['submit'] = 1;
        return 1;
      }
    }

    return 0;
  }

  /**
   * Vérifie si l'évaluation existe déjà
   * 
   * @param string $id_order
   * @param string $id_user
   * @param string $id_seller
   * @return bool 
   */
  static public function checkIfRatingExists($iIdOrder, $iIdUser, $iIdSeller) {
    $rq = "SELECT *             
            FROM " . PREFIX . "rate.`rating`             
            WHERE id_order=".(int)$iIdOrder." AND 
                id_user=".(int)$iIdUser." AND 
                id_seller=".(int)$iIdSeller." ;";

    return (count(Sql::Get($rq, 1)) > 0);
  }

  static public function updateRating($iIdOrder, $iIdUser, $iIdSeller, $sComment) {

    // exit si on a déjà soumis le formulaire
    if (isset($_SESSION['submit']))
      return false;

    if (self::checkIfRatingExists((int)$iIdOrder, (int)$iIdUser, (int)$iIdSeller)) {

      $sRq = "UPDATE " . PREFIX . "rate.`rating` 
                SET `answer` = '" . mysql_real_escape_string($sComment) . "', 
                    `date_answer`=NOW() 
                WHERE `id_order`='" . (int)$iIdOrder . "' AND 
                    `id_user`='" . (int)$iIdUser . "' AND 
                    `id_seller`='" . (int)$iIdSeller . "';";

      if (Sql::Set($sRq)) {
        $_SESSION['submit'] = 1;
        return true;
      }
    }
    return false;
  }  
}