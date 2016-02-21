<?php
if (CRON) {
    t("Filtrer par :");
    t("");
    t("");
    t("");
    t("");
    t("");
    t("Date la plus ancienne :");
    t("Date la plus récente :");
    t("Articles dernièrement ajoutés.");
    t("Achetez et vendez gratuitement en toute simplicité.");
    t("");
} else {
    ?>
    <!--<header><img src="images/online-store-icon-1.jpg" /></header>-->
    <header></header>
    <section>
        <article>

    <!--<div class="ui-state-highlight ui-corner-all"><span class="ui-icon ui-icon-info" style="float: left"></span></div><br />-->
        </article>
        <article>
            <div class="ui-state-highlight ui-corner-all" style="float:left;width:100%;height: auto;border: #ffffff;">
                <span><?php t("Achetez et vendez gratuitement en toute simplicité.") ?></span><br />
                <form id="searchForm" action="<?php t("./Recherche") ?>" method="GET">
                    <input type="text" name="keywords" value="" onfocus="this.select();" style="width: 80%"/><br />
                    <input type="hidden" id="filters" name="filters" value=""/>    
                    <input type="submit" value="<?php t("Rechercher") ?>" />
                    <input name="modaleFilter" link="#infoFiltre" type="button" value="<?php t("Filtrer/Trier") ?>" />
                </form>
            </div><br />
            <?php /* Modale Filtre */ ?>
            <div id="infoFiltre" title="<?php t("Filtrer et trier la recherche") ?>" class="hidden">
                <h2 class="cB fL"><?php t("Filtrer par :") ?></h2>
                <hr class="cB fL"/>
                <p><span class="cB fL"><?php t("Prix min :") ?></span><span class="fR"><input id="prix_min" type="text" class="ta-r" value="0"/></span></p>
                <p><span class="cB fL"><?php t("Prix max :") ?></span><span class="fR"><input id="prix_max" type="text" class="ta-r" value="0"/></span></p>

                <br />
                <h2 class="cB fL"><?php t("Trier par :") ?></h2>
                <hr class="cB fL"/>
                <p><span class="cB fL"><?php t("Prix croissant :") ?></span><span class="fR"><input id="orderby_price1" name="orderby_price" type="radio" class="ta-r" value="ASC"/></span></p>
                <p><span class="cB fL"><?php t("Prix décroissant :") ?></span><span class="fR"><input id="orderby_price2" name="orderby_price" type="radio" class="ta-r" value="DESC"/></span></p>
                <hr class="cB fL"/>
                <p><span class="cB fL"><?php t("Date la plus ancienne :") ?></span><span class="fR"><input id="orderby_date1" name="orderby_date" type="radio" class="ta-r" value="ASC"/></span></p>
                <p><span class="cB fL"><?php t("Date la plus récente :") ?></span><span class="fR"><input id="orderby_date2" name="orderby_date" type="radio" class="ta-r" value="DESC"/></span></p>
            </div>
        </article>  
    </section>
    <footer>
        <section>                            
            <article>
                <div class="ui-state-highlight ui-corner-all" style="float:left;width:100%;height: auto;border: #ffffff;">
                    <span><?php t("Articles dernièrement ajoutés."); ?></span><br />
                    <?php foreach ($var_items as $row) { ?>
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
            </article>
        </section>
    </footer>

    
    <script src="script/search.js" type="text/javascript"></script>
    <?php
}?>