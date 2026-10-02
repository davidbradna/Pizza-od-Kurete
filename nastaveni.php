<?php
/* ==========================================================================
   HLAVNÍ NASTAVENÍ A KONFIGURACE (NASTAVENI.PHP)
   Jednotné místo pro správu hesel, kontaktů a informací o pobočkách.
   ========================================================================== */

// Verze aplikace
define('VERZE_SYSTEMU', 'v1.2.0');

// Tajné heslo do administrace
define('HESLO_ADMIN', 'kure123');

// Cesty k datovým souborům
define('CESTA_DATABAZE_PIZZY', __DIR__ . '/data/pizzy.json');
define('CESTA_DATABAZE_INGREDIENCE', __DIR__ . '/data/ingredience.json');
define('CESTA_DATABAZE', __DIR__ . '/data/databaze.json'); // Původní sloučená (pro zpětnou kompatibilitu)
define('CESTA_ZALOHY', __DIR__ . '/data/zalohy');

// Nastavení poboček
$KONFIGURACE_POBOCKY = [
  'rychnov' => [
    'kod'             => 'rychnov',
    'nazev'           => 'Rychnov nad Kněžnou',
    'nazev_kratky'    => 'Rychnov n.K.',
    'telefon'         => '739 149 142',
    'telefon_cisty'   => '739149142',
    'adresa'          => 'Panská 123, Rychnov nad Kněžnou, 516 01',
    'oteviraci_doba'  => 'Po - Ne: 11:00 - 22:00'
  ],
  'usti' => [
    'kod'             => 'usti',
    'nazev'           => 'Ústí nad Orlicí',
    'nazev_kratky'    => 'Ústí n.O.',
    'telefon'         => '774 741 818',
    'telefon_cisty'   => '774741818',
    'adresa'          => 'Mírové náměstí 10, Ústí nad Orlicí, 562 01',
    'oteviraci_doba'  => 'Po - Ne: 11:00 - 22:00'
  ]
];

// Minimální počet pizz pro rozvoz
define('MINIMALNI_OBJEDNAVKA_PIZZ', 2);
?>
