<?php

header('Cache-Control: public, max-age=3600, must-revalidate');
header('Expires: ' . gmdate("D, d M Y H:i:", $_SERVER['REQUEST_TIME'] + 3600));

$path = '../../komunote_lib/';
define('CRON', false);
include($path . "class/MvcClasses.class.php");



$bench = new Bench();
$bench->start();

$oMongo = new \Mongo();
$oTable = $oMongo->komunote;

$oUsers = $oTable->user;

$aCursor = $oUsers->find();


//var_dump($aCursor);
$bench->stop();
foreach ($aCursor as $aRow) {
  echo $aRow['name'], ' ', $aRow['age'], ' ans <br />';
}


echo "<br /> Temps d'exécution :", ($bench->getResultInSeconds());


set_time_limit(90);
//error_reporting(0);
//$start = microtime(true);
$bench = new Bench();
$bench->start();

////////////////////////////////////////////////////////////////////////////////
// Table User / admin
////////////////////////////////////////////////////////////////////////////////
//for($j=0; $j<10; $j++) {
//    $rq = "INSERT INTO " . PREFIX . "admin.`user` (`email`,`pseudo`,`password`,`question`,`answer`,`date`,`dateConnection`,`active`) VALUES ";
//
//    for ($i = 0; $i < 1000; ++$i) {
//
//        if($i>0)
//            $rq .=',';
//
//        $rq .= "(                    
//                    '" . Sql::getUniqueId() . "',
//                      '" . Sql::getUniqueId() . "',
//                    '" . Sql::getUniqueId() . "',
//                    '" . Sql::getUniqueId() . "',
//                    '" . Sql::getUniqueId() . "',
//                    NOW(),
//                    NOW(),
//              0)";          
//        //die($rq);
//    }
//
//    Sql::Set($rq);    
//}
////////////////////////////////////////////////////////////////////////////////
// Table item / shop
////////////////////////////////////////////////////////////////////////////////
//for($j=0; $j<10; $j++) {
//    $rq = "INSERT INTO " . PREFIX . "shop.`item`
//	  (`id_user`,`keywords`,`description`,`unit_price`,`unit_shipping_price`,`shipping_type`,`state`, `date_created`, `last_modified`) VALUES ";
//
//    for ($i = 0; $i < 1000; ++$i) {
//
//        if($i>0)
//            $rq .=',';
//
//        $aKeywords = array('ps3 uncharted 3','xbox 360 Gears of war','wii Mario Kart', 'PSP Final Fantasy Advent Children');
//        $aDescriptions = array("un grand jeu pour la plateforme de Sony","Meilleur jeux FPS de l'année",
//            "Très bon jeu de voiture", "Un jeu d'aventure à vous couper le souffle");
//        
//        $rq .= "('" .                    
//                    rand() . "','" .
//                    mysql_real_escape_string($aKeywords[array_rand($aKeywords,1)]) . "','" .
//                    mysql_real_escape_string($aDescriptions[array_rand($aDescriptions,1)]) . "'," .
//                    rand(10,1000) . "," .
//                    rand(0,100) . "," .
//                    0 . ",".
//                    0 . ",
//	  NOW(),          
//	  NOW())";       
//                
//    }
//    
//    Sql::Set($rq);    
//}


function getWordWeight($sWord) {

  $sWord = strtoupper($sWord);
  $iSize = strlen($sWord);
  $iWeight = 0;

  for ($i = 0; $i < $iSize; $i++) {
    $iWeight +=ord($sWord[$i]);
  }
  return $iWeight;
}

function getSentenceWeight($sSentence) {

  $aWords = explode(' ', strtoupper($sSentence));

  $iWeight = 0;
  if (count($aWords) > 0) {
    foreach ($aWords as $sWord) {
      $iSize = strlen($sWord);

      for ($i = 0; $i < $iSize; $i++) {
        $iWeight +=ord($sWord[$i]);
      }
    }
  }

  return $iWeight;
}

function findSimilarResults($sTarget) {
  $aEntries = array(
      "PS3 Disgaea 3",
      "PS3 Disgaea",
      "PS3",
      "Disgaea",
      "Uncharted drake's",
      "PS3 Final Fantasy 13",
      "PS3 Final Fantasy",
      "Final Fantasy",
      "Drake's fortune",
      "Final Fantasy 13",
      "PS3 Final Fantasy XII",
      "Final Fantasy XIII",
      "Uncharted drake's fortune");

  $aWeight = array();
  foreach ($aEntries as $iKey => $sEntry) {
    $sEntry = rtrim($sEntry);
    $aWords = explode(' ', $sEntry);

    $iCount = count($aWords);
    $sCombo = '';
    $iWc = 0;
    $iSc = getSentenceWeight($sEntry);

    for ($i = 0; $i < $iCount; $i++) {
      $sCombo.= $aWords[$i] . ' ';
      $iWc += getWordWeight($aWords[$i]);
      $aWeight[$iWc]['Items'][$iSc][$iKey] = '{k:"' . rtrim($sCombo) . '", w:' . $iWc . ', s:' . $iSc . ', e:' . $iKey . '}';
    }

    $sCombo = '';
    $iWc = 0;
    for ($i = $iCount - 1; $i > -1; $i--) {
      $sCombo = $aWords[$i] . ' ' . $sCombo;
      $iWc += getWordWeight($aWords[$i]);
      $aWeight[$iWc]['Items'][$iSc][$iKey] = '{k:"' . rtrim($sCombo) . '", w:' . $iWc . ', s:' . $iSc . ', e:' . $iKey . '}';
    }
    $aWeight[$iSc]['Entry'] = $sEntry;
  }

  $iTargetWeight = getSentenceWeight($sTarget);

  // résultat exact
  $aExactResult = array();
  if (isset($aWeight[$iTargetWeight])) {
    // si il existe exactement un résultat
    $aExactResult[] = $aWeight[$iTargetWeight];
  }

  do_dump($aWeight);
  echo '<p>Résultats pour : Fantasy XIII (poids : ' . $iTargetWeight . ')</p>';

  $aNearResults = array();
  // sinon proposer une liste de résultats se rapprochant du terme recherché
  foreach ($aEntries as $sEntry) {
    $i10 = getSentenceWeight($sEntry);
    if ($i10 >= $iTargetWeight * 0.9 && $i10 <= $iTargetWeight * 1.10) {
      $aNearResults['10%'][] = array($i10, $aWeight[$i10]);
    }

    $i25 = getSentenceWeight($sEntry);
    if ($i25 >= $iTargetWeight * 0.8 && $i10 <= $iTargetWeight * 1.20) {
      $aNearResults['20%'][] = array($i25, $aWeight[$i25]);
    }
  }



  return array('exactResult' => $aExactResult, 'nearResults' => $aNearResults);
}

//echo getWordWeight('ABC'), '<br /><br />';
//echo getWordWeight('メール'), '<br />';
//echo '<h3>Recherche : </h3><br />';
//echo getWordWeight('P3S'), '<br /><br />';
//echo '<pre>';
//echo print_r(findSimilarResults("Fantasy XIII"));
//echo '</pre>';
//echo '<h3>Recherche : </h3><br />';
//do_dump(findSimilarResults("Fantasy XIII"));


$bench->stop();
echo "<br /> Temps d'exécution :", ($bench->getResultInSeconds());

//phpinfo();