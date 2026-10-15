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
  $pizzy = [];
  if (file_exists($cestaPizzy)) {
    $obsah = file_get_contents($cestaPizzy);
    $data = json_decode($obsah, true);
    if (is_array($data)) $pizzy = $data;
  } elseif (file_exists($cestaKDatabazi)) {
    // Fallback z původní sloučené databáze
    $staraDb = json_decode(file_get_contents($cestaKDatabazi), true);
    if (isset($staraDb['pizzy']) && is_array($staraDb['pizzy'])) {
      $pizzy = $staraDb['pizzy'];
      uloziPizzy($pizzy, false);
    }
  }

  // Zajištění unikátních ID v případě historických duplicit
  if (!empty($pizzy)) {
    $pouzitaId = [];
    $zmena = false;
    $maxId = 0;
    foreach ($pizzy as $p) {
      if (isset($p['id']) && is_numeric($p['id'])) {
        $maxId = max($maxId, (int)$p['id']);
      }
    }
    foreach ($pizzy as &$p) {
      $id = $p['id'] ?? 0;
      if (in_array($id, $pouzitaId, true) || empty($id)) {
        $maxId++;
        $p['id'] = $maxId;
        $zmena = true;
      } else {
        $pouzitaId[] = $id;
      }
    }
    unset($p);
    if ($zmena) {
      uloziPizzy($pizzy, false);
    }
  }

  return $pizzy;
}
function nactiDatabaziPizz() { return nactiPizzy(); }
function ziskatPizzy() { return nactiPizzy(); }

// Získání pizz pro veřejné menu (neaktivní pizza týdne se nepropisuje do menu, smí zde být pouze aktivní pizza týdne)
function ziskatPizzyProVerejnost() {
  $pizzy = nactiPizzy();
  return array_values(array_filter($pizzy, function($p) {
    if (!empty($p['pizzaTydne']) && empty($p['aktivniPizzaTydne'])) {
      return false;
    }
    return true;
  }));
}

