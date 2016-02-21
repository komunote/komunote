<?php
if (CRON) {
  t("Erreur inconnue dans le formulaire.");
  t("Pseudo déjà utilisé.");
  t("L'adresse email soumise est déjà utilisée.");
  
} else {
  ?>  
  <?php if (isset($_GET['p']) && $_GET['p'] === 'checkUser') { ?>
      $('#errorForm').html("<?php t("Erreur inconnue dans le formulaire.") ?>");
    <?php if ($var_result === 'pseudo') { ?>	
          $('#errorForm').html("<?php t("Pseudo déjà utilisé.") ?>");
          showPopup($('#pseudo'),'#errorForm');
          $('#pseudo').val('');	
          $('#accordeon').accordion({autoHeight:false,animated: 'bounceslide',active:0});
    <?php } else if ($var_result === 'email') { ?>
          $('#errorForm').html("<?php t("L'adresse email soumise est déjà utilisée.") ?>");
          showPopup($('#email'),'#errorForm');
          $('#email').val('');
          $('#email2').val('');	
          $('#accordeon').accordion({autoHeight:false,animated: 'bounceslide',active:1});
    <?php }   
    }
}