<?php
/* ==========================================================================
   DYNAMICKA STRANKA INGREDIENCI PRO PIZZA OD KURETE (INGREDIENCE.PHP)
   Vsechny nazvy trid, ID a komentarov jsou v cestine bez diakritiky
   ========================================================================== */

require_once __DIR__ . '/databaze.php';

$suroviny = ziskatSuroviny();
$pizzy = ziskatPizzy();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kvalitní Ingredience | PIZZA OD KUŘETE</title>
  <meta name="description" content="Poznejte původ a kvalitu našich surovin pro pizzu. Čerstvé sýry D.O.P. z Itálie, lokální kuřecí maso a 48 hodin zrající těsto.">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styl.css?v=<?php echo time(); ?>">

  <script>
    window.seznamSurovinZDatabaze = <?php echo json_encode($suroviny, JSON_UNESCAPED_UNICODE); ?>;
    window.seznamPizzZDatabaze = <?php echo json_encode($pizzy, JSON_UNESCAPED_UNICODE); ?>;
  </script>
</head>
<body>

  <!-- HORNI LISTA S KAPSULOVYM PREPINACEM A TELEFONEM POD NIM -->
  <header class="horni-kontakty-lista">
    <div class="kontejner kontakty-obsah-stredovy">
      
      <div style="display: flex; align-items: center; gap: 15px;">
        <a href="index.php" class="tlacitko tlacitko-cervene" style="padding: 6px 16px; font-size: 0.85rem;">&lsaquo; Zpět na Jídelní lístek</a>
        
        <!-- KAPSULOVY PREPINAC -->
        <div class="kapsle-prepinac-pobocky" data-aktivni="rychnov">
          <div class="kapsle-slajdr-bily"></div>
          <button class="kapsle-polozka" data-pobocka-kod="rychnov" onclick="prepnoutPobocku('rychnov')">Rychnov n.K.</button>
          <button class="kapsle-polozka" data-pobocka-kod="usti" onclick="prepnoutPobocku('usti')">Ústí n.O.</button>
        </div>
      </div>

      <!-- TELEFON POD SWITCHEM -->
      <div class="telefon-pod-switchem" id="horni-aktivni-kontakt">
        <a href="tel:739149142" class="telefonni-odkaz"><svg width="18" height="18" viewBox="0 0 24 24" fill="#ffffff" style="margin-right: 6px; vertical-align: -2px;"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.02-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>739 149 142</a>
      </div>

    </div>
  </header>

  <!-- UVODNI HERO SEKCE INGREDIENCI -->
  <section class="sekce-uvod-ingredience">
    <div class="kontejner" style="max-width: 900px;">
      <h1 class="nadpis-hlavni" style="font-size: 3rem;">TAJEMSTVÍ NAŠÍ CHUTI LEŽÍ V SUROVINÁCH</h1>
      <p class="podnadpis-hlavni" style="font-size: 1.3rem; margin-top: 10px;">Žádné náhrady. Pouze poctivé ingredience s certifikovaným původem z Itálie a od českých farmářů.</p>
    </div>
  </section>

  <!-- FILTROVANI SUROVIN A MRIZKA -->
  <section class="sekce-jidelni-listek" style="background-color: var(--barva-pozadi-tmava);">
    <div class="kontejner">
      
      <div class="skupina-kategorii" style="margin-bottom: 40px;">
        <button class="tlacitko-kategorie tlacitko-kategorie-surovin aktivni" data-kategorie="vse">VŠECHNY SUROVINY</button>
        <button class="tlacitko-kategorie tlacitko-kategorie-surovin" data-kategorie="syry">ITÁLSKÉ SÝRY</button>
        <button class="tlacitko-kategorie tlacitko-kategorie-surovin" data-kategorie="maso">MASO & UZENINY</button>
        <button class="tlacitko-kategorie tlacitko-kategorie-surovin" data-kategorie="zelenina">ZELENINA & BYLINKY</button>
        <button class="tlacitko-kategorie tlacitko-kategorie-surovin" data-kategorie="omacky">OMÁČKY & TĚSTO</button>
        <button class="tlacitko-kategorie tlacitko-kategorie-surovin" data-kategorie="specialni">SPECIÁLNÍ</button>
      </div>

      <div class="mrizka-ingredienci" id="mrizka-ingredienci-kontejner">
        <!-- Vykresluje ingredience.js -->
      </div>

    </div>
  </section>

  <!-- MODALNI OKNO DETAILU SUROVINY -->
  <div class="modalni-okno-pozadi" id="modal-detail-suroviny">
    <div class="modalni-okno-obsah">
      <button class="zavrit-modal" onclick="zavritModalSuroviny()">&times;</button>
      <div id="modal-detail-suroviny-obsah">
        <!-- Obsah plni JS -->
    </div>
  </div>

  <script src="aplikace.js?v=<?php echo time(); ?>"></script>
  <script src="ingredience.js?v=<?php echo time(); ?>"></script>
</body>
</html>
