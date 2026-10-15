<?php
/* ==========================================================================
   DYNAMICKA HLAVNI STRANKA PRO PIZZA OD KURETE (INDEX.PHP)
   Vsechny nazvy trid, ID a komentarov jsou v cestine bez diakritiky
   ========================================================================== */

require_once __DIR__ . '/nastaveni.php';
require_once __DIR__ . '/databaze.php';

$pizzy = ziskatPizzyProVerejnost();
$suroviny = ziskatSuroviny();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PIZZA OD KUŘETE | Přijďte si zobnout</title>
  <meta name="description" content="Pizzerie a rozvoz pizzy v Rychnově nad Kněžnou a Ústí nad Orlicí. Čerstvé ingredience, pravé italské sýry a skvělá pizza.">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styl.css?v=<?php echo filemtime(__DIR__ . '/styl.css'); ?>">
  
  <script>
    // Predani databazovych pizz a surovin z PHP do JavaScriptu
    window.seznamPizzZDatabaze = <?php echo json_encode($pizzy, JSON_UNESCAPED_UNICODE); ?>;
    window.seznamSurovinZDatabaze = <?php echo json_encode($suroviny, JSON_UNESCAPED_UNICODE); ?>;
  </script>
</head>
<body class="stranka-index">

  <!-- 1. UNIVERZÁLNÍ PLNOHODNOTNÁ NAVIGAČNÍ LIŠTA -->
  <header class="horni-kontakty-lista horni-lista-index">
    <div class="navigace-podstranka-obsah">
      
      <!-- BRAND & LOGO (VLEVO: KUŘE + GRAFICKÝ NÁPIS PIZZA OD KUŘETE) -->
      <a href="index.php" class="navigace-brand-obal" title="Pizza od Kuřete">
        <img src="media/logo/logo-pizza.png" alt="Kuře Pizza od Kuřete" class="navigace-brand-logo" style="height: 50px; width: auto; max-width: 55px; object-fit: contain;">
        <img src="media/logo/napis-pizza-od-kurete.png" alt="PIZZA OD KUŘETE" class="navigace-brand-napis-img" style="height: 40px; width: auto; object-fit: contain;">
      </a>

      <!-- PRAVÁ ČÁST: MENU POLOŽKY (ZAROVNANÉ DOPRAVA) + IKONA TELEFONU + HAMBURGER -->
      <div class="navigace-prava-skupina">
        
        <!-- HLAVNÍ NAVIGAČNÍ MENU (NA MOBILU SE SKRÝVÁ DO HAMBURGERU) -->
        <ul class="navigace-odkazy-menu" id="navigace-odkazy-menu">
          <li><a href="#jidelni-listek" class="navigace-odkaz-polozka">Jídelní lístek</a></li>
          <li><a href="nase-pizza.php" class="navigace-odkaz-polozka">Naše pizza</a></li>
          <li><a href="#provozovny" class="navigace-odkaz-polozka">Pizzerie</a></li>
          <li><a href="#rozvoz" class="navigace-odkaz-polozka">Rozvoz</a></li>
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

  <!-- 2. HERO SEKCE - UVOD -->
  <section class="sekce-uvod-hero" id="uvod">
    <div class="obsah-hero">
      
      <!-- Logo Kuřete na Pizze (Odkaz do administrace) -->
      <a href="administrace.php" class="logo-kure-ikona" title="Správa menu (Administrace)">
        <img src="media/logo/logo-pizza.png?v=<?php echo filemtime(__DIR__ . '/media/logo/logo-pizza.png'); ?>" alt="PIZZA OD KUŘETE Logo">
      </a>

      <h1 class="nadpis-hlavni">PIZZA OD KUŘETE</h1>
      <p class="podnadpis-hlavni">přijďte si zobnout</p>
      
      <div class="oteviraci-doba-stitek">
        pondělí - sobota: 11:00 ~ 21:00
      </div>

      <div class="tlacitka-hlavicka-skupina">
        <!-- 1. ŘADA: Pizzerie, Rozvoz, Jídelní lístek -->
        <div class="appky-rada appky-rada-1">
          <!-- Appka 1: Pizzerie -->
          <a href="#provozovny" class="tlacitko-app-polozka appka-pizzerie" title="Pizzerie a pobočky">
            <div class="appka-ikona-box">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
              </svg>
            </div>
            <span class="appka-popisek">Pizzerie</span>
          </a>

          <!-- Appka 2: Rozvoz (ikona: osobní auto) -->
          <a href="#rozvoz" class="tlacitko-app-polozka appka-rozvoz" title="Rozvoz pizzy">
            <div class="appka-ikona-box">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.5 2.8C2.1 10.7 2 11 2 11.4V16c0 .6.4 1 1 1h2"></path>
                <circle cx="7" cy="17" r="2"></circle>
                <path d="M9 17h6"></path>
                <circle cx="17" cy="17" r="2"></circle>
              </svg>
            </div>
            <span class="appka-popisek">Rozvoz<span class="desktop-text-jen"> pizzy</span></span>
          </a>

          <!-- Appka 3: Jídelní lístek (ikona: otevřené menu) -->
          <a href="#jidelni-listek" class="tlacitko-app-polozka appka-listek" title="Jídelní lístek">
            <div class="appka-ikona-box">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
              </svg>
            </div>
            <span class="appka-popisek">Jídelní lístek</span>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- 3. SEKCE: BENEFITY / PŘEDNOSTI (PŮVODNĚ ROZDÍL V CHUTI) -->
  <section class="sekce-benefity sekce-rozdil-v-chuti">
    <!-- 1. HLAVNÍ BANNER: PŘEDSTAVENÍ ŠÉFPIZZAŘE A MAJITELE KUŘETE -->
    <div class="kure-predstaveni-banner">
      <div class="kure-predstaveni-kontejner">
        <div class="kure-predstaveni-foto-box">
          <img src="https://images.unsplash.com/photo-1577219491135-ce391730fb2c?q=80&w=800&auto=format&fit=crop" alt="Šéfpizzař a majitel Kuře" class="kure-predstaveni-foto" loading="lazy">
        </div>
        <div class="kure-predstaveni-obsah">
          <span class="kure-predstaveni-stitek">MAJITEL &amp; HLAVNÍ PIZZAŘ</span>
          <h2 class="kure-predstaveni-nadpis">ROZDÍL POZNÁTE NA PRVNÍ ZOBNUTÍ</h2>
          <p class="kure-predstaveni-text">Kuře má největší štěstí na kamarády, hlavně na Krtka, kterého považuje za genia srovnatelného jen Járou Cimrmanem... a to ještě ztěží</p>
          <a href="nase-pizza.php" class="tlacitko tlacitko-cervene kure-predstaveni-tlacitko">
            <span>Příběh naší pizzy</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
          </a>
        </div>
      </div>
    </div>

    <!-- 2. SUROVINY POD BANNEREM (na desktopu 3 v řadě, na mobilu touch-carousel) -->
    <div class="benefity-obal rozdil-suroviny-obal">
      <div class="benefity-pas rozdil-suroviny-pas">
        
        <!-- Položka 1: Těsto -->
        <a href="nase-pizza.php#pribeh-pizzy-sekce" class="benefity-karta rozdil-surovina-karta" title="Přejít na detail o těstě">
          <div class="benefity-karta-foto rozdil-surovina-foto">
            <img src="https://images.unsplash.com/photo-1579684947550-22e945225d9a?q=80&w=600&auto=format&fit=crop" alt="Těsto je alfa a omega" loading="lazy">
            <span class="benefity-karta-cislo rozdil-surovina-cislo">01</span>
          </div>
          <div class="benefity-karta-obsah rozdil-surovina-obsah">
            <div class="benefity-karta-nadpis rozdil-surovina-nadpis">TĚSTO JE ALFA A OMEGA</div>
          </div>
        </a>

        <!-- Položka 2: Pec -->
        <a href="nase-pizza.php#pribeh-pizzy-sekce" class="benefity-karta rozdil-surovina-karta" title="Přejít na detail o peci">
          <div class="benefity-karta-foto rozdil-surovina-foto">
            <img src="https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?q=80&w=600&auto=format&fit=crop" alt="Pizza nesmí v peci klepat kosu" loading="lazy">
            <span class="benefity-karta-cislo rozdil-surovina-cislo">02</span>
          </div>
          <div class="benefity-karta-obsah rozdil-surovina-obsah">
            <div class="benefity-karta-nadpis rozdil-surovina-nadpis">PIZZA NESMÍ V PECI KLEPAT KOSU</div>
          </div>
        </a>

        <!-- Položka 3: Suroviny -->
        <a href="nase-pizza.php#pribeh-pizzy-sekce" class="benefity-karta rozdil-surovina-karta" title="Přejít na detail o surovinách">
          <div class="benefity-karta-foto rozdil-surovina-foto">
            <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=600&auto=format&fit=crop" alt="Suroviny pro vybíravé" loading="lazy">
            <span class="benefity-karta-cislo rozdil-surovina-cislo">03</span>
          </div>
          <div class="benefity-karta-obsah rozdil-surovina-obsah">
            <div class="benefity-karta-nadpis rozdil-surovina-nadpis">SUROVINY PRO VYBÍRAVÉ</div>
          </div>
        </a>

      </div>
    </div>
  </section>

  <!-- 4. SEKCE: JIDELNI LISTEK -->
  <section class="sekce-jidelni-listek" id="jidelni-listek">
    <div class="kontejner">
      <div class="hlavicka-jidelniku">
        <h2 class="nadpis-sekce">JÍDELNÍ LÍSTEK</h2>
        <p class="podnadpis-vyberte">Vyberte si podle chuti</p>
      </div>

      <!-- Obale pro filtry -->
      <div class="obalo-filtru">
        
        <!-- 4 Kapslove Switche (Pouze 2 moznosti) -->
        <div class="mrizka-prepinacu-filtrum">
          <!-- Switch 1: Maso -->
          <div class="mini-kapsle-prepinac" id="sw-maso">
            <button type="button" class="mini-kapsle-tlacitko" data-hodnota="masite" onclick="nastavitPrepinac('sw-maso', 'masite')">S masem</button>
            <button type="button" class="mini-kapsle-tlacitko" data-hodnota="bezmase" onclick="nastavitPrepinac('sw-maso', 'bezmase')">Bez masa</button>
          </div>

          <!-- Switch 2: Palivost -->
          <div class="mini-kapsle-prepinac" id="sw-palivost">
            <button type="button" class="mini-kapsle-tlacitko" data-hodnota="nepaliva" onclick="nastavitPrepinac('sw-palivost', 'nepaliva')">Nepálivá</button>
            <button type="button" class="mini-kapsle-tlacitko" data-hodnota="paliva" onclick="nastavitPrepinac('sw-palivost', 'paliva')">Pálivá</button>
          </div>

          <!-- Switch 3: Zaklad -->
          <div class="mini-kapsle-prepinac" id="sw-zaklad">
            <button type="button" class="mini-kapsle-tlacitko" data-hodnota="sugo" onclick="nastavitPrepinac('sw-zaklad', 'sugo')">Sugo</button>
            <button type="button" class="mini-kapsle-tlacitko" data-hodnota="bily" onclick="nastavitPrepinac('sw-zaklad', 'bily')">Bílý základ</button>
          </div>

        </div>

        <div class="skupina-kategorii">
          <button class="tlacitko-kategorie" data-kategorie="nejprodavanejsi">NEJPRODÁVANĚJŠÍ</button>
          <button class="tlacitko-kategorie" data-kategorie="doporucujeme">DOPORUČUJEME</button>
        </div>

      </div>

      <!-- Mrizka Karty Pizz -->
      <div class="mrizka-pizz" id="mrizka-pizz-kontejner">
        <!-- Vykresluje se dynamicky z aplikace.js -->
      </div>
    </div>
  </section>

  <!-- 5. SEKCE: ROZVOZ -->
  <section class="sekce-rozvoz" id="rozvoz">
    <div class="kontejner">
      <h2 class="nadpis-sekce">ROZVOZ</h2>
      <p class="podnadpis-rozvozu">PIZZU VÁM RÁDI DOVEZEME DO <span>10 KM</span></p>

      <!-- 3 Informační karty bez ikon -->
      <div class="mrizka-karet-rozvozu">
        <div class="karta-rozvoz-info karta-cena-dopravy">
          <h3 class="nadpis-karty-rozvoz">Cena dopravy</h3>
          <p class="hodnota-karty-rozvoz">Po městě: <strong>+ 10 Kč</strong></p>
          <p class="popis-karty-rozvoz">Mimo město: <strong>+ 7 Kč / 1 km</strong></p>

          <!-- Vyhledávací pole obcí pro zjištění ceny dopravy přímo uvnitř této karty -->
          <div class="rozvoz-vyhledavani-mobil-obal rozvoz-vyhledavani-karta-obal">
            <input type="text" id="vstup-hledat-obec-mobil" class="vstup-vyhledat-obec vstup-vyhledat-mobil" placeholder="vložte jméno obce" oninput="filtrovatAktivniRozvozObce(this.value, true)">
            
            <div class="mrizka-stiku-obci mrizka-vyhledavani-vysledky-mobil" id="vysledky-obci-mobil" style="display: none;">
              <!-- Rychnov obce -->
              <span class="stitek-obec-polozka skryto" data-obec="Bílý Újezd"><strong>Bílý Újezd</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Brocná"><strong>Brocná</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Byzhradec"><strong>Byzhradec</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Častolovice"><strong>Častolovice</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Černíkovice"><strong>Černíkovice</strong> <span class="stitek-vzdalenost">8 km</span> <span class="cena-obce-stitek">56 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Debřece"><strong>Debřece</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Dlouhá Ves"><strong>Dlouhá Ves</strong> <span class="stitek-vzdalenost">4 km</span> <span class="cena-obce-stitek">28 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Domašín"><strong>Domašín</strong> <span class="stitek-vzdalenost">5 km</span> <span class="cena-obce-stitek">35 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Doudleby nad Orlicí"><strong>Doudleby nad Orlicí</strong> <span class="stitek-vzdalenost">8 km</span> <span class="cena-obce-stitek">56 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Hláska"><strong>Hláska</strong> <span class="stitek-vzdalenost">9 km</span> <span class="cena-obce-stitek">63 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Hroška"><strong>Hroška</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Jahodov"><strong>Jahodov</strong> <span class="stitek-vzdalenost">6 km</span> <span class="cena-obce-stitek">42 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Jaroslav"><strong>Jaroslav</strong> <span class="stitek-vzdalenost">6 km</span> <span class="cena-obce-stitek">42 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Javornice"><strong>Javornice</strong> <span class="stitek-vzdalenost">7 km</span> <span class="cena-obce-stitek">49 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Javornice - Betlém"><strong>Javornice - Betlém</strong> <span class="stitek-vzdalenost">7 km</span> <span class="cena-obce-stitek">49 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Ještětice"><strong>Ještětice</strong> <span class="stitek-vzdalenost">8 km</span> <span class="cena-obce-stitek">56 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Kostelec nad Orlicí"><strong>Kostelec nad Orlicí</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Kvasiny"><strong>Kvasiny</strong> <span class="stitek-vzdalenost">8 km</span> <span class="cena-obce-stitek">56 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Ledská"><strong>Ledská</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Libel"><strong>Libel</strong> <span class="stitek-vzdalenost">7 km</span> <span class="cena-obce-stitek">49 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Liberk"><strong>Liberk</strong> <span class="stitek-vzdalenost">8 km</span> <span class="cena-obce-stitek">56 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Lično"><strong>Lično</strong> <span class="stitek-vzdalenost">9 km</span> <span class="cena-obce-stitek">63 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Lipovka"><strong>Lipovka</strong> <span class="stitek-vzdalenost">3 km</span> <span class="cena-obce-stitek">21 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Litohrady"><strong>Litohrady</strong> <span class="stitek-vzdalenost">4 km</span> <span class="cena-obce-stitek">28 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Lokot"><strong>Lokot</strong> <span class="stitek-vzdalenost">3 km</span> <span class="cena-obce-stitek">21 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Lukavice"><strong>Lukavice</strong> <span class="stitek-vzdalenost">7 km</span> <span class="cena-obce-stitek">49 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Lupenice"><strong>Lupenice</strong> <span class="stitek-vzdalenost">5 km</span> <span class="cena-obce-stitek">35 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Malá Ledská"><strong>Malá Ledská</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Merklovice"><strong>Merklovice</strong> <span class="stitek-vzdalenost">9 km</span> <span class="cena-obce-stitek">63 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Panská Habrová"><strong>Panská Habrová</strong> <span class="stitek-vzdalenost">4 km</span> <span class="cena-obce-stitek">28 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Peklo nad Zdobnicí"><strong>Peklo nad Zdobnicí</strong> <span class="stitek-vzdalenost">6 km</span> <span class="cena-obce-stitek">42 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Přím"><strong>Přím</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Roveň"><strong>Roveň</strong> <span class="stitek-vzdalenost">5 km</span> <span class="cena-obce-stitek">35 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Skuhrov nad Bělou"><strong>Skuhrov nad Bělou</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Slemeno"><strong>Slemeno</strong> <span class="stitek-vzdalenost">5 km</span> <span class="cena-obce-stitek">35 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Solnice"><strong>Solnice</strong> <span class="stitek-vzdalenost">5 km</span> <span class="cena-obce-stitek">35 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Synkov"><strong>Synkov</strong> <span class="stitek-vzdalenost">7 km</span> <span class="cena-obce-stitek">49 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Třebešov"><strong>Třebešov</strong> <span class="stitek-vzdalenost">6 km</span> <span class="cena-obce-stitek">42 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Tutleky"><strong>Tutleky</strong> <span class="stitek-vzdalenost">8 km</span> <span class="cena-obce-stitek">56 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Vamberk"><strong>Vamberk</strong> <span class="stitek-vzdalenost">7 km</span> <span class="cena-obce-stitek">49 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Záměl"><strong>Záměl</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
              <!-- Ústí obce -->
              <span class="stitek-obec-polozka skryto" data-obec="Černovír"><strong>Černovír</strong> <span class="stitek-vzdalenost">Zóna 2</span> <span class="cena-obce-stitek">50 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Česká Třebová"><strong>Česká Třebová</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="České Libchavy"><strong>České Libchavy</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Dlouhá Třebová"><strong>Dlouhá Třebová</strong> <span class="stitek-vzdalenost">Zóna 2</span> <span class="cena-obce-stitek">50 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Dolní Dobrouč"><strong>Dolní Dobrouč</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Dolní Houžovec"><strong>Dolní Houžovec</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Dolní Libchavy"><strong>Dolní Libchavy</strong> <span class="stitek-vzdalenost">Zóna 2</span> <span class="cena-obce-stitek">50 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Džbánov"><strong>Džbánov</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Horní Houžovec"><strong>Horní Houžovec</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Horní Libchavy"><strong>Horní Libchavy</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Hrádek"><strong>Hrádek</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Jehnědí"><strong>Jehnědí</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Kerhartice"><strong>Kerhartice</strong> <span class="stitek-vzdalenost">Zóna 2</span> <span class="cena-obce-stitek">50 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Knapovec"><strong>Knapovec</strong> <span class="stitek-vzdalenost">Zóna 2</span> <span class="cena-obce-stitek">50 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Lanšperk"><strong>Lanšperk</strong> <span class="stitek-vzdalenost">Zóna 2</span> <span class="cena-obce-stitek">50 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Orlické Podhůří"><strong>Orlické Podhůří</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Přívrat"><strong>Přívrat</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Rozsocha"><strong>Rozsocha</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Rviště"><strong>Rviště</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Říčky"><strong>Říčky</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Skuhrov"><strong>Skuhrov</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Sloupnice"><strong>Sloupnice</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
              <span class="stitek-obec-polozka skryto" data-obec="Sudislav nad Orlicí"><strong>Sudislav nad Orlicí</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            </div>
          </div>
        </div>

        <div class="karta-rozvoz-info">
          <h3 class="nadpis-karty-rozvoz">Minimální objednávka</h3>
          <p class="hodnota-karty-rozvoz"><strong>2 pizzy</strong></p>
          <p class="popis-karty-rozvoz">Vždy čerstvé a horké přímo z pece</p>
        </div>

        <div class="karta-rozvoz-info">
          <h3 class="nadpis-karty-rozvoz">Způsob platby</h3>
          <p class="hodnota-karty-rozvoz"><strong>Hotově i kartou</strong></p>
          <p class="popis-karty-rozvoz">Platební terminál má řidič vždy u sebe</p>
        </div>
      </div>

      <!-- Akční blok: Velká kulatá ikona telefonu a tlačítko pro rozbalení mapy -->
      <div class="akce-rozvoz-blok">
        <div class="obal-tlacitka-volat-rozvoz">
          <button type="button" class="kruhove-tlacitko-volat" id="btn-rozvoz-volani" onclick="prepnoutPodoknoVolaniRozvoz(event)" title="Zavolat na provozovnu">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="currentColor">
              <path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.02-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
            </svg>
          </button>

          <!-- Vyskakovací podokno přivázané přímo k tomuto tlačítku -->
          <div class="podokno-volani-pobocky podokno-volani-rozvoz" id="podokno-volani-rozvoz-box">
            <div class="podokno-volani-zahlavi">Zavolejte nám pro rozvoz:</div>
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

        <!-- Tlačítko pro rozbalení/sbalení celé sekce mapy (dočasně zneaktivněno) -->
        <div style="margin-top: 22px; text-align: center; display: none;">
          <button type="button" class="tlacitko-mapa-rozbalit" id="btn-toggle-rozvoz-mapa" onclick="prepnoutRozbaleniRozvozoveMapy()">
            <span>Cena rozvozu mimo město</span>
            <svg class="ikona-sipka-rozbaleni" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Celošířkový tmavý blok pro mapu se switchem a vyhledáváním (výchozí stav: skrytý, na celou šířku obrazovky s ostrými hranami) -->
    <div class="rozvoz-mapa-hlavni-blok" id="rozvoz-mapa-velky-blok" style="display: none;">
      
      <!-- Plovoucí tooltip s cenou a km při najetí myší na PC -->
      <div id="rozvoz-mapa-tooltip" class="rozvoz-mapa-tooltip">
        <span class="tooltip-obec-nazev"></span>
        <div class="tooltip-obec-detaily">
          <span class="tooltip-obec-km"></span>
          <span class="tooltip-obec-cena"></span>
        </div>
      </div>
          
      <!-- Switch s přepínáním měst -->
      <div class="rozvoz-switch-obal">
            <div class="kapsle-prepinac-pobocky" id="prepinac-rozvoz-mapa" data-aktivni="rychnov">
              <div class="kapsle-slajdr-bily"></div>
              <button type="button" class="kapsle-polozka aktivni" data-pobocka-kod="rychnov" onclick="prepnoutPobockuRozvozMapy('rychnov')">Rychnov n. K.</button>
              <button type="button" class="kapsle-polozka" data-pobocka-kod="usti" onclick="prepnoutPobockuRozvozMapy('usti')">Ústí nad Orlicí</button>
            </div>
          </div>

          <!-- Vyhledávací textové pole s nápovědou -->
          <div class="rozvoz-vyhledavani-obal">
            <input type="text" id="vstup-hledat-obec-rozvoz" class="vstup-vyhledat-obec" placeholder="Zadejte jméno obce pro zjištění ceny rozvozu" oninput="filtrovatAktivniRozvozObce(this.value)">
          </div>

          <!-- Dynamické štítky vyhledaných obcí -->
          <div class="mrizka-stiku-obci mrizka-vyhledavani-vysledky" id="vysledky-obci-rychnov" style="display: none;">
            <span class="stitek-obec-polozka skryto" data-obec="Bílý Újezd"><strong>Bílý Újezd</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Brocná"><strong>Brocná</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Byzhradec"><strong>Byzhradec</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Častolovice"><strong>Častolovice</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Černíkovice"><strong>Černíkovice</strong> <span class="stitek-vzdalenost">8 km</span> <span class="cena-obce-stitek">56 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Debřece"><strong>Debřece</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Dlouhá Ves"><strong>Dlouhá Ves</strong> <span class="stitek-vzdalenost">4 km</span> <span class="cena-obce-stitek">28 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Domašín"><strong>Domašín</strong> <span class="stitek-vzdalenost">5 km</span> <span class="cena-obce-stitek">35 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Doudleby nad Orlicí"><strong>Doudleby nad Orlicí</strong> <span class="stitek-vzdalenost">8 km</span> <span class="cena-obce-stitek">56 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Hláska"><strong>Hláska</strong> <span class="stitek-vzdalenost">9 km</span> <span class="cena-obce-stitek">63 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Hroška"><strong>Hroška</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Jahodov"><strong>Jahodov</strong> <span class="stitek-vzdalenost">6 km</span> <span class="cena-obce-stitek">42 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Jaroslav"><strong>Jaroslav</strong> <span class="stitek-vzdalenost">6 km</span> <span class="cena-obce-stitek">42 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Javornice"><strong>Javornice</strong> <span class="stitek-vzdalenost">7 km</span> <span class="cena-obce-stitek">49 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Javornice - Betlém"><strong>Javornice - Betlém</strong> <span class="stitek-vzdalenost">7 km</span> <span class="cena-obce-stitek">49 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Ještětice"><strong>Ještětice</strong> <span class="stitek-vzdalenost">8 km</span> <span class="cena-obce-stitek">56 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Kostelec nad Orlicí"><strong>Kostelec nad Orlicí</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Kvasiny"><strong>Kvasiny</strong> <span class="stitek-vzdalenost">8 km</span> <span class="cena-obce-stitek">56 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Ledská"><strong>Ledská</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Libel"><strong>Libel</strong> <span class="stitek-vzdalenost">7 km</span> <span class="cena-obce-stitek">49 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Liberk"><strong>Liberk</strong> <span class="stitek-vzdalenost">8 km</span> <span class="cena-obce-stitek">56 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Lično"><strong>Lično</strong> <span class="stitek-vzdalenost">9 km</span> <span class="cena-obce-stitek">63 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Lipovka"><strong>Lipovka</strong> <span class="stitek-vzdalenost">3 km</span> <span class="cena-obce-stitek">21 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Litohrady"><strong>Litohrady</strong> <span class="stitek-vzdalenost">4 km</span> <span class="cena-obce-stitek">28 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Lokot"><strong>Lokot</strong> <span class="stitek-vzdalenost">3 km</span> <span class="cena-obce-stitek">21 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Lukavice"><strong>Lukavice</strong> <span class="stitek-vzdalenost">7 km</span> <span class="cena-obce-stitek">49 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Lupenice"><strong>Lupenice</strong> <span class="stitek-vzdalenost">5 km</span> <span class="cena-obce-stitek">35 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Malá Ledská"><strong>Malá Ledská</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Merklovice"><strong>Merklovice</strong> <span class="stitek-vzdalenost">9 km</span> <span class="cena-obce-stitek">63 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Panská Habrová"><strong>Panská Habrová</strong> <span class="stitek-vzdalenost">4 km</span> <span class="cena-obce-stitek">28 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Peklo nad Zdobnicí"><strong>Peklo nad Zdobnicí</strong> <span class="stitek-vzdalenost">6 km</span> <span class="cena-obce-stitek">42 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Přím"><strong>Přím</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Roveň"><strong>Roveň</strong> <span class="stitek-vzdalenost">5 km</span> <span class="cena-obce-stitek">35 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Skuhrov nad Bělou"><strong>Skuhrov nad Bělou</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Slemeno"><strong>Slemeno</strong> <span class="stitek-vzdalenost">5 km</span> <span class="cena-obce-stitek">35 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Solnice"><strong>Solnice</strong> <span class="stitek-vzdalenost">5 km</span> <span class="cena-obce-stitek">35 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Synkov"><strong>Synkov</strong> <span class="stitek-vzdalenost">7 km</span> <span class="cena-obce-stitek">49 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Třebešov"><strong>Třebešov</strong> <span class="stitek-vzdalenost">6 km</span> <span class="cena-obce-stitek">42 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Tutleky"><strong>Tutleky</strong> <span class="stitek-vzdalenost">8 km</span> <span class="cena-obce-stitek">56 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Vamberk"><strong>Vamberk</strong> <span class="stitek-vzdalenost">7 km</span> <span class="cena-obce-stitek">49 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Záměl"><strong>Záměl</strong> <span class="stitek-vzdalenost">10 km</span> <span class="cena-obce-stitek">70 Kč</span></span>
          </div>

          <div class="mrizka-stiku-obci mrizka-vyhledavani-vysledky" id="vysledky-obci-usti" style="display: none;">
            <span class="stitek-obec-polozka skryto" data-obec="Černovír"><strong>Černovír</strong> <span class="stitek-vzdalenost">Zóna 2</span> <span class="cena-obce-stitek">50 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Česká Třebová"><strong>Česká Třebová</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="České Libchavy"><strong>České Libchavy</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Dlouhá Třebová"><strong>Dlouhá Třebová</strong> <span class="stitek-vzdalenost">Zóna 2</span> <span class="cena-obce-stitek">50 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Dolní Dobrouč"><strong>Dolní Dobrouč</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Dolní Houžovec"><strong>Dolní Houžovec</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Dolní Libchavy"><strong>Dolní Libchavy</strong> <span class="stitek-vzdalenost">Zóna 2</span> <span class="cena-obce-stitek">50 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Džbánov"><strong>Džbánov</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Horní Houžovec"><strong>Horní Houžovec</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Horní Libchavy"><strong>Horní Libchavy</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Hrádek"><strong>Hrádek</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Jehnědí"><strong>Jehnědí</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Kerhartice"><strong>Kerhartice</strong> <span class="stitek-vzdalenost">Zóna 2</span> <span class="cena-obce-stitek">50 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Knapovec"><strong>Knapovec</strong> <span class="stitek-vzdalenost">Zóna 2</span> <span class="cena-obce-stitek">50 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Lanšperk"><strong>Lanšperk</strong> <span class="stitek-vzdalenost">Zóna 2</span> <span class="cena-obce-stitek">50 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Orlické Podhůří"><strong>Orlické Podhůří</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Přívrat"><strong>Přívrat</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Rozsocha"><strong>Rozsocha</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Rviště"><strong>Rviště</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Říčky"><strong>Říčky</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Skuhrov"><strong>Skuhrov</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Sloupnice"><strong>Sloupnice</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
            <span class="stitek-obec-polozka skryto" data-obec="Sudislav nad Orlicí"><strong>Sudislav nad Orlicí</strong> <span class="stitek-vzdalenost">Zóna 3</span> <span class="cena-obce-stitek">80 Kč</span></span>
          </div>

          <!-- Radarová mapa Rychnov (Velká bezešvá mapa se všemi 41 obcemi) -->
          <div id="rozvoz-mapa-svg-rychnov" class="rozvoz-mapa-svg-platno">
            <svg viewBox="0 0 880 620" class="vektor-mapa-svg">
              <!-- Zóna do 10 km (+7 Kč / 1 km) -->
              <circle cx="440" cy="310" r="270" class="mapa-kruh-zona zona-okoli-10km" />
              <text x="440" y="32" class="mapa-popisek-zony">OKRUH ROZVOZU DO 10 KM (+7 KČ / 1 KM)</text>

              <!-- Zóna Město Rychnov (+10 Kč) -->
              <circle cx="440" cy="310" r="54" class="mapa-kruh-zona zona-mesto-stred" />
              
              <!-- Střed - Pizzerie Rychnov -->
              <circle cx="440" cy="310" r="8" class="mapa-bod-stred" />
              <text x="440" y="286" class="mapa-popisek-mesto">Rychnov n.K.</text>



              <!-- Sever / Severovýchod -->
              <circle cx="361" cy="67" r="4" class="mapa-bod-obec" />
              <text x="353" y="71" text-anchor="end" class="mapa-text-obec">Brocná</text>

              <circle cx="418" cy="184" r="4" class="mapa-bod-obec" />
              <text x="410" y="188" text-anchor="end" class="mapa-text-obec">Solnice</text>

              <circle cx="483" cy="110" r="4" class="mapa-bod-obec" />
              <text x="491" y="114" class="mapa-text-obec">Kvasiny</text>

              <circle cx="548" cy="79" r="4" class="mapa-bod-obec" />
              <text x="556" y="83" class="mapa-text-obec">Skuhrov n. B.</text>

              <circle cx="577" cy="158" r="4" class="mapa-bod-obec" />
              <text x="585" y="162" class="mapa-text-obec">Liberk</text>

              <circle cx="621" cy="168" r="4" class="mapa-bod-obec" />
              <text x="629" y="172" class="mapa-text-obec">Hláska</text>

              <circle cx="602" cy="234" r="4" class="mapa-bod-obec" />
              <text x="610" y="238" class="mapa-text-obec">Lukavice</text>

              <!-- Východ / Jihovýchod -->
              <circle cx="540" cy="292" r="4" class="mapa-bod-obec" />
              <text x="548" y="296" class="mapa-text-obec">Panská Habrová</text>

              <circle cx="618" cy="326" r="4" class="mapa-bod-obec" />
              <text x="626" y="330" class="mapa-text-obec">Javornice</text>

              <circle cx="610" cy="365" r="4" class="mapa-bod-obec" />
              <text x="618" y="369" class="mapa-text-obec">Javornice - Betlém</text>

              <circle cx="665" cy="430" r="4" class="mapa-bod-obec" />
              <text x="673" y="434" class="mapa-text-obec">Přím</text>

              <circle cx="518" cy="376" r="4" class="mapa-bod-obec" />
              <text x="526" y="380" class="mapa-text-obec">Dlouhá Ves</text>

              <circle cx="542" cy="424" r="4" class="mapa-bod-obec" />
              <text x="550" y="428" class="mapa-text-obec">Jahodov</text>

              <circle cx="521" cy="440" r="4" class="mapa-bod-obec" />
              <text x="529" y="444" class="mapa-text-obec">Jaroslav</text>

              <circle cx="488" cy="429" r="4" class="mapa-bod-obec" />
              <text x="496" y="433" class="mapa-text-obec">Roveň</text>

              <!-- Jih / Jihovýchod -->
              <circle cx="477" cy="485" r="4" class="mapa-bod-obec" />
              <text x="485" y="489" class="mapa-text-obec">Vamberk</text>

              <circle cx="445" cy="463" r="4" class="mapa-bod-obec" />
              <text x="437" y="467" text-anchor="end" class="mapa-text-obec">Peklo n. Z.</text>

              <circle cx="416" cy="539" r="4" class="mapa-bod-obec" />
              <text x="408" y="543" text-anchor="end" class="mapa-text-obec">Merklovice</text>

              <circle cx="370" cy="555" r="4" class="mapa-bod-obec" />
              <text x="362" y="559" text-anchor="end" class="mapa-text-obec">Záměl</text>

              <circle cx="350" cy="494" r="4" class="mapa-bod-obec" />
              <text x="342" y="498" text-anchor="end" class="mapa-text-obec">Doudleby n. O.</text>

              <circle cx="365" cy="414" r="4" class="mapa-bod-obec" />
              <text x="357" y="418" text-anchor="end" class="mapa-text-obec">Lupenice</text>

              <circle cx="292" cy="452" r="4" class="mapa-bod-obec" />
              <text x="284" y="456" text-anchor="end" class="mapa-text-obec">Tutleky</text>

              <circle cx="229" cy="453" r="4" class="mapa-bod-obec" />
              <text x="221" y="457" text-anchor="end" class="mapa-text-obec">Kostelec n. O.</text>

              <!-- Západ / Jihozápad -->
              <circle cx="389" cy="366" r="4" class="mapa-bod-obec" />
              <text x="381" y="370" text-anchor="end" class="mapa-text-obec">Lokot</text>

              <circle cx="265" cy="347" r="4" class="mapa-bod-obec" />
              <text x="257" y="351" text-anchor="end" class="mapa-text-obec">Synkov</text>

              <circle cx="261" cy="316" r="4" class="mapa-bod-obec" />
              <text x="253" y="320" text-anchor="end" class="mapa-text-obec">Libel</text>

              <circle cx="188" cy="345" r="4" class="mapa-bod-obec" />
              <text x="180" y="349" text-anchor="end" class="mapa-text-obec">Častolovice</text>

              <circle cx="185" cy="301" r="4" class="mapa-bod-obec" />
              <text x="177" y="305" text-anchor="end" class="mapa-text-obec">Ledská</text>

              <circle cx="187" cy="275" r="4" class="mapa-bod-obec" />
              <text x="179" y="279" text-anchor="end" class="mapa-text-obec">Malá Ledská</text>

              <circle cx="312" cy="299" r="4" class="mapa-bod-obec" />
              <text x="304" y="303" text-anchor="end" class="mapa-text-obec">Slemeno</text>

              <circle cx="292" cy="270" r="4" class="mapa-bod-obec" />
              <text x="284" y="274" text-anchor="end" class="mapa-text-obec">Třebešov</text>

              <circle cx="364" cy="317" r="4" class="mapa-bod-obec" />
              <text x="356" y="321" text-anchor="end" class="mapa-text-obec">Lipovka</text>

              <circle cx="348" cy="267" r="4" class="mapa-bod-obec" />
              <text x="340" y="271" text-anchor="end" class="mapa-text-obec">Litohrady</text>

              <circle cx="335" cy="237" r="4" class="mapa-bod-obec" />
              <text x="327" y="241" text-anchor="end" class="mapa-text-obec">Domašín</text>

              <!-- Severozápad -->
              <circle cx="227" cy="224" r="4" class="mapa-bod-obec" />
              <text x="219" y="228" text-anchor="end" class="mapa-text-obec">Lično</text>

              <circle cx="219" cy="182" r="4" class="mapa-bod-obec" />
              <text x="211" y="186" text-anchor="end" class="mapa-text-obec">Debřece</text>

              <circle cx="245" cy="146" r="4" class="mapa-bod-obec" />
              <text x="237" y="150" text-anchor="end" class="mapa-text-obec">Byzhradec</text>

              <circle cx="298" cy="163" r="4" class="mapa-bod-obec" />
              <text x="290" y="167" text-anchor="end" class="mapa-text-obec">Černíkovice</text>

              <circle cx="331" cy="136" r="4" class="mapa-bod-obec" />
              <text x="323" y="140" text-anchor="end" class="mapa-text-obec">Ještětice</text>

              <circle cx="294" cy="101" r="4" class="mapa-bod-obec" />
              <text x="286" y="105" text-anchor="end" class="mapa-text-obec">Hroška</text>

              <circle cx="332" cy="79" r="4" class="mapa-bod-obec" />
              <text x="324" y="83" text-anchor="end" class="mapa-text-obec">Bílý Újezd</text>


            </svg>
          </div>

          <!-- Radarová mapa Ústí nad Orlicí (Velká bezešvá mapa se všemi 23 obcemi) -->
          <div id="rozvoz-mapa-svg-usti" class="rozvoz-mapa-svg-platno" style="display: none;">
            <svg viewBox="0 0 880 620" class="vektor-mapa-svg">
              <!-- Zóna 3 (80 Kč) -->
              <circle cx="440" cy="310" r="270" class="mapa-kruh-zona zona-okoli-10km" />
              <text x="440" y="32" class="mapa-popisek-zony">OKRUH ROZVOZU (ZÓNA 2: 50 KČ | ZÓNA 3: 80 KČ)</text>

              <!-- Zóna 2 (50 Kč) -->
              <circle cx="440" cy="310" r="160" class="mapa-kruh-zona" style="stroke: rgba(245, 158, 11, 0.45); stroke-width: 1.5; stroke-dasharray: 6 6; fill: rgba(245, 158, 11, 0.03);" />

              <!-- Zóna Město Ústí (+10 Kč) -->
              <circle cx="440" cy="310" r="54" class="mapa-kruh-zona zona-mesto-stred" />
              
              <!-- Střed - Pizzerie Ústí -->
              <circle cx="440" cy="310" r="8" class="mapa-bod-stred" />
              <text x="440" y="286" class="mapa-popisek-mesto">Ústí n.O.</text>

              <!-- Sever / Severovýchod -->
              <circle cx="440" cy="225" r="4.5" class="mapa-bod-obec" />
              <text x="448" y="229" class="mapa-text-obec">Dolní Libchavy</text>

              <circle cx="440" cy="175" r="4.5" class="mapa-bod-obec" />
              <text x="448" y="179" class="mapa-text-obec">Horní Libchavy</text>

              <circle cx="430" cy="125" r="4.5" class="mapa-bod-obec" />
              <text x="422" y="129" text-anchor="end" class="mapa-text-obec">České Libchavy</text>

              <circle cx="610" cy="215" r="4.5" class="mapa-bod-obec" />
              <text x="618" y="219" class="mapa-text-obec">Dolní Dobrouč</text>

              <circle cx="530" cy="240" r="4.5" class="mapa-bod-obec" />
              <text x="538" y="244" class="mapa-text-obec">Lanšperk</text>

              <circle cx="480" cy="265" r="4.5" class="mapa-bod-obec" />
              <text x="488" y="269" class="mapa-text-obec">Černovír</text>

              <!-- Východ / Jihovýchod -->
              <circle cx="535" cy="375" r="4.5" class="mapa-bod-obec" />
              <text x="543" y="379" class="mapa-text-obec">Dlouhá Třebová</text>

              <circle cx="600" cy="460" r="4.5" class="mapa-bod-obec" />
              <text x="608" y="464" class="mapa-text-obec">Česká Třebová</text>

              <circle cx="555" cy="330" r="4.5" class="mapa-bod-obec" />
              <text x="563" y="334" class="mapa-text-obec">Dolní Houžovec</text>

              <circle cx="615" cy="340" r="4.5" class="mapa-bod-obec" />
              <text x="623" y="344" class="mapa-text-obec">Horní Houžovec</text>

              <circle cx="505" cy="355" r="4.5" class="mapa-bod-obec" />
              <text x="513" y="359" class="mapa-text-obec">Říčky</text>

              <circle cx="550" cy="425" r="4.5" class="mapa-bod-obec" />
              <text x="558" y="429" class="mapa-text-obec">Skuhrov</text>

              <circle cx="455" cy="385" r="4.5" class="mapa-bod-obec" />
              <text x="463" y="389" class="mapa-text-obec">Knapovec</text>

              <circle cx="485" cy="470" r="4.5" class="mapa-bod-obec" />
              <text x="493" y="474" class="mapa-text-obec">Přívrat</text>

              <!-- Jih / Jihozápad -->
              <circle cx="310" cy="375" r="4.5" class="mapa-bod-obec" />
              <text x="302" y="379" text-anchor="end" class="mapa-text-obec">Džbánov</text>

              <circle cx="260" cy="420" r="4.5" class="mapa-bod-obec" />
              <text x="252" y="424" text-anchor="end" class="mapa-text-obec">Sloupnice</text>

              <!-- Západ / Severozápad -->
              <circle cx="370" cy="310" r="4.5" class="mapa-bod-obec" />
              <text x="362" y="314" text-anchor="end" class="mapa-text-obec">Kerhartice</text>

              <circle cx="340" cy="250" r="4.5" class="mapa-bod-obec" />
              <text x="332" y="254" text-anchor="end" class="mapa-text-obec">Hrádek</text>

              <circle cx="330" cy="210" r="4.5" class="mapa-bod-obec" />
              <text x="322" y="214" text-anchor="end" class="mapa-text-obec">Jehnědí</text>

              <circle cx="370" cy="180" r="4.5" class="mapa-bod-obec" />
              <text x="362" y="184" text-anchor="end" class="mapa-text-obec">Orlické Podhůří</text>

              <circle cx="350" cy="140" r="4.5" class="mapa-bod-obec" />
              <text x="342" y="144" text-anchor="end" class="mapa-text-obec">Rozsocha</text>

              <circle cx="385" cy="145" r="4.5" class="mapa-bod-obec" />
              <text x="377" y="149" text-anchor="end" class="mapa-text-obec">Rviště</text>

              <circle cx="280" cy="260" r="4.5" class="mapa-bod-obec" />
              <text x="272" y="264" text-anchor="end" class="mapa-text-obec">Sudislav nad Orlicí</text>
            </svg>
          </div>

        </div>
  </section>

  <!-- 6. SEKCE: PROVOZOVNY -->
  <section class="sekce-provozovny" id="provozovny">
    <div class="kontejner">
      <h2 class="nadpis-sekce" style="text-align: center; margin-bottom: 24px;">Provozovny</h2>

      <!-- Decentní černobílý switch mezi městy (Rychnov n.K. a Ústí n.O.) -->
      <div class="provozovny-switch-obal">
        <div class="kapsle-prepinac-cernobily" id="prepinac-provozovny" data-aktivni="rychnov">
          <div class="kapsle-slajdr-bily-cb"></div>
          <button type="button" class="kapsle-polozka-cb aktivni" data-pobocka-kod="rychnov" onclick="prepnoutPobocku('rychnov')">Rychnov n.K.</button>
          <button type="button" class="kapsle-polozka-cb" data-pobocka-kod="usti" onclick="prepnoutPobocku('usti')">Ústí n.O.</button>
        </div>
      </div>

      <div class="mrizka-provozovny" style="grid-template-columns: 1fr; max-width: 800px; margin: 0 auto;">
        
        <!-- Provozovna Rychnov -->
        <div class="karta-provozovny" id="provozovna-karta-rychnov">
          <div class="obsah-provozovny">
            <h3 class="nazev-provozovny">RYCHNOV NAD KNĚŽNOU</h3>
            <p class="adresa-provozovny">Panská 123, Rychnov nad Kněžnou, 516 01</p>

            <div class="blok-hodin">
              <p class="nadpis-hodin">Pizzerie:</p>
              <p class="cas-hodin">pondělí - sobota: 11:00 ~ 21:00</p>
            </div>

            <div class="blok-hodin">
              <p class="nadpis-hodin">Rozvozy:</p>
              <p class="cas-hodin">pondělí - sobota: 11:00 ~ 14:00, 15:00~19:00</p>
            </div>

            <a href="https://mapy.com/s/hecazafoju" target="_blank" rel="noopener noreferrer" class="tlacitko tlacitko-sede" style="margin-top: 15px;">UKÁZAT NA MAPĚ</a>
          </div>
          
          <div class="karusel-provozovny-obal" id="karusel-rychnov">
            <div class="karusel-snimky-pas">
              <div class="karusel-snimek">
                <img src="media/obrazky/provozovny/provozovna_usti1.jpg" alt="Pizzerie Rychnov nad Kněžnou - Provozovna" loading="lazy" decoding="async">
              </div>
              <div class="karusel-snimek">
                <img src="media/obrazky/provozovny/provozovna_usti2.jpg" alt="Pizzerie Rychnov nad Kněžnou - Interiér" loading="lazy" decoding="async">
              </div>
            </div>
            
            <button type="button" class="karusel-sipka karusel-sipka-vlevo" onclick="posunoutKarusel('karusel-rychnov', -1)" aria-label="Předchozí snímek">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
              </svg>
            </button>
            <button type="button" class="karusel-sipka karusel-sipka-vpravo" onclick="posunoutKarusel('karusel-rychnov', 1)" aria-label="Další snímek">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </button>
            
            <div class="karusel-tecky-obalo">
              <button class="karusel-tecka aktivni" onclick="nastavitSnimekKaruselu('karusel-rychnov', 0)" aria-label="Snímek 1"></button>
              <button class="karusel-tecka" onclick="nastavitSnimekKaruselu('karusel-rychnov', 1)" aria-label="Snímek 2"></button>
            </div>
          </div>
        </div>

        <!-- Provozovna Usti -->
        <div class="karta-provozovny" id="provozovna-karta-usti" style="display: none;">
          <div class="obsah-provozovny">
            <h3 class="nazev-provozovny">ÚSTÍ NAD ORLICÍ</h3>
            <p class="adresa-provozovny">Mírové náměstí 10, Ústí nad Orlicí, 562 01</p>

            <div class="blok-hodin">
              <p class="nadpis-hodin">Pizzerie:</p>
              <p class="cas-hodin">pondělí - sobota: 11:00 ~ 21:00</p>
            </div>

            <div class="blok-hodin">
              <p class="nadpis-hodin">Rozvozy:</p>
              <p class="cas-hodin">pondělí - sobota: 11:00 ~ 14:00, 15:00~19:00</p>
            </div>

            <a href="https://mapy.com/s/jomatotube" target="_blank" rel="noopener noreferrer" class="tlacitko tlacitko-sede" style="margin-top: 15px;">UKÁZAT NA MAPĚ</a>
          </div>
          
          <div class="karusel-provozovny-obal" id="karusel-usti">
            <div class="karusel-snimky-pas">
              <div class="karusel-snimek">
                <img src="media/obrazky/provozovny/provozovna_usti1.jpg" alt="Pizzerie Ústí nad Orlicí - Provozovna" loading="lazy" decoding="async">
              </div>
              <div class="karusel-snimek">
                <img src="media/obrazky/provozovny/provozovna_usti2.jpg" alt="Pizzerie Ústí nad Orlicí - Interiér a výdej" loading="lazy" decoding="async">
              </div>
            </div>
            
            <button type="button" class="karusel-sipka karusel-sipka-vlevo" onclick="posunoutKarusel('karusel-usti', -1)" aria-label="Předchozí snímek">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
              </svg>
            </button>
            <button type="button" class="karusel-sipka karusel-sipka-vpravo" onclick="posunoutKarusel('karusel-usti', 1)" aria-label="Další snímek">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </button>
            
            <div class="karusel-tecky-obalo">
              <button class="karusel-tecka aktivni" onclick="nastavitSnimekKaruselu('karusel-usti', 0)" aria-label="Snímek 1"></button>
              <button class="karusel-tecka" onclick="nastavitSnimekKaruselu('karusel-usti', 1)" aria-label="Snímek 2"></button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- 7. MODALNI OKNA -->

  <!-- MODÁLNÍ OKNO NÁKUPNÍHO SEZNAMU -->
  <div class="modalni-okno-pozadi" id="modal-kosiku" onclick="if(event.target === this) zavritModal('modal-kosiku')">
    <div class="modalni-okno-obsah">
      <button class="zavrit-modal" onclick="zavritModal('modal-kosiku')">&times;</button>
      <h2 class="nadpis-sekce" style="font-size: 1.8rem; margin-bottom: 15px;">NÁKUPNÍ SEZNAM</h2>
      
      <!-- Seznam položek v nákupním seznamu -->
      <div id="seznam-polozek-kosik-modal" class="seznam-polozek-kosiku"></div>

      <!-- Blok ceny rozvozu pod seznamem pizz -->
      <div id="kosik-rozvoz-blok" class="kosik-rozvoz-sekce" style="margin-top: 18px; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.12);">
        <label for="kosik-vyhledat-obec" style="display: flex; align-items: center; justify-content: space-between; font-size: 0.92rem; color: #ccc; margin-bottom: 8px; font-weight: 700;">
          <span>Cena rozvozu:</span>
          <span id="kosik-rozvoz-vybrana-cena-badge" style="display: none; color: #f59e0b; font-weight: 800;"></span>
        </label>
        <div style="position: relative;">
          <input type="text" id="kosik-vyhledat-obec" class="vstup-vyhledat-obec" placeholder="Vyhledejte obec pro zjištění ceny rozvozu..." oninput="hledatRozvozVKosiku(this.value)" autocomplete="off" style="padding-right: 36px;">
          <button type="button" id="kosik-smazat-rozvoz" onclick="vymazatRozvozVKosiku()" style="display: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #aaa; font-size: 1.3rem; line-height: 1; cursor: pointer; padding: 4px;" title="Zrušit vybraný rozvoz">&times;</button>
        </div>
        <div id="kosik-vysledky-obci" class="mrizka-stiku-obci mrizka-vyhledavani-vysledky-mobil" style="display: none; max-height: 160px; overflow-y: auto; margin-top: 8px;"></div>
      </div>

      <!-- Celková cena -->
      <div id="celkova-cena-kosik-modal" class="celkova-cena-kosiku" style="margin-top: 18px;">Celkem: 0 Kč</div>

      <!-- Telefonická objednávka: Ikona telefonu s vyskakovacím oknem výběru pobočky -->
      <div class="kosik-volani-sekce" id="kosik-volani-sekce-obal" style="margin-top: 25px; text-align: center; position: relative;">
        <p style="color: #ccc; font-size: 0.9rem; margin-bottom: 12px;">Objednávku dokončíte telefonicky:</p>
        <div style="display: inline-block; position: relative;">
          <button type="button" class="tlacitko-telefon-ikona tlacitko-telefon-kosik" id="btn-otevrit-volani-kosik" onclick="prepnoutPodoknoVolaniKosik(event)" title="Zavolat na provozovnu" aria-label="Zavolat na provozovnu" style="width: 56px; height: 56px; border-radius: 50%; background-color: var(--barva-cervena-hlavni, #e50914); color: #ffffff; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 4px 18px rgba(229, 9, 20, 0.45); transition: all 0.25s ease;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.02-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
            </svg>
          </button>

          <!-- Vyskakovací podokno s výběrem provozovny -->
          <div class="podokno-volani-pobocky podokno-volani-kosik" id="podokno-volani-kosik-box">
            <div class="podokno-volani-zahlavi">Zavolejte nám pro objednávku:</div>
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
  </div>

  <!-- MODALNI OKNO PREHLEDU ALERGENU -->
  <div class="modalni-okno-pozadi" id="modal-prehled-alergenu" onclick="if(event.target === this) zavritModal('modal-prehled-alergenu')">
    <div class="modalni-okno-obsah" style="max-width: 650px;">
      <button class="zavrit-modal" onclick="zavritModal('modal-prehled-alergenu')">&times;</button>
      <h2 class="nadpis-sekce" style="font-size: 1.8rem; color: #e53e3e; margin-bottom: 15px;">SEZNAM ALERGENŮ EU</h2>
      <p style="color: #ccc; font-size: 0.95rem; margin-bottom: 20px;">Dle nařízení EU 1169/2011 uvádíme přehled 14 oficiálních alergenů:</p>
      
      <div style="display: grid; grid-template-columns: 1fr; gap: 10px; max-height: 60vh; overflow-y: auto; padding-right: 5px;">
        <div style="background: #222; padding: 10px 14px; border-radius: 8px; border: 1px solid #333; display: flex; align-items: center; gap: 12px;">
          <span style="background: #e53e3e; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">1</span>
          <div><strong style="color: #fff;">Lepek (obiloviny)</strong><small style="display: block; color: #888;">Pšenice, žito, ječmen, oves, špalda</small></div>
        </div>
        <div style="background: #222; padding: 10px 14px; border-radius: 8px; border: 1px solid #333; display: flex; align-items: center; gap: 12px;">
          <span style="background: #e53e3e; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">2</span>
          <div><strong style="color: #fff;">Korýši</strong><small style="display: block; color: #888;">Krevety, humři, krabi</small></div>
        </div>
        <div style="background: #222; padding: 10px 14px; border-radius: 8px; border: 1px solid #333; display: flex; align-items: center; gap: 12px;">
          <span style="background: #e53e3e; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">3</span>
          <div><strong style="color: #fff;">Vejce</strong><small style="display: block; color: #888;">Vaječné bílky, žloutky, majonéza</small></div>
        </div>
        <div style="background: #222; padding: 10px 14px; border-radius: 8px; border: 1px solid #333; display: flex; align-items: center; gap: 12px;">
          <span style="background: #e53e3e; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">4</span>
          <div><strong style="color: #fff;">Ryby</strong><small style="display: block; color: #888;">Losos, tuňák, ančovičky</small></div>
        </div>
        <div style="background: #222; padding: 10px 14px; border-radius: 8px; border: 1px solid #333; display: flex; align-items: center; gap: 12px;">
          <span style="background: #e53e3e; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">5</span>
          <div><strong style="color: #fff;">Arašídy (podzemnice olejná)</strong><small style="display: block; color: #888;">Arašídové oleje a másla</small></div>
        </div>
        <div style="background: #222; padding: 10px 14px; border-radius: 8px; border: 1px solid #333; display: flex; align-items: center; gap: 12px;">
          <span style="background: #e53e3e; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">6</span>
          <div><strong style="color: #fff;">Sója</strong><small style="display: block; color: #888;">Sójová omáčka, tofu</small></div>
        </div>
        <div style="background: #222; padding: 10px 14px; border-radius: 8px; border: 1px solid #333; display: flex; align-items: center; gap: 12px;">
          <span style="background: #e53e3e; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">7</span>
          <div><strong style="color: #fff;">Mléko a laktóza</strong><small style="display: block; color: #888;">Mozzarella, sýry, smetana, máslo</small></div>
        </div>
        <div style="background: #222; padding: 10px 14px; border-radius: 8px; border: 1px solid #333; display: flex; align-items: center; gap: 12px;">
          <span style="background: #e53e3e; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">8</span>
          <div><strong style="color: #fff;">Ořechy (skořápkové plody)</strong><small style="display: block; color: #888;">Mandle, lískové ořechy, pistácie, kešu</small></div>
        </div>
        <div style="background: #222; padding: 10px 14px; border-radius: 8px; border: 1px solid #333; display: flex; align-items: center; gap: 12px;">
          <span style="background: #e53e3e; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">9</span>
          <div><strong style="color: #fff;">Celer</strong><small style="display: block; color: #888;">Celerová nať, celerová sůl</small></div>
        </div>
        <div style="background: #222; padding: 10px 14px; border-radius: 8px; border: 1px solid #333; display: flex; align-items: center; gap: 12px;">
          <span style="background: #e53e3e; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">10</span>
          <div><strong style="color: #fff;">Hořčice</strong><small style="display: block; color: #888;">Hořčičná semínka, omáčky</small></div>
        </div>
        <div style="background: #222; padding: 10px 14px; border-radius: 8px; border: 1px solid #333; display: flex; align-items: center; gap: 12px;">
          <span style="background: #e53e3e; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">11</span>
          <div><strong style="color: #fff;">Sezam</strong><small style="display: block; color: #888;">Sezamová semínka, olej</small></div>
        </div>
        <div style="background: #222; padding: 10px 14px; border-radius: 8px; border: 1px solid #333; display: flex; align-items: center; gap: 12px;">
          <span style="background: #e53e3e; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">12</span>
          <div><strong style="color: #fff;">Oxid siřičitý (SO₂)</strong><small style="display: block; color: #888;">Víno, sušené ovoce, konzervanty</small></div>
        </div>
        <div style="background: #222; padding: 10px 14px; border-radius: 8px; border: 1px solid #333; display: flex; align-items: center; gap: 12px;">
          <span style="background: #e53e3e; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">13</span>
          <div><strong style="color: #fff;">Vlčí bob (lupina)</strong><small style="display: block; color: #888;">Lupinová mouka</small></div>
        </div>
        <div style="background: #222; padding: 10px 14px; border-radius: 8px; border: 1px solid #333; display: flex; align-items: center; gap: 12px;">
          <span style="background: #e53e3e; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0;">14</span>
          <div><strong style="color: #fff;">Měkkýši</strong><small style="display: block; color: #888;">Mušle, chobotnice, slávky</small></div>
        </div>
      </div>
    </div>
  </div>

  <!-- MODALNI OKNO VELKEHO NAHLEDU INGREDIENCE (FOTKA, NAZEV A POPIS DOLE) -->
  <div class="modalni-okno-pozadi" id="modal-obrazek-ingredience" onclick="if(event.target === this) zavritModal('modal-obrazek-ingredience')">
    <div class="modalni-okno-obsah modal-nahled-ingredience-obsah">
      <button class="zavrit-modal" onclick="zavritModal('modal-obrazek-ingredience')" title="Zavřít náhled">&times;</button>
      <div id="modal-nahled-ingredience-telo">
        <!-- Obsah plni JS -->
      </div>
    </div>
  </div>

  <!-- MODÁLNÍ OKNO PRO ZVĚTŠENÝ NÁHLED FOTKY PIZZY (DESKTOP) -->
  <div class="modalni-okno-pozadi" id="modal-obrazek-pizza" onclick="if(event.target === this) zavritModal('modal-obrazek-pizza')">
    <div class="modalni-okno-obsah modal-nahled-pizza-obsah">
      <button class="zavrit-modal" onclick="zavritModal('modal-obrazek-pizza')" title="Zavřít náhled">&times;</button>
      <div id="modal-nahled-pizza-telo">
        <!-- Vkládá JS -->
      </div>
    </div>
  </div>

  <!-- PLOVOUCÍ SEZNAM (TLACÍTKO VPRAVO DOLE) -->
  <button class="plovuci-kosik-tlacitko" id="plovuci-seznam-tlacitko" onclick="otvoritModal('modal-kosiku')" style="display: none;">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
    </svg>
    <span>Seznam</span>
    <span class="pocitadlo-kosiku" id="pocitadlo-kosiku-cislo">0</span>
  </button>

  <script src="js/aplikace.js?v=<?php echo filemtime(__DIR__ . '/js/aplikace.js'); ?>"></script>
</body>
</html>
