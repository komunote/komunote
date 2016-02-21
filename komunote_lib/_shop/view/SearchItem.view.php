<?php 
if (CRON) {
    t("Filtrer par :");
    t("Trier par :");
    t("Annuler");
    t("Prix min :");
    t("Prix max :");
    t("Prix croissant :");
    t("Prix décroissant :");
    t("Date la plus ancienne :");
    t("Date la plus récente :");
    t("");
    t("");
    t("");
    t("");
    t("");
    t("");
} else {
?>
<header></header>
<section>  
  <article>
      <div class="ui-state-highlight ui-corner-all" style="float:left;width:100%;height: auto;border: #ffffff;">
          <span><?php t("Mots clés séparés par des espaces.") ?></span><br />
        <form id="searchForm" action="<?php t("./Recherche") ?>" method="GET">
          <input type="text" name="keywords" value="<?php echo $var_keywords ?>" onfocus="this.select();" style="width: 80%"/><br />
          <input type="hidden" id="filters" name="filters" value=""/>    
          <input type="submit" value="<?php t("Rechercher")?>" />
          <input type="button" name="modaleFilter" link="#infoFiltre" value="<?php t("Filtrer/Trier")?>" />
        </form>
      </div>
  </article>
</section>
<footer></footer>

<h1><?php echo isset($var_records) ? $var_records : 0; ?> <?php t("résultat(s)") ?> :</h1><br />
<?php $filters = ShopMvcController::getFilters($var_filters);  ?>
<?php if (isset($var_matchingFiles)) { ?>
    <?php
    $nbPages = (int) ($var_records / 10);
    $i = (int) ($var_offset / 10);
    $previousPage = $i;
    $currentPage = $i + 1;
    $nextPage = $i + 2;
    $url = _t("./Recherche") . '?keywords=' . $var_keywords.'&filters='.$var_filters;//.'&filters=price_ASC__date_DESC__priceGt_50__priceLte_150';
        
    ?>
    <?php /* Affichage de la pagination */ ?>
    <?php if ($nbPages > 1) { ?>
        <div>
            <?php if ($currentPage > 1) { ?>
                <a id="linkFirstPage" href="<?php echo $url; ?>">&lt;</a>
                <a id="linkPage<?php echo $previousPage ?>" href="<?php echo $url . "&offset=" . (10 * ($previousPage - 1)); ?>"><?php echo $previousPage ?></a>
            <?php } ?>                                            
            <span id="linkCurrent" class="menuSelected"><?php echo $currentPage ?></span>                        
            <?php if ($currentPage < $nbPages ) { ?>
                <a id="linkPage<?php echo $nextPage ?>" href="<?php echo $url . "&offset=" . (10 * ($nextPage - 1)); ?>"><?php echo $nextPage ?></a>
                <a id="linkLastPage" href="<?php echo $url . "&offset=" . (10 * ($nbPages-1)); ?>">&gt;</a>                
            <?php } ?>
        </div>
    <?php } ?>

    <div class="ui-state-highlight ui-corner-all" style="float:left;width:100%;height: auto;border: #ffffff;">        
        <?php foreach ($var_matchingFiles as $row) { ?>
            <div class="ui-state-highlight ui-corner-all" style="float:left;width:315px;height: 360px;">
                <meta itemprop="url" content="http://<?php echo DB_HOST; ?>/<?php t("./Boutiques") ?>/<?php echo $row['id'] ?>/<?php echo rawurlencode($row['keywords']) ?>/" />
                <meta itemprop="description" content="<?php echo $row['keywords'] ?>" />
                <a 
                   href="<?php t("./Boutiques") ?>/<?php echo $row['id'] ?>/<?php echo rawurlencode($row['keywords']) ?>/">                                    
                    <span><img src="images/shop/<?php echo $row['id'] ?>/<?php echo $row['id'] ?>.jpg" style="width:128px;height:auto;" onerror="this.src='images/komunote2.gif'"/></span><br />
                    <span><?php echo $row['keywords'] ?></span><br /><br />                    
                    <span style="color:black;"><?php echo $row['unit_price'] ?> &euro;</span>
                </a>      
            </div>
        <?php } ?>                              
    </div>

    <!--<div id="accordeonMatching">
        <?php foreach($var_matchingFiles as $row) { ?>
            <h3><a href="#">
                <div style="float:left;width:200px"><?php echo $row['keywords'] ?></div>                
                <div style="color:black;float:right;width:100px"><?php echo $row['unit_price'] ?> &euro;</div>
                <img src="images/shop/<?php echo $row['id'] ?>/<?php echo $row['id'] ?>.jpg" style="width:128px;height:auto;" onerror="this.src='images/komunote2.gif'"/>
              </a>
            </h3>
            <div>
              <p><?php echo $row['description'] ?></p>
              <a id="link_<?php echo $row['id'] ?>" href="<?php t("./Boutiques") ?>/<?php echo $row['id'] ?>/<?php echo $row['keywords'] ?>/">
                <?php t("consulter cette page") ?>
              </a>
            </div>
        <?php } ?>
    </div>    -->
<?php } ?>

<?php /* Modale Filtre */?>
<div id="infoFiltre" title="<?php t("Filtrer et trier la recherche") ?>" class="hidden">
    <h2 class="cB fL"><?php t("Filtrer par :") ?></h2>
    <hr class="cB fL"/>
    <p><span class="cB fL"><?php t("Prix min :") ?></span>
        <span class="fR"><input id="prix_min" type="text" class="ta-r" value="<?php echo isset($filters['priceGt']) ? $filters['priceGt']:''?>"/></span></p>
    <p><span class="cB fL"><?php t("Prix max :") ?></span>
        <span class="fR"><input id="prix_max" type="text" class="ta-r" value="<?php echo isset($filters['priceLt']) ? $filters['priceLt']:'' ?>"/></span></p>

    <br />
    <h2 class="cB fL"><?php t("Trier par :") ?></h2>
    <hr class="cB fL"/>
    <p><span class="cB fL"><?php t("Prix croissant :") ?></span>
        <span class="fR"><input id="orderby_price1" name="orderby_price" type="radio" class="ta-r" value="ASC" <?php echo (isset($filters['price']) && $filters['price']=='ASC') ? 'checked':'';?>/></span></p>
    <p><span class="cB fL"><?php t("Prix décroissant :") ?></span>
        <span class="fR"><input id="orderby_price2" name="orderby_price" type="radio" class="ta-r" value="DESC" <?php echo (isset($filters['price']) && $filters['price']=='DESC') ? 'checked':'';?>/></span></p>
    <hr class="cB fL"/>
    <p><span class="cB fL"><?php t("Date la plus ancienne :") ?></span>
        <span class="fR"><input id="orderby_date1" name="orderby_date" type="radio" class="ta-r" value="ASC" <?php echo (isset($filters['date']) && $filters['date']=='ASC') ? 'checked':'';?>/></span></p>
    <p><span class="cB fL"><?php t("Date la plus récente :") ?></span>
        <span class="fR"><input id="orderby_date2" name="orderby_date" type="radio" class="ta-r" value="DESC" <?php echo (isset($filters['date']) && $filters['date']=='DESC') ? 'checked':'';?>/></span></p>

    <p></p>
</div>
<script src="script/search.js" type="text/javascript"></script>
<?php }