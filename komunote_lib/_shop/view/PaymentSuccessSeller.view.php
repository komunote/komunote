<?php 
if (CRON) {
    t("Le paiement Paypal de votre vente a été enregistré.");
    t("Redirection en cours...");
    t("/Mon-compte");
    t("L'enregistrement du paiement Paypal de votre vente n'a pas été validé ou a déjà été validé.");
    t("");
    t("");
    t("");
    t("");
    t("");
    t("");
    
 } else {
   
  if ($var_result) { ?>    
        <p><?php t("Le paiement Paypal de votre vente a été enregistré.") ?></p>        
        <p><?php t("Redirection en cours...") ?></p>
        <meta http-equiv="refresh" content="3;url=http://<?php echo DB_HOST?><?php t("/Mon-compte") ?>" />    
<?php } else { ?>    
        <p><?php t("L'enregistrement du paiement Paypal de votre vente n'a pas été validé ou a déjà été validé.") ?></p>                
        <p><?php t("Redirection en cours...") ?></p>
        <meta http-equiv="refresh" content="5;url=http://<?php echo DB_HOST?><?php t("/Mon-compte") ?>" />    
<?php }
 }