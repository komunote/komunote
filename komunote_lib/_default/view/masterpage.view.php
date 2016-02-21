<?php
if (CRON) {
    t("./Acheter");
    t("./Accueil");
    t("./Au-revoir");
    t("./Bienvenue");
    t("./Concept");
    t("./Inscription");
    t("./Mon-compte");
    t("./Rechercher");
    t("./Vendre");
    t("communauté, paypal, vendre, acheter, crédit, gratuit, gratuitement, sans frais");
    t("Achetez ou vendez tout ce que vous souhaitez. Komunote, sans frais. Réglez vos achats avec Paypal pour une sécurité maximale. Achat, vente, annonce, réservations... La plateforme d'échange la moins chère du monde.");
    t("Déconnexion");
    t("Connexion");
    t("Email");
    t("Mot de passe");
    t("Vendre");
    t("Acheter");
    t("Inscription");
    t("Concept");
    t("Cliquez OK pour vous déconnecter");
    t("Mon compte");
    t("Le Javascript doit être activé pour que Komunote puisse fonctionner correctement.");    
    t("");
}
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['language'], '-', strtoupper($_SESSION['language']) ?>" xmlns:fb="http://ogp.me/ns/fb#">
    <head>
        <meta charset="UTF-8" /><meta name="google-site-verification" content="6V5umEbe-_o9alZDmj0jqqM-YlpgL8CZDquuz4RXcPQ" />
        <meta name="keywords" content="<?php t("communauté, paypal, vendre, acheter, crédit, gratuit, gratuitement, sans frais") ?>,<?php echo isset($var_item['keywords']) ? str_replace(' ',',',$var_item['keywords']):''; ?> " />
        <meta name="description" content="<?php t("Achetez ou vendez tout ce que vous souhaitez. Komunote, sans frais. Réglez vos achats avec Paypal pour une sécurité maximale. Achat, vente, annonce, réservations... La plateforme d'échange la moins chère du monde.") ?>" />
        <base href="http://<?php echo DB_HOST; ?>/" />
        <link rel="shortcut icon" href="images/favicon.ico" />
        <link rel="icon" type="image/gif" href="images/animated_favicon1.gif" />
        <?php
        $c = isset($_GET['c']) ? $_GET['c'] : (isset($_POST['c']) ? $_POST['c'] : '');
        $a = isset($_GET['a']) ? $_GET['a'] : (isset($_POST['a']) ? $_POST['a'] : '');
        ?>        
        <link rel="stylesheet" href="style/screen.css" type="text/css"/>                    
        <link href="style/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>    
        <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>-->
        <script src="script/jquery-1.7.1.min.js" type="text/javascript"></script>
        <!--<script src="script/jquery.mobile-1.4.0.min.js" type="text/javascript"></script>-->
        <!--<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js" type="text/javascript"></script>-->
        <script src="script/jquery-1.8.11.ui.min.js" type="text/javascript"></script>        
        <script src="script/trad<?php echo $_SESSION['language'] ?>.js" type="text/javascript"></script>
        <script src="script/masterpageHeader.js" type="text/javascript"></script>
        <?php if ($c == 'admin' && $a == 'Signin') { ?><script src="script/signIn.js" type="text/javascript"></script><?php } ?>
        <?php if ($c == 'admin' && $a == 'Account') { ?><script src="script/account.js" type="text/javascript"></script><?php } ?>
        <title>Komunote</title>	
    </head>    
    <body>                          
        <header id="headerNav">
            <nav id="logoNav">
                <a href="<?php t("./Rechercher") ?>">
                    <span class="komunoteTitle"><span class="blueF">Komu</span><span class="blueC">note</span></span>
                </a>                
            </nav>
            <nav id="loginMenuNav">
                <?php /*language selection */ ?>
                <?php if ($_SESSION['language'] == 'FR') { ?>
                <a href="./Search"><img src="images/en-flag.gif" width="16px" height="12px" alt="English" title="English" /></a>&nbsp; 
                <a href="./Sagasu"><img src="images/jp-flag.gif" width="16px" alt="日本語" title="日本語" /></a>
                <?php } else if ($_SESSION['language'] == 'JP') { ?>
                <a href="./Search"><img src="images/en-flag.gif" width="16px" height="12px" alt="English" title="English" /></a>&nbsp;
                <a href="./Rechercher"><img src="images/fr-flag.gif" width="16px" height="12px" alt="Français" title="Français" /></a>
                <?php } else { ?>
                <a href="./Rechercher" id="linkLanguageFR"><img src="images/fr-flag.gif" width="16px" height="12px" alt="Français" title="Français" /></a>&nbsp;
                <a href="./Sagasu" id="linkLanguageJP"><img src="images/jp-flag.gif" width="16px" alt="日本語" title="日本語" /></a>
                <?php } ?>
            </nav>            
            <nav id="menuNav">
                <ul id="menuUl">          
                    <li><a href="<?php t("./Rechercher") ?>" title="<?php t("Acheter") ?>" class="menu<?php echo $c == 'shop' && ($a == 'Search' || $a == 'SearchItem') ? '' : 'Not'; ?>Selected"><?php t("Acheter") ?></a></li> 
                <?php if (isset($_SESSION['logged'])) { ?>
                    <li><a href="<?php t("./Vendre") ?>" title="<?php t("Vendre") ?>" class="menu<?php echo $c == 'shop' && $a == 'Sell' ? '' : 'Not'; ?>Selected"><?php t("Vendre") ?></a></li> 
                <?php } ?>          
                <?php if (!isset($_SESSION['logged'])) { ?>
                    <li><a href="<?php t("./Inscription") ?>" class="menu<?php echo $c == 'admin' && $a == 'Signin' ? '' : 'Not'; ?>Selected"><?php t("Inscription") ?></a></li>
                    <li><a href="<?php t("./Concept") ?>" title="<?php t("Concept") ?>" class="menu<?php echo $a == 'Concept' ? '' : 'Not'; ?>Selected"><?php t("Concept") ?></a></li> 
                <?php } ?>          
                <?php if (isset($_SESSION['logged'])) { ?>
                    <li><a href="<?php t("./Mon-compte") ?>" class="menu<?php echo $c == 'admin' ? '' : 'Not'; ?>Selected" title="<?php t("Mon compte") ?>"><?php t("Mon compte") ?></a></li>
                <?php } ?>                
                <?php /*sign in/out */ ?>                            
                    <li>
                        <span id="errorLogin" style="color: #ff0000;"></span>
                        <?php if (isset($_SESSION['logged']) && $_SESSION['logged']) { ?>
                        <span id="linkLogout" class="menuNotSelected" title="<?php t("Déconnexion") ?>"><span class="ui-icon ui-icon-power"></span></span>
                        <?php } else { ?>
                        <span id="linkLogin" class="menuNotSelected" title="<?php t("Connexion") ?>"><span class="ui-icon ui-icon-power"></span></span>
                        <?php } ?>
                        <div id="popupLogin" title="<?php t("Connexion") ?>" style="display: none;">
                            <form id="formLogin" name="formLogin" action="<?php t("./Bienvenue") ?>" method="post" title="<?php t("Connexion") ?>">
                                <p><?php t("Email") ?> <br /><input type="text" name="_email" id="_email"/></p>
                                <p><?php t("Mot de passe") ?> <br />
                                    <input type="password" name="_password" id="_password"/>
                                </p>
                                <input type="hidden" value="1" name="cx"/>
                            </form>
                        </div>
                        <div id="popupLogout" title="<?php t("Déconnexion") ?>" style="display: none;">
                            <form id="formLogout" name="formLogout" action="<?php t("./Au-revoir") ?>" method="post" title="<?php t("Déconnexion") ?>">
                                <p><?php t("Cliquez OK pour vous déconnecter") ?></p>
                            </form>
                        </div>                        
                    </li>
                </ul>
            </nav>            
        </header>
       
        <section id="bodyDiv">            
            <article id="bodyCenterDiv">
                <noscript><h1><span class="ui-icon ui-icon-alert"></span><?php t("Le Javascript doit être activé pour que Komunote puisse fonctionner correctement.") ?></h1></noscript>
                <?php echo $var_result; ?>
                <p>                         
                    <?php if ($c == 'shop' && $a == 'Show') { ?>                    
                        <div id="fb-root"></div>
                        <fb:share-button  href="" type="button_count"></fb:share-button>                         
                        <g:plusone></g:plusone>  
                    <?php } ?>                   
                        &nbsp;
                </p>                
            </article>
        </section>

        <script src="script/masterpageFooter.js" type="text/javascript"></script>                
        
        <footer id="footerDiv" style="margin-top: 10px;">
            <p align="center"><strong><span class="bleuF">Komu</span><span class="bleuC">note</span></strong> - <a href="<?php t("./Infos-legales") ?>"><?php t("Infos légales") ?></a></p>
        </footer>                               
                
        <!-- google analytics -->               
        <script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');ga('create', 'UA-46793804-1', 'komunote.eu');ga('send', 'pageview');</script>        
        <script src="https://www.google.com/jsapi?key=ABQIAAAANfnTios5gQI-s1wde7XvWxTw_HdSptI3TKSE7OKwpyYML5Y_DxQ81mb9vLrqJLvYLLhHGJXK3r1O4A" type="text/javascript"></script>                     
        
        <?php if ($c == 'shop' && $a == 'Show') { ?>                    
        <script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: 'fr'}</script>       
        <script>
            //309437425817038
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;            
            <?php if ($_SESSION['language'] == 'FR') { ?>
            js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1&appId=309437425817038"; //207576555921115 
            <?php } else if ($_SESSION['language'] == 'JP') { ?>
            js.src = "//connect.facebook.net/ja_JP/all.js#xfbml=1&appId=309437425817038";
            <?php } else { ?>
            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=309437425817038";
            <?php } ?>  
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
        </script>
        <?php } ?>
    </body>
</html>