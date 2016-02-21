<?php
if (CRON) {
  t("Bienvenue");
  t("Adresse e-mail ou mot de passe incorrect.");
  t("");  
  
} else {
  ?>
  <?php if (isset($_SESSION['logged'])) { ?>
    <?php if (isset($_GET['mobile'])) { ?>
      <?php echo $var_mobile ?>
    <?php } else { ?>
      <meta http-equiv="refresh" content="2;url=http://<?php echo DB_HOST ?><?php t("/Mon-compte") ?>" />
      <p><?php t("Bienvenue") ?> <?php echo $_SESSION['user_pseudo'] ?></p>
    <?php } ?>    
  <?php } else { ?>
    <?php if (isset($_GET['mobile'])) { ?>
      <?php echo $var_mobile ?>
    <?php } else { ?>
      <p><?php t("Adresse e-mail ou mot de passe incorrect.") ?></p>        
    <?php } ?>
  <?php
  }
}