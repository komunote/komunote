<?php
if (CRON) {
    t("Neuf");
    t("Comme neuf");
    t("Bon état");
    t("Mauvais état");
    t("Mots clés");
    t("Fiche produit");
    t("Livraison");
    t("Vendeur");
    t("Cliquez pour agrandir");
    t("Description");
    t("Prix");
    t("Frais de port");
    t("Particulier");
    t("Mode de livraison");
    t("Lettre");
    t("Colissimo");
    t("Lettre Max");
    t("Chronopost");
    t("UPS");
    t("FEDEX");
    t("Autre");
    t("");
    t("");
    t("");
} else {

    $etatObjet = (int) $var_item['state'];
    $etatMessage = array(100 => _t('Neuf'), 75 => _t('Comme neuf'), 50 => _t('Bon état'), 25 => _t('Mauvais état'));
    $coef = (100 - $etatObjet * 25);
    $aShippingType = array(0 => _t('Standard'), 1 => _t('Lettre'), 2 => _t('Colissimo'), 3 => _t('Lettre Max'), 4 => _t('Chronopost'), 5 => 'UPS', 6 => 'Fedex', 7 => 'Autre');
    ?>
    <!-- visionneuse -->
    <div id="div_image_<?php echo $var_item['id'] ?>" title="<?php echo $var_item['keywords'] ?>" class="hidden" style="width: 100%;"><img src="images/shop/<?php echo $var_item['id'] ?>/<?php echo $var_item['id'] ?>.jpg"/>
    </div>
    <p><strong><?php echo $var_item['keywords'] ?></strong></p><br />

    <meta itemprop="description" content="<?php echo $var_item['keywords'] ?>" />

    <section>
        <article style="text-align: center;">
            <table style="margin : 10px auto auto auto;">
                <tr><td width="250px">&nbsp;</td><td width="350px">&nbsp;</td></tr>                
                <tr>                    
                    <td colspan="2"><span id="image_<?php echo $var_item['id'] ?>" title="<?php t("Cliquez pour agrandir") ?>" style="cursor:pointer;padding-right: 10px;">
                            <img src="images/shop/<?php echo $var_item['id'] ?>/<?php echo $var_item['id'] ?>.jpg" style="width:128px;height:auto;" onerror="this.src='images/komunote2.gif'"/>
                        </span></td>                    
                </tr>                
                <tr>                    
                    <td><div style="float:right;"><h2><?php t("Etat") ?> :</h2></div></td>
                    <td>
                        <div id="etat-produit" style="height:20px; width: 64px;float: right;"></div>
                        <span style="padding-right: 10px;"><?php echo $etatMessage[$coef] ?></span>
                    </td>
                </tr>
                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                <tr>
                    <td><div style="float:right;"><h2><?php t("Description") ?> :</h2></div></td>
                    <td><div style="float:right;"><?php echo $var_item['description'] ?></div></td>
                </tr>
                <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                <tr>
                    <td><div style="float:right;"><h2><?php t("Prix") ?> :</h2></div></td>
                    <td><div style="float:right;"><?php echo $var_item['unit_price'] ?> &euro;</div></td>
                </tr>
                <tr>
                    <td><div style="float:right;"><h2><?php t("Mode de livraison"); ?> :</h2></div></td>
                    <td><div style="float:right;"><?php echo $aShippingType[$var_item['shipping_type']]; ?></div></td>
                </tr>
                <tr>
                    <td><div style="float:right;"><h2><?php t("Frais de port") ?> :</h2></div></td>
                    <td><div style="float:right;"><?php echo $var_item['unit_shipping_price'] ?> &euro;</div></td>
                </tr>
                <tr>
                    <td><div style="float:right;"><h2><?php t("Vendeur") ?> :</h2></div></td>
                    <td><div style="float:right;"><?php t("Vendeur") ?> :</h2>
                            <a href="#"><?php echo $var_user['pseudo'] ?></a>       <?php /* profil/<?php echo $var_user['id'] ?> */ ?>                     
                        </div>
                    </td>
                </tr>     
                <tr>
                    <td><div style="float:right;">&nbsp;</div></td>
                    <td><div style="float:right;"><?php t("Particulier") ?></div></td>
                </tr>
            </table>
            <br />
        </article>
    </section>

    <?php /* ?>
      <!-- tabs -->
      <div id="tabs">
      <ul><li><a href="#tabs-fiche"><?php t("Fiche produit") ?></a></li>
      <li><a href="#tabs-livraison"><?php t("Livraison") ?></a></li>
      <li><a href="#tabs-vendeur"><?php t("Vendeur") ?></a></li></ul>
      <!-- info de la fiche -->
      <div id="tabs-fiche">
      <header></header>
      <aside>
      <span style="padding-right: 10px;float: left;"><?php echo $etatMessage[$coef] ?></span>
      <div id="etat-produit" style="height:20px; width: 64px;float: left;"></div><br />
      <span id="image_<?php echo $var_item['id'] ?>" title="<?php t("Cliquez pour agrandir") ?>" style="cursor:pointer;float:left;padding-right: 10px;">
      <img src="images/shop/<?php echo $var_item['id'] ?>/<?php echo $var_item['id'] ?>.jpg" style="width:128px;height:auto;" onerror="this.src='images/komunote2.gif'"/>
      </span>
      </aside>
      <section>
      <article>
      <h2><?php t("Description") ?> :</h2>
      <?php echo $var_item['description'] ?>
      </article><br />
      <article>
      <h2><?php t("Prix") ?> :</h2>
      <?php echo $var_item['unit_price'] ?> &euro;
      </article><br />
      <article>
      <h2><?php t("Frais de port") ?> :</h2>
      <?php echo $var_item['unit_shipping_price'] ?> &euro;
      </article>
      </section>

      </div>
      <!-- info de livraison -->
      <div id="tabs-livraison">
      <header></header>
      <section><h2><?php t("Frais de port"); ?> :</h2>
      <article><?php echo $var_item['unit_shipping_price']; ?> &euro;</article>
      </section><br />
      <section><h2><?php t("Mode de livraison"); ?></h2>
      <article><?php echo $aShippingType[$var_item['shipping_type']]; ?></article>
      </section>
      </div>
      <!-- info sur le vendeur -->
      <div id="tabs-vendeur"><p><h2><?php t("Vendeur") ?> :</h2><a href="profil/<?php echo $var_user['id'] ?>"><?php echo $var_user['pseudo'] ?></a></p><br /><p><?php t("Particulier") ?></p></div>
      </div>

     */ ?>

    <?php if (isset($_SESSION['logged']) && $var_user['id'] != $_SESSION['user_id']) { ?>
        <?php /* bouton achat */ ?> 
        <form id="paypal_form" action="<?php t("./Boutiques-paypal-form") ?>" method="POST">    
            <span id="shop_buy_button" class="menuNotSelected"><?php t("Régler avec Paypal la somme de") ?> <?php echo $var_item['unit_price'] + $var_item['unit_shipping_price'] ?> €</span>
            <input type="hidden" name="item" value="<?php echo htmlentities(json_encode(array('user' => $var_user, 'item' => $var_item))) ?>" />    
        </form>
        <?php /* modale */ ?> 
        <div id="shop_confirm_buy" title="<?php t("Confirmation Achat") ?>" class="hidden"><p><?php t("Vous êtes sur le point de confirmer définitivement votre achat.") ?></p><p><?php t("Etes-vous d'accord avec toutes les conditions décrites par le vendeur ?") ?></p></div>    
    <?php } else if (!isset($_SESSION['user_id']) || $var_user['id'] != $_SESSION['user_id']) { ?>
        <?php /* bouton inscription */ ?> 
        <a href="<?php t("./Inscription") ?>" class="menuNotSelected"><?php t("Inscription requise pour effectuer cet achat") ?></a>
    <?php } ?>

    <script type="text/javascript">
        $(function() {
            $('#shop_buy_button').click(function() {
                $('#shop_confirm_buy').dialog({
                    modal: true,
                    show: 'slide',
                    width: 480,
                    buttons: {
                        'Oui': function() {
                            $('#shop_buy_button').hide('slow');
                            $('#paypal_form').submit();
                            $(this).dialog('close');
                        },
                        'Non': function() {
                            $(this).dialog('close');
                        }
                    }
                });
            });
            $('#image_<?php echo $var_item['id'] ?>').click(function() {
                $('#div_image_<?php echo $var_item['id'] ?>').dialog({modal: true, width: 480});
            });
            var colors = {'100': '#0099FF', '75': 'Green', '50': 'Orange', '25': '#FF0000'};
            etat = <?php echo $coef ?>;
            $('#tabs').tabs();
            /* état du produit : neuf, comme-neuf, bon état, mauvais état */
            $("#etat-produit").progressbar({value: etat});
            $('#etat-produit > .ui-widget-header').css({'background': colors[etat]});
        });
    </script>
    <?php
}