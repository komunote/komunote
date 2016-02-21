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
  ?>
  <?php if ($var_result) { ?>
    <p><?php t("Votre article a été mis en vente avec succès") ?></p>
    <meta http-equiv="refresh" content="5;url=http://<?php echo DB_HOST?><?php t("/Mon-compte") ?>" />    
  <?php } else { ?>
    <p><?php t("Suite à une erreur interne, votre article n'a pas été mis en vente.") ?><a href="./<?php t("Vendre") ?>"><?php t("Réessayer") ?></a></p>
  <?php
  }
}