<?php
/* ==========================================================================
   STRANKA NAŠE PIZZA (NASE-PIZZA.PHP)
   Vsechny nazvy trid, ID a komentarov jsou v cestine bez diakritiky
   ========================================================================== */

require_once __DIR__ . '/nastaveni.php';
require_once __DIR__ . '/databaze.php';

$suroviny = ziskatSuroviny();
$pizzy = ziskatPizzyProVerejnost();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Naše pizza | PIZZA OD KUŘETE</title>
  <meta name="description" content="Poznejte původ a kvalitu našich surovin pro pizzu. Čerstvé sýry D.O.P. z Itálie, lokální kuřecí maso a 48 hodin zrající těsto.">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styl.css?v=<?php echo filemtime(__DIR__ . '/styl.css'); ?>">

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
          <li><a href="nase-pizza.php" class="navigace-odkaz-polozka aktivni">Naše pizza</a></li>
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

  <!-- PŘÍBĚH NAŠÍ PIZZY (MAGAZÍNOVÉ ZIG-ZAG ROZLOŽENÍ) -->
  <section class="pribeh-pizzy-sekce" id="pribeh-pizzy-sekce">
    <div class="pribeh-pizzy-kontejner">

      <!-- 01: Těsto -->
      <article class="pribeh-pizzy-radek">
        <div class="pribeh-pizzy-foto">
          <img src="https://images.unsplash.com/photo-1579684947550-22e945225d9a?q=80&w=1600&auto=format&fit=crop" alt="Těsto je alfa a omega" loading="lazy">
        </div>
        <div class="pribeh-pizzy-obsah">
          <span class="pribeh-pizzy-cislo">01</span>
          <h3 class="pribeh-pizzy-nadpis">Těsto je alfa a omega</h3>
          <p class="pribeh-pizzy-text">To naše připravujeme ze směsi prvotřídních italských mouk. Díky jejich stálé kvalitě můžeme vypracovat 
              těsto s hydratací 75%, která ve spojení s dlouhou fermentací (48 hodin) znamená nadýchanější a zároveň křupavější těsto, hlubší chuť 
              a lepší stravitelnost. Do těsta také vracíme ty části obilného zrna, které obsahují důležité vitamíny a minerály (pšeničné klíčky a otruby), 
              a přidáváme vlákninu a lněná, slunečnicová a dýňová semínka pro výrazné zlepšení nutričních hodnot.</p>
          <div class="pribeh-pizzy-fakta">
            <div class="pribeh-pizzy-fakt">
              <span class="pribeh-pizzy-fakt-hodnota">75 %</span>
              <span class="pribeh-pizzy-fakt-popis">hydratace</span>
            </div>
            <div class="pribeh-pizzy-fakt">
              <span class="pribeh-pizzy-fakt-hodnota">48 h</span>
              <span class="pribeh-pizzy-fakt-popis">fermentace</span>
            </div>
            <div class="pribeh-pizzy-fakt">
              <span class="pribeh-pizzy-fakt-hodnota">Nutriční hodnoty</span>
              <span class="pribeh-pizzy-fakt-popis">minerály, vitamíny</span>
            </div>
          </div>
        </div>
      </article>

      <!-- 02: Pec -->
      <article class="pribeh-pizzy-radek pribeh-pizzy-radek-obraceny">
        <div class="pribeh-pizzy-foto">
          <img src="https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?q=80&w=1600&auto=format&fit=crop" alt="Pizza nesmí v peci klepat kosu" loading="lazy">
        </div>
        <div class="pribeh-pizzy-obsah">
          <span class="pribeh-pizzy-cislo">02</span>
          <h3 class="pribeh-pizzy-nadpis">Pizza nesmí v peci klepat kosu</h3>
          <p class="pribeh-pizzy-text">Díky použití speciálních kamenů a úpravě pece můžeme pizzu péci při 450°C. Při této teplotě pizza v peci nezůstane déle než dvě a půl minuty, 
              díky čemuž bude okraj příjemně křupavý, zatímco vnitřek okraje zůstane vláčný, měkký a nadýchaný.</p>
          <div class="pribeh-pizzy-fakta">
            <div class="pribeh-pizzy-fakt">
              <span class="pribeh-pizzy-fakt-hodnota">450 °C</span>
              <span class="pribeh-pizzy-fakt-popis">teplota pece</span>
            </div>
            <div class="pribeh-pizzy-fakt">
              <span class="pribeh-pizzy-fakt-hodnota">2,5 min</span>
              <span class="pribeh-pizzy-fakt-popis">doba pečení</span>
            </div>
            <div class="pribeh-pizzy-fakt">
              <span class="pribeh-pizzy-fakt-hodnota">křupavý okraj</span>
              <span class="pribeh-pizzy-fakt-popis">vnitřek vláčný</span>
            </div>
          </div>
        </div>
      </article>

      <!-- 03: Suroviny -->
      <article class="pribeh-pizzy-radek">
        <div class="pribeh-pizzy-foto">
          <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=1600&auto=format&fit=crop" alt="Suroviny pro vybíravé" loading="lazy">
        </div>
        <div class="pribeh-pizzy-obsah">
          <span class="pribeh-pizzy-cislo">03</span>
          <h3 class="pribeh-pizzy-nadpis">Suroviny pro vybíravé</h3>
          <p class="pribeh-pizzy-text">Používáme zeleninu a kuřecí maso od lokálních dodavatelů, 
              sýry a uzeniny výhradně italského původu (až na španělské chorizo). Na naší pizze tedy ochutnáte například originální parmskou šunku 
              (Prosciutto di Parma D.O.P.) a pravý parmezán (Parmigiano reggiano D.O.P.).</p>
          <div class="pribeh-pizzy-fakta">
            <div class="pribeh-pizzy-fakt">
              <span class="pribeh-pizzy-fakt-hodnota">Itálie, Španělsko</span>
              <span class="pribeh-pizzy-fakt-popis">parmská šunka a parmezán</span>
            </div>
            <div class="pribeh-pizzy-fakt">
              <span class="pribeh-pizzy-fakt-hodnota">čerstvá zelenina</span>
              <span class="pribeh-pizzy-fakt-popis">od místních dodavatelů</span>
            </div>
          </div>
        </div>
      </article>

    </div>
  </section>

  <!-- FILTROVANI SUROVIN A MRIZKA -->
  <section class="sekce-jidelni-listek" style="background-color: var(--barva-pozadi-tmava);">
    <div class="kontejner">
      <h2 class="nadpis-sekce" style="text-align: center; margin-bottom: 24px;">INGREDIENCE</h2>
        
        
      <div class="ingredience-zalozky-obal">
        <button type="button" class="ingredience-zalozka-tlacitko aktivni" data-kategorie="syry">ITÁLSKÉ SÝRY</button>
        <button type="button" class="ingredience-zalozka-tlacitko" data-kategorie="maso">MASO & UZENINY</button>
        <button type="button" class="ingredience-zalozka-tlacitko" data-kategorie="zelenina">ZELENINA & BYLINKY</button>
        <button type="button" class="ingredience-zalozka-tlacitko" data-kategorie="omacky">OMÁČKY & TĚSTO</button>
        <button type="button" class="ingredience-zalozka-tlacitko" data-kategorie="specialni">SPECIÁLNÍ</button>
      </div>

      <div class="mrizka-ingredienci mrizka-ingredienci-magazin" id="mrizka-ingredienci-kontejner">
        <?php foreach ($suroviny as $s): ?>
          <?php
            $surovinaNazev = mb_strtolower(trim($s['nazev']));
            $cistyId = mb_strtolower(trim((string)$s['id']));
            $pizzySeSurovinou = array_filter($pizzy, function($p) use ($cistyId, $surovinaNazev) {
              if (isset($p['ingredience']) && is_array($p['ingredience'])) {
                foreach ($p['ingredience'] as $ingId) {
                  if (mb_strtolower(trim((string)$ingId)) === $cistyId) return true;
                }
              }
              if (!empty($p['slozeni']) && $surovinaNazev !== '' && mb_stripos($p['slozeni'], $surovinaNazev) !== false) {
                return true;
              }
              return false;
            });
            $pocetPizz = count($pizzySeSurovinou);
            if ($pocetPizz === 0) {
              continue;
            }
            $katSuroviny = mb_strtolower(trim((string)($s['kategorie'] ?? 'ostatni')));
            $jeAktivniVychozi = ($katSuroviny === 'syry');
          ?>
          <div class="karta-ingredience" id="ingredience-<?php echo htmlspecialchars($s['id']); ?>" data-kategorie="<?php echo htmlspecialchars($katSuroviny); ?>"<?php echo $jeAktivniVychozi ? '' : ' style="display: none;"'; ?>>
            <div class="fotka-ingredience-obal">
              <img src="<?php echo htmlspecialchars($s['obrazek']); ?>" alt="<?php echo htmlspecialchars($s['nazev']); ?>" loading="lazy">
            </div>
            <div class="obsah-ingredience">
              <?php if (!empty($s['puvod'])): ?>
                <span class="ingredience-magazin-puvod"><?php echo htmlspecialchars(zformatovatPuvodSVlajkou($s['puvod'])); ?></span>
              <?php endif; ?>
              <h3 class="nazev-ingredience"><?php echo htmlspecialchars($s['nazev']); ?></h3>
              <p class="popis-ingredience"><?php echo htmlspecialchars($s['popis']); ?></p>
              <button type="button" class="tlacitko-pizzy-surovina ingredience-magazin-odkaz" onclick="otevritModalPizzSuroviny(this, event); return false;">
                <span>V <?php echo $pocetPizz; ?> <?php echo ($pocetPizz === 1) ? 'pizze' : 'pizzách'; ?></span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="ikona-sipka-odkaz"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
              </button>
            </div>

            <!-- PREDPRIPRAVENY OBSAH PRO MODAL SEZNAMU PIZZ -->
            <div class="pizzy-suroviny-obsah" style="display: none;">
              <h2 class="nadpis-sekce" style="font-size: 1.6rem; color: var(--barva-cervena-hlavni, #e50914); margin-bottom: 10px;"><?php echo htmlspecialchars($s['nazev']); ?></h2>
              <p style="color: #ccc; margin-bottom: 20px;">Tuto poctivou surovinu najdete v následujících pizzách z našeho jídelníčku:</p>
              <div>
                <?php if (empty($pizzySeSurovinou)): ?>
                  <p style="color: #aaa; padding: 15px 0;">Zatím žádná pizza v menu neobsahuje tuto surovinu.</p>
                <?php else: ?>
                  <?php foreach ($pizzySeSurovinou as $pizza): ?>
                    <div class="modal-pizza-surovina-radek">
                      <div class="modal-pizza-surovina-info">
                        <strong style="color: #fff; font-size: 1.1rem; display: block; margin-bottom: 2px;">🍕 <?php echo htmlspecialchars($pizza['nazev']); ?> (<?php echo htmlspecialchars($pizza['cena']); ?> Kč)</strong>
                        <small style="color: #aaa; line-height: 1.4; display: block;"><?php echo htmlspecialchars($pizza['slozeni'] ?? ''); ?></small>
                      </div>
                      <button type="button" class="tlacitko tlacitko-cervene tlacitko-pridat-ze-suroviny" onclick="pridatDoKosikuZeSuroviny(<?php echo (int)$pizza['id']; ?>, this)" style="padding: 6px 15px; font-size: 0.85rem; flex-shrink: 0; cursor: pointer; border: none;">Přidat do seznamu</button>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </section>

  <!-- MODALNI OKNO DETAILU SUROVINY (SEZNAM PIZZ) -->
  <div class="modalni-okno-pozadi" id="modal-detail-suroviny" onclick="if(event.target === this) zavritModalSuroviny(event)">
    <div class="modalni-okno-obsah" onclick="event.stopPropagation()">
      <button type="button" class="zavrit-modal" onclick="zavritModalSuroviny(event)" title="Zavřít">&times;</button>
      <div id="modal-detail-suroviny-obsah">
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
  <script src="js/ingredience.js?v=<?php echo filemtime(__DIR__ . '/js/ingredience.js'); ?>"></script>
</body>
</html>