function uloziPizzy($pizzy, $vytvoritZalohu = false) {
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

// Pomocná funkce pro doplnění vlajky k zemi původu, pokud chybí
function zformatovatPuvodSVlajkou($puvod) {
  if (empty($puvod) || !is_string($puvod)) return '';
  $puvod = trim($puvod);
  if ($puvod === '') return '';

  // Pokud již obsahuje jakoukoliv vlajku nebo emoji
  if (preg_match('/[\x{1F1E6}-\x{1F1FF}]/u', $puvod) || preg_match('/[\x{1F300}-\x{1F9FF}]/u', $puvod)) {
    return $puvod;
  }

  // Odstranění diakritiky pro spolehlivé porovnání
  $bezDiakritiky = mb_strtolower($puvod, 'UTF-8');
  $prevodniTabulka = [
    'á'=>'a', 'č'=>'c', 'ď'=>'d', 'é'=>'e', 'ě'=>'e', 'í'=>'i', 'ň'=>'n',
    'ó'=>'o', 'ř'=>'r', 'š'=>'s', 'ť'=>'t', 'ú'=>'u', 'ů'=>'u', 'ý'=>'y', 'ž'=>'z'
  ];
  $norm = strtr($bezDiakritiky, $prevodniTabulka);

  if (strpos($norm, 'ital') !== false || strpos($norm, 'neapol') !== false || strpos($norm, 'kampan') !== false || strpos($norm, 'parma') !== false || strpos($norm, 'sicil') !== false || strpos($norm, 'rim') !== false) {
    return '🇮🇹 ' . $puvod;
  }
  if (strpos($norm, 'cesk') !== false || strpos($norm, 'morav') !== false || strpos($norm, 'lokal') !== false || strpos($norm, 'cr') !== false || strpos($norm, 'czech') !== false) {
    return '🇨🇿 ' . $puvod;
  }
  if (strpos($norm, 'spanel') !== false || strpos($norm, 'esp') !== false) {
    return '🇪🇸 ' . $puvod;
  }
  if (strpos($norm, 'franc') !== false) {
    return '🇫🇷 ' . $puvod;
  }
  if (strpos($norm, 'reck') !== false) {
    return '🇬🇷 ' . $puvod;
  }
  if (strpos($norm, 'nemec') !== false || strpos($norm, 'germ') !== false) {
    return '🇩🇪 ' . $puvod;
  }
  if (strpos($norm, 'rakous') !== false) {
    return '🇦🇹 ' . $puvod;
  }
  if (strpos($norm, 'sloven') !== false) {
    return '🇸🇰 ' . $puvod;
  }
  if (strpos($norm, 'polsk') !== false) {
    return '🇵🇱 ' . $puvod;
  }
  if (strpos($norm, 'angli') !== false || strpos($norm, 'brit') !== false) {
    return '🇬🇧 ' . $puvod;
  }
  if (strpos($norm, 'usa') !== false || strpos($norm, 'americ') !== false) {
    return '🇺🇸 ' . $puvod;
  }

  // Výchozí pro pizzerii je italská vlajka
  return '🇮🇹 ' . $puvod;
}

function nactiIngredience() {
  global $cestaIngredience, $cestaKDatabazi;
  $data = [];
  if (file_exists($cestaIngredience)) {
    $obsah = file_get_contents($cestaIngredience);
    $dec = json_decode($obsah, true);
    if (is_array($dec) && !empty($dec)) $data = $dec;
  } elseif (file_exists($cestaKDatabazi)) {
    // Fallback z původní sloučené databáze
    $staraDb = json_decode(file_get_contents($cestaKDatabazi), true);
    if (isset($staraDb['suroviny']) && is_array($staraDb['suroviny']) && !empty($staraDb['suroviny'])) {
      $data = $staraDb['suroviny'];
      uloziIngredience($data, false);
    }
  }

  // Všude na webu zajistíme zobrazení země původu zároveň s vlajkou
  if (!empty($data)) {
    foreach ($data as &$sur) {
      if (!empty($sur['puvod'])) {
        $sur['puvod'] = zformatovatPuvodSVlajkou($sur['puvod']);
      }
    }
    unset($sur);
  }

  return $data;
}
function nactiDatabaziIngredienci() { return nactiIngredience(); }
function ziskatSuroviny() { return nactiIngredience(); }

function uloziIngredience($ingredience, $vytvoritZalohu = false) {
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

function uloziDatabazi($data, $vytvoritZalohu = false) {
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

// Ruční vytvoření zálohy (volá se po stisku tlačítka "Vytvořit zálohu")
function vytvoritRucniZalohu() {
  $adresar = ziskatAdresarZaloh();
  if (!is_dir($adresar)) {
    @mkdir($adresar, 0777, true);
  }
  $db = nactiDatabazi();
  $casRazitko = date('Y-m-d_H-i-s');
  $obsahJSON = json_encode($db, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  $cestaKZaloze = $adresar . '/databaze_' . $casRazitko . '.json';
  $vysledek = @file_put_contents($cestaKZaloze, $obsahJSON) !== false;
  cistitStareZalohy($adresar, '*.json', 200);
  return $vysledek;
}

// Smazání záloh starších než 1 měsíc (30 dní)
function smazatZalohyStarsiNezMesic() {
  $adresar = ziskatAdresarZaloh();
  if (!is_dir($adresar)) {
    return 0;
  }
  $soubory = glob($adresar . '/*.json');
  if (!$soubory) {
    return 0;
  }
  
  $casHranice = time() - (30 * 86400); // 30 dní (1 měsíc)
  $smazanoPocet = 0;

  foreach ($soubory as $soubor) {
    $casSouboru = filemtime($soubor);
    if ($casSouboru < $casHranice) {
      if (@unlink($soubor)) {
        $smazanoPocet++;
      }
    }
  }

  return $smazanoPocet;
}

// Pomocná funkce pro promazání starých záloh
function cistitStareZalohy($adresar, $maska = '*.json', $limit = 200) {
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
  $pizzy = ziskatPizzyProVerejnost();
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

  // Mapa pořadí surovin podle ID
  $poradiMap = [];
  foreach ($db['suroviny'] as $idx => $sur) {
    if (isset($sur['id'])) {
      $poradiMap[$sur['id']] = $idx;
    }
  }

  foreach ($db['pizzy'] as &$pizza) {
    if (isset($pizza['ingredience']) && is_array($pizza['ingredience'])) {
      // 1. Seřadit pizza['ingredience'] přesně podle pořadí v db['suroviny']
      usort($pizza['ingredience'], function($a, $b) use ($poradiMap) {
        $posA = $poradiMap[$a] ?? 9999;
        $posB = $poradiMap[$b] ?? 9999;
        return $posA - $posB;
      });
      $pizza['ingredience'] = array_values($pizza['ingredience']);

      // 2. Sestavit slovní složení v přesném pořadí ze surovin
      $nazvySurovin = [];
      foreach ($pizza['ingredience'] as $ingId) {
        foreach ($db['suroviny'] as $sur) {
          if ($sur['id'] === $ingId) {
            $nazvySurovin[] = $sur['nazev'];
            break;
          }
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
