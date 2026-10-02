<?php
/* ==========================================================================
   POMOCNÉ FUNKCE PRO PRÁCI S ODDĚLENÝMI DATABÁZEMI (DATABAZE.PHP)
   Oddělená databáze pro Pizzy (data/pizzy.json) a Ingredience (data/ingredience.json).
   Všechny názvy funkcí a proměnných jsou v češtině bez diakritiky.
   ========================================================================== */

require_once __DIR__ . '/nastaveni.php';

$cestaPizzy = defined('CESTA_DATABAZE_PIZZY') ? CESTA_DATABAZE_PIZZY : (__DIR__ . '/data/pizzy.json');
$cestaIngredience = defined('CESTA_DATABAZE_INGREDIENCE') ? CESTA_DATABAZE_INGREDIENCE : (__DIR__ . '/data/ingredience.json');
$cestaKDatabazi = defined('CESTA_DATABAZE') ? CESTA_DATABAZE : (__DIR__ . '/data/databaze.json');

// Funkce pro získání adresáře záloh
function ziskatAdresarZaloh() {
  $adresar = defined('CESTA_ZALOHY') ? CESTA_ZALOHY : (__DIR__ . '/data/zalohy');
  if (!is_dir($adresar)) {
    @mkdir($adresar, 0777, true);
  }
  return $adresar;
}

// --------------------------------------------------------------------------
// 1. ODDĚLENÁ DATABÁZE PIZZ (data/pizzy.json)
// --------------------------------------------------------------------------

function nactiPizzy() {
  global $cestaPizzy, $cestaKDatabazi;
  if (file_exists($cestaPizzy)) {
    $obsah = file_get_contents($cestaPizzy);
    $data = json_decode($obsah, true);
    if (is_array($data)) return $data;
  }

  // Fallback z původní sloučené databáze
  if (file_exists($cestaKDatabazi)) {
    $staraDb = json_decode(file_get_contents($cestaKDatabazi), true);
    if (isset($staraDb['pizzy']) && is_array($staraDb['pizzy'])) {
      uloziPizzy($staraDb['pizzy'], false);
      return $staraDb['pizzy'];
    }
  }

  return [];
}
function nactiDatabaziPizz() { return nactiPizzy(); }
function ziskatPizzy() { return nactiPizzy(); }

function uloziPizzy($pizzy, $vytvoritZalohu = true) {
  global $cestaPizzy;
  $adresarData = dirname($cestaPizzy);
  if (!is_dir($adresarData)) {
    @mkdir($adresarData, 0777, true);
  }

  $obsahJSON = json_encode(array_values($pizzy), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

  if ($vytvoritZalohu) {
    $adresar = ziskatAdresarZaloh();
    if (is_dir($adresar)) {
      $casRazitko = date('Y-m-d_H-i-s');
      $cestaKZaloze = $adresar . '/pizzy_' . $casRazitko . '.json';
      @file_put_contents($cestaKZaloze, $obsahJSON);
      cistitStareZalohy($adresar, 'pizzy_*.json', 50);
    }
  }

  return file_put_contents($cestaPizzy, $obsahJSON) !== false;
}

// --------------------------------------------------------------------------
// 2. ODDĚLENÁ DATABÁZE INGREDIENCÍ (data/ingredience.json)
// --------------------------------------------------------------------------

function nactiIngredience() {
  global $cestaIngredience, $cestaKDatabazi;
  if (file_exists($cestaIngredience)) {
    $obsah = file_get_contents($cestaIngredience);
    $data = json_decode($obsah, true);
    if (is_array($data)) return $data;
  }

  // Fallback z původní sloučené databáze
  if (file_exists($cestaKDatabazi)) {
    $staraDb = json_decode(file_get_contents($cestaKDatabazi), true);
    if (isset($staraDb['suroviny']) && is_array($staraDb['suroviny'])) {
      uloziIngredience($staraDb['suroviny'], false);
      return $staraDb['suroviny'];
    }
  }

  return [];
}
function nactiDatabaziIngredienci() { return nactiIngredience(); }
function ziskatSuroviny() { return nactiIngredience(); }

function uloziIngredience($ingredience, $vytvoritZalohu = true) {
  global $cestaIngredience;
  $adresarData = dirname($cestaIngredience);
  if (!is_dir($adresarData)) {
    @mkdir($adresarData, 0777, true);
  }

  $obsahJSON = json_encode(array_values($ingredience), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

  if ($vytvoritZalohu) {
    $adresar = ziskatAdresarZaloh();
    if (is_dir($adresar)) {
      $casRazitko = date('Y-m-d_H-i-s');
      $cestaKZaloze = $adresar . '/ingredience_' . $casRazitko . '.json';
      @file_put_contents($cestaKZaloze, $obsahJSON);
      cistitStareZalohy($adresar, 'ingredience_*.json', 50);
    }
  }

  return file_put_contents($cestaIngredience, $obsahJSON) !== false;
}

// --------------------------------------------------------------------------
// 3. SLOUČENÉ ROZHRANÍ PRO ZPĚTNOU KOMPATIBILITU
// --------------------------------------------------------------------------

function nactiDatabazi() {
  return [
    'pizzy' => nactiPizzy(),
    'suroviny' => nactiIngredience()
  ];
}

function uloziDatabazi($data, $vytvoritZalohu = true) {
  global $cestaKDatabazi;
  $uspechPizzy = true;
  $uspechIngr = true;

  if (isset($data['pizzy']) && is_array($data['pizzy'])) {
    $uspechPizzy = uloziPizzy($data['pizzy'], $vytvoritZalohu);
  }
  if (isset($data['suroviny']) && is_array($data['suroviny'])) {
    $uspechIngr = uloziIngredience($data['suroviny'], $vytvoritZalohu);
  }

  // Uložíme i sloučenou kopii pro maximální bezpečí
  $obsahJSON = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  @file_put_contents($cestaKDatabazi, $obsahJSON);

  return ($uspechPizzy && $uspechIngr);
}

// Pomocná funkce pro promazání starých záloh
function cistitStareZalohy($adresar, $maska = '*.json', $limit = 50) {
  $zalohy = glob($adresar . '/' . $maska);
  if ($zalohy && count($zalohy) > $limit) {
    sort($zalohy);
    $keSmazani = array_slice($zalohy, 0, count($zalohy) - $limit);
    foreach ($keSmazani as $soubor) {
      @unlink($soubor);
    }
  }
}

// --------------------------------------------------------------------------
// 4. SPRÁVA ZÁLOH (SEZNAM A OBNOVENÍ)
// --------------------------------------------------------------------------

function ziskatSeznamZaloh() {
  $adresar = ziskatAdresarZaloh();
  if (!is_dir($adresar)) {
    return [];
  }
  $soubory = glob($adresar . '/*.json');
  if (!$soubory) {
    return [];
  }

  $seznam = [];
  foreach ($soubory as $cesta) {
    $nazev = basename($cesta);
    $casVytvoreni = filemtime($cesta);
    $velikost = filesize($cesta);

    $pocetPizz = 0;
    $pocetSurovin = 0;
    $typ = 'Kompletní';

    $raw = @file_get_contents($cesta);
    if ($raw) {
      $dec = json_decode($raw, true);
      if (strpos($nazev, 'pizzy_') === 0) {
        $typ = 'Pouze Pizzy';
        $pocetPizz = is_array($dec) ? count($dec) : 0;
      } elseif (strpos($nazev, 'ingredience_') === 0) {
        $typ = 'Pouze Ingredience';
        $pocetSurovin = is_array($dec) ? count($dec) : 0;
      } else {
        $pocetPizz = isset($dec['pizzy']) && is_array($dec['pizzy']) ? count($dec['pizzy']) : 0;
        $pocetSurovin = isset($dec['suroviny']) && is_array($dec['suroviny']) ? count($dec['suroviny']) : 0;
      }
    }

    $seznam[] = [
      'soubor' => $nazev,
      'cesta' => $cesta,
      'typ' => $typ,
      'cas' => $casVytvoreni,
      'datum' => date('d.m.Y H:i:s', $casVytvoreni),
      'velikost' => round($velikost / 1024, 1) . ' KB',
      'pocet_pizz' => $pocetPizz,
      'pocet_surovin' => $pocetSurovin
    ];
  }

  usort($seznam, function($a, $b) {
    return $b['cas'] - $a['cas'];
  });

  return $seznam;
}

function obnovitZalohu($nazevSouboru) {
  global $cestaPizzy, $cestaIngredience, $cestaKDatabazi;
  $nazevSouboru = basename($nazevSouboru);
  $adresar = ziskatAdresarZaloh();
  $cestaKZaloze = $adresar . '/' . $nazevSouboru;
  if (!file_exists($cestaKZaloze)) {
    return false;
  }

  $raw = file_get_contents($cestaKZaloze);
  $data = json_decode($raw, true);
  if (!$data) return false;

  if (strpos($nazevSouboru, 'pizzy_') === 0) {
    return uloziPizzy($data, true);
  } elseif (strpos($nazevSouboru, 'ingredience_') === 0) {
    return uloziIngredience($data, true);
  } else {
    return uloziDatabazi($data, true);
  }
}

// --------------------------------------------------------------------------
// 5. POMOCNÉ FUNKCE PRO VAZBY MEZI PIZZAMI A SUROVINAMI
// --------------------------------------------------------------------------

function ziskatPizzyProSurovinu($idSuroviny) {
  $pizzy = ziskatPizzy();
  $vysledek = [];
  foreach ($pizzy as $pizza) {
    if (isset($pizza['ingredience']) && is_array($pizza['ingredience']) && in_array($idSuroviny, $pizza['ingredience'])) {
      $vysledek[] = $pizza;
    }
  }
  return $vysledek;
}

function aktualizovatSlozeniVsechPizz(&$db) {
  if (!isset($db['pizzy']) || !is_array($db['pizzy']) || !isset($db['suroviny']) || !is_array($db['suroviny'])) {
    return;
  }

  foreach ($db['pizzy'] as &$pizza) {
    if (isset($pizza['ingredience']) && is_array($pizza['ingredience'])) {
      $nazvySurovin = [];
      foreach ($db['suroviny'] as $sur) {
        if (in_array($sur['id'], $pizza['ingredience'])) {
          $nazvySurovin[] = $sur['nazev'];
        }
      }
      if (!empty($nazvySurovin)) {
        $pizza['slozeni'] = implode(', ', $nazvySurovin);
      }
    }
  }
  unset($pizza);
}
?>
