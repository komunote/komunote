<?php 
if (CRON) {
    t("");
    t("");
    t("");
    t("");
    t("");
    t("");
    t("");
    t("");
    t("");
    t("");
    
} else {
  
  if ($var_result) { ?>    
        <p><?php t("Votre paiement Paypal a été accepté.") ?></p>
        <p><?php t("Komunote vous remercie pour votre achat.") ?></p>
        <p><?php t("Redirection en cours...") ?></p>
        <meta http-equiv="refresh" content="3;url=http://<?php echo DB_HOST?><?php t("/Rechercher") ?>" />    
<?php } else { ?>
        <p><?php t("Votre paiement n'est pas validé ou a déjà été validé.") ?></p>
        <p><?php t("Si votre paiement a été effectué mais qu'il n'apparait pas sur votre compte comme validé, merci de contacter le vendeur.") ?></p>
        <p><?php t("Ce dernier sera en mesure de valider manuellement votre paiement après une vérification sur son compte Paypal.") ?></p>
        <p><?php t("Redirection en cours...") ?></p>
        <meta http-equiv="refresh" content="5;url=http://<?php echo DB_HOST?><?php t("/Mon-compte") ?>" />    
<?php }
}