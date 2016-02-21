<?php 
if (CRON) {
    t("Votre article a été annulé.");
    t("Redirection en cours...");
    t("Votre article n'a pas été annulé ou a déjà été annulé.");    
    t("/Mon-compte");
    t("");
    t("");
    t("");
    
} else {
  
  if ($var_result) { ?>
    <p><?php t("Votre article a été annulé.") ?></p>    
    <p><?php t("Redirection en cours...") ?></p>
    <meta http-equiv="refresh" content="3;url=http://<?php echo DB_HOST?><?php t("/Mon-compte") ?>" />
<?php } else { ?>
    <p><?php t("Votre article n'a pas été annulé ou a déjà été annulé.") ?></p>    
    <p><?php t("Redirection en cours...") ?></p>
    <meta http-equiv="refresh" content="3;url=http://<?php echo DB_HOST?><?php t("/Mon-compte") ?>" />
<?php }
}