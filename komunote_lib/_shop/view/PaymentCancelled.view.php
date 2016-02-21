<?php 
if (CRON) {
    t("Votre commande a été annulée.");
    t("Redirection en cours...");
    t("Votre commande n'a pas été annulée ou a déjà été annulée.");
    t("Merci de contacter le vendeur.");
    t("Ce dernier sera en mesure d'annuler manuellement votre commande après une vérification sur son compte Paypal.");
    t("Si vous souhaitez être remboursé, faites-en la demande auprès du vendeur qui procèdera à un remboursement Paypal et à une annulation de votre commande.");
    t("/Rechercher");
    t("");
    t("");
    t("");
    
} else {
  
  if ($var_result) { ?>
    <p><?php t("Votre commande a été annulée.") ?></p>    
    <p><?php t("Redirection en cours...") ?></p>
    <meta http-equiv="refresh" content="3;url=http://<?php echo DB_HOST?><?php t("/Mon-compte") ?>" />
<?php } else { ?>
    <p><?php t("Votre commande n'a pas été annulée ou a déjà été annulée.") ?></p>
    <p><?php t("Merci de contacter le vendeur.") ?></p>
    <p><?php t("Ce dernier sera en mesure d'annuler manuellement votre commande après une vérification sur son compte Paypal.") ?></p>
    <p><?php t("Si vous souhaitez être remboursé, faites-en la demande auprès du vendeur qui procèdera à un remboursement Paypal et à une annulation de votre commande.") ?></p>      
<?php }
}