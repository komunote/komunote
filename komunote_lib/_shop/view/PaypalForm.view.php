<?php 
if (CRON) {
    t("Une erreur s'est produite. La vente n'a pas pu être réalisée.");
    t("/Paiement-paypal-succes/");
    t("/Paiement-paypal-annule/");
    t("");
    t("");
    t("");
    t("");
    t("");
    t("");
    t("");
    
} else {
  
  if (!isset($var_order)) { ?>
    <p><?php t("Page non autorisée.") ?></p>
    <p><?php t("Redirection en cours...") ?></p>
    <meta http-equiv="refresh" content="2;url=http://<?php echo DB_HOST?><?php t("/Rechercher") ?>" />
<?php } else if ($var_order['id']===2) { ?>
    <p><?php t("Achat déjà effectué ou une commande non validée est déjà en cours. ") ?></p>
    <p><?php t("Veuillez vérifier votre compte.") ?></p>
    <p><?php t("Redirection en cours...") ?></p>
    <meta http-equiv="refresh" content="3;url=http://<?php echo DB_HOST?><?php t("/Mon-compte") ?>" />
<?php } else if ($var_order['id']===2) { ?>
    <p><?php t("Achat en cours ou déjà effectué par un autre utilisateur. ") ?></p>
    <p><?php t("Veuillez réessayer ultérieurement.") ?></p>
    <p><?php t("Redirection en cours...") ?></p>
    <meta http-equiv="refresh" content="3;url=http://<?php echo DB_HOST?><?php t("/Mon-compte") ?>" />
<?php } else { ?>    
    <?php
    /* création de la clé paypal qui contient l'id de l'item et id user */
    if (isset($_SESSION['logged'])) {
        $key = sha256_crypt($var_order['id'] . $_SESSION['user_id']);                
    }
    ?>
    <!-- visionneuse -->
    <div id="div_image_<?php echo $var_item['id'] ?>" title="<?php echo $var_item['keywords'] ?>" class="hidden" style="width: 100%;">
        <img src="images/shop/<?php echo $var_item['id'] ?>/<?php echo $var_item['id'] ?>.jpg"/></div>    
    <h2><?php echo $var_item['keywords'] ?></h2><br />
    <span id="image_<?php echo $var_item['id'] ?>" title="<?php t("Cliquez pour agrandir") ?>" class="pointer">
        <img src="images/shop/<?php echo $var_item['id'] ?>/<?php echo $var_item['id'] ?>.jpg" width="128px"/></span>
    <h3><?php echo $var_item['description'] ?></h3><br />
    <p><?php echo _t("Vendeur :"), ' ', $var_user['pseudo'] ?></p><br />

    <a id="link_<?php echo $var_item['id']; ?>" target="_new" 
       href="<?php t("Boutiques") ?>/<?php echo $var_item['id'] ?>/<?php echo $var_item['keywords'] ?>/">
    <?php t("consulter la fiche") ?></a><br /><br />
    <p><?php t("Somme à régler pour finaliser l'achat") ?></p>
    <p><?php echo _t("(frais de port inclus) : "), $var_item['unit_price'] + $var_item['unit_shipping_price'] ?> €</p><br />    

    <?php /* bouton acheter Paypal   */ ?> 
    <?php if (isset($key) && isset($var_order['id']) && $var_order['id'] >0) { ?>
        <div id="paypal_div" class="show"><br />
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="return" value="URLspecificToThisTransaction" />
                <input type="hidden" name="cmd" value="_xclick"/>
                <input type="hidden" name="business" value="<?php echo $var_user['email'] ?>"/>
                <input type="hidden" name="lc" value="FR"/>
                <input type="hidden" name="item_name" value="Komunote-<?php echo $var_item['keywords'] ?>"/>
                <input type="hidden" name="item_number" value="<?php echo $var_item['id'] ?>"/>
                <input type="hidden" name="amount" value="<?php echo $var_item['unit_price'] ?>"/>
                <input type="hidden" name="currency_code" value="EUR"/>
                <input type="hidden" name="button_subtype" value="services"/>
                <input type="hidden" name="no_note" value="0"/>
                <input type="hidden" name="tax_rate" value="0.000"/>
                <input type="hidden" name="shipping" value="<?php echo $var_item['unit_shipping_price'] ?>"/>
            <? /* url de validation des paiements */ ?>
                <input type="hidden" name="return" value="http://www.komunote.eu<?php t("/Paiement-paypal-succes/") ?><?php echo $var_order['id'] ?>/<?php echo $key ?>"/>
            <? /* url d'annulation des paiements */ ?>
                <input type="hidden" name="cancel_return" value="http://www.komunote.eu<?php t("/Paiement-paypal-annule/") ?><?php echo $var_order['id'] ?>/<?php echo $key ?>"/>
            <? // url de notification instantanée des paiements   ?>
                <input type="hidden" name="notify_url" value="http://alanb.com/doodads/notify.cgi"/>
                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_SM.gif:NonHostedGuest"/>
                <input type="image" src="https://www.paypal.com/fr_FR/FR/i/btn/btn_buynow_SM.gif" border="0" name="submit" 
                       alt="<?php t("PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !") ?>"/>
                <img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1"/>
            </form>
        </div>
    <?php } else { ?>
    <p><?php t("Une erreur s'est produite. La vente n'a pas pu être réalisée.");?></p>
    <?php } ?>
<?php }?>

<script type="text/javascript">    
    $(function(){$('#image_<?php echo $var_item['id'] ?>').click(function(){$('#div_image_<?php echo $var_item['id'] ?>').dialog({modal: true,width:480});});});        
</script>

<?php } 