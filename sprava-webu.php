<?php
/* ==========================================================================
   SPRÁVA WEBU: ARCHITEKTURA, KOMPONENTY A SOUBOROVÁ STRUKTURA
   sprava-webu.php
   ========================================================================== */

session_start();
require_once __DIR__ . '/nastaveni.php';
require_once __DIR__ . '/databaze.php';

// Kontrola prihlaseni do administrace
$jePrihlasen = !empty($_SESSION['prihlasen']);
if (!$jePrihlasen) {
  header('Location: administrace.php');
  exit;
}

// Aktivni zalozka z URL parametru nebo vychozi
$aktivniZalozka = $_GET['zalozka'] ?? 'komponenty';
if (!in_array($aktivniZalozka, ['komponenty', 'struktura'])) {
  $aktivniZalozka = 'komponenty';
}

// Zakladni statistiky
$pocetPizz = 0;
$pocetSurovin = 0;
try {
  $dbData = nactiDatabazi();
  $pocetPizz = count($dbData['pizzy'] ?? []);
  $pocetSurovin = count($dbData['suroviny'] ?? []);
} catch (Exception $e) {}

$cssModuly = [
  'base' => [
    [
      'soubor' => 'css/base/promenne.css',
      'nazev' => 'CSS Proměnné',
      'popis' => 'Centrální definice globálních CSS proměnných – barvy (červená #e50914, tmavé pozadí #0d0d0d, zlatý akcent #f59e0b), písmo Google Font Outfit, rámečky, přechody a stíny.',
      'prvky' => ':root, --barva-cervena-hlavni, --font-hlavni, --stin-karty',
      'vyskyt' => 'Globálně celý web (index, nase-pizza, administrace)',
      'nahled' => '
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
          <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; background: #161616; border: 1px solid #333; border-radius: 6px; font-size: 0.78rem;">
            <span style="width: 14px; height: 14px; background: #e50914; border-radius: 3px; display: inline-block;"></span> #e50914 (Červená)
          </span>
          <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; background: #161616; border: 1px solid #333; border-radius: 6px; font-size: 0.78rem;">
            <span style="width: 14px; height: 14px; background: #f59e0b; border-radius: 3px; display: inline-block;"></span> #f59e0b (Zlatá)
          </span>
          <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; background: #161616; border: 1px solid #333; border-radius: 6px; font-size: 0.78rem;">
            <span style="width: 14px; height: 14px; background: #0d0d0d; border: 1px solid #444; border-radius: 3px; display: inline-block;"></span> #0d0d0d (Pozadí)
          </span>
          <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; background: #161616; border: 1px solid #333; border-radius: 6px; font-size: 0.78rem;">
            <span style="font-weight: 800; font-family: Outfit, sans-serif;">Aa</span> Outfit (Font)
          </span>
        </div>'
    ],
    [
      'soubor' => 'css/base/reset.css',
      'nazev' => 'Reset a základní typografie',
      'popis' => 'Reset okrajů a výplní (*, html, body), nastavení box-sizing, tělo dokumentu, centrální layout kontejner a globální sdílená typografie sekcí.',
      'prvky' => '*, html, body, .kontejner, .nadpis-sekce, .text-popis',
      'vyskyt' => 'Všechny šablony webu',
      'nahled' => '
        <div style="padding: 10px; background: #141416; border-radius: 6px;">
          <div class="nadpis-sekce" style="font-size: 1.15rem; margin-bottom: 6px;">Nadpis sekce (.nadpis-sekce)</div>
          <div class="text-popis" style="font-size: 0.85rem; margin-bottom: 0; line-height: 1.5;">Ukázka standardního odstavcového popisu sekcí se sekundární barvou písma a čitelnou výškou řádku.</div>
        </div>'
    ]
  ],
  'components' => [
    [
      'soubor' => 'css/components/tlacitko.css',
      'nazev' => 'Tlačítka',
      'popis' => 'Základní designový systém tlačítek s hover efekty, barevnými variantami (červená, šedá, tmavě červená) a stíny.',
      'prvky' => '.tlacitko, .tlacitko-cervene, .tlacitko-sede, .tlacitko-cervene-tmave',
      'vyskyt' => 'Celý web a administrace',
      'nahled' => '
        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
          <button type="button" class="tlacitko tlacitko-cervene" style="padding: 6px 14px; font-size: 0.82rem;">Červené</button>
          <button type="button" class="tlacitko tlacitko-cervene-tmave" style="padding: 6px 14px; font-size: 0.82rem;">Tmavě červené</button>
          <button type="button" class="tlacitko tlacitko-sede" style="padding: 6px 14px; font-size: 0.82rem;">Šedé tlačítko</button>
        </div>'
    ],
    [
      'soubor' => 'css/components/modal.css',
      'nazev' => 'Univerzální modální okna',
      'popis' => 'Styly překryvné modální vrstvy s rozostřením pozadí (backdrop-filter: blur), animací zobrazení, centrovaným bílo-černým boxem a zavíracím křížkem.',
      'prvky' => '.modalni-okno-pozadi, .modalni-okno-obsah, .zavrit-modal',
      'vyskyt' => 'Nákupní seznam, alergeny, náhledy surovin',
      'nahled' => '
        <div style="position: relative; background: rgba(0,0,0,0.6); padding: 12px; border-radius: 8px; border: 1px solid #2a2a2a;">
          <div style="background: #161616; border: 1px solid rgba(255,255,255,0.12); border-radius: 8px; padding: 12px 14px; position: relative;">
            <span style="position: absolute; top: 6px; right: 10px; font-size: 1.3rem; color: #888; cursor: pointer;">&times;</span>
            <strong style="color: #ffffff; font-size: 0.88rem; text-transform: uppercase;">Modální dialog</strong>
            <p style="color: #999; font-size: 0.78rem; margin: 4px 0 0 0;">Centrovaný box s tmavým pozadím a rozostřením okolí.</p>
          </div>
        </div>'
    ],
    [
      'soubor' => 'css/components/kosik.css',
      'nazev' => 'Košík / Nákupní seznam',
      'popis' => 'Plovoucí FAB tlačítko v pravém dolním rohu, odznak s počtem kusů, rozbalovací obsah seznamu s ovládacími prvky plus/mínus a celkový cenový součet.',
      'prvky' => '.plovuci-kosik-tlacitko, .polozka-kosiku, .tlacitko-kosik-mnozstvi, .kosik-celkovy-soucet',
      'vyskyt' => 'index.php, nase-pizza.php',
      'nahled' => '
        <div style="display: flex; flex-direction: column; gap: 8px;">
          <div style="display: inline-flex; align-items: center; gap: 10px; background: #e50914; color: #fff; padding: 6px 14px; border-radius: 30px; width: fit-content; font-weight: 700; font-size: 0.84rem; box-shadow: 0 4px 12px rgba(229,9,20,0.45);">
            <span>Seznam</span>
            <span style="background: #ffffff; color: #e50914; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.75rem;">2</span>
          </div>
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 10px; background: #18181f; border-radius: 6px; font-size: 0.82rem;">
            <div><strong>1. Margherita</strong> <span style="color: #888;">(32 cm)</span></div>
            <div style="display: flex; align-items: center; gap: 6px;">
              <span style="background: #262626; padding: 2px 6px; border-radius: 4px; font-weight: 700; font-size: 0.75rem;">- 1 +</span>
              <span style="color: #f59e0b; font-weight: 800;">155 Kč</span>
            </div>
          </div>
        </div>'
    ],
    [
      'soubor' => 'css/components/prepinac-pobocek.css',
      'nazev' => 'Přepínače poboček a kontakty',
      'popis' => 'Horní kontaktní lišta se zkompaktněním při scrollu, plovoucí FAB tlačítko rychlého volání s pulzující animací a přepínače poboček (Ústí nad Labem / Teplice).',
      'prvky' => '.horni-kontakty-lista, .plovuci-volani-tlacitko, .podokno-volani-box, .pobocky-kapsle-obal',
      'vyskyt' => 'Hlavička webu, sekce Provozovny',
      'nahled' => '
        <div style="display: flex; flex-direction: column; gap: 8px;">
          <div class="mini-kapsle-prepinac" style="width: fit-content;">
            <button type="button" class="mini-kapsle-tlacitko aktivni">Ústí nad Labem</button>
            <button type="button" class="mini-kapsle-tlacitko">Teplice</button>
          </div>
          <div style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.8rem; color: #9ca3af;">
            <span style="width: 28px; height: 28px; border-radius: 50%; background: #e50914; border: 2px solid #fff; display: inline-flex; align-items: center; justify-content: center; color: #fff; font-weight: 900; font-size: 0.7rem; box-shadow: 0 2px 8px rgba(229,9,20,0.6);">TEL</span>
            <span>Plovoucí tlačítko přímého volání</span>
          </div>
        </div>'
    ],
    [
      'soubor' => 'css/components/navigace-podstranka.css',
      'nazev' => 'Navigační lišta podstránek',
      'popis' => 'Plnohodnotná responzivní navigační lišta pro podstránky (jako Naše pizza) s logem, telefonem, odkazy, mobilním hamburgerem a off-canvas výsuvným menu.',
      'prvky' => '.navigace-podstranka-obsah, .hamburger-tlacitko, .mobilni-menu-vysuvne',
      'vyskyt' => 'nase-pizza.php',
      'nahled' => '
        <div style="background: #000; border: 1px solid #262626; border-radius: 6px; padding: 8px 12px; display: flex; justify-content: space-between; align-items: center;">
          <div style="font-weight: 900; font-size: 0.8rem; color: #fff;">PIZZA OD KUŘETE</div>
          <div style="display: flex; gap: 10px; font-size: 0.75rem; font-weight: 700; color: #aaa;">
            <span style="color: #e50914;">Menu</span>
            <span>O nás</span>
            <span>Rozvoz</span>
          </div>
          <div style="background: #222; border: 1px solid #444; border-radius: 4px; padding: 2px 6px; font-size: 0.7rem; font-weight: 800; color: #fff;">MENU</div>
        </div>'
    ],
    [
      'soubor' => 'css/components/modal-ingredience.css',
      'nazev' => 'Náhled suroviny (Pop-up)',
      'popis' => 'Velké modální okno zobrazující detail suroviny: velkou fotografii, nutriční hodnoty (bílkoviny, tuky, sacharidy, kalorie), popis původu a seznam pizz obsahujících tuto surovinu.',
      'prvky' => '.modal-nahled-ingredience-obsah, .nahled-ingredience-nutricni-grid, .modal-pizza-surovina-radek',
      'vyskyt' => 'index.php, nase-pizza.php',
      'nahled' => '
        <div style="background: #141418; border: 1px solid #282830; border-radius: 8px; padding: 10px; display: flex; gap: 10px; align-items: center;">
          <div style="width: 70px; height: 50px; background: #222; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 0.68rem; color: #777;">FOTO</div>
          <div style="flex-grow: 1;">
            <strong style="color: #fff; font-size: 0.82rem; display: block; text-transform: uppercase;">San Marzano D.O.P.</strong>
            <span style="color: #999; font-size: 0.72rem;">22 kcal | Bílkoviny 1.1 g</span>
          </div>
          <button type="button" class="tlacitko" style="background: #2ed573; color: #fff; padding: 4px 8px; font-size: 0.72rem; border: none; border-radius: 4px; font-weight: 700;">Přidáno (1 ks)</button>
        </div>'
    ]
  ],
  'blocks' => [
    [
      'soubor' => 'css/blocks/hero.css',
      'nazev' => 'Hero sekce (Úvod)',
      'popis' => 'Úvodní banner s fotografií pizzeristy a pece, logo pizzerie, hlavní nadpis, dynamický štítek otevírací doby v reálném čase a tlačítka aplikací.',
      'prvky' => '.sekce-uvod-hero, .obsah-hero, .logo-kure-ikona, .oteviraci-doba-stitek',
      'vyskyt' => 'index.php (hlavní stránka)',
      'nahled' => '
        <div style="background: linear-gradient(180deg, #18181b 0%, #0d0d0f 100%); border: 1px solid #282830; border-radius: 8px; padding: 12px; text-align: center;">
          <span class="oteviraci-doba-stitek" style="display: inline-block; font-size: 0.72rem; padding: 3px 10px; margin-bottom: 6px;">PRÁVĚ MÁME OTEVŘENO: 11:00 - 22:00</span>
          <div style="margin: 2px 0 6px 0; font-size: 0.95rem; font-weight: 900; text-transform: uppercase; color: #fff;">POCTIVÁ NEAPOLSKÁ PIZZA</div>
          <div style="display: flex; gap: 6px; justify-content: center;">
            <span style="background: #222; border: 1px solid #333; padding: 3px 10px; border-radius: 4px; font-size: 0.72rem; color: #fff; font-weight: 700;">App Store</span>
            <span style="background: #222; border: 1px solid #333; padding: 3px 10px; border-radius: 4px; font-size: 0.72rem; color: #fff; font-weight: 700;">Google Play</span>
          </div>
        </div>'
    ],
    [
      'soubor' => 'css/blocks/rozdil-v-chuti.css',
      'nazev' => 'Rozdíl v chuti',
      'popis' => 'Sekce „Rozdíl v chuti poznáte na první zobnutí“: edge-to-edge klikací banner, vizitka pizzeristy Kuřete a 3 dlaždice klíčových surovin s prémiovým stínováním.',
      'prvky' => '.sekce-rozdil-v-chuti, .rozdil-hero-banner, .kure-predstaveni-banner, .rozdil-surovina-karta',
      'vyskyt' => 'index.php',
      'nahled' => '
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
          <div style="background: #161616; border: 1px solid rgba(229,9,20,0.4); border-radius: 6px; padding: 10px;">
            <div style="color: #e50914; font-size: 1.2rem; font-weight: 900; line-height: 1;">01</div>
            <strong style="color: #fff; font-size: 0.8rem; text-transform: uppercase;">Mouka Caputo</strong>
            <p style="color: #888; font-size: 0.7rem; margin: 2px 0 0 0;">Pšeničná mouka typu 00 z Neapole.</p>
          </div>
          <div style="background: #161616; border: 1px solid #2a2a2a; border-radius: 6px; padding: 10px;">
            <div style="color: #e50914; font-size: 1.2rem; font-weight: 900; line-height: 1;">02</div>
            <strong style="color: #fff; font-size: 0.8rem; text-transform: uppercase;">San Marzano</strong>
            <p style="color: #888; font-size: 0.7rem; margin: 2px 0 0 0;">Vulkanická rajčata pod Vesuvem.</p>
          </div>
        </div>'
    ],
    [
      'soubor' => 'css/blocks/jidelni-listek.css',
      'nazev' => 'Jídelní lístek & filtry',
      'popis' => 'Mřížka dlaždic pizz, interaktivní kapslové filtry (sugo / bílý základ, pálivost, masitost), kategorie (nejprodávanější, doporučujeme), zvýrazněná Pizza týdne a tlačítka rychlého vložení do košíku.',
      'prvky' => '.sekce-jidelni-listek, .mini-kapsle-prepinac, .mrizka-pizz, .karta-pizzy, .tlacitko-pridat-plus',
      'vyskyt' => 'index.php',
      'nahled' => '
        <div style="display: flex; flex-direction: column; gap: 8px;">
          <div style="display: flex; gap: 6px; flex-wrap: wrap;">
            <span class="tlacitko-kategorie aktivni" style="padding: 4px 10px; font-size: 0.72rem;">VŠECHNY</span>
            <span class="tlacitko-kategorie" style="padding: 4px 10px; font-size: 0.72rem;">NEJPRODÁVANĚJŠÍ</span>
          </div>
          <div class="karta-pizzy" style="padding: 10px; position: relative;">
            <div class="ciselny-odznak" style="width: 20px; height: 20px; font-size: 0.75rem; top: 8px; left: 8px;">1</div>
            <div style="height: 45px; background: #1a1a1a; border-radius: 4px; margin-bottom: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; color: #666;">FOTO PIZZY</div>
            <strong class="nazev-pizzy" style="font-size: 0.85rem; margin-bottom: 2px;">MARGHERITA</strong>
            <div class="patka-karty-pizzy" style="padding-top: 6px;">
              <span class="cena-pizzy" style="font-size: 0.95rem;">155 Kč</span>
              <button type="button" class="tlacitko-pridat-plus" style="width: 26px; height: 26px; font-size: 1rem;">+</button>
            </div>
          </div>
        </div>'
    ],
    [
      'soubor' => 'css/blocks/rozvoz.css',
      'nazev' => 'Rozvoz a interaktivní mapa',
      'popis' => 'Sekce rozvozu: 3 karty parametrů doručení, interaktivní mapa s rozvozovými zónami a vyhledávač obcí s okamžitým zobrazením ceny doručení a kilometráže.',
      'prvky' => '.sekce-rozvoz, .mapa-ram, .blok-vyhledavani-obce, .karta-pasmo, .stitek-obec-polozka',
      'vyskyt' => 'index.php',
      'nahled' => '
        <div style="display: flex; flex-direction: column; gap: 6px;">
          <div class="karta-pasmo pasmo-1" style="padding: 8px 10px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <strong style="color: #fff; font-size: 0.78rem;">PÁSMO 1 (MĚSTO)</strong>
              <span class="cena-odznak zdarma" style="font-size: 0.7rem; padding: 2px 6px;">ZDARMA</span>
            </div>
          </div>
          <div style="display: flex; gap: 4px; flex-wrap: wrap;">
            <span class="stitek-obec-polozka" style="font-size: 0.7rem; padding: 2px 6px;">Centrum (zdarma)</span>
            <span class="stitek-obec-polozka shoda" style="font-size: 0.7rem; padding: 2px 6px;">Povrly (8 km) 49 Kč</span>
          </div>
        </div>'
    ],
    [
      'soubor' => 'css/blocks/provozovny.css',
      'nazev' => 'Provozovna a kontakty',
      'popis' => 'Karta provozovny: přesná adresa, tabulka otevírací doby pro každý den v týdnu, tlačítka rychlého volání, sociální sítě a fotogalerie provozovny s karuselem.',
      'prvky' => '.sekce-provozovny, .karta-provozovny, .oteviraci-doba-tabulka, .karusel-provozovna',
      'vyskyt' => 'index.php',
      'nahled' => '
        <div style="background: #141418; border: 1px solid #282830; border-radius: 6px; padding: 10px; font-size: 0.75rem;">
          <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #242424; padding-bottom: 4px; margin-bottom: 4px;">
            <strong style="color: #fff;">POBOČKA ÚSTÍ NAD LABEM</strong>
            <span style="color: #f59e0b; font-weight: 700;">OTEVŘENO</span>
          </div>
          <div style="color: #aaa; margin-bottom: 4px;">Mírové náměstí 102/5</div>
          <div style="display: flex; justify-content: space-between; color: #888;">
            <span>Po - Čt:</span> <span style="color: #fff;">11:00 - 22:00</span>
          </div>
        </div>'
    ],
    [
      'soubor' => 'css/blocks/karusel.css',
      'nazev' => 'Hero karusel (Naše pizza)',
      'popis' => 'Velký fotografický karusel přes celou šířku obrazovky na stránce Naše pizza. Plynulý dotykový i tlačítkový posun, navigace šipkami a tečkami.',
      'prvky' => '.hlavni-karusel-sekce, .hlavni-karusel-pas, .hlavni-karusel-snimek, .hlavni-karusel-sipka',
      'vyskyt' => 'nase-pizza.php',
      'nahled' => '
        <div style="background: #000; border: 1px solid #282830; border-radius: 6px; padding: 12px; text-align: center;">
          <div style="font-size: 0.68rem; color: #f59e0b; font-weight: 800; text-transform: uppercase;">PŘÍBĚH NAŠÍ PIZZY</div>
          <strong style="color: #fff; font-size: 0.82rem; display: block; margin: 2px 0 6px 0;">RUČNĚ VYTÁHOVANÉ TĚSTO</strong>
          <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
            <span style="background: #222; color: #fff; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.65rem;">&lsaquo;</span>
            <span style="color: #888; font-size: 0.65rem; letter-spacing: 2px;">• — •</span>
            <span style="background: #222; color: #fff; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.65rem;">&rsaquo;</span>
          </div>
        </div>'
    ],
    [
      'soubor' => 'css/blocks/pribeh-pizzy.css',
      'nazev' => 'Příběh pizzy (Magazín)',
      'popis' => 'Magazínové zig-zag rozložení surovin: střídání velkých fotografií s textovými příběhy, nutriční karty faktů a tlačítka na zobrazení pizz, které danou surovinu obsahují.',
      'prvky' => '.pribeh-pizzy-sekce, .pribeh-pizzy-radek, .pribeh-pizzy-fakta-grid, .tlacitko-pizzy-surovina',
      'vyskyt' => 'nase-pizza.php',
      'nahled' => '
        <div style="display: grid; grid-template-columns: 60px 1fr; gap: 10px; background: #141418; border: 1px solid #282830; border-radius: 6px; padding: 8px; align-items: center;">
          <div style="height: 50px; background: #222; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; color: #777;">FOTO</div>
          <div>
            <strong style="color: #fff; font-size: 0.78rem; text-transform: uppercase; display: block;">Tradiční pečení</strong>
            <p style="color: #999; font-size: 0.7rem; margin: 2px 0 0 0;">Pečení při 450 °C v peci na dřevo.</p>
          </div>
        </div>'
    ]
  ],
  'responsive' => [
    [
      'soubor' => 'css/responsive/mobile.css',
      'nazev' => 'Mobilní responzivita',
      'popis' => 'Kompletní mobilní pravidla importovaná na samém konci kaskády. Obsahuje hardware optimalizaci (vypnutí těžkého GPU blur na mobilech), adaptaci písma, mřížek a nákupního seznamu pro displeje pod 768px, 640px a 480px.',
      'prvky' => '@media (max-width: 768px), @media (max-width: 480px), .horni-kontakty-lista.horni-lista-index',
      'vyskyt' => 'Celý web na mobilních zařízeních',
      'nahled' => '
        <div style="border: 2px solid #333; border-radius: 10px; padding: 8px; background: #000; width: 120px; margin: 0 auto; text-align: center;">
          <div style="width: 24px; height: 2px; background: #444; border-radius: 2px; margin: 0 auto 6px auto;"></div>
          <div style="background: #e50914; height: 14px; border-radius: 2px; margin-bottom: 4px;"></div>
          <div style="background: #222; height: 10px; border-radius: 2px; margin-bottom: 4px;"></div>
          <div style="background: #222; height: 10px; border-radius: 2px;"></div>
          <div style="font-size: 0.6rem; color: #888; margin-top: 6px;">Do 768px / 480px</div>
        </div>'
    ]
  ]
];

$strukturaSouboru = [
  'php' => [
    ['cesta' => 'index.php', 'popis' => 'Hlavní vstupní stránka webu pizzerie. Zobrazuje hero sekci, rozdíl v chuti, jídelní lístek s filtry, rozvoz s mapou a kontakty na provozovnu. Načítá data o pizzách z databáze a generuje dynamický nákupní seznam.'],
    ['cesta' => 'nase-pizza.php', 'popis' => 'Magazínová podstránka zaměřená na filozofii řemeslného těsta a kvalitu surovin. Obsahuje velký fotografický karusel, zig-zag bloky surovin s nutričními fakty a interaktivní pop-up okna.'],
    ['cesta' => 'administrace.php', 'popis' => 'Zabezpečené administrační rozhraní chráněné heslem. Umožňuje plnou správu pizz (přidávání, úpravy, mazání, Pizza týdne, nahrávání fotek), správu surovin a automatické zálohování/obnovu databáze.'],
    ['cesta' => 'sprava-webu.php', 'popis' => 'Tato dokumentační a přehledová stránka správy webu. Přináší vizualizaci komponent design systému a kompletní souborovou strukturu projektu s vysvětlením.'],
    ['cesta' => 'databaze.php', 'popis' => 'Centrální backendový modul pro čtení a ukládání JSON dat. Zajišťuje bezpečný atomický zápis přes dočasné soubory, automatické vytváření časových záloh a synchronizaci datových souborů.'],
    ['cesta' => 'nastaveni.php', 'popis' => 'Základní konfigurační soubor pizzerie. Definuje heslo do administrace, cesty k adresářům a prostředí serveru.']
  ],
  'css' => [
    ['cesta' => 'styl.css', 'popis' => 'Hlavní CSS manifest webu. Obsahuje přesně seřazené @import direktivy načítající jednotlivé moduly v přesném pořadí kaskády (Base → Components → Blocks → Responsive).'],
    ['cesta' => 'css/base/', 'popis' => 'Adresář základních pravidel: definice barevných proměnných, reset prvků a globální sdílená typografie sekcí.'],
    ['cesta' => 'css/components/', 'popis' => 'Znovupoužitelné designové komponenty: tlačítka, modální okna, košík, přepínače poboček, podstránková navigace a náhledy surovin.'],
    ['cesta' => 'css/blocks/', 'popis' => 'Konkrétní bloky a sekce stránek: hero, rozdíl v chuti, jídelní lístek, rozvoz s mapou, provozovna, karusel a magazínový příběh pizzy.'],
    ['cesta' => 'css/responsive/', 'popis' => 'Mobilní responzivní vrstva importovaná na konci kaskády pro displeje tabletů a chytrých telefonů.'],
    ['cesta' => 'css/administrace.css', 'popis' => 'Specializované styly pro rozhraní webové administrace (formuláře, tabulky, editační okna a správa záloh).']
  ],
  'js' => [
    ['cesta' => 'js/aplikace.js', 'popis' => 'Klientská logika pro hlavní stránku webu: filtrování pizz podle kategorií a surovin, plovoucí nákupní seznam (přidávání/odebírání položek, výpočet součtu), otevírací doba v reálném čase a vyhledávač obcí rozvozu.'],
    ['cesta' => 'js/ingredience.js', 'popis' => 'Klientská logika pro podstránku Naše pizza: obsluha velkého hero karuselu, filtrování záložek surovin, otevírání pop-up modálů s vazbou na pizzy a synchronizace stavu tlačítek s košíkem.'],
    ['cesta' => 'js/administrace.js', 'popis' => 'Podpůrná klientská logika pro administraci (potvrzovací dialogy při mazání, přepínání záložek a živý náhled při úpravách).'],
    ['cesta' => 'aplikace.js / ingredience.js', 'popis' => 'Kořenové zrcadlové kopie skriptů udržované v identickém stavu se složkou js/ pro maximální kompatibilitu hostingu.']
  ],
  'data' => [
    ['cesta' => 'data/databaze.json', 'popis' => 'Primární databáze celého webu ve formátu JSON. Obsahuje kompletní pole všech pizz, surovin, kategorií a provozních nastavení.'],
    ['cesta' => 'data/pizzy.json', 'popis' => 'Samostatně synchronizovaný JSON export seznamu všech pizz pro rychlé čtení a externí integrace.'],
    ['cesta' => 'data/ingredience.json', 'popis' => 'Samostatně synchronizovaný JSON export všech surovin s jejich popisy a nutričními parametry.'],
    ['cesta' => 'zalohy/', 'popis' => 'Adresář automatických záloh. Před každým uložením změn v administraci je zde vytvořen časově označený snapshot databáze pro možnost okamžité obnovy jedním kliknutím.']
  ],
  'media' => [
    ['cesta' => 'obrazky/', 'popis' => 'Adresář pro nahrané fotografie pizz a surovin z webové administrace.'],
    ['cesta' => 'media/logo/', 'popis' => 'Originální vektorová a rastrová loga pizzerie Pizza od Kuřete.'],
    ['cesta' => 'media/obrazky/', 'popis' => 'Statické fotografie provozovny a grafické prvky rozhraní.'],
    ['cesta' => 'GEMINI.md', 'popis' => 'Klíčová projektová pravidla vývoje: přísný zákaz nepřidávat nevyžádané prvky, ochrana globálních stylů a povinnost uvádět seznam souborů pro FTP po každé úpravě.']
  ]
];
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Správa webu & Architektura | PIZZA OD KUŘETE</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styl.css?v=<?php echo filemtime(__DIR__ . '/styl.css'); ?>">
  <link rel="stylesheet" href="css/administrace.css?v=<?php echo filemtime(__DIR__ . '/css/administrace.css'); ?>">
  <style>
    body {
      background-color: #0b0c10;
      color: #e5e7eb;
      font-family: 'Outfit', sans-serif;
      margin: 0;
      padding-bottom: 80px;
    }

    .sprava-kontejner {
      max-width: 1240px;
      margin: 30px auto;
      padding: 0 20px;
    }

    .horni-kontakty-lista {
      position: sticky !important;
      top: 0 !important;
      z-index: 1000;
      background-color: rgba(13, 13, 16, 0.96) !important;
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      padding: 10px 20px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);
    }

    .sprava-hlavicka {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      flex-wrap: wrap;
      gap: 15px;
    }

    .sprava-titul {
      font-size: 2.1rem;
      font-weight: 900;
      color: #ffffff;
      margin: 0;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .sprava-titul span {
      color: var(--barva-cervena-hlavni, #e50914);
    }

    .sprava-perex {
      color: #9ca3af;
      font-size: 1.05rem;
      margin-top: 6px;
      max-width: 800px;
      line-height: 1.6;
    }

    /* KARTY STATISTIK */
    .mrizka-statistik {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 16px;
      margin-bottom: 35px;
    }

    .karta-statistika {
      background: #14151a;
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 12px;
      padding: 18px 20px;
      display: flex;
      flex-direction: column;
      gap: 6px;
      transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .karta-statistika:hover {
      transform: translateY(-2px);
      border-color: rgba(229, 9, 20, 0.4);
    }

    .statistika-cislo {
      font-size: 2rem;
      font-weight: 900;
      color: #ffffff;
      line-height: 1;
    }

    .statistika-cislo.cervena { color: #e50914; }
    .statistika-cislo.zlata { color: #f59e0b; }
    .statistika-cislo.zelena { color: #10b981; }
    .statistika-cislo.modra { color: #3b82f6; }

    .statistika-popis {
      color: #9ca3af;
      font-size: 0.88rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    /* ZÁLOŽKOVÁ NAVIGACE */
    .admin-zalozky-obal {
      display: flex;
      gap: 36px;
      margin-bottom: 30px;
      border-bottom: 2px solid #22232a;
      padding-bottom: 0;
    }

    .admin-zalozka-tlacitko {
      padding: 12px 6px 16px 6px;
      background: transparent;
      border: none;
      border-bottom: 4px solid transparent;
      margin-bottom: -2px;
      border-radius: 0;
      color: #888888;
      font-size: 1.15rem;
      font-weight: 700;
      cursor: pointer;
      transition: color 0.2s ease, border-color 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      font-family: inherit;
    }

    .admin-zalozka-tlacitko:hover {
      color: #ffffff;
    }

    .admin-zalozka-tlacitko.aktivni {
      border-color: #e50914;
      color: #ffffff;
      font-weight: 800;
    }

    .admin-zalozka-obsah {
      display: none;
    }

    .admin-zalozka-obsah.aktivni {
      display: block;
      animation: fadeIn 0.25s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(6px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* SEKCE A KARTY KOMPONENT */
    .skupina-sekce-obal {
      margin-bottom: 40px;
    }

    .skupina-nadpis-obal {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 18px;
      padding-bottom: 10px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .skupina-nadpis {
      font-size: 1.35rem;
      font-weight: 800;
      color: #ffffff;
      text-transform: uppercase;
      margin: 0;
    }

    .skupina-stitek {
      font-size: 0.75rem;
      font-weight: 800;
      padding: 3px 10px;
      border-radius: 20px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .stitek-base { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.4); }
    .stitek-comp { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.4); }
    .stitek-block { background: rgba(229, 9, 20, 0.2); color: #f87171; border: 1px solid rgba(229, 9, 20, 0.4); }
    .stitek-resp { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); }

    .mrizka-komponent {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
      gap: 20px;
    }

    .karta-komponenty {
      background: #14151a;
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 14px;
      padding: 22px;
      display: flex;
      flex-direction: column;
      gap: 12px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
      transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .karta-komponenty:hover {
      transform: translateY(-3px);
      border-color: rgba(255, 255, 255, 0.2);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
    }

    .komponenta-hlavicka {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 10px;
    }

    .komponenta-nazev {
      font-size: 1.15rem;
      font-weight: 800;
      color: #ffffff;
      margin: 0;
    }

    .kod-soubor {
      font-family: Consolas, monospace;
      font-size: 0.8rem;
      color: #f59e0b;
      background: rgba(245, 158, 11, 0.1);
      padding: 3px 8px;
      border-radius: 6px;
      border: 1px solid rgba(245, 158, 11, 0.25);
      display: inline-block;
      word-break: break-all;
    }

    .komponenta-popis {
      color: #d1d5db;
      font-size: 0.92rem;
      line-height: 1.55;
      margin: 0;
      flex-grow: 1;
    }

    /* NÁHLED PRVKU */
    .nahled-prvku-obal {
      background: #0d0d10;
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 10px;
      padding: 14px;
      margin-top: 4px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .nahled-prvku-popisek {
      font-size: 0.72rem;
      font-weight: 800;
      color: #9ca3af;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      margin-bottom: 2px;
    }

    .komponenta-meta {
      display: flex;
      flex-direction: column;
      gap: 6px;
      padding-top: 10px;
      border-top: 1px solid rgba(255, 255, 255, 0.06);
      font-size: 0.82rem;
    }

    .meta-radek {
      display: flex;
      gap: 8px;
    }

    .meta-label {
      color: #9ca3af;
      font-weight: 700;
      min-width: 70px;
      flex-shrink: 0;
    }

    .meta-hodnota {
      color: #e5e7eb;
    }

    .meta-hodnota code {
      font-family: Consolas, monospace;
      color: #60a5fa;
      background: rgba(59, 130, 246, 0.1);
      padding: 1px 5px;
      border-radius: 4px;
    }

    /* TABULKA A KARTY STRUKTURY SOUBORŮ */
    .strom-prehled-box {
      background: #0f1015;
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 14px;
      padding: 24px;
      margin-bottom: 35px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    }

    .strom-kod {
      font-family: Consolas, 'Courier New', monospace;
      font-size: 0.88rem;
      line-height: 1.7;
      color: #d1d5db;
      margin: 0;
      white-space: pre;
      overflow-x: auto;
    }

    .strom-kod .s-slozka { color: #f59e0b; font-weight: 700; }
    .strom-kod .s-php { color: #60a5fa; }
    .strom-kod .s-css { color: #34d399; }
    .strom-kod .s-js { color: #fbbf24; }
    .strom-kod .s-json { color: #f87171; }
    .strom-kod .s-komentar { color: #6b7280; font-style: italic; }

    .kategorie-souboru-box {
      margin-bottom: 30px;
    }

    .tabulka-souboru {
      width: 100%;
      border-collapse: collapse;
      background: #14151a;
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 12px;
      overflow: hidden;
    }

    .tabulka-souboru th {
      background: #1a1b22;
      color: #ffffff;
      text-align: left;
      padding: 14px 18px;
      font-weight: 800;
      font-size: 0.88rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .tabulka-souboru td {
      padding: 14px 18px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.06);
      font-size: 0.92rem;
      vertical-align: top;
    }

    .tabulka-souboru tr:last-child td {
      border-bottom: none;
    }

    .tabulka-souboru tr:hover td {
      background: rgba(255, 255, 255, 0.02);
    }

    .soubor-badge {
      font-family: Consolas, monospace;
      font-size: 0.85rem;
      font-weight: 700;
      padding: 4px 10px;
      border-radius: 6px;
      display: inline-block;
      white-space: nowrap;
    }

    .badge-php { background: rgba(59, 130, 246, 0.15); color: #93c5fd; border: 1px solid rgba(59, 130, 246, 0.3); }
    .badge-css { background: rgba(16, 185, 129, 0.15); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.3); }
    .badge-js { background: rgba(245, 158, 11, 0.15); color: #fde68a; border: 1px solid rgba(245, 158, 11, 0.3); }
    .badge-data { background: rgba(239, 68, 68, 0.15); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); }
    .badge-media { background: rgba(168, 85, 247, 0.15); color: #d8b4fe; border: 1px solid rgba(168, 85, 247, 0.3); }

    @media (max-width: 768px) {
      .sprava-titul { font-size: 1.6rem; }
      .admin-zalozky-obal { gap: 18px; }
      .admin-zalozka-tlacitko { font-size: 0.95rem; }
      .mrizka-komponent { grid-template-columns: 1fr; }
      .tabulka-souboru { display: block; overflow-x: auto; }
    }
  </style>
</head>
<body>

  <!-- HORNI LISTA -->
  <header class="horni-kontakty-lista">
    <div class="kontejner kontakty-obsah" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
      <div style="display: flex; align-items: center; gap: 15px;">
        <span style="font-weight: 800; color: #ffffff; letter-spacing: 0.5px;">ADMINISTRACE PIZZERIE</span>
        <span style="color: #6b7280; font-size: 0.9rem;">/</span>
        <span style="font-weight: 700; color: #f59e0b; font-size: 0.9rem;">Správa webu</span>
      </div>
      <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <a href="administrace.php" class="tlacitko tlacitko-cervene" style="padding: 6px 16px; font-size: 0.85rem; text-decoration: none;">&lsaquo; Administrace</a>
        <a href="index.php" class="tlacitko tlacitko-sede" style="padding: 6px 16px; font-size: 0.85rem; text-decoration: none;">Zpět na web</a>
        <a href="administrace.php?akce=odhlasit" class="tlacitko tlacitko-sede" style="padding: 6px 16px; font-size: 0.85rem; text-decoration: none;">Odhlásit se</a>
      </div>
    </div>
  </header>

  <div class="sprava-kontejner">

    <!-- HLAVIČKA STRÁNKY -->
    <div class="sprava-hlavicka">
      <div>
        <h1 class="sprava-titul">Správa webu & <span>Architektura</span></h1>
        <p class="sprava-perex">
          Kompletní přehled stavebních kamenů projektu <strong>Pizza od Kuřete</strong>. Projekt využívá modulární strukturu podle standardu <strong>STETSON</strong> s plným oddělením bází, komponent, bloků a responzivní vrstvy.
        </p>
      </div>
    </div>

    <!-- SOUHRNNÉ KARTY -->
    <div class="mrizka-statistik">
      <div class="karta-statistika">
        <span class="statistika-cislo cervena"><?php echo $pocetPizz; ?></span>
        <span class="statistika-popis">Aktivních pizz v menu</span>
      </div>
      <div class="karta-statistika">
        <span class="statistika-cislo zlata"><?php echo $pocetSurovin; ?></span>
        <span class="statistika-popis">Surovin v databázi</span>
      </div>
      <div class="karta-statistika">
        <span class="statistika-cislo zelena">16</span>
        <span class="statistika-popis">Modulárních CSS souborů</span>
      </div>
      <div class="karta-statistika">
        <span class="statistika-cislo modra">33</span>
        <span class="statistika-popis">Řádků v styl.css manifestu</span>
      </div>
    </div>

    <!-- PŘEPÍNAČ ZÁLOŽEK -->
    <div class="admin-zalozky-obal">
      <button type="button" class="admin-zalozka-tlacitko <?php echo ($aktivniZalozka === 'komponenty') ? 'aktivni' : ''; ?>" onclick="prepnoutZalozkuSpravy('komponenty')">
        Seznam komponent
      </button>
      <button type="button" class="admin-zalozka-tlacitko <?php echo ($aktivniZalozka === 'struktura') ? 'aktivni' : ''; ?>" onclick="prepnoutZalozkuSpravy('struktura')">
        Souborová struktura
      </button>
    </div>

    <!-- ====================================================================
         ZÁLOŽKA 1: SEZNAM KOMPONENT
         ==================================================================== -->
    <div id="sekce-zalozka-komponenty" class="admin-zalozka-obsah <?php echo ($aktivniZalozka === 'komponenty') ? 'aktivni' : ''; ?>">

      <!-- 1. BASE -->
      <div class="skupina-sekce-obal">
        <div class="skupina-nadpis-obal">
          <h2 class="skupina-nadpis">1. Základní vrstva (Base)</h2>
          <span class="skupina-stitek stitek-base">css/base/</span>
        </div>
        <div class="mrizka-komponent">
          <?php foreach ($cssModuly['base'] as $modul): ?>
            <div class="karta-komponenty">
              <div class="komponenta-hlavicka">
                <h3 class="komponenta-nazev"><?php echo htmlspecialchars($modul['nazev']); ?></h3>
              </div>
              <span class="kod-soubor"><?php echo htmlspecialchars($modul['soubor']); ?></span>
              <p class="komponenta-popis"><?php echo htmlspecialchars($modul['popis']); ?></p>

              <!-- NÁHLED PRVKU -->
              <div class="nahled-prvku-obal">
                <span class="nahled-prvku-popisek">Náhled prvku</span>
                <?php echo $modul['nahled']; ?>
              </div>

              <div class="komponenta-meta">
                <div class="meta-radek">
                  <span class="meta-label">Prvky:</span>
                  <span class="meta-hodnota"><code><?php echo htmlspecialchars($modul['prvky']); ?></code></span>
                </div>
                <div class="meta-radek">
                  <span class="meta-label">Výskyt:</span>
                  <span class="meta-hodnota"><?php echo htmlspecialchars($modul['vyskyt']); ?></span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 2. COMPONENTS -->
      <div class="skupina-sekce-obal">
        <div class="skupina-nadpis-obal">
          <h2 class="skupina-nadpis">2. Znovupoužitelné komponenty (Components)</h2>
          <span class="skupina-stitek stitek-comp">css/components/</span>
        </div>
        <div class="mrizka-komponent">
          <?php foreach ($cssModuly['components'] as $modul): ?>
            <div class="karta-komponenty">
              <div class="komponenta-hlavicka">
                <h3 class="komponenta-nazev"><?php echo htmlspecialchars($modul['nazev']); ?></h3>
              </div>
              <span class="kod-soubor"><?php echo htmlspecialchars($modul['soubor']); ?></span>
              <p class="komponenta-popis"><?php echo htmlspecialchars($modul['popis']); ?></p>

              <!-- NÁHLED PRVKU -->
              <div class="nahled-prvku-obal">
                <span class="nahled-prvku-popisek">Náhled prvku</span>
                <?php echo $modul['nahled']; ?>
              </div>

              <div class="komponenta-meta">
                <div class="meta-radek">
                  <span class="meta-label">Prvky:</span>
                  <span class="meta-hodnota"><code><?php echo htmlspecialchars($modul['prvky']); ?></code></span>
                </div>
                <div class="meta-radek">
                  <span class="meta-label">Výskyt:</span>
                  <span class="meta-hodnota"><?php echo htmlspecialchars($modul['vyskyt']); ?></span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 3. BLOCKS -->
      <div class="skupina-sekce-obal">
        <div class="skupina-nadpis-obal">
          <h2 class="skupina-nadpis">3. Bloky sekcí webu (Blocks)</h2>
          <span class="skupina-stitek stitek-block">css/blocks/</span>
        </div>
        <div class="mrizka-komponent">
          <?php foreach ($cssModuly['blocks'] as $modul): ?>
            <div class="karta-komponenty">
              <div class="komponenta-hlavicka">
                <h3 class="komponenta-nazev"><?php echo htmlspecialchars($modul['nazev']); ?></h3>
              </div>
              <span class="kod-soubor"><?php echo htmlspecialchars($modul['soubor']); ?></span>
              <p class="komponenta-popis"><?php echo htmlspecialchars($modul['popis']); ?></p>

              <!-- NÁHLED PRVKU -->
              <div class="nahled-prvku-obal">
                <span class="nahled-prvku-popisek">Náhled prvku</span>
                <?php echo $modul['nahled']; ?>
              </div>

              <div class="komponenta-meta">
                <div class="meta-radek">
                  <span class="meta-label">Prvky:</span>
                  <span class="meta-hodnota"><code><?php echo htmlspecialchars($modul['prvky']); ?></code></span>
                </div>
                <div class="meta-radek">
                  <span class="meta-label">Výskyt:</span>
                  <span class="meta-hodnota"><?php echo htmlspecialchars($modul['vyskyt']); ?></span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 4. RESPONSIVE -->
      <div class="skupina-sekce-obal">
        <div class="skupina-nadpis-obal">
          <h2 class="skupina-nadpis">4. Mobilní responzivita (Responsive)</h2>
          <span class="skupina-stitek stitek-resp">css/responsive/</span>
        </div>
        <div class="mrizka-komponent">
          <?php foreach ($cssModuly['responsive'] as $modul): ?>
            <div class="karta-komponenty">
              <div class="komponenta-hlavicka">
                <h3 class="komponenta-nazev"><?php echo htmlspecialchars($modul['nazev']); ?></h3>
              </div>
              <span class="kod-soubor"><?php echo htmlspecialchars($modul['soubor']); ?></span>
              <p class="komponenta-popis"><?php echo htmlspecialchars($modul['popis']); ?></p>

              <!-- NÁHLED PRVKU -->
              <div class="nahled-prvku-obal">
                <span class="nahled-prvku-popisek">Náhled prvku</span>
                <?php echo $modul['nahled']; ?>
              </div>

              <div class="komponenta-meta">
                <div class="meta-radek">
                  <span class="meta-label">Prvky:</span>
                  <span class="meta-hodnota"><code><?php echo htmlspecialchars($modul['prvky']); ?></code></span>
                </div>
                <div class="meta-radek">
                  <span class="meta-label">Výskyt:</span>
                  <span class="meta-hodnota"><?php echo htmlspecialchars($modul['vyskyt']); ?></span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>

    <!-- ====================================================================
         ZÁLOŽKA 2: SOUBOROVÁ STRUKTURA
         ==================================================================== -->
    <div id="sekce-zalozka-struktura" class="admin-zalozka-obsah <?php echo ($aktivniZalozka === 'struktura') ? 'aktivni' : ''; ?>">

      <!-- VIZUÁLNÍ STROM -->
      <div class="strom-prehled-box">
        <h3 style="color: #ffffff; font-size: 1.15rem; font-weight: 800; margin-top: 0; margin-bottom: 15px; text-transform: uppercase;">
          Schéma stromové struktury
        </h3>
        <pre class="strom-kod">
<span class="s-slozka">PIZZA OD KURETE/</span>
├── <span class="s-php">index.php</span>                         <span class="s-komentar"># Hlavní prezentační stránka a jídelní lístek</span>
├── <span class="s-php">nase-pizza.php</span>                    <span class="s-komentar"># Magazínová podstránka o filozofii a surovinách</span>
├── <span class="s-php">administrace.php</span>                  <span class="s-komentar"># Administrační rozhraní pro správu menu, pizz a záloh</span>
├── <span class="s-php">sprava-webu.php</span>                   <span class="s-komentar"># Přehled architektury, komponent a struktury projektu</span>
├── <span class="s-php">databaze.php</span>                      <span class="s-komentar"># Backend modul pro čtení/zápis JSON a tvorbu záloh</span>
├── <span class="s-php">nastaveni.php</span>                     <span class="s-komentar"># Globální konfigurace a přístupová hesla</span>
├── <span class="s-css">styl.css</span>                          <span class="s-komentar"># Hlavní manifest CSS (načítá 16 modulů)</span>
│
├── <span class="s-slozka">css/</span>                              <span class="s-komentar"># Modulární architektura stylů STETSON</span>
│   ├── <span class="s-slozka">base/</span>                         <span class="s-komentar"># Základ: promenne.css, reset.css</span>
│   ├── <span class="s-slozka">components/</span>                   <span class="s-komentar"># Komponenty: tlacitko, modal, kosik, prepinace, navigace</span>
│   ├── <span class="s-slozka">blocks/</span>                       <span class="s-komentar"># Bloky sekcí: hero, rozdil, menu, rozvoz, provozovny, karusel</span>
│   ├── <span class="s-slozka">responsive/</span>                   <span class="s-komentar"># Responzivita: mobile.css</span>
│   └── <span class="s-css">administrace.css</span>              <span class="s-komentar"># Samostatné styly pro administrátorské rozhraní</span>
│
├── <span class="s-slozka">js/</span>                               <span class="s-komentar"># Klientské JavaScripty</span>
│   ├── <span class="s-js">aplikace.js</span>                   <span class="s-komentar"># Košík, filtry, vyhledávání obcí a realtime otevírací doba</span>
│   ├── <span class="s-js">ingredience.js</span>                <span class="s-komentar"># Karusel snímků a modální náhledy surovin</span>
│   └── <span class="s-js">administrace.js</span>               <span class="s-komentar"># Potvrzovací dialogy a editační logika v adminu</span>
│
├── <span class="s-slozka">data/</span>                             <span class="s-komentar"># Datová vrstva webu</span>
│   ├── <span class="s-json">databaze.json</span>                 <span class="s-komentar"># Hlavní JSON databáze (pizzy, suroviny, nastavení)</span>
│   ├── <span class="s-json">pizzy.json</span>                    <span class="s-komentar"># Samostatný synchronizovaný export pizz</span>
│   └── <span class="s-json">ingredience.json</span>              <span class="s-komentar"># Samostatný synchronizovaný export surovin</span>
│
├── <span class="s-slozka">zalohy/</span>                           <span class="s-komentar"># Automatické snapshoty databáze před každou úpravou</span>
├── <span class="s-slozka">obrazky/</span>                          <span class="s-komentar"># Nahrané fotografie pizz a surovin</span>
├── <span class="s-slozka">media/</span>                            <span class="s-komentar"># Loga, ikony a grafika</span>
└── <span class="s-komentar">GEMINI.md</span>                         <span class="s-komentar"># Pravidla vývoje a FTP synchronizace</span>
        </pre>
      </div>

      <!-- TABULKA 1: PHP STRÁNKY -->
      <div class="kategorie-souboru-box">
        <h3 style="color: #ffffff; font-size: 1.15rem; font-weight: 800; margin-bottom: 12px; text-transform: uppercase;">
          1. PHP stránky a backend
        </h3>
        <table class="tabulka-souboru">
          <thead>
            <tr>
              <th style="width: 220px;">Soubor</th>
              <th>Účel a vysvětlení funkce</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($strukturaSouboru['php'] as $s): ?>
              <tr>
                <td><span class="soubor-badge badge-php"><?php echo htmlspecialchars($s['cesta']); ?></span></td>
                <td><?php echo htmlspecialchars($s['popis']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- TABULKA 2: CSS ARCHITEKTURA -->
      <div class="kategorie-souboru-box">
        <h3 style="color: #ffffff; font-size: 1.15rem; font-weight: 800; margin-bottom: 12px; text-transform: uppercase;">
          2. Kaskádové styly (CSS)
        </h3>
        <table class="tabulka-souboru">
          <thead>
            <tr>
              <th style="width: 220px;">Soubor / Složka</th>
              <th>Účel a vysvětlení funkce</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($strukturaSouboru['css'] as $s): ?>
              <tr>
                <td><span class="soubor-badge badge-css"><?php echo htmlspecialchars($s['cesta']); ?></span></td>
                <td><?php echo htmlspecialchars($s['popis']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- TABULKA 3: JAVASCRIPT -->
      <div class="kategorie-souboru-box">
        <h3 style="color: #ffffff; font-size: 1.15rem; font-weight: 800; margin-bottom: 12px; text-transform: uppercase;">
          3. JavaScript a klientská logika
        </h3>
        <table class="tabulka-souboru">
          <thead>
            <tr>
              <th style="width: 220px;">Soubor</th>
              <th>Účel a vysvětlení funkce</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($strukturaSouboru['js'] as $s): ?>
              <tr>
                <td><span class="soubor-badge badge-js"><?php echo htmlspecialchars($s['cesta']); ?></span></td>
                <td><?php echo htmlspecialchars($s['popis']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- TABULKA 4: DATOVÁ VRSTVA -->
      <div class="kategorie-souboru-box">
        <h3 style="color: #ffffff; font-size: 1.15rem; font-weight: 800; margin-bottom: 12px; text-transform: uppercase;">
          4. Datové soubory (JSON) a zálohování
        </h3>
        <table class="tabulka-souboru">
          <thead>
            <tr>
              <th style="width: 220px;">Soubor / Složka</th>
              <th>Účel a vysvětlení funkce</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($strukturaSouboru['data'] as $s): ?>
              <tr>
                <td><span class="soubor-badge badge-data"><?php echo htmlspecialchars($s['cesta']); ?></span></td>
                <td><?php echo htmlspecialchars($s['popis']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- TABULKA 5: MÉDIA A PRAVIDLA -->
      <div class="kategorie-souboru-box">
        <h3 style="color: #ffffff; font-size: 1.15rem; font-weight: 800; margin-bottom: 12px; text-transform: uppercase;">
          5. Média, podklady a pravidla
        </h3>
        <table class="tabulka-souboru">
          <thead>
            <tr>
              <th style="width: 220px;">Soubor / Složka</th>
              <th>Účel a vysvětlení funkce</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($strukturaSouboru['media'] as $s): ?>
              <tr>
                <td><span class="soubor-badge badge-media"><?php echo htmlspecialchars($s['cesta']); ?></span></td>
                <td><?php echo htmlspecialchars($s['popis']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </div>

  </div>

  <script>
    function prepnoutZalozkuSpravy(nazev) {
      document.querySelectorAll('.admin-zalozka-tlacitko').forEach(b => b.classList.remove('aktivni'));
      document.querySelectorAll('.admin-zalozka-obsah').forEach(o => o.classList.remove('aktivni'));

      const cil = document.getElementById('sekce-zalozka-' + nazev);
      if (cil) {
        cil.classList.add('aktivni');
      }

      const tlacitko = Array.from(document.querySelectorAll('.admin-zalozka-tlacitko')).find(b => b.getAttribute('onclick').includes(nazev));
      if (tlacitko) {
        tlacitko.classList.add('aktivni');
      }

      if (window.history && window.history.replaceState) {
        window.history.replaceState(null, '', '?zalozka=' + encodeURIComponent(nazev));
      }
    }
  </script>

</body>
</html>
