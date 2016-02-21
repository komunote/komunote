<?php
if (CRON) {
  t("Rejoignez-nous c'est gratuit !");
  t("en savoir plus");
  t("./Inscription-envoyee");
  t("Pseudo");
  t("Choisissez votre pseudonyme");
  t("mon_pseudo");
  t("Entre 3 et 32 chiffres ou lettres uniquement");
  t("Adresse email");
  t("contact@email.com");
  t("Une adresse email valide");
  t("Une adresse email valide identique à celle saisie ci-dessus");
  t("Sécurité");
  t("Une question secrète à propos de vous");
  t("Quel est le nom de mon animal domestique ?");
  t("Quel est le nom de jeune fille de ma mère ?");
  t("Quelle est ma ville natale ?");
  t("Quel est le nom de mon employeur ?");
  t("Entre 3 et 32 chiffres ou lettres uniquement");
  t("Armani (en minuscule)");
  t("Code de sécurité");
  t("Taper le code suivant");
  t("Valider l'inscription");
  t("Informations");
  t("Aucune donnée personnelle nominative ne vous sera demandée ni conservée sur les bases de données de");
  t("exige simplement une adresse email grâce à laquelle vous pourrez acheter et vendre en toute simplicité");
  t("L'inscription est nécessaire pour pouvoir profiter gratuitement des services de");
  t("encourage ses membres à utiliser");
  t("pour acheter et vendre en toute sécurité");
  t("Votre adresse est votre identifiant.&nbsp;");
  t("Une adresse valide est donc nécessaire pour pouvoir utiliser les services de");
  t("Elle doit être identique à votre adresse Paypal.");
  t("Cette adresse email sera liée à votre compte Paypal pour vos mises en vente.");
  t("Aucun changement d'adresse email ne sera accepté.");  
  t("Mot de passe");
  t("6 à 32 caractères autorisés : Lettre (minuscules/majuscules) et chiffres uniquement.");
  t("Conseil : Utilisez un mot de passe différent que celui utilisé avec Paypal.");
  t("En cas de perte de votre mot de passe, vous pourrez le réinitialiser en répondant à une question personnelle que vous pouvez définir vous-même.");
  t("Code de sécurité");
  t("Sécurité");
  t("Pour valider l'inscription vous devez saisir le code ci-dessous.");
} else {
  ?>
  <?php if (isset($_SESSION['logged'])) { ?>
    <meta http-equiv="refresh" content="0;url=http://<?php echo DB_HOST ?><?php t("/Rechercher") ?>" />    
  <?php } ?>
  <?php $komunote = '&nbsp;<strong><span class="bleuF">Komu</span><span class="bleuC">note</span></strong>&nbsp;'; ?>
  <img src="images/join_icon.jpg" />
  <h1><?php t("Rejoignez-nous c'est gratuit !") ?><span class="pointer" name="modale" link="#info">
      <img src="images/question.gif" title="(<?php t("en savoir plus") ?>)" alt="(<?php t("en savoir plus") ?>)" class="btSquareS"/></span>      
  </h1>
  <br />
  <form id="formInscription" name="formInscription" 
        action="<?php t("./Inscription-envoyee") ?>" class="formular" method="post">
    <div id="errorForm" style="color: #ff0000;" class="hidden" title="Erreur"></div>
    <div id="accordeon">            
      <?php /* Pseudonyme */ ?>
      <h3 name="modale" link="#infoPseudo"><a href="#"><?php t("Pseudo") ?></a></h3>
      <div><p><?php t("Choisissez votre pseudonyme") ?> :</p><input type="text" name="pseudo" id="pseudo" placeholder="<?php t("mon_pseudo") ?>" title="<?php t("Entre 3 et 32 chiffres ou lettres uniquement") ?>" maxlength="32" required/></div>
      <?php /* Adresse email */ ?>
      <h3 name="modale" link="#infoEmail"><a href="#" id="accEmail"><?php t("Adresse email") ?></a></h3>        
      <div><input type="email" name="email" id="email" placeholder="<?php t("contact@email.com") ?>" title="<?php t("Une adresse email valide") ?>" maxlength="128" required/>
        <p>(<?php t("confirmation") ?>)</p><input type="email" name="email2" id="email2" title="<?php t("Une adresse email valide identique à celle saisie ci-dessus") ?>" required/></div>
      <?php /* Mot de passe */ ?>
      <h3 name="modale" link="#infoPassword"><a href="#" ><?php t("Mot de passe") ?></a></h3>
      <div><input type="password" name="password" id="password" title="<?php t("Entre 3 et 32 chiffres ou lettres uniquement") ?>" maxlength="32" required/><p>(<?php t("confirmation") ?>)</p><input type="password" name="password2" id="password2" title="<?php t("Entre 3 et 32 chiffres ou lettres uniquement identiques au mot de passe saisi ci-dessus") ?>" maxlength="32" required/></div>
      <?php /* Question de sécurité */ ?>
      <h3 name="modale" link="#infoSecurite"><a href="#"><?php t("Sécurité") ?></a></h3>
      <div>
        <p><?php t("Une question secrète à propos de vous") ?> : </p>
        <select id="question" name="question">
          <option value="0" selected><?php t("Quel est le nom de mon animal domestique ?") ?></option>
          <option value="1"><?php t("Quel est le nom de jeune fille de ma mère ?") ?></option>
          <option value="2"><?php t("Quelle est ma ville natale ?") ?></option>
          <option value="3"><?php t("Quel est le nom de mon employeur ?") ?></option>
        </select>        
        <p><?php t("Réponse") ?> : </p>
        <input type="text" name="answer" id="answer" placeholder="<?php t("Armani (en minuscule)") ?>" title="<?php t("Entre 3 et 64 chiffres ou lettres uniquement") ?>" maxlength="64" required/>
      </div>
      <?php /* Code de sécurité */ ?>
      <h3 name="modale" link="#infoCode"><a href="#"><?php t("Code de sécurité") ?></a></h3>
      <div><p><?php t("Taper le code suivant") ?> :</p><p><span id="codeVerif"  style="font-weight: 900;color:#0000ff;"><?php echo rand(1000, 9999); ?></span>&nbsp;<span id="codeSecu" style="font-weight: 900;color:#ff0000;"></span></p><div id="digicode"></div></div>
    </div>
    <p><input id="btValider" type="button" class="hidden" onclick="check();" value="<?php t("Valider l'inscription") ?>"/></p>
    <input name="p" type="hidden" value="insert"/><input name="validation" type="hidden" value="666"/>
  </form><br/><br/><br/>
  <?php /* MODALES */ ?>
  <?php /* Modale Information */ ?>
  <div id="info" title="<?php t("Informations") ?>" class="hidden">
    <p><?php t("Aucune donnée personnelle nominative ne vous sera demandée ni conservée sur les bases de données de");
  echo $komunote ?>.</p><br /><p>
  <?php echo $komunote;
  t("exige simplement une adresse email grâce à laquelle vous pourrez acheter et vendre en toute simplicité") ?>.</p>
    <p><?php t("L'inscription est nécessaire pour pouvoir profiter gratuitement des services de");
  echo $komunote ?>.</p><br />
    <p><?php echo $komunote;
  t("encourage ses membres à utiliser") ?> <span class="bleuF">Paypal</span>
      <?php t("pour acheter et vendre en toute sécurité") ?>.</p></div>
      <?php /* Modale Adresse email */ ?>
  <div id="infoEmail" title="<?php t("Adresse email") ?>" class="hidden">
    <p><span id="ui-alert"></span>
      <?php t("Votre adresse email est votre identifiant.&nbsp;");
      t("Un adresse valide est donc nécessaire pour pouvoir utiliser les services de");
      echo $komunote
      ?>.</p><br />
    <p><?php
      t("Elle doit être identique à votre adresse Paypal. ");
      t("Cette adresse email sera liée à votre compte Paypal pour vos mises en vente.");
      t("Aucun changement d'adresse email ne sera accepté.")
      ?>
    </p>
  </div>
      <?php /* Modale Mot de passe */ ?>
  <div id="infoPassword" title="<?php t("Mot de passe") ?>" class="hidden">
    <p><span id="ui-alert"></span>
      <?php t("6 à 32 caractères autorisés : Lettre (minuscules/majuscules) et chiffres uniquement.") ?></p>
    <p><span id="ui-info"></span>
  <?php t("Conseil : Utilisez un mot de passe différent que celui utilisé avec Paypal.") ?></p></div>
  <?php /* Modale Question Sécurité */ ?>
  <div id="infoSecurite" title="<?php t("Sécurité") ?>" class="hidden"><p><span id="ui-info"></span>
  <?php t("En cas de perte de votre mot de passe, vous pourrez le réinitialiser en répondant à une question personnelle que vous pouvez définir vous-même.") ?></p></div>
  <?php /* Modale Code de sécurité */ ?>
  <div id="infoCode" title="<?php t("Code de sécurité") ?>" class="hidden"><p><span id="ui-info"></span>
  <?php t("Pour valider l'inscription vous devez saisir le code ci-dessous.") ?></p></div>

  <script type="text/javascript">
    $(document).ready(function(){
      $("[name='modale']").click(function(){
        showPopup(this, $(this).attr('link'));
              
      });      
    });
    //onclick="showPopup(this,'#infoEmail');return false;"
  </script>
<?php
}