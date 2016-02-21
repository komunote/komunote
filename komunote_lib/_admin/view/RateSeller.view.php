<?php 
if (CRON) {
    t("Vendeur :");
    t("./Evaluer-vendeur-succes");
    t("Note :");
    t("Commentaire");
    t("Evaluer le vendeur");
    t("Votre réponse");
    t("Confirmez-vous votre réponse ?");
    t("");
    t("");
    t("");
    t("");
    t("");
    
} else {
?>
<h1><?php t("Vendeur :") ?> <?php echo $var_user['pseudo'] ?></h1><br />
<p><?php echo $var_item['description'] ?></p><br />    
<p><img src="images/shop/<?php echo $var_item['id'] ?>/<?php echo $var_item['id'] ?>.jpg" width="50px" height="50px" onerror="this.src='images/komunote2.gif'"/></p><br />

<form id="RateSellerForm" action="<?php t("./Evaluer-vendeur-succes") ?>" method="POST">       
    <div><?php t("Note :") ?> <span id="span-score">50</span> / 100<br /><br /><div id="slider"></div></div><br />
    <?php t("Commentaire") ?><br /><br />
    <textarea id="comment" name ="comment" rows="8"></textarea><br /><br />
    <span id="linkRateSeller"><?php t("Evaluer le vendeur") ?></span><br />
    <input type="hidden" id="score" name="score" value="50"/>   
    <input type="hidden" name="order" value="<?php echo htmlentities(json_encode($var_order)) ?>" />    
</form>

<div id="rate_confirm" title="<?php t("Votre réponse") ?>" class="hidden">
    <p><?php t("Confirmez-vous votre réponse ?") ?></p><br />    
</div>

<script type="text/javascript">
    $(document).ready(function(){        
        $('#slider').slider({
            animate:true,min:0,max:100,value:50,
            change:function(event, ui) {
                $('#span-score').html(ui.value).removeClass().addClass(ui.value <50 ? 'red' : (ui.value > 75 ?'blueC':''));                
                $('#score').val(ui.value);
            }});                
        
        $('#linkRateSeller').click(function(){
            $('#rate_confirm').dialog({
                modal: true,show: 'slide',width:480,
                buttons: {
                    'Oui': function() {$('#RateSellerForm').submit();$(this).dialog('close');},
                    'Non': function() {$(this).dialog('close');}}});
                });    
    });
</script>
<?php }