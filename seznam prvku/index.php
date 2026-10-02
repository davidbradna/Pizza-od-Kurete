<?php
/* ==========================================================================
   DYNAMICKA HLAVNI STRANKA PRO PIZZA OD KURETE (INDEX.PHP)
   Vsechny nazvy trid, ID a komentarov jsou v cestine bez diakritiky
   ========================================================================== */

require_once __DIR__ . '/nastaveni.php';
require_once __DIR__ . '/databaze.php';

$pizzy = ziskatPizzy();
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
  <link rel="stylesheet" href="styl.css?v=<?php echo time(); ?>">
  
  <script>
    // Predani databazovych pizz a surovin z PHP do JavaScriptu
    window.seznamPizzZDatabaze = <?php echo json_encode($pizzy, JSON_UNESCAPED_UNICODE); ?>;
    window.seznamSurovinZDatabaze = <?php echo json_encode($suroviny, JSON_UNESCAPED_UNICODE); ?>;
  </script>
</head>
<body>

  <!-- 1. UNIVERZÁLNÍ PLNOHODNOTNÁ NAVIGAČNÍ LIŠTA -->
  <header class="horni-kontakty-lista">
    <div class="navigace-podstranka-obsah">
      
      <!-- BRAND & LOGO (VLEVO: KUŘE + GRAFICKÝ NÁPIS PIZZA OD KUŘETE) -->
      <a href="index.php" class="navigace-brand-obal" title="Pizza od Kuřete">
        <img src="media/logo/logo-pizza.png" alt="Kuře Pizza od Kuřete" class="navigace-brand-logo" style="height: 50px; width: auto; max-width: 55px; object-fit: contain; display: block;">
        <img src="media/logo/napis-pizza-od-kurete.png" alt="PIZZA OD KUŘETE" class="navigace-brand-napis-img" style="height: 40px; width: auto; display: block; object-fit: contain;">
      </a>

      <!-- PRAVÁ ČÁST: MENU POLOŽKY (ZAROVNANÉ DOPRAVA) + IKONA TELEFONU + HAMBURGER -->
      <div class="navigace-prava-skupina">
        
        <!-- HLAVNÍ NAVIGAČNÍ MENU (NA MOBILU SE SKRÝVÁ DO HAMBURGERU) -->
        <ul class="navigace-odkazy-menu" id="navigace-odkazy-menu">
          <li><a href="#jidelni-listek" class="navigace-odkaz-polozka">Jídelní lístek</a></li>
          <li><a href="ingredience.php" class="navigace-odkaz-polozka">Ingredience</a></li>
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
        <img src="media/logo/logo-pizza.png?v=<?php echo time(); ?>" alt="PIZZA OD KUŘETE Logo">
      </a>

      <h1 class="nadpis-hlavni">PIZZA OD KUŘETE</h1>
      <p class="podnadpis-hlavni">přijďte si zobnout</p>
      
      <div class="oteviraci-doba-stitek">
        pondělí - sobota: 11:00 ~ 21:00
      </div>

      <div class="tlacitka-hlavicka-skupina">
        <a href="#jidelni-listek" class="tlacitko tlacitko-cervene">JÍDELNÍ LÍSTEK</a>
        <a href="#provozovny" class="tlacitko tlacitko-cervene-tmave">PIZZERIE</a>
        <a href="#rozvoz" class="tlacitko tlacitko-sede">ROZVOZ PIZZY</a>
      </div>
    </div>
  </section>

  <!-- 3. SEKCE: ROZDIL V CHUTI -->
  <section class="sekce-rozdil-v-chuti">
    <div class="kontejner mrizka-rozdil">
      <div class="textova-cast">
        <h2 class="nadpis-sekce">ROZDÍL V CHUTI<br>POZNÁTE NA PRVNÍ<br>ZOBNUTÍ</h2>
        <p class="text-popis">
          Téměř výhradně italské sýry a uzeniny - s výjimkou choriza, to je španělské.
          Čerstvá zelenina a kuřecí maso od lokálních dodavatelů.
          Mouka, která do sebe pojme více vody a uchová ji v sobě i po upečení.
        </p>
        <a href="ingredience.php" class="tlacitko tlacitko-sede">
          Jaké ochutnáte ingredience
        </a>
      </div>
      <div class="obale-fotky-picer">
        <img src="https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?q=80&w=800&auto=format&fit=crop" alt="Pizzaiolo házející těsto">
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

          <!-- Switch 4: Misto / Rozvoz -->
          <div class="mini-kapsle-prepinac" id="sw-misto">
            <button type="button" class="mini-kapsle-tlacitko" data-hodnota="v_pizzerii" onclick="nastavitPrepinac('sw-misto', 'v_pizzerii')">V pizzerii</button>
            <button type="button" class="mini-kapsle-tlacitko" data-hodnota="rozvoz" onclick="nastavitPrepinac('sw-misto', 'rozvoz')">Rozvoz</button>
          </div>
        </div>

        <div class="skupina-kategorii">
          <button class="tlacitko-kategorie" data-kategorie="nejprodavanejsi">NEJPRODÁVANĚJŠÍ</button>
          <button class="tlacitko-kategorie" data-kategorie="doporucujeme">DOPORUČUJEME</button>
        </div>

      </div>

      <!-- TEXT NAPOVEDY POD FILTREM -->
      <p class="text-napoveda-kosiku" style="text-align: center; color: #f59e0b; font-size: 1.05rem; font-weight: 600; margin-top: 25px; margin-bottom: 35px; max-width: 750px; margin-left: auto; margin-right: auto; line-height: 1.5;">
        Přidej si pizzy do seznamu nákupu, spočítáme celkovou cenu a připomeneme, co jste si vybrali, aby jste si nemusel nic pamatovat.
      </p>

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

      <div class="informace-rozvozu-box">
        <p class="radek-cenik">po městě: <strong>+ 10 Kč</strong> - mimo město: <strong>+ 7 Kč / 1 km</strong></p>
        <p class="radek-cenik">minimální objednávka: <strong>2 pizzy</strong></p>
        <p class="zlaty-text-platba">PLATBA HOTOVĚ I KARTOU</p>
      </div>

      <div class="mrizka-kontakty-rozvoz" style="max-width: 700px; margin: 0 auto; width: 100%;">
        <div class="karta-kontakt-rozvozu" id="rozvoz-karta-rychnov">
          <span class="mesto-nazev">Rychnov nad Kněžnou:</span>
          <a href="tel:739149142" class="telefon-velky">739 149 142</a>
          <button class="tlacitko tlacitko-sede tlacitko-mapa-bez-zalamovani" onclick="otvoritModal('modal-mapa-rychnov')">MAPA ROZVOZŮ</button>
        </div>

        <div class="karta-kontakt-rozvozu" id="rozvoz-karta-usti" style="display: none;">
          <span class="mesto-nazev">Ústí nad Orlicí:</span>
          <a href="tel:774741818" class="telefon-velky">774 741 818</a>
          <button class="tlacitko tlacitko-sede tlacitko-mapa-bez-zalamovani" onclick="otvoritModal('modal-mapa-usti')">MAPA ROZVOZŮ</button>
        </div>
      </div>
    </div>
  </section>

  <!-- 6. SEKCE: PROVOZOVNY -->
  <section class="sekce-provozovny" id="provozovny">
    <div class="kontejner">
      <h2 class="nadpis-sekce" style="text-align: center; margin-bottom: 40px;">Provozovny</h2>

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

            <button class="tlacitko tlacitko-sede" style="margin-top: 15px;" onclick="otvoritModal('modal-mapa-rychnov')">UKÁZAT NA MAPĚ</button>
          </div>
          
          <div class="karusel-provozovny-obal" id="karusel-rychnov">
            <div class="karusel-snimky-pas">
              <div class="karusel-snimek">
                <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=800&auto=format&fit=crop" alt="Pizzerie Rychnov - Priprava pizzy">
                <div class="karusel-popisek">Kuchyně a příprava čerstvých pizz</div>
              </div>
              <div class="karusel-snimek">
                <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=800&auto=format&fit=crop" alt="Pizzerie Rychnov - Pec">
                <div class="karusel-popisek">Tradiční pec na pizzu</div>
              </div>
              <div class="karusel-snimek">
                <img src="https://images.unsplash.com/photo-1579751626657-72bc17010498?q=80&w=800&auto=format&fit=crop" alt="Pizzerie Rychnov - Interier">
                <div class="karusel-popisek">Příjemné prostředí pro hosty</div>
              </div>
            </div>
            
            <button class="karusel-sipka karusel-sipka-vlevo" onclick="posunoutKarusel('karusel-rychnov', -1)">&lsaquo;</button>
            <button class="karusel-sipka karusel-sipka-vpravo" onclick="posunoutKarusel('karusel-rychnov', 1)">&rsaquo;</button>
            
            <div class="karusel-tecky-obalo">
              <button class="karusel-tecka aktivni" onclick="nastavitSnimekKaruselu('karusel-rychnov', 0)"></button>
              <button class="karusel-tecka" onclick="nastavitSnimekKaruselu('karusel-rychnov', 1)"></button>
              <button class="karusel-tecka" onclick="nastavitSnimekKaruselu('karusel-rychnov', 2)"></button>
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

            <button class="tlacitko tlacitko-sede" style="margin-top: 15px;" onclick="otvoritModal('modal-mapa-usti')">UKÁZAT NA MAPĚ</button>
          </div>
          
          <div class="karusel-provozovny-obal" id="karusel-usti">
            <div class="karusel-snimky-pas">
              <div class="karusel-snimek">
                <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=800&auto=format&fit=crop" alt="Pizzerie Ústí - Interiér">
                <div class="karusel-popisek">Stoly a posezení pro zákazníky</div>
              </div>
              <div class="karusel-snimek">
                <img src="https://images.unsplash.com/photo-1590846406792-0adc7f938f1d?q=80&w=800&auto=format&fit=crop" alt="Pizzerie Ústí - Bar">
                <div class="karusel-popisek">Moderní bar a výdej rozvozů</div>
              </div>
              <div class="karusel-snimek">
                <img src="https://images.unsplash.com/photo-1552566626-52f8b828add9?q=80&w=800&auto=format&fit=crop" alt="Pizzerie Ústí - Atmosféra">
                <div class="karusel-popisek">Večerní atmosféra pizzerie</div>
              </div>
            </div>
            
            <button class="karusel-sipka karusel-sipka-vlevo" onclick="posunoutKarusel('karusel-usti', -1)">&lsaquo;</button>
            <button class="karusel-sipka karusel-sipka-vpravo" onclick="posunoutKarusel('karusel-usti', 1)">&rsaquo;</button>
            
            <div class="karusel-tecky-obalo">
              <button class="karusel-tecka aktivni" onclick="nastavitSnimekKaruselu('karusel-usti', 0)"></button>
              <button class="karusel-tecka" onclick="nastavitSnimekKaruselu('karusel-usti', 1)"></button>
              <button class="karusel-tecka" onclick="nastavitSnimekKaruselu('karusel-usti', 2)"></button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- 7. MODALNI OKNA -->
  <div class="modalni-okno-pozadi" id="modal-mapa-rychnov">
    <div class="modalni-okno-obsah">
      <button class="zavrit-modal" onclick="zavritModal('modal-mapa-rychnov')">&times;</button>
      <h2 class="nadpis-sekce" style="font-size: 1.6rem;">MAPA ROZVOZU - RYCHNOV NAD KNĚŽNOU</h2>
      <p style="color: #ccc; margin-bottom: 20px;">Okruh dovozu do 10 km v okolí Rychnova nad Kněžnou.</p>
      <div style="background: #222; padding: 40px; text-align: center; border-radius: 8px; border: 1px dashed #555;">
        <span style="font-size: 3rem;">🗺️</span>
        <p style="color: #f59e0b; margin-top: 10px; font-weight: bold;">Mapa pro Rychnov nad Kněžnou (okruh 10 km)</p>
      </div>
    </div>
  </div>

  <div class="modalni-okno-pozadi" id="modal-mapa-usti">
    <div class="modalni-okno-obsah">
      <button class="zavrit-modal" onclick="zavritModal('modal-mapa-usti')">&times;</button>
      <h2 class="nadpis-sekce" style="font-size: 1.6rem;">MAPA ROZVOZU - ÚSTÍ NAD ORLICÍ</h2>
      <p style="color: #ccc; margin-bottom: 20px;">Okruh dovozu do 10 km v okolí Ústí nad Orlicí.</p>
      <div style="background: #222; padding: 40px; text-align: center; border-radius: 8px; border: 1px dashed #555;">
        <span style="font-size: 3rem;">🗺️</span>
        <p style="color: #f59e0b; margin-top: 10px; font-weight: bold;">Mapa pro Ústí nad Orlicí (okruh 10 km)</p>
      </div>
    </div>
  </div>

  <!-- MODALNI OKNO DETAILU PIZZY -->
  <div class="modalni-okno-pozadi" id="modal-detail-pizzy">
    <div class="modalni-okno-obsah" style="max-width: 650px;">
      <button class="zavrit-modal" onclick="zavritModal('modal-detail-pizzy')">&times;</button>
      <div id="obsah-detailu-pizzy-modal"></div>
    </div>
  </div>

  <div class="modalni-okno-pozadi" id="modal-kosiku">
    <div class="modalni-okno-obsah">
      <button class="zavrit-modal" onclick="zavritModal('modal-kosiku')">&times;</button>
      <h2 class="nadpis-sekce" style="font-size: 1.8rem; margin-bottom: 15px;">VÁŠ NÁKUPNÍ KOŠÍK</h2>
      <div id="seznam-polozek-kosik-modal" class="seznam-polozek-kosiku"></div>
      <div id="celkova-cena-kosik-modal" class="celkova-cena-kosiku">Celkem: 0 Kč</div>

      <div style="margin-top: 25px; text-align: center;">
        <p style="color: #ccc; font-size: 0.9rem; margin-bottom: 15px;">Pro dokončení objednávky zavolejte na vybranou provozovnu:</p>
        <div id="kosik-volat-tlacitko-kontejner">
          <a href="tel:739149142" class="tlacitko tlacitko-cervene">Volat pro objednávku Rychnov (739 149 142)</a>
        </div>
      </div>
    </div>
  </div>

  <!-- MODALNI OKNO PREHLEDU ALERGENU -->
  <div class="modalni-okno-pozadi" id="modal-prehled-alergenu">
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

  <button class="plovuci-kosik-tlacitko" onclick="otvoritModal('modal-kosiku')">
    <span>🛒 Košík</span>
    <span class="pocitadlo-kosiku" id="pocitadlo-kosiku-cislo">0</span>
  </button>

  <script src="js/aplikace.js?v=<?php echo time(); ?>"></script>
</body>
</html>
