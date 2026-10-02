<?php
/* ==========================================================================
   DYNAMICKA STRANKA INGREDIENCI PRO PIZZA OD KURETE (INGREDIENCE.PHP)
   Vsechny nazvy trid, ID a komentarov jsou v cestine bez diakritiky
   ========================================================================== */

require_once __DIR__ . '/nastaveni.php';
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

  <!-- 1. UNIVERZÁLNÍ PLNOHODNOTNÁ NAVIGAČNÍ LIŠTA PRO PODSTRÁNKY -->
  <header class="horni-kontakty-lista">
    <div class="navigace-podstranka-obsah">
      
      <!-- BRAND & LOGO (VLEVO: KUŘE + GRAFICKÝ NÁPIS PIZZA OD KUŘETE) -->
      <a href="index.php" class="navigace-brand-obal" title="Zpět na hlavní stránku Pizza od Kuřete">
        <img src="media/logo/logo-pizza.png" alt="Kuře Pizza od Kuřete" class="navigace-brand-logo" style="height: 50px; width: auto; max-width: 55px; object-fit: contain; display: block;">
        <img src="media/logo/napis-pizza-od-kurete.png" alt="PIZZA OD KUŘETE" class="navigace-brand-napis-img" style="height: 40px; width: auto; display: block; object-fit: contain;">
      </a>

      <!-- PRAVÁ ČÁST: MENU POLOŽKY (ZAROVNANÉ DOPRAVA) + IKONA TELEFONU + HAMBURGER -->
      <div class="navigace-prava-skupina">
        
        <!-- HLAVNÍ NAVIGAČNÍ MENU (NA MOBILU SE SKRÝVÁ DO HAMBURGERU) -->
        <ul class="navigace-odkazy-menu" id="navigace-odkazy-menu">
          <li><a href="index.php#jidelni-listek" class="navigace-odkaz-polozka">Jídelní lístek</a></li>
          <li><a href="ingredience.php" class="navigace-odkaz-polozka aktivni">Ingredience</a></li>
          <li><a href="index.php#provozovny" class="navigace-odkaz-polozka">Pizzerie</a></li>
          <li><a href="index.php#rozvoz" class="navigace-odkaz-polozka">Rozvoz</a></li>
        </ul>

        <!-- AKČNÍ PRVKY: TELEFON S DROPDOWNEM + HAMBURGER -->
        <div class="navigace-akce-obal">
          
          <!-- IKONA SLUCHÁTKA -->
          <button type="button" class="tlacitko-telefon-ikona" id="btn-otevrit-volani" onclick="prepnoutPodoknoVolani(event)" title="Zobrazit telefonní čísla na pobočky">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.02-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
            </svg>
          </button>

          <!-- HAMBURGER TLAČÍTKO PRO MOBILY -->
          <button type="button" class="tlacitko-hamburger" id="btn-mobil-menu" onclick="prepnoutMobilMenu(event)" aria-label="Otevřít menu">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="3" y1="12" x2="21" y2="12"></line>
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
          </button>

          <!-- VYSKAKOVACÍ PODOKNO S VÝBĚREM TELEFONU -->
          <div class="podokno-volani-pobocky" id="podokno-volani-box">
            <div class="podokno-volani-zahlavi">Zavolejte nám:</div>
            <a href="tel:739149142" class="podokno-polozka-volani">
              <div>
                <span class="podokno-polozka-nazev">Rychnov nad Kněžnou</span>
                <span class="podokno-polozka-cislo">739 149 142</span>
              </div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="color: #f59e0b;"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.02-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
            </a>
            <a href="tel:774741818" class="podokno-polozka-volani">
              <div>
                <span class="podokno-polozka-nazev">Ústí nad Orlicí</span>
                <span class="podokno-polozka-cislo">774 741 818</span>
              </div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="color: #f59e0b;"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.02-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
            </a>
          </div>

        </div>

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

  <script src="js/aplikace.js?v=<?php echo time(); ?>"></script>
  <script src="js/ingredience.js?v=<?php echo time(); ?>"></script>
</body>
</html>
