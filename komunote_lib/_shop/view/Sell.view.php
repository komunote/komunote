<?php
if (CRON) {
  t("Vendez votre nouvel article");
  t("./Boutique-Mise-en-ligne-succes");
  t("Mots clés décrivant votre article");
  t("Mots de plus de 3 lettres séparés par des espaces par ordre d'importance");
  t("Description");
  t("Description détaillée de l'article en lui même");
  t("Prix (min. 1 €)");
  t("Mode de livraison");
  t("Lettre");
  t("Colissimo");
  t("Lettre Max");
  t("Chronopost");
  t("UPS");
  t("FEDEX");
  t("Autre");
  t("Etat de l'objet");
  t("Neuf");
  t("Comme neuf");
  t("Bon état");
  t("Mauvais état");
  t("Frais de port");
  t("Image au format JPEF uniquement < 2 Mo");
  t("Consignes à respecter");
  t("Avant de mettre en vente votre article");
  t("Assurez-vous de disposer de l'article en stock avant de le vendre");
  t("Assurez-vous de son état (Neuf, comme-neuf, etc.)");
  t("Rassemblez tout le nécessaire à l'envoi du colis (lettre, timbres, étiquette etc.)");
  t("Lorsque votre article est vendu");
  t("Accusez réception du paiement à l'acheteur");
  t("Informez l'acheteur de la date d'envoi et la date de réception estimée");
  t("Une fois vendu votre article n'est plus disponible sur Komunote");
  t("Attention ! Tout vendeur ne respectant pas les consignes se verra radié de Komunote");
  t("Code de sécurité");
  t("Taper le code suivant");
  t("Validez votre email Paypal");
  t("Par sécurité l'adresse email n'est pas modifiable !");
  t("Mettre en vente");
  t("Pour valider la mise en vente vous devez saisir le code ci-dessous");
  t("Vous devez disposer d'un compte pour pouvoir acheter ou vendre");
  t("./Inscription");
  t("Je m'inscris, c'est gratuit");
  t("");
  
} else {
  ?>
  <?php if (isset($_SESSION['logged'])) { ?>
    <h3><?php t("Vendez votre nouvel article") ?> : </h3><br />  
    <form action="<?php t('./Boutique-Mise-en-ligne-succes') ?>" id="formSellNew" method="post" enctype="multipart/form-data">      
      <div id="accordeon">      
        <!-- mots clés -->
        <h3><a href="#" onclick="$('#_keywords').focus();"><?php t("Mots clés décrivant votre article") ?></a></h3>
        <div><input type="text" id="_keywords" name="_keywords" value="" maxlength="128"/><br /><p><span id="ui-info"></span><?php t("Mots de plus de 3 lettres séparés par des espaces par ordre d'importance") ?></p></div>
        <!-- description -->
        <h3><a href="#"><?php t("Description") ?></a></h3>
        <div><p><span id="ui-info"></span><?php t("Description détaillée de l'article en lui même") ?>.</p><textarea id="_description" name="_description" rows="15"></textarea></div>
        <!-- Prix -->
        <h3><a href="#" id="_price_link"><?php t("Prix (min. 1 €)") ?></a></h3><div><input type="text" id="_price" name="_price" value="0.00" required="required" class="ta-r" />€</div>
        <!-- état -->
        <h3><a href="#" id="_state_link"><?php t("Etat de l'objet") ?></a></h3>
        <div>
          <select id ="_state" name="_state" size="4">
            <option value="0" selected="selected"><?php t("Neuf") ?></option>
            <option value="1"><?php t("Comme neuf") ?></option>
            <option value="2"><?php t("Bon état") ?></option>
            <option value="3"><?php t("Mauvais état") ?></option>          
          </select>
        </div>
        <!-- mode de livraison -->
        <h3><a href="#" id="_shipping_type_link"><?php t("Mode de livraison") ?></a></h3>
        <div>
          <select id ="_shipping_type" name="_shipping_type" size="7">
            <option value="1" selected="selected"><?php t("Lettre") ?></option>
            <option value="2"><?php t("Colissimo") ?></option>
            <option value="3"><?php t("Lettre Max") ?></option>
            <option value="4"><?php t("Chronopost") ?></option>
            <option value="5"><?php t("UPS") ?></option>
            <option value="6"><?php t("FEDEX") ?></option>
            <option value="0"><?php t("Autre") ?></option>
          </select>
        </div>              
        <!-- Frais de port -->
        <h3><a href="#" id="_shipping_link"><?php t("Frais de port") ?></a></h3><div><input type="text" id="_shipping" name="_shipping" value="0.00" required="required" class="ta-r"/>€</div>
        <!-- Image(s) -->
        <h3><a href="#" id="uploaded_image_link"><?php t("Image au format JPEG uniquement < 2 Mo") ?> :</a></h3><div><input type="file" id="uploaded_image" name="uploaded_image" value=""/><input type="hidden" name="MAX_FILE_SIZE" value="2097152"><br /></div>
        <!-- Consignes -->
        <h3><a href="#"><?php t("Consignes à respecter") ?> :</a></h3>
        <div class="ta-l">
          <h2><?php t("Avant de mettre en vente votre article") ?> :</h2>
          <ol style="list-style: decimal;padding-left: 20px;">
            <li><?php t("Assurez-vous de disposer de l'article en stock avant de le vendre") ?></li>
            <li><?php t("Assurez-vous de son état (Neuf, comme-neuf, etc.)") ?></li>
            <li><?php t("Rassemblez tout le nécessaire à l'envoi du colis (lettre, timbres, étiquette etc.)") ?></li>          
          </ol><br />        
          <h2><?php t("Lorsque votre article est vendu") ?></h2>
          <ol style="list-style: decimal;padding-left: 20px;">
            <li><?php t("Accusez réception du paiement à l'acheteur") ?></li>
            <li><?php t("Informez l'acheteur de la date d'envoi et la date de réception estimée") ?>.</li>
            <li><p><span id="ui-alert"></span><?php t("Une fois vendu votre article n'est plus disponible sur Komunote") ?>.</p></li>
          </ol><br />        
          <p><span id="ui-alert"></span><?php t("Attention ! Tout vendeur ne respectant pas les consignes se verra radié de Komunote") ?>.</p>
        </div>

        <!-- Code de sécurité -->
        <h3 onclick="showPopup(this,'#infoCode');return false;"><a href="#" id="digicode_link"><?php t("Code de sécurité") ?></a></h3>
        <div><p><?php t("Taper le code suivant") ?> :</p><p><span id="codeVerif"  style="font-weight: 900;color:#0000ff;"><?php echo rand(1000, 9999); ?></span>&nbsp;<span id="codeSecu" style="font-weight: 900;color:#ff0000;"></span></p><div id="digicode"></div></div>    
        <!-- Bouton valider -->
        <h3><a href="#"><?php t("Validez votre email Paypal") ?></a></h3>
        <div><p><?php echo $_SESSION['user_email']; ?></p><br /><p><span id="ui-alert"></span><?php t("Par sécurité l'adresse email n'est pas modifiable !") ?></p><br />          
          <input type="submit" id="submitSellNew" value="<?php t("Mettre en vente") ?>" />
        </div>      
      </div>
    </form>

    <!-- Modale Code de sécurité -->
    <div id="infoCode" title="<?php t("Code de sécurité") ?>" class="hidden"><p><span id="ui-info"></span><?php t("Pour valider la mise en vente vous devez saisir le code ci-dessous") ?></p></div>
    <div id="divError" class="hidden"></div>
    <script type="text/javascript">
      <!--
      $(document).ready(function(){                                       
        $('#accordeon').accordion({autoHeight:false,animated: 'bounceslide'});
        //createDigicode('#digicode');
          
        $('#submitSellNew').click(function(){                    
          if (!$('#_price').val().match(formats.decimal.reg) || parseInt($('#_price').val()) < 1) {showError('#_price', formats.decimal.msg);return false;}
          if (!$('#_shipping').val().match(formats.decimal.reg)) {showError('#_shipping', formats.decimal.msg);return false;}        
          if(codeSecu !== 'ok') {$('#digicode_link').click();showPopup('#codeSecu','#infoCode');return false;}        
          $('#formSellNew').submit();
        });
        $('#uploaded_image').button();                  	  
      });    
      $('#formSellNew').get(0).focus();
      showError = function (sender, msg) {$('#divError').html(msg);$(sender+'_link').click();$(sender).addClass('ui-state-error');showPopup(sender,'#divError');}
      -->
    </script>

  <?php } else { ?>

    <div class="ui-state-error ui-corner-all"><span class="ui-icon ui-icon-alert" style="float: left"></span><?php t("Vous devez disposer d'un compte pour pouvoir acheter ou vendre") ?>.</div><br />
    <div><a id="link_signin" href="<?php t("./Inscription") ?>"><?php t("Je m'inscris, c'est gratuit") ?></a></div>
    <script type="text/javascript">
      $(function(){});
    </script>
  <?php
  }
}