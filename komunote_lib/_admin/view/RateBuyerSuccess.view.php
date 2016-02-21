<?php
if (CRON) {
  t("/Mon-compte");
  t("Votre évaluation a bien été prise en compte.");
  t("Komunote vous remercie.");
  t("L'évaluation a déjà été soumise.");
  t("Echec de la prise en compte de votre évaluation.");
  t("");
  t("");
  t("");
  t("");
  t("");
} else {
  ?>
  <meta http-equiv="refresh" content="3;url=http://<?php echo DB_HOST ?><?php t("/Mon-compte") ?>" />    
  <?php if ($var_result == 1) { ?>
    <p><?php t("Votre évaluation a bien été prise en compte.") ?></p>
    <p><?php t("Komunote vous remercie.") ?></p>
  <?php } else if ($var_result == 2) { ?>
    <p><?php t("L'évaluation a déjà été soumise.") ?></p>
  <?php } else { ?>
    <p><?php t("Echec de la prise en compte de votre évaluation.") ?></p>
  <?php
  }
}
