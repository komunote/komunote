<!DOCTYPE html>
<html lang="fr">
  <head>    
    <meta charset="utf-8" />
    <meta name="google-site-verification" content="6V5umEbe-_o9alZDmj0jqqM-YlpgL8CZDquuz4RXcPQ" />
    <meta name="keywords" content="komunote, community, paypal, sell, buy, credit" />
    <meta name="description" content="Buy, sell, announce, reservation for a discount price. The cost is not in percentage but only in credits. Pay everything with Paypal ! Sell everywhere to anyone in 2 minutes !" />
    <base href="http://<?php echo DB_HOST;?>/komunote/" >
    <link rel="stylesheet" href="style/main.css" type="text/css" />		
    <!--<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
    <link href="style/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
    <!--<link href="style/jquery-1.8.11.ui.css" rel="stylesheet" type="text/css"/>-->
    
    <!--<script src="https://www.google.com/jsapi?key=ABQIAAAANfnTios5gQI-s1wde7XvWxTw_HdSptI3TKSE7OKwpyYML5Y_DxQ81mb9vLrqJLvYLLhHGJXK3r1O4A" type="text/javascript"></script>-->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js" type="text/javascript"></script>-->
    <script src="script/jquery-1.5.1.min.js" type="text/javascript"></script>	
    <script src="script/jquery-1.8.11.ui.min.js" type="text/javascript"></script>
    
    <!--<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js" type="text/javascript"></script>-->	
    <title>Komunote</title>	
  </head>
  <?php
  $c = isset($_GET['c']) ? $_GET['c'] : (isset($_POST['c']) ? $_POST['c'] : '');
  $a = isset($_GET['a']) ? $_GET['a'] : (isset($_POST['a']) ? $_POST['a'] : '');
  ?>
  <body>
    <div id="headerDiv">
      <div id="logoDiv">
        <a href="./Accueil">          
          <span class="komunoteTitle"><span class="bleuF">Komu</span><span class="bleuC">note</span></span>
        </a>
      </div><br />
      <div id="menuDiv">
        <ul id="menuUl">          
          <li>
            <a href="./Search" title="Acheter" class="menu<?php echo $c == 'shop' && $a =='Search' ? '' : 'Not'; ?>Selected">
              Buy
            </a>		  
          </li> 
          <?php if (isset($_SESSION['logged'])) { ?>
          <li>
            <a href="./Sell" title="Vendre" class="menu<?php echo $c == 'shop' && $a =='Sell' ? '' : 'Not'; ?>Selected">
              Sell
            </a>		  
          </li> 
          <?php } ?>          
          <?php if (!isset($_SESSION['logged'])) { ?>
            <li>
              <a href="./Sign-in" class="menu<?php echo $c == 'admin' && $a == 'Signin' ? '' : 'Not'; ?>Selected">
                Signin
              </a>
            </li>
            <li>
              <a href="./Concept" title="Comment ça fonctionne ?" class="menu<?php echo $c == 'concept' ? '' : 'Not'; ?>Selected">
                How does it work ?
              </a>		  
            </li> 
          <?php } ?>          
          <?php if (isset($_SESSION['logged'])) { ?>
            <li>
              <a href="./My-account" class="menu<?php echo $c == 'admin' && $a == 'Account' ? '' : 'Not'; ?>Selected" title="Mon compte">My account</a>
            </li>
          <?php } ?>
        </ul>
      </div>

      <div id="loginMenuDiv">        
        <span id="errorLogin" style="color: #ff0000;"></span>
        <?php if (isset($_SESSION['logged']) && $_SESSION['logged']) { ?>
          <span id="linkLogout" class="menuNotSelected">Log out</span>
        <?php } else { ?>
          <span id="linkLogin" class="menuNotSelected">Log in</span>
        <?php } ?>

        <div id="popupLogin" title="Connexion">
          <form id="formLogin" name="formLogin" action="./Home" method="post" title="Recherche">
            <p>E-mail <br />
              <input type="text" size="30" name="_email" id="_email"/></p>
            <p>Password <br />
              <input type="password" size="30" name="_password" id="_password"/></p>
            <input type="hidden" value="1" name="cx"/>
          </form>
        </div>

        <div id="popupLogout" title="Déconnexion">
          <form id="formLogout" name="formLogout" action="./Home" method="post" title="Recherche">
            <p>Cliquez OK pour vous déconnecter de <strong><span class="bleuF">Komu</span><span class="bleuC">note</span></strong></p>
            <input type="hidden" value="1" name="dcx"/>
          </form>
        </div>

        <a href="./Francais"><img src="images/fr-flag.gif" width="16px" height="12px" alt="Français" title="Francais" /></a>
      </div>
    </div>

    <div id="bodyDiv">
      <div id="bodyCenterDiv">
        <?php echo $var_result; ?>
        <!-- Placez cette balise à l'endroit où vous souhaitez que le bouton +1 s'affiche -->
        <g:plusone></g:plusone>
      </div>
    </div>
    
    <script src="script/masterpageFR.js" type="text/javascript"></script>
    
    <!--<div id="pubRightSide" style="display: inline-block;float: left;width: 128px;background:#ffffff; border: 1px solid #abcafe; -moz-border-radius: 5px; -webkit-border-radius: 5px;">
      <p>Publicité</p>
    </div>-->

    <div id="footerDiv" style="margin-top: 10px;">
      <p align="center">
        <strong><span class="bleuF">Komu</span><span class="bleuC">note</span></strong> - 
        <span><?php t("Infos légales")?></span>
        <!--<br /><br />
                <a href="http://validator.w3.org/check?uri=referer" target="_blank">
          <img src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0 Transitional" height="31" width="88" />
                </a>-->
      </p>
    </div>
    <!-- google analytics -->
    <script type="text/javascript">
      <!--
      /*var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-25710173-1']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();*/
      -->
    </script>

    <!-- google plus one 
    <script type="text/javascript" src="https://apis.google.com/js/plusone.js">
      //{lang: 'fr'}
    </script>
    -->
  </body>
</html>