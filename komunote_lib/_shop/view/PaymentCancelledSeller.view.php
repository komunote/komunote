<?php 
if (CRON) {
    t("La commande a été annulée.");
    t("Redirection en cours...");
    t("La commande n'a pas été annulée car elle est peut être déjà ou a été validé et payée par l'acheteur.");
    t("Si ce dernier a validé la commande il vous est possible de l'annuler en effectuant une remboursement Paypal.");
    t("");
    t("");
    t("");
    t("");
    t("");
    t("");
    
} else {
  
  if ($var_result) { ?>
    <p><?php t("La commande a été annulée.") ?></p>    
    <p><?php t("Redirection en cours...") ?></p>
    <meta http-equiv="refresh" content="3;url=http://<?php echo DB_HOST?><?php t("/Mon-compte") ?>" />
<?php } else { ?>
    <p><?php t("La commande n'a pas été annulée car elle est peut être déjà ou a été validé et payée par l'acheteur.") ?></p>    
    <p><?php t("Si ce dernier a validé la commande il vous est possible de l'annuler en effectuant une remboursement Paypal.") ?></p>
    <p><?php t("Redirection en cours...") ?></p>    
<?php }
}