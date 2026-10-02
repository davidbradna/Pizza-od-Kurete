<?php
/* ==========================================================================
   POMOCNE FUNKCE PRO PRACI S DATABAZI (DATABAZE.PHP)
   Vsechny nazvy funkcii a promennych jsou v cestine bez diakritiky
   ========================================================================== */

require_once __DIR__ . '/nastaveni.php';

$cestaKDatabazi = defined('CESTA_DATABAZE') ? CESTA_DATABAZE : (__DIR__ . '/data/databaze.json');

// Funkce pro nacteni cele databaze z JSON
function nactiDatabazi() {
  global $cestaKDatabazi;
  // Pokud jeste neexistuje v data/, zkusime puvodni v korenu a zkopirujeme
  if (!file_exists($cestaKDatabazi) && file_exists(__DIR__ . '/databaze.json')) {
    $adresarData = dirname($cestaKDatabazi);
    if (!is_dir($adresarData)) {
      @mkdir($adresarData, 0777, true);
    }
    @copy(__DIR__ . '/databaze.json', $cestaKDatabazi);
  }

  if (!file_exists($cestaKDatabazi)) {
    return ['suroviny' => [], 'pizzy' => []];
  }
  $obsah = file_get_contents($cestaKDatabazi);
  return json_decode($obsah, true) ?: ['suroviny' => [], 'pizzy' => []];
}

// Funkce pro ziskani adresare zaloh
function ziskatAdresarZaloh() {
  $adresar = defined('CESTA_ZALOHY') ? CESTA_ZALOHY : (__DIR__ . '/data/zalohy');
  if (!is_dir($adresar)) {
    @mkdir($adresar, 0777, true);
  }
  return $adresar;
}

// Funkce pro ulozeni cele databaze do JSON vcetne automaticke zalohy
function uloziDatabazi($data, $vytvoritZalohu = true) {
  global $cestaKDatabazi;
  $obsahJSON = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  
  if ($vytvoritZalohu) {
    $adresar = ziskatAdresarZaloh();
    if (is_dir($adresar)) {
      $casRazitko = date('Y-m-d_H-i-s');
      $cestaKZaloze = $adresar . '/db_' . $casRazitko . '.json';
      @file_put_contents($cestaKZaloze, $obsahJSON);

      // Udrzujeme historii poslednich 50 zaloh, starsi smazeme
      $vsechnyZalohy = glob($adresar . '/db_*.json');
      if ($vsechnyZalohy && count($vsechnyZalohy) > 50) {
        sort($vsechnyZalohy);
        $keSmazani = array_slice($vsechnyZalohy, 0, count($vsechnyZalohy) - 50);
        foreach ($keSmazani as $soubor) {
          @unlink($soubor);
        }
      }
    }
  }

  return file_put_contents($cestaKDatabazi, $obsahJSON) !== false;
}

// Funkce pro ziskani seznamu dostupnych zaloh
function ziskatSeznamZaloh() {
  $adresar = ziskatAdresarZaloh();
  if (!is_dir($adresar)) {
    return [];
  }
  $soubory = glob($adresar . '/db_*.json');
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
    $raw = @file_get_contents($cesta);
    if ($raw) {
      $dec = json_decode($raw, true);
      $pocetPizz = isset($dec['pizzy']) && is_array($dec['pizzy']) ? count($dec['pizzy']) : 0;
      $pocetSurovin = isset($dec['suroviny']) && is_array($dec['suroviny']) ? count($dec['suroviny']) : 0;
    }

    $seznam[] = [
      'soubor' => $nazev,
      'cesta' => $cesta,
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

// Funkce pro obnoveni databaze ze zalohy
function obnovitZalohu($nazevSouboru) {
  global $cestaKDatabazi;
  $nazevSouboru = basename($nazevSouboru);
  $adresar = ziskatAdresarZaloh();
  $cestaKZaloze = $adresar . '/' . $nazevSouboru;
  if (!file_exists($cestaKZaloze)) {
    return false;
  }
  // Pred obnovenim udelame bezpecnostni zalohu stavajiciho stavu
  $aktualniData = nactiDatabazi();
  uloziDatabazi($aktualniData, true);

  return copy($cestaKZaloze, $cestaKDatabazi);
}

// Funkce pro ziskani vsech pizz
function ziskatPizzy() {
  $db = nactiDatabazi();
  return $db['pizzy'] ?? [];
}

// Funkce pro ziskani vsech surovin
function ziskatSuroviny() {
  $db = nactiDatabazi();
  return $db['suroviny'] ?? [];
}

// Funkce pro nalezani pizz obsahujicich danou surovinu
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

// Funkce pro aktualizaci textoveho slozeni vsech pizz podle aktualnich nazvu surovin
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
