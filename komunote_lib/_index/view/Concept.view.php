<?php

if (CRON) {
  t("Achetez et vendez sans frais (*) sur Komunote.com");
  t("Pour des raisons de sécurité et de confiance Komunote a choisi Paypal comme unique moyen de paiement.");
  t("");
  t("(*) Paypal applique, indépendemment de Komunote, une commission en pourcentage (3.4% + 0.25 €) pour chaque vente effectuée à la charge du vendeur.");
  t("Komunote n'est aucunement lié à Paypal.");
  t("");
  t("");
  t("");
  t("");
  t("");
} else {
  ?>

<header><img src="images/online-store-icon-1.jpg" /></header>
<section>
  <article></article>
  <article><?php t("Achetez et vendez sans frais (*) sur Komunote.com");?></article>  
  <article><img src="images/selling.jpg" /></article>
  <article><?php t("Pour des raisons de sécurité et de confiance Komunote a choisi Paypal comme unique moyen de paiement.");?></article>
  <a href="http://www.paypal.com" target="_blank">
    <article><img src="images/logo-paypal.jpeg" /></article>
    <article><img src="images/paypal_icon.jpg" /></article>
  </a>
  <article><?php t("");?></article>
</section><br />
<footer>
  <section>
    <article><?php t("(*) Paypal applique, indépendemment de Komunote, une commission en pourcentage (3.4% + 0.25 €) pour chaque vente effectuée à la charge du vendeur.");?></article>
    <article><?php t("Komunote n'est aucunement lié à Paypal.");?></article>
  </section>  
</footer>
    
    
<?php
}