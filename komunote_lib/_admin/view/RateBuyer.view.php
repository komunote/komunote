<?php
if (CRON) {
  t("Acheteur :");
  t("./Evaluer-acheteur-succes");
  t("Sa note :");
  t("Son commentaire :");
  t("Votre réponse :");
  t("Répondre à l'acheteur");
  t("Confirmez-vous votre réponse ?");
  t("Seulement une seule réponse non modifiable sera prise en compte");
  t("");
  t("");
} else {
  ?>
  <h1><?php t("Acheteur :") ?> <?php echo $var_user['pseudo'] ?></h1><br />
  <p><?php echo $var_item['description'] ?></p><br />
  <p><img src="images/shop/<?php echo $var_item['id'] ?>/<?php echo $var_item['id'] ?>.jpg" width="50px" height="50px" onerror="this.src='images/komunote2.gif'"/></p><br />

  <form id="RateBuyerForm" action="<?php t("./Evaluer-acheteur-succes") ?>" method="POST">
    <p><?php t("Sa note :") ?></p>
    <p><?php echo $var_rating['score'] ?>/ 100</p><br />
    <p><?php t("Son commentaire :") ?></p>
    <p>"<?php echo $var_rating['comment'] ?>"</p><br />
    <p><?php t("Votre réponse :") ?><p/><br />
    <textarea id="comment" name ="comment" rows="8"></textarea><br /><br />
    <span id="linkRateBuyer"><?php t("Répondre à l'acheteur") ?></span><br />     
    <input type="hidden" name="order" value="<?php echo htmlentities(json_encode($var_order)) ?>" />    
  </form>

  <div id="rate_confirm" title="<?php t("Votre réponse :") ?>" class="hidden">
    <p><?php t("Confirmez-vous votre réponse ?") ?></p><br />    
    <p><span id="ui-info"></span><?php t("Seulement une seule réponse non modifiable sera prise en compte.") ?></p>        
  </div>

  <script type="text/javascript">
    <!--
    $(document).ready(function(){        
      $('#linkRateBuyer').click(function(){
        $('#rate_confirm').dialog({
          modal: true,show: 'slide',width:480,
          buttons: {
            'Oui': function() {$('#RateBuyerForm').submit();$(this).dialog('close');},
            'Non': function() {$(this).dialog('close');}}});});    
    });
    -->
  </script>
<?php
}