<?php
if (CRON) {
  t("Informations");
  t("Achats");
  t("Ventes");
  t("Articles");
  t("Historique");
  t("Evaluations");
  t("Confirmation de la vente");  
  t("Vous êtes sur le point de confirmer définitivement que vous avez bien reçu votre paiement sur votre compte Paypal.");  
  t("Etes-vous certain(e) de confirmer ce paiement ?");
  t("Annulation de la vente");
  t("Vous êtes sur le point d'annuler définitivement la vente.");
  t("Confirmation de la suppression");
  t("Vous êtes sur le point de supprimer définitivement cet article.");
  t("Etes-vous certain(e) de confirmer cette suppression ?");
  t("Etes-vous certain(e) ?");
  t("Vendeur :");
  t("Acheteur :");
  t("Prix unitaire :");
  t("Frais de port :");
  t("Quantité :");
  t("Donner votre avis");
  t("Mon avis");
  t("Son avis");
  t("Ma réponse");
  t("Sa réponse :");
  t("Date paiement :");
  t("Répondre");
  t("Date :");
  t("Effectuer le paiement");
  t("Confirmer le paiement");
  t("Achat annulé le :");
  t("Annuler la commande");
  t("./Boutiques-paypal-form");
  t("./Paiement-succes-vendeur");
  t("./Evaluer-vendeur");
  t("./Evaluer-acheteur");
  t("./Paiement-annule");  
  t("./Vente-supprimer");
  t("Supprimer");
  t("");
  t("");
  t("");
} else {
  ?>
  <?php /* tabs */ ?>
  <div id="tabs">
    <ul>
      <li><a href="#tabs-infos"><?php t("Informations") ?></a></li>
      <li><a href="#tabs-shopping-list"><?php t("Achats") ?></a></li>
      <li><a href="#tabs-sales-list"><?php t("Ventes") ?></a></li>
      <li><a href="#tabs-items-list"><?php t("Articles") ?></a></li>
    </ul>
    <div id="tabs-infos"><p><?php echo $var_account['user']['pseudo'] ?></p><p><?php echo $var_account['kredit'] ?></p></div>
    <div id="tabs-shopping-list"><div id="tabs-shopping"><ul><li><a href="#tabs-shopping-histo"><?php t("Historique") ?></a></li><li><a href="#tabs-shopping-rating"><?php t("Evaluations") ?></a></li></ul><div id="tabs-shopping-histo"><div id="accordeon-shopping-list"></div></div><div id="tabs-shopping-rating"><div id="accordeon-shopping-rating-list"></div></div></div></div>
    <div id="tabs-sales-list"><div id="tabs-sales"><ul><li><a href="#tabs-sales-histo"><?php t("Historique") ?></a></li><li><a href="#tabs-sales-rating"><?php t("Evaluations") ?></a></li></ul><div id="tabs-sales-histo"><div id="accordeon-sales-list"></div></div><div id="tabs-sales-rating"><div id="accordeon-sales-rating-list"></div></div></div></div>    
    <div id="tabs-items-list"></div>
  </div>
  <?php /* modale */ ?>   
  <div id="shop_confirm_sale" title="<?php t("Confirmation de la vente") ?>" class="hidden">
    <p><?php t("Vous êtes sur le point de confirmer définitivement que vous avez bien reçu votre paiement sur votre compte Paypal.") ?></p>
    <p><?php t("Etes-vous certain(e) de confirmer ce paiement ?") ?></p></div>
  <div id="shop_cancel_sale" title="<?php t("Annulation de la vente") ?>" class="hidden">
    <p><?php t("Vous êtes sur le point d'annuler définitivement la vente.") ?></p>
    <p><?php t("Etes-vous certain(e) ?") ?></p></div>
<div id="shop_confirm_delete" title="<?php t("Confirmation de la suppression") ?>" class="hidden">
    <p><?php t("Vous êtes sur le point de supprimer définitivement cet article.") ?></p>
    <p><?php t("Etes-vous certain(e) de confirmer cette suppression ?") ?></p></div>

  <script type="text/javascript">
    $(function(){
      /* traduction  
      * 0 : français
      * 1 : anglais
      * 2 : japonais
      */
      t = {
        'Vendeur':{'0':"<?php t("Vendeur :") ?>",'1':"<?php t("Acheteur :") ?>"},
        'PU':{'0':"<?php t("Prix unitaire :") ?>",'1':"<?php t("Prix unitaire :") ?>"},
        'FDP':{'0':"<?php t("Frais de port :") ?>",'1':"<?php t("Frais de port :") ?>"},
        'Qte':{'0':"<?php t("Quantité :") ?>",'1':"<?php t("Quantité :") ?>"},
        'DonnerAvis':{'0':"<?php t("Donner votre avis") ?>",'1':"<?php t("Donner votre avis") ?>"},
        'SonAvis':{'0':"<?php t("Son avis :") ?>",'1':"<?php t("Mon avis :") ?>"},
        'MonAvis':{'0':"<?php t("Mon avis :") ?>",'1':"<?php t("Son avis :") ?>"},
        'MaReponse':{'0':"<?php t("Ma réponse :") ?>",'1':"<?php t("Sa réponse :") ?>"},
        'DatePaiement':{'0':"<?php t("Date paiement :") ?>",'1':"<?php t("Date paiement :") ?>"},
        'Repondre':{'0':"<?php t("Répondre") ?>",'1':"<?php t("Répondre") ?>"},
        'SaReponse':{'0':"<?php t("Sa réponse :") ?>",'1':"<?php t("Ma réponse :") ?>"},
        'Date':{'0':"<?php t("Date :") ?>",'1':"<?php t("Date :") ?>"},
        'EffectuerPaiement':{'0':"<?php t("Effectuer le paiement") ?>",'1':"<?php t("Confirmer le paiement") ?>"},
        'AchatAnnuleLe':{'0':"<?php t("Achat annulé le : ") ?>",'1':"<?php t("Achat annulé le : ") ?>"},
        'UrlBPF':{'0':"<?php t("./Boutiques-paypal-form") ?>",'1':"<?php t("./Paiement-succes-vendeur") ?>"},
        'UrlEV':{'0':"<?php t("./Evaluer-vendeur") ?>",'1':"<?php t("./Evaluer-acheteur") ?>"},
        'UrlPA':{'0':"<?php t("./Paiement-annule") ?>",'1':"<?php t("./Paiement-annule-vendeur") ?>"},
        'UrlDel':"<?php t("./Vente-supprimer") ?>",        
        'AnnulerCommande':{'0':"<?php t("Annuler la commande") ?>",'1':"<?php t("Annuler la commande") ?>"},         
        'SupprimerFiche':"<?php t("Supprimer") ?>"
      };
      generateAccount(
      [{
          'accordeon':'#accordeon-shopping-list',
          'accordeon_rating':'#accordeon-shopping-rating-list',
          'rows': <?php echo json_encode($var_account['shopping_list']) ?>,
          'cancelShopping': 'cancel_shopping_button_'
        },
        {
          'accordeon':'#accordeon-sales-list',
          'accordeon_rating':'#accordeon-sales-rating-list',
          'rows': <?php echo json_encode($var_account['sales_list']) ?>,
          'cancelShopping': 'cancel_sale_button_'
        }], 
        t);  
      
      var aItemsList = <?php echo json_encode($var_account['items_list']) ?>;
      var itemsBlock = $('#tabs-items-list');
      console.debug(aItemsList);
      for(var i in aItemsList){
        var row = aItemsList[i];
        var item={        
        'item':{
          'id':row.id_item,
          'description':row.description
          }
        };
        itemsBlock.append($('<p/>')
          .append($('<form id="form-'+row.id_item+'" action="'+t.UrlDel+'/'+row.id_item+'/'+row.id_user+'" method="POST" />')
            .append('<p><span class="fR cB"><span id="ui-info"></span>'+
              '<input id="confirm_delete_button_'+row.id_item+'" type="button" value="'+t.SupprimerFiche+'" /></span></p>'))
          .append($('<img src="images/shop/'+row.id_item+'/'+row.id_item+'.jpg" style="width:128px;height:auto;" onerror="this.src=\'images/komunote2.gif\'"/>'+
            '<p>'+row.description+'</span></p>'+
            '<p>'+t.PU[0]+' </span><span class="fR">'+row.unit_price+' &euro;</span></p>'+
            '<p>'+t.FDP[0]+' </span><span class="fR">'+row.unit_shipping_price+' &euro;</span></p>'))                                        
          .append($('<hr/>')
          
        ));
      }
      /* tabs */
      $('[id^="tabs"]').tabs();       
      /* accordéons */
      $('[id^="accordeon-"]').accordion({autoHeight:false,animated: 'bounceslide'});        
      $('[id^="confirm_sale_button_"]').click(function(){var senderId = $(this).attr('id');$('#shop_confirm_sale').dialog({modal: true,show: 'slide',width:480,buttons: {'Oui': function() {$('#'+senderId).parent().parent().parent().submit();$(this).dialog('close');},'Non': function() {$(this).dialog('close');}}});});            
      $('[id^="confirm_delete_button_"]').click(function(){var senderId = $(this).attr('id');$('#shop_confirm_delete').dialog({modal: true,show: 'slide',width:480,buttons: {'Oui': function() {$('#'+senderId).parent().parent().parent().submit();$(this).dialog('close');},'Non': function() {$(this).dialog('close');}}});});            
      $('[id^="cancel_sale_button_"], [id^="cancel_shopping_button_"]').click(function(){var senderId = $(this).attr('id');$('#shop_cancel_sale').dialog({modal: true,show: 'slide',width:480,buttons: {'Oui': function() {$('#'+senderId).parent().parent().parent().submit();$(this).dialog('close');},'Non': function() {$(this).dialog('close');}}});});            
      $('[name^="hisRating"]').addClass('blueC');$('[name^="myRating"]').addClass('blueF');        
    }); 
  </script>
  <?php
}