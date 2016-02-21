<?php
if (CRON) {
  t("Inscription réussie.");
  t("/Rechercher");
  t("L'inscription a échoué.");
  t("./Inscription");
  t("Réessayer");
  t("");
  t("");
  t("");
  t("");
  t("");
} else {
  ?>
  <?php if (isset($_POST['p']) && $_POST['p'] === 'insert') { ?>
    <?php if ($var_result) { ?>
      <p><?php t("Inscription réussie.") ?></p>
      <meta http-equiv="refresh" content="2;url=http://<?php echo DB_HOST ?><?php t("/Rechercher") ?>" />
    <?php } else { ?>
      <p><?php t("L'inscription a échoué.") ?> 
        <a href="<?php t("./Inscription") ?>"><?php t("Réessayer") ?></a>
      </p>
    <?php } ?>
    <?php
  }
}