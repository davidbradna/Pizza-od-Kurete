<?php
/* ==========================================================================
   WEBOVA ADMINISTRACE PRO PIZZA OD KURETE (ADMINISTRACE.PHP)
   Vsechny nazvy promennych, funkcii, trid a komentare jsou v cestine bez diakritiky
   ========================================================================== */

session_start();
require_once __DIR__ . '/databaze.php';

// Tajne heslo pro prihlaseni do administrace
$tajneHeslo = 'kure123';
$zpravaOznameni = '';
$zpravaChyba = '';

// Odhlaseni
if (isset($_GET['akce']) && $_GET['akce'] === 'odhlasit') {
  unset($_SESSION['prihlasen']);
  header('Location: administrace.php');
  exit;
}

// Prihlaseni
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prihlasit_stisknuto'])) {
  $zadaniHeslo = $_POST['heslo_vstup'] ?? '';
  if ($zadaniHeslo === $tajneHeslo) {
    $_SESSION['prihlasen'] = true;
  } else {
    $zpravaChyba = 'Nesprávné heslo! Zkuste to znovu.';
  }
}

// Kontrola prihlaseni
$jePrihlasen = !empty($_SESSION['prihlasen']);

// Pokud je prihlasen, obslouzime akce zapisu
if ($jePrihlasen && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $db = nactiDatabazi();

  // Ulozeni / Uprava pizzy
  if (isset($_POST['ulozit_pizzu_stisknuto'])) {
    $idPizzy = isset($_POST['id_pizzy']) && $_POST['id_pizzy'] !== '' ? intval($_POST['id_pizzy']) : (time());
    $cisloPizzy = intval($_POST['cislo_pizzy'] ?? 1);
    $nazevPizzy = trim($_POST['nazev_pizzy'] ?? '');
    // Vybrane ingredience
    $vybraneIngredience = $_POST['ingredience_seznam'] ?? [];

    // Automaticke sestaveni textu slozeni ze zaškrtnutych surovin
    $nazvySurovin = [];
    foreach ($db['suroviny'] as $sur) {
      if (in_array($sur['id'], $vybraneIngredience)) {
        $nazvySurovin[] = $sur['nazev'];
      }
    }
    $slozeniPizzy = !empty($nazvySurovin) ? implode(', ', $nazvySurovin) : 'Vlastní výběr surovin';

    $cenaPizzy = intval($_POST['cena_pizzy'] ?? 0);
    // Alergeny jako pole cisel [1, 7, 12, ...]
    $alergenyCislaPizzy = array_map('intval', $_POST['alergeny_cisla_pizza'] ?? []);

    // Kategorie / Flagy
    $masite = isset($_POST['flag_masite']);
    $bezmase = isset($_POST['flag_bezmase']);
    $paliva = isset($_POST['flag_paliva']);
    $nepaliva = isset($_POST['flag_nepaliva']);
    $sugo = isset($_POST['flag_sugo']);
    $bilyZaklad = isset($_POST['flag_bily']);
    $naMiste = isset($_POST['flag_na_miste']);
    $sSebou = isset($_POST['flag_s_sebou']);
    $doporucujeme = isset($_POST['flag_doporucujeme']);
    $nejprodavanejsi = isset($_POST['flag_nejprodavanejsi']);
    $pizzaTydne = isset($_POST['flag_pizza_tydne']);

    // Zpracovani obrazku (nahravani souboru z PC/mobilu)
    $cestaKObrazku = $_POST['stavajici_obrazek'] ?? 'https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?q=80&w=500&auto=format&fit=crop';
    
    if (isset($_FILES['fotka_soubor']) && $_FILES['fotka_soubor']['error'] === UPLOAD_ERR_OK) {
      $slozkyObrazku = __DIR__ . '/obrazky/';
      if (!file_exists($slozkyObrazku)) {
        mkdir($slozkyObrazku, 0777, true);
      }
      $pripona = pathinfo($_FILES['fotka_soubor']['name'], PATHINFO_EXTENSION);
      $novyNazev = 'pizza_' . time() . '_' . rand(100, 999) . '.' . $pripona;
      $cilovySoubor = $slozkyObrazku . $novyNazev;
      
      if (move_uploaded_file($_FILES['fotka_soubor']['tmp_name'], $cilovySoubor)) {
        $cestaKObrazku = 'obrazky/' . $novyNazev;
      }
    }

    $novaPizza = [
      'id'              => $idPizzy,
      'cislo'           => $cisloPizzy,
      'nazev'           => $nazevPizzy,
      'slozeni'         => $slozeniPizzy,
      'ingredience'     => array_values($vybraneIngredience),
      'alergeny_cisla'  => $alergenyCislaPizzy,
      'cena'            => $cenaPizzy,
      'masite'          => $masite,
      'bezmase'         => $bezmase,
      'paliva'          => $paliva,
      'nepaliva'        => $nepaliva,
      'sugo'            => $sugo,
      'bilyZaklad'      => $bilyZaklad,
      'naMiste'         => $naMiste,
      'sSebou'          => $sSebou,
      'doporucujeme'    => $doporucujeme,
      'nejprodavanejsi'  => $nejprodavanejsi,
      'pizzaTydne'      => $pizzaTydne,
      'obrazek'         => $cestaKObrazku
    ];

    // Najdeme zda upravujeme nebo pridavame
    $indexNalezen = -1;
    foreach ($db['pizzy'] as $idx => $p) {
      if ($p['id'] === $idPizzy) {
        $indexNalezen = $idx;
        break;
      }
    }

    if ($indexNalezen >= 0) {
      $db['pizzy'][$indexNalezen] = $novaPizza;
      $zpravaOznameni = "Pizza {$nazevPizzy} byla úspěšně upravena!";
    } else {
      $db['pizzy'][] = $novaPizza;
      $zpravaOznameni = "Nová pizza {$nazevPizzy} byla přidána do menu!";
    }

    uloziDatabazi($db);
  }

  // Mazani pizzy
  if (isset($_POST['smazat_pizzu_stisknuto'])) {
    $idSmazat = intval($_POST['id_pizzy_smazat']);
    $db['pizzy'] = array_values(array_filter($db['pizzy'], function($p) use ($idSmazat) {
      return $p['id'] !== $idSmazat;
    }));
    uloziDatabazi($db);
    $zpravaOznameni = "Pizza byla úspěšně smazána z menu.";
  }

  // Ulozeni / Uprava ingredience
  if (isset($_POST['ulozit_surovinu_stisknuto'])) {
    $idSuroviny = trim($_POST['id_suroviny'] ?? ('surovina_' . time()));
    $nazevSuroviny = trim($_POST['nazev_suroviny'] ?? '');
    $kategorieSuroviny = trim($_POST['kategorie_suroviny'] ?? 'syry');
    $puvodSuroviny = trim($_POST['puvod_suroviny'] ?? '🇮🇹 Itálie');
    $popisSuroviny = trim($_POST['popis_suroviny'] ?? '');
    $alergenyCislaSuroviny = array_map('intval', $_POST['alergeny_cisla_suroviny'] ?? []);

    $cestaKObrazku = $_POST['stavajici_obrazek_suroviny'] ?? 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?q=80&w=600&auto=format&fit=crop';
    
    if (isset($_FILES['fotka_suroviny_soubor']) && $_FILES['fotka_suroviny_soubor']['error'] === UPLOAD_ERR_OK) {
      $slozkyObrazku = __DIR__ . '/obrazky/';
      if (!file_exists($slozkyObrazku)) {
        mkdir($slozkyObrazku, 0777, true);
      }
      $pripona = pathinfo($_FILES['fotka_suroviny_soubor']['name'], PATHINFO_EXTENSION);
      $novyNazev = 'surovina_' . time() . '_' . rand(100, 999) . '.' . $pripona;
      $cilovySoubor = $slozkyObrazku . $novyNazev;
      
      if (move_uploaded_file($_FILES['fotka_suroviny_soubor']['tmp_name'], $cilovySoubor)) {
        $cestaKObrazku = 'obrazky/' . $novyNazev;
      }
    }

    $novaSurovina = [
      'id'             => $idSuroviny,
      'nazev'          => $nazevSuroviny,
      'kategorie'      => $kategorieSuroviny,
      'puvod'          => $puvodSuroviny,
      'popis'          => $popisSuroviny,
      'alergeny_cisla' => $alergenyCislaSuroviny,
      'obrazek'        => $cestaKObrazku
    ];

    $indexNalezen = -1;
    foreach ($db['suroviny'] as $idx => $s) {
      if ($s['id'] === $idSuroviny) {
        $indexNalezen = $idx;
        break;
      }
    }

    if ($indexNalezen >= 0) {
      $db['suroviny'][$indexNalezen] = $novaSurovina;
      $zpravaOznameni = "Ingredience {$nazevSuroviny} byla upravena!";
    } else {
      $db['suroviny'][] = $novaSurovina;
      $zpravaOznameni = "Nová ingredience {$nazevSuroviny} byla přidána!";
    }

    // Automaticka aktualizace slozeni vsech pizz pri zmene suroviny
    aktualizovatSlozeniVsechPizz($db);

    uloziDatabazi($db);
  }

  // Mazani suroviny
  if (isset($_POST['smazat_surovinu_stisknuto'])) {
    $idSmazat = trim($_POST['id_suroviny_smazat']);
    $db['suroviny'] = array_values(array_filter($db['suroviny'], function($s) use ($idSmazat) {
      return $s['id'] !== $idSmazat;
    }));

    // Odebrani smazane suroviny z ingredienci jednotlivych pizz
    if (isset($db['pizzy']) && is_array($db['pizzy'])) {
      foreach ($db['pizzy'] as &$pizza) {
        if (isset($pizza['ingredience']) && is_array($pizza['ingredience'])) {
          $pizza['ingredience'] = array_values(array_filter($pizza['ingredience'], function($id) use ($idSmazat) {
            return $id !== $idSmazat;
          }));
        }
      }
      unset($pizza);
    }

    // Automaticka aktualizace slozeni vsech pizz
    aktualizovatSlozeniVsechPizz($db);

    uloziDatabazi($db);
    $zpravaOznameni = "Ingredience byla smazána.";
  }

  // Vytvoreni rucni zalohy
  if (isset($_POST['vytvorit_zalohu_stisknuto'])) {
    if (uloziDatabazi($db, true)) {
      $zpravaOznameni = 'Bezpečnostní záloha databáze byla úspěšně vytvořena!';
    } else {
      $zpravaChyba = 'Chyba při vytváření zálohy.';
    }
  }

  // Obnoveni ze zalohy
  if (isset($_POST['obnovit_zalohu_stisknuto'])) {
    $souborKZaloze = trim($_POST['nazev_zalohy'] ?? '');
    if (!empty($souborKZaloze) && obnovitZalohu($souborKZaloze)) {
      $zpravaOznameni = "Databáze byla úspěšně obnovena ze zálohy: {$souborKZaloze}!";
    } else {
      $zpravaChyba = 'Nepodařilo se obnovit vybranou zálohu.';
    }
  }

  // Export databaze do JSON souboru (ke stazeni do PC)
  if (isset($_POST['exportovat_databazi_stisknuto'])) {
    $obsahStazeni = json_encode($db, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="databaze_zaloha_' . date('Y-m-d_H-i-s') . '.json"');
    header('Content-Length: ' . strlen($obsahStazeni));
    echo $obsahStazeni;
    exit;
  }

  // Import databaze z nahraneho JSON souboru
  if (isset($_POST['importovat_databazi_stisknuto']) && isset($_FILES['soubor_json'])) {
    $soubor = $_FILES['soubor_json'];
    if ($soubor['error'] === UPLOAD_ERR_OK && is_uploaded_file($soubor['tmp_name'])) {
      $obsah = file_get_contents($soubor['tmp_name']);
      $parsovanaData = json_decode($obsah, true);
      if (is_array($parsovanaData) && isset($parsovanaData['pizzy'])) {
        uloziDatabazi($parsovanaData, true);
        $zpravaOznameni = 'Databáze byla úspěšně obnovena z nahraného JSON souboru!';
      } else {
        $zpravaChyba = 'Nahraný soubor nemá platnou strukturu databáze pizz a ingrediencí.';
      }
    } else {
      $zpravaChyba = 'Chyba při nahrávání souboru do administrace.';
    }
  }
}

// Nacteni aktualnich dat
$db = nactiDatabazi();
$pizzySeznam = $db['pizzy'] ?? [];
$surovinySeznam = $db['suroviny'] ?? [];
$seznamZaloh = ziskatSeznamZaloh();

// Rozdeleni na Stale pizzy a Pizzy tydne (BOD 2)
$pizzyStaleSeznam = array_values(array_filter($pizzySeznam, function($p) {
  return empty($p['pizzaTydne']);
}));
$pizzyTydneSeznam = array_values(array_filter($pizzySeznam, function($p) {
  return !empty($p['pizzaTydne']);
}));

// Priprava upravovane pizzy
$upravovanaPizza = null;
if ($jePrihlasen && (isset($_GET['upravit_pizzu']) || isset($_GET['upravit_pizzu_tydne']))) {
  $idUpravit = intval($_GET['upravit_pizzu'] ?? $_GET['upravit_pizzu_tydne']);
  foreach ($pizzySeznam as $p) {
    if ($p['id'] === $idUpravit) {
      $upravovanaPizza = $p;
      break;
    }
  }
}

// Priprava upravovane suroviny
$upravovanaSurovina = null;
if ($jePrihlasen && isset($_GET['upravit_surovinu'])) {
  $idUpravit = trim($_GET['upravit_surovinu']);
  foreach ($surovinySeznam as $s) {
    if ($s['id'] === $idUpravit) {
      $upravovanaSurovina = $s;
      break;
    }
  }
}

// Seznam EU alergenu
$seznamEuAlergen = [
  1  => 'Lepek (obiloviny)',
  2  => 'Korýši',
  3  => 'Vejce',
  4  => 'Ryby',
  5  => 'Arašídy',
  6  => 'Sója',
  7  => 'Mléko a laktóza',
  8  => 'Ořechy',
  9  => 'Celer',
  10 => 'Hořčice',
  11 => 'Sezam',
  12 => 'Oxid siřičitý (SO₂)',
  13 => 'Vlčí bob (lupina)',
  14 => 'Měkkýši',
];
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrace | PIZZA OD KUŘETE</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styl.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="css/administrace.css?v=<?php echo time(); ?>">
  <style>
    body { background-color: #0d0d0d; color: #ffffff; padding-bottom: 80px; }
    .admin-kontejner { max-width: 1240px; margin: 30px auto; padding: 0 20px; }

    /* ZÁLOŽKOVÁ NAVIGACE */
    .admin-zalozky-obal {
      display: flex;
      gap: 14px;
      margin-bottom: 30px;
      border-bottom: 2px solid #222222;
      padding-bottom: 14px;
    }
    .admin-zalozka-tlacitko {
      padding: 14px 28px;
      background: #161616;
      border: 1px solid #2a2a2a;
      border-radius: 12px;
      color: #aaa;
      font-size: 1.1rem;
      font-weight: 800;
      cursor: pointer;
      transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
      display: flex;
      align-items: center;
      gap: 10px;
      font-family: var(--font-hlavni);
    }
    .admin-zalozka-tlacitko:hover {
      background: #222222;
      color: #ffffff;
      transform: translateY(-2px);
    }
    .admin-zalozka-tlacitko.aktivni {
      background: linear-gradient(135deg, #991b1b, #7f1d1d);
      border-color: #ef4444;
      color: #ffffff;
      box-shadow: 0 6px 20px rgba(229, 9, 20, 0.35);
    }

    /* OBSAH ZÁLOŽEK */
    .admin-zalozka-obsah { display: none; }
    .admin-zalozka-obsah.aktivni { display: block; }

    /* NADPIS SEZNAMU */
    .sekce-nadpis-hlavni {
      font-size: 1.75rem;
      font-weight: 900;
      color: #f59e0b;
      font-family: var(--font-hlavni);
      margin-bottom: 22px;
      letter-spacing: 0.03em;
    }

    /* OVLÁDACÍ LIŠTA PRO PIZZY (VŠECHNO V JEDNOM ŘÁDKU NA DESKTOPU) */
    .admin-ovladaci-lista-pizzy {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
      margin-bottom: 24px;
      width: 100%;
    }
    .admin-ovladaci-leva-cast {
      display: flex;
      align-items: center;
      gap: 16px;
      flex: 1;
      max-width: 800px;
    }
    .admin-vyhledavani-obal {
      flex: 1;
      min-width: 240px;
      max-width: 420px;
    }
    .admin-ovladaci-lista-pizzy #sw-pohled-pizz {
      margin-left: auto;
      flex-shrink: 0;
    }

    /* MOBILNÍ ZOBRAZENÍ: POD SEBOU, SWITCH STÁLÉ PIZZY ZAROVNANÝ DOPRAVA */
    @media (max-width: 850px) {
      .admin-ovladaci-lista-pizzy {
        flex-direction: column;
        align-items: stretch;
        gap: 14px;
      }
      .admin-ovladaci-leva-cast {
        flex-direction: column;
        align-items: stretch;
        max-width: 100%;
        gap: 14px;
      }
      .admin-ovladaci-lista-pizzy #sw-filtr-pizz-tydne {
        align-self: flex-end;
      }
      .admin-vyhledavani-obal {
        width: 100%;
        max-width: 100%;
      }
      .admin-ovladaci-lista-pizzy #sw-pohled-pizz {
        align-self: flex-end;
        margin-left: auto;
      }
    }

    /* FILTRAČNÍ ŘÁDEK PRO SUROVINY */
    .admin-filtr-radek-cisty {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
      margin-bottom: 24px;
      flex-wrap: wrap;
    }
    .admin-vyhledavani-vstup {
      padding: 12px 18px;
      background: #161616;
      border: 1px solid #333333;
      color: #ffffff;
      border-radius: 8px;
      font-size: 0.98rem;
      min-width: 240px;
      transition: all 0.2s ease;
    }
    .admin-vyhledavani-vstup:focus {
      border-color: #f59e0b;
      box-shadow: 0 0 12px rgba(245, 158, 11, 0.25);
      outline: none;
    }

    /* ŘÁDEK PRO PŘIDÁNÍ PIZZE A PREPINAC POČTU DLAŽDIC (3 / 4) */
    .admin-radek-pridat-prepinac {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
      margin-bottom: 22px;
      flex-wrap: wrap;
    }
    .prepinac-dlazdic-obal {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .prepinac-dlazdic-stitek {
      font-size: 0.9rem;
      font-weight: 700;
      color: #aaaaaa !important;
    }

    body, input, button, select, textarea, td, th, label, span, p, h1, h2, h3, h4, h5, h6 {
      font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
    }

    /* ODEBRÁNÍ ŠIPEK (PŘIDAT/ODEBRAT) U ČÍSELNÝCH VSTUPŮ (BOD 8) */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
      -webkit-appearance: none !important;
      margin: 0 !important;
    }
    input[type="number"] {
      -moz-appearance: textfield !important;
      appearance: textfield !important;
    }

    /* SAMOSTATNÝ BOX FORMULÁŘE PRO PŘIDÁNÍ PIZZE (PODBARVEN ZELENĚ, NIŽŠÍ VÝŠKA) */
    .admin-samostatny-box-formular {
      background-color: rgba(22, 163, 74, 0.15);
      border: 1px solid #16a34a;
      border-radius: 14px;
      padding: 10px 20px;
      margin-bottom: 20px;
      box-shadow: 0 6px 20px rgba(22, 163, 74, 0.15);
    }

    /* SKUPINY FORMULÁŘE A VĚTŠÍ MEZERAVOST (BOD 7) */
    .formular-skupina {
      margin-bottom: 26px;
    }

    /* VSTUPNÍ POLE FORMULÁŘE (DOKONALE JEDNOTNÝ VZHLED PODLE POLE "HLEDAT PIZZU PODLE NÁZVU") */
    input[type="text"],
    input[type="number"],
    input[type="password"],
    textarea,
    select,
    .admin-vyhledavani-vstup {
      width: 100% !important;
      padding: 12px 18px !important;
      background: #161616 !important;
      border: 1px solid #333333 !important;
      color: #ffffff !important;
      border-radius: 8px !important;
      font-size: 0.98rem !important;
      font-family: var(--font-hlavni) !important;
      box-sizing: border-box !important;
      text-align: left !important;
      transition: all 0.2s ease !important;
    }

    input[type="text"]:focus,
    input[type="number"]:focus,
    input[type="password"]:focus,
    textarea:focus,
    select:focus,
    .admin-vyhledavani-vstup:focus {
      border-color: #f59e0b !important;
      box-shadow: 0 0 12px rgba(245, 158, 11, 0.25) !important;
      outline: none !important;
    }

    input::placeholder,
    textarea::placeholder,
    .admin-vyhledavani-vstup::placeholder {
      color: #aaaaaa !important;
      font-weight: 400 !important;
      opacity: 1 !important;
    }

    /* 1. ŘÁDEK FORMULÁŘE: ČÍSLO PIZZY, NÁZEV PIZZY (PLNĚ PRODLOŽENÉ), CENA V KČ, ⭐ PIZZA TÝDNE */
    .formular-radek-trojice {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 26px;
      flex-wrap: wrap;
    }
    .vstup-obal-cislo {
      width: 85px; /* Jen tak široké, aby se vešlo trojciferné číslo */
      flex-shrink: 0;
    }
    .vstup-uzky-cislo input {
      padding: 12px 10px !important;
      text-align: center !important;
    }
    .vstup-obal-nazev {
      flex: 1; /* Plně roztažené přes celý volný prostor */
      min-width: 250px;
    }
    .vstup-obal-cena {
      width: 130px; /* Mezi názvem a pizzou týdne */
      flex-shrink: 0;
    }
    .vstup-obal-star {
      flex-shrink: 0;
    }

    /* KAPSLOVÉ SWITCHERY (PŘESNĚ PODLE VZORU Z OBRÁZKU - BÍLÝ AKTIVNÍ SLIDER) */
    .mini-kapsle-prepinac {
      background: #141414;
      border: 1px solid #2a2a2a;
      padding: 4px;
      border-radius: 40px;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }
    .mini-kapsle-tlacitko {
      background: transparent;
      border: none;
      color: #ffffff;
      font-size: 0.9rem;
      font-weight: 700;
      padding: 8px 20px;
      border-radius: 30px;
      cursor: pointer;
      font-family: var(--font-hlavni);
      transition: background-color 0.2s ease, color 0.2s ease;
      white-space: nowrap;
    }
    .mini-kapsle-tlacitko:hover {
      color: #ffffff;
      opacity: 0.9;
    }
    .mini-kapsle-tlacitko.aktivni {
      background-color: #ffffff !important;
      color: #000000 !important;
      font-weight: 800 !important;
      border-radius: 30px !important;
      box-shadow: 0 2px 10px rgba(255, 255, 255, 0.25) !important;
    }

    /* TEXTOVÉ TLAČÍTKO PŘIDAT PIZZU (VĚTŠÍ IKONA +) */
    .tlacitko-text-pridat {
      background: transparent;
      border: none;
      color: #ffffff !important;
      font-size: 1.15rem;
      font-weight: 800;
      cursor: pointer;
      padding: 6px 0;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      transition: opacity 0.2s ease, transform 0.2s ease;
    }
    .tlacitko-text-pridat:hover {
      color: #ffffff !important;
      opacity: 0.85;
      transform: translateX(4px);
    }
    .ikona-plus-bila {
      color: #ffffff !important;
      font-weight: 900;
      font-size: 1.85rem;
      line-height: 1;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 28px;
      height: 28px;
    }

    /* TLAČÍTKO PIZZA TÝDNE VE FORMULÁŘI */
    .tlacitko-star-formular {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      height: 46px;
      padding: 0 18px;
      background: #161616;
      border: 1px solid #333333;
      border-radius: 8px;
      color: #aaaaaa;
      font-size: 0.95rem;
      font-weight: 700;
      cursor: pointer;
      white-space: nowrap;
      transition: all 0.25s ease;
    }
    .tlacitko-star-formular:hover {
      border-color: #f59e0b;
      color: #ffffff;
      transform: translateY(-1px);
    }
    .tlacitko-star-formular.aktivni {
      background: linear-gradient(135deg, #d97706, #b45309);
      border-color: #f59e0b;
      color: #ffffff;
      box-shadow: 0 4px 14px rgba(245, 158, 11, 0.4);
    }

    /* INGREDIENCE, ALERGENY A DALŠÍ PŘÍZNAKY JAKO INTERAKTIVNÍ TLAČÍTKA */
    .skupina-alergen {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 8px;
    }
    .alergen-checkbox {
      display: none !important;
    }
    .alergen-stitek {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 16px;
      background: #161616;
      border: 1px solid #333333;
      border-radius: 10px;
      color: #bbbbbb;
      font-size: 0.92rem;
      font-weight: 600;
      cursor: pointer;
      user-select: none;
      transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .alergen-stitek:hover {
      background: #222222;
      border-color: #555555;
      color: #ffffff;
      transform: translateY(-1px);
    }
    .alergen-checkbox:checked + .alergen-stitek {
      background: #222222;
      border-color: #f59e0b;
      color: #ffffff;
      box-shadow: 0 3px 12px rgba(245, 158, 11, 0.25);
      font-weight: 700;
    }
    .alergen-cislo {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 22px;
      height: 22px;
      background: #2a2a2a;
      color: #ffffff;
      border-radius: 50%;
      font-size: 0.8rem;
      font-weight: 800;
      transition: all 0.2s ease;
    }
    .alergen-checkbox:checked + .alergen-stitek .alergen-cislo {
      background: #f59e0b;
      color: #000000;
    }

    /* CUSTOM NAHRÁVÁNÍ FOTKY (BÍLÉ TLAČÍTKO BEZ IKONY) */
    .custom-file-upload-wrapper {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-top: 6px;
    }
    .custom-file-input-hidden {
      display: none !important;
    }
    .tlacitko-custom-upload {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 11px 24px;
      background: #ffffff;
      border: 1px solid #ffffff;
      border-radius: 8px;
      color: #111111;
      font-size: 0.95rem;
      font-weight: 800;
      cursor: pointer;
      transition: all 0.25s ease;
      font-family: var(--font-hlavni);
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
    }
    .tlacitko-custom-upload:hover {
      background: #f1f5f9;
      border-color: #cbd5e1;
      color: #000000;
      box-shadow: 0 6px 16px rgba(255, 255, 255, 0.4);
      transform: translateY(-2px);
    }
    .napis-soubor-stav {
      font-size: 0.92rem;
      color: #aaaaaa;
      font-weight: 500;
    }

    /* ČÍSELNÝ ODZNAK PIZZE */
    .ciselny-odznak {
      background-color: #e50914;
      color: #ffffff !important;
      font-weight: 900;
      font-size: 1rem;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
      flex-shrink: 0;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
    }
    .pizza-dlazdice-foto-obal .ciselny-odznak {
      position: absolute;
      top: 12px;
      left: 12px;
      z-index: 2;
    }

    /* POHLED DLAŽDICE VS SEZNAM */
    .pizzy-mrizka-kontejner {
      margin-top: 18px;
      transition: all 0.25s ease;
    }
    
    /* REŽIM DLAŽDICE: DEFAULTNĚ 4 DLAŽDICE NA ŘÁDEK */
    .pizzy-mrizka-kontejner,
    .pizzy-mrizka-kontejner.pohled-dlazdice {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
    }
    .pizzy-mrizka-kontejner.pohled-dlazdice .ciselny-odznak-seznam-obal,
    .pizzy-mrizka-kontejner.pohled-dlazdice .seznam-cislo-prefiks {
      display: none !important;
    }
    .pizzy-mrizka-kontejner.pohled-dlazdice .seznam-textovy-blok {
      display: flex;
      flex-direction: column;
      flex: 1;
    }
    .pizzy-mrizka-kontejner.pohled-dlazdice .pizza-dlazdice-pata {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-top: 16px;
      border-top: 1px solid #282828;
      margin-top: auto;
    }
    @media (max-width: 1100px) {
      .pizzy-mrizka-kontejner,
      .pizzy-mrizka-kontejner.pohled-dlazdice {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    @media (max-width: 640px) {
      .pizzy-mrizka-kontejner,
      .pizzy-mrizka-kontejner.pohled-dlazdice {
        grid-template-columns: 1fr;
      }
    }

    /* REŽIM SEZNAM NA DESKTOPU: ČÍSLO VLEVO, NÁZEV, INGREDIENCE A CENA POD SEBOU ZAROVNANÉ (BOD 6) */
    .pizzy-mrizka-kontejner.pohled-seznam {
      display: flex !important;
      flex-direction: column;
      gap: 14px;
    }
    .pizzy-mrizka-kontejner.pohled-seznam .pizza-dlazdice {
      background: #161616;
      border: 1px solid #2a2a2a;
      border-radius: 12px;
      padding: 18px 24px;
      box-shadow: none;
      display: flex;
      flex-direction: row;
      align-items: flex-start;
      justify-content: space-between;
      gap: 20px;
      transition: border-color 0.2s ease;
    }
    .pizzy-mrizka-kontejner.pohled-seznam .pizza-dlazdice:hover {
      border-color: #f59e0b;
      transform: none;
    }
    .pizzy-mrizka-kontejner.pohled-seznam .pizza-dlazdice-foto-obal {
      display: none !important;
    }
    .pizzy-mrizka-kontejner.pohled-seznam .pizza-dlazdice-obsah {
      padding: 0;
      display: flex !important;
      flex-direction: column !important;
      gap: 4px !important;
      flex: 1;
      position: relative;
      padding-right: 110px;
    }
    .pizzy-mrizka-kontejner.pohled-seznam .ciselny-odznak-seznam-obal {
      display: none !important;
    }
    .pizzy-mrizka-kontejner.pohled-seznam .seznam-cislo-prefiks {
      display: inline !important;
      font-weight: 800;
      color: #ffffff;
    }
    .pizzy-mrizka-kontejner.pohled-seznam .seznam-textovy-blok {
      display: flex;
      flex-direction: column;
      gap: 4px;
      flex: 1;
      min-width: 0;
    }
    .pizzy-mrizka-kontejner.pohled-seznam .pizza-dlazdice-zahlavi {
      margin-bottom: 4px;
    }
    .pizzy-mrizka-kontejner.pohled-seznam .pizza-dlazdice-slozeni {
      display: none !important;
    }
    .pizzy-mrizka-kontejner.pohled-seznam .pizza-dlazdice-alergeny {
      display: none !important;
    }
    .pizzy-mrizka-kontejner.pohled-seznam .pizza-dlazdice-pata {
      border-top: none;
      padding-top: 0;
      margin-top: 2px;
      display: block;
    }
    .pizzy-mrizka-kontejner.pohled-seznam .pizza-dlazdice-cena {
      display: block !important;
      font-size: 1.25rem;
      font-weight: 900;
      color: #f59e0b !important;
      text-align: left;
    }
    .pizzy-mrizka-kontejner.pohled-seznam .pizza-dlazdice-akce {
      position: absolute;
      top: 0;
      right: 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* RESPONZIVITA SEZNAMU PRO MOBILY: 3. ŘÁDEK CENA VLEVO A TLAČÍTKA ZAROVNANÁ VPRAVO */
    @media (max-width: 680px) {
      .pizzy-mrizka-kontejner.pohled-seznam .pizza-dlazdice {
        flex-direction: column;
        align-items: flex-start;
        padding: 16px;
      }
      .pizzy-mrizka-kontejner.pohled-seznam .pizza-dlazdice-obsah {
        width: 100%;
        position: static;
        padding-right: 0;
        display: flex !important;
        flex-direction: column !important;
        gap: 4px !important;
      }
      .pizzy-mrizka-kontejner.pohled-seznam .pizza-dlazdice-pata {
        width: 100%;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        margin-top: 8px;
        padding-top: 10px;
        border-top: 1px solid #282828;
      }
      .pizzy-mrizka-kontejner.pohled-seznam .pizza-dlazdice-cena {
        margin-bottom: 0;
      }
      .pizzy-mrizka-kontejner.pohled-seznam .pizza-dlazdice-akce {
        position: static;
        margin-left: auto;
      }
    }

    .pizza-dlazdice {
      background: #161616;
      border: 1px solid #2a2a2a;
      border-radius: 16px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease, box-shadow 0.25s ease;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
    }
    .pizza-dlazdice:hover {
      border-color: #f59e0b;
      box-shadow: 0 12px 32px rgba(245, 158, 11, 0.2);
      transform: translateY(-5px);
    }
    .pizza-dlazdice-foto-obal {
      position: relative;
      width: 100%;
      height: 180px;
      background: #0d0d0d;
      overflow: hidden;
    }
    .pizza-dlazdice-fotka {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.4s ease;
    }
    .pizza-dlazdice:hover .pizza-dlazdice-fotka {
      transform: scale(1.07);
    }
    .stitek-pizza-tydne-karta {
      position: absolute;
      top: 14px;
      right: 14px;
      background: linear-gradient(135deg, #d97706, #b45309);
      color: #ffffff !important;
      font-size: 0.78rem;
      font-weight: 800;
      padding: 5px 12px;
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.6);
      display: flex;
      align-items: center;
      gap: 5px;
      letter-spacing: 0.03em;
    }
    .pizza-dlazdice-obsah {
      padding: 20px;
      display: flex;
      flex-direction: column;
      flex: 1;
    }
    .pizza-dlazdice-zahlavi {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 10px;
      margin-bottom: 10px;
    }
    .pizza-dlazdice-nazev {
      font-size: 1.25rem;
      font-weight: 800;
      color: #ffffff;
      line-height: 1.25;
    }
    .pizza-dlazdice-slozeni {
      font-size: 0.94rem;
      color: #bbbbbb !important;
      margin-bottom: 14px;
      line-height: 1.48;
      flex: 1;
    }
    .pizza-dlazdice-alergeny {
      font-size: 0.85rem;
      color: #888888 !important;
      margin-bottom: 14px;
      background: transparent;
      padding: 0;
      border: none;
      display: block;
    }
    .pizza-dlazdice-pata {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-top: 16px;
      border-top: 1px solid #282828;
      margin-top: auto;
    }
    .pizza-dlazdice-cena {
      font-size: 1.4rem;
      font-weight: 900;
      color: #f59e0b !important;
      white-space: nowrap;
      letter-spacing: 0.02em;
    }
    .pizza-dlazdice-akce {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* RÁMEČKOVÁ TLAČÍTKA PRO IKONY (UPRAVIT A SMAZAT) */
    .tlacitko-ikona-ramcek {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 42px;
      height: 42px;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
      text-decoration: none;
      padding: 0;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
    }
    
    /* IKONA UPRAVIT: BÍLÉ POZADÍ */
    .tlacitko-ikona-upravit {
      background: #ffffff;
      border: 1px solid #ffffff;
      color: #111111;
    }
    .tlacitko-ikona-upravit:hover {
      background: #f1f5f9;
      border-color: #cbd5e1;
      color: #000000;
      box-shadow: 0 6px 16px rgba(255, 255, 255, 0.4);
      transform: translateY(-2px);
    }
    
    /* IKONA SMAZAT: ČERVENÉ POZADÍ */
    .tlacitko-ikona-smazat {
      background: #dc2626;
      border: 1px solid #dc2626;
      color: #ffffff;
    }
    .tlacitko-ikona-smazat:hover {
      background: #b91c1c;
      border-color: #991b1b;
      color: #ffffff;
      box-shadow: 0 6px 16px rgba(220, 38, 38, 0.5);
      transform: translateY(-2px);
    }

    .tlacitko-ikona-ramcek svg {
      stroke: currentColor;
    }

    /* OZNÁMENÍ A CHYBOVÉ BOXY */
    .oznameni-uspesne {
      background-color: rgba(22, 163, 74, 0.15);
      border: 1px solid #16a34a;
      color: #4ade80;
      padding: 14px 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-weight: 600;
      font-size: 0.95rem;
      box-shadow: 0 4px 12px rgba(22, 163, 74, 0.15);
    }
    .oznameni-chyba {
      background-color: rgba(220, 38, 38, 0.15);
      border: 1px solid #dc2626;
      color: #f87171;
      padding: 14px 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-weight: 600;
      font-size: 0.95rem;
      box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
    }
    .tlacitko-zavrit-oznameni {
      background: transparent;
      border: none;
      color: currentColor;
      font-size: 1.1rem;
      cursor: pointer;
      padding: 0 4px;
      opacity: 0.7;
      transition: opacity 0.2s ease;
      line-height: 1;
    }
    .tlacitko-zavrit-oznameni:hover {
      opacity: 1;
    }
  </style>
</head>
<body>

  <!-- HORNI LISTA -->
  <header class="horni-kontakty-lista">
    <div class="kontejner kontakty-obsah">
      <div style="display: flex; align-items: center; gap: 15px;">
        <span style="font-weight: 800; color: #ffffff;">ADMINISTRACE PIZZERIE</span>
      </div>
      <div style="display: flex; align-items: center; gap: 10px;">
        <a href="index.php" class="tlacitko tlacitko-cervene" style="padding: 6px 16px; font-size: 0.85rem;">&lsaquo; Zpět na web</a>
        <?php if ($jePrihlasen): ?>
          <a href="administrace.php?akce=odhlasit" class="tlacitko tlacitko-sede" style="padding: 6px 16px; font-size: 0.85rem;">Odhlásit se</a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <div class="admin-kontejner">

    <?php if (!empty($zpravaOznameni)): ?>
      <div class="oznameni-uspesne">
        <span><?php echo htmlspecialchars($zpravaOznameni); ?></span>
        <button type="button" class="tlacitko-zavrit-oznameni" onclick="this.parentElement.style.display='none'" title="Zavřít oznamení">✕</button>
      </div>
    <?php endif; ?>

    <?php if (!empty($zpravaChyba)): ?>
      <div class="oznameni-chyba">
        <span><?php echo htmlspecialchars($zpravaChyba); ?></span>
        <button type="button" class="tlacitko-zavrit-oznameni" onclick="this.parentElement.style.display='none'" title="Zavřít oznamení">✕</button>
      </div>
    <?php endif; ?>

    <?php if (!$jePrihlasen): ?>
      
      <!-- FORMULAR PRIHLASENI -->
      <div class="admin-formular-box-inline" style="max-width: 450px; margin: 80px auto;">
        <h2 class="nadpis-sekce" style="font-size: 1.8rem; text-align: center; margin-bottom: 20px;">PŘIHLÁŠENÍ DO ADMINISTRACE</h2>
        <form method="POST" action="administrace.php">
          <div class="formular-skupina">
            <label for="heslo_vstup">Zadejte administrátorské heslo:</label>
            <input type="password" id="heslo_vstup" name="heslo_vstup" required placeholder="Heslo (např. kure123)" style="width: 100%; padding: 12px; background: #0d0d0d; border: 1px solid #333; color: #ffffff; border-radius: 6px;">
          </div>
          <button type="submit" name="prihlasit_stisknuto" class="tlacitko tlacitko-cervene" style="width: 100%;">Vstoupit do administrace</button>
        </form>
      </div>

    <?php else: ?>

      <!-- 1. TABY NAHOŘE -->
      <div class="admin-zalozky-obal">
        <button type="button" class="admin-zalozka-tlacitko <?php echo !$upravovanaSurovina ? 'aktivni' : ''; ?>" onclick="prepnoutZalozku('pizzy')">
          PIZZY (<?php echo count($pizzySeznam); ?>)
        </button>
        <button type="button" class="admin-zalozka-tlacitko <?php echo $upravovanaSurovina ? 'aktivni' : ''; ?>" onclick="prepnoutZalozku('ingredience')">
          INGREDIENCE (<?php echo count($surovinySeznam); ?>)
        </button>
        <button type="button" class="admin-zalozka-tlacitko" onclick="prepnoutZalozku('zalohy')">
          ZÁLOHY & VERZOVÁNÍ (<?php echo count($seznamZaloh); ?>)
        </button>
      </div>

      <!-- ====================================================================
           ZÁLOŽKA 1: PIZZY
           ==================================================================== -->
      <div id="sekce-zalozka-pizzy" class="admin-zalozka-obsah <?php echo !$upravovanaSurovina ? 'aktivni' : ''; ?>">
        
        <!-- NADPIS -->
        <h2 class="sekce-nadpis-hlavni">SEZNAM PIZZ V MENU (<?php echo count($pizzySeznam); ?>)</h2>

        <!-- SAMOSTATNÝ BOX PRO PŘIDÁNÍ / ÚPRAVU PIZZY (BOD 1 A 2: UMÍSTĚNÍ MEZI NADPIS A SWITCH) -->
        <div class="admin-samostatny-box-formular">
          
          <!-- TEXTOVÉ TLAČÍTKO "+ PŘIDAT NOVOU PIZZU" -->
          <button type="button" class="tlacitko-text-pridat" onclick="zobrazitFormularPizzu()">
            <span class="ikona-plus-bila">+</span>
            <span style="color: #ffffff !important; font-weight: 800;"><?php echo $upravovanaPizza ? 'Upravit pizzu: ' . htmlspecialchars($upravovanaPizza['nazev']) : 'Přidat novou pizzu'; ?></span>
          </button>

          <!-- FORMULÁŘ PRO PŘIDÁNÍ / ÚPRAVU PIZZY -->
          <div id="obal-formular-pizza" class="admin-formular-box-inline" style="<?php echo $upravovanaPizza ? 'display:block; margin-top: 18px;' : 'display:none; margin-top: 18px;'; ?>">
            
            <form method="POST" action="administrace.php" enctype="multipart/form-data">
              <input type="hidden" name="id_pizzy" value="<?php echo $upravovanaPizza['id'] ?? ''; ?>">
              <input type="hidden" name="stavajici_obrazek" value="<?php echo $upravovanaPizza['obrazek'] ?? ''; ?>">

              <!-- 1. ŘÁDEK: ČÍSLO PIZZY, NÁZEV PIZZY (20% ŠIRŠÍ), CENA V KČ (MEZI NÁZVEM A PIZZOU TÝDNE), PIZZA TÝDNE -->
              <div class="formular-radek-trojice">
                
                <!-- ČÍSLO PIZZY -->
                <div class="vstup-obal-cislo">
                  <input type="number" name="cislo_pizzy" required placeholder="Číslo" value="<?php echo $upravovanaPizza['cislo'] ?? (count($pizzySeznam)+1); ?>" class="vstup-uzky-cislo">
                </div>

                <!-- NÁZEV PIZZY -->
                <div class="vstup-obal-nazev">
                  <input type="text" name="nazev_pizzy" required placeholder="Název pizzy (např. MARGHERITA)" value="<?php echo htmlspecialchars($upravovanaPizza['nazev'] ?? ''); ?>">
                </div>

                <!-- CENA -->
                <div class="vstup-obal-cena">
                  <input type="number" name="cena_pizzy" required placeholder="Cena v Kč" value="<?php echo isset($upravovanaPizza['cena']) ? $upravovanaPizza['cena'] : ''; ?>" class="vstup-uzky-cena">
                </div>

                <!-- PIZZA TÝDNE -->
                <div class="vstup-obal-star">
                  <input type="checkbox" id="chk_pizza_tydne" name="flag_pizza_tydne" style="display:none;" <?php echo !empty($upravovanaPizza['pizzaTydne']) ? 'checked' : ''; ?>>
                  <button type="button" id="btn-star-formular" class="tlacitko-star-formular <?php echo !empty($upravovanaPizza['pizzaTydne']) ? 'aktivni' : ''; ?>" onclick="prepnoutStarFormular()" title="Pizza týdne">
                    <span>Pizza týdne</span>
                  </button>
                </div>

              </div>

              <!-- VYBER INGREDIENCI -->
              <div class="formular-skupina">
                <label style="display:block; margin-bottom: 6px; color: #aaaaaa; font-weight: 600; font-size: 0.92rem;">Ingredience pizzy (kliknutím vyberte suroviny):</label>
                <div class="skupina-alergen">
                  <?php if (empty($surovinySeznam)): ?>
                    <p style="color: #aaa; font-style: italic;">Zatím žádné ingredience. Nejprve přidejte suroviny v záložce Ingredience.</p>
                  <?php else: ?>
                    <?php foreach ($surovinySeznam as $sur): ?>
                      <?php $zaskrtnuto = isset($upravovanaPizza['ingredience']) && in_array($sur['id'], $upravovanaPizza['ingredience']); ?>
                      <input type="checkbox" class="alergen-checkbox" id="ingr_pizza_<?php echo htmlspecialchars($sur['id']); ?>" name="ingredience_seznam[]" value="<?php echo htmlspecialchars($sur['id']); ?>" <?php echo $zaskrtnuto ? 'checked' : ''; ?>>
                      <label class="alergen-stitek tlacitko-ingredience-stitek" for="ingr_pizza_<?php echo htmlspecialchars($sur['id']); ?>">
                        <span><?php echo htmlspecialchars($sur['nazev']); ?></span>
                      </label>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>

              <!-- EU ALERGENY PRO PIZZU -->
              <?php
                $zaskrtnuteAlergenyPizzy = $upravovanaPizza['alergeny_cisla'] ?? [];
              ?>
              <div class="formular-skupina">
                <label style="display:block; margin-bottom: 6px; color: #aaaaaa; font-weight: 600; font-size: 0.92rem;">Alergeny EU (kliknutím vyberte alergen):</label>
                <div class="skupina-alergen">
                  <?php foreach ($seznamEuAlergen as $cislo => $nazevAlergenu): ?>
                    <?php $jeZaskrtnuto = in_array($cislo, $zaskrtnuteAlergenyPizzy); ?>
                    <input type="checkbox" class="alergen-checkbox" id="alergen_pizza_<?php echo $cislo; ?>" name="alergeny_cisla_pizza[]" value="<?php echo $cislo; ?>" <?php echo $jeZaskrtnuto ? 'checked' : ''; ?>>
                    <label class="alergen-stitek" for="alergen_pizza_<?php echo $cislo; ?>">
                      <span class="alergen-cislo"><?php echo $cislo; ?></span>
                      <span><?php echo $nazevAlergenu; ?></span>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>

              <!-- KATEGORIE A FLAGY -->
              <div class="formular-skupina">
                <label style="display:block; margin-bottom: 6px; color: #aaaaaa; font-weight: 600; font-size: 0.92rem;">Vlastnosti a příznaky pizzy:</label>
                
                <div class="skupina-vlastnosti-box">
                  
                  <!-- 1. Druh masa (2-možnostní switch) -->
                  <div class="prepinac-skupina-obal">
                    <span class="prepinac-stitek-popis">Druh masa:</span>
                    <div class="mini-kapsle-prepinac" id="sw-admin-maso">
                      <button type="button" class="mini-kapsle-tlacitko <?php echo empty($upravovanaPizza['bezmase']) ? 'aktivni' : ''; ?>" data-val="optA" onclick="prepnoutAdminKapsli('maso', 'optA')">Masité</button>
                      <button type="button" class="mini-kapsle-tlacitko <?php echo !empty($upravovanaPizza['bezmase']) ? 'aktivni' : ''; ?>" data-val="optB" onclick="prepnoutAdminKapsli('maso', 'optB')">Bezmasé</button>
                    </div>
                    <input type="checkbox" id="chk_masite" name="flag_masite" style="display:none;" <?php echo empty($upravovanaPizza['bezmase']) ? 'checked' : ''; ?>>
                    <input type="checkbox" id="chk_bezmase" name="flag_bezmase" style="display:none;" <?php echo !empty($upravovanaPizza['bezmase']) ? 'checked' : ''; ?>>
                  </div>

                  <!-- 2. Pálivost (2-možnostní switch) -->
                  <div class="prepinac-skupina-obal">
                    <span class="prepinac-stitek-popis">Pálivost:</span>
                    <div class="mini-kapsle-prepinac" id="sw-admin-palivost">
                      <button type="button" class="mini-kapsle-tlacitko <?php echo empty($upravovanaPizza['paliva']) ? 'aktivni' : ''; ?>" data-val="optB" onclick="prepnoutAdminKapsli('palivost', 'optB')">Nepálivá</button>
                      <button type="button" class="mini-kapsle-tlacitko <?php echo !empty($upravovanaPizza['paliva']) ? 'aktivni' : ''; ?>" data-val="optA" onclick="prepnoutAdminKapsli('palivost', 'optA')">Pálivá</button>
                    </div>
                    <input type="checkbox" id="chk_paliva" name="flag_paliva" style="display:none;" <?php echo !empty($upravovanaPizza['paliva']) ? 'checked' : ''; ?>>
                    <input type="checkbox" id="chk_nepaliva" name="flag_nepaliva" style="display:none;" <?php echo empty($upravovanaPizza['paliva']) ? 'checked' : ''; ?>>
                  </div>

                  <!-- 3. Základ pizzy (2-možnostní switch) -->
                  <div class="prepinac-skupina-obal">
                    <span class="prepinac-stitek-popis">Základ pizzy:</span>
                    <div class="mini-kapsle-prepinac" id="sw-admin-zaklad">
                      <button type="button" class="mini-kapsle-tlacitko <?php echo empty($upravovanaPizza['bilyZaklad']) ? 'aktivni' : ''; ?>" data-val="optA" onclick="prepnoutAdminKapsli('zaklad', 'optA')">Sugo základ</button>
                      <button type="button" class="mini-kapsle-tlacitko <?php echo !empty($upravovanaPizza['bilyZaklad']) ? 'aktivni' : ''; ?>" data-val="optB" onclick="prepnoutAdminKapsli('zaklad', 'optB')">Bílý základ</button>
                    </div>
                    <input type="checkbox" id="chk_sugo" name="flag_sugo" style="display:none;" <?php echo empty($upravovanaPizza['bilyZaklad']) ? 'checked' : ''; ?>>
                    <input type="checkbox" id="chk_bily" name="flag_bily" style="display:none;" <?php echo !empty($upravovanaPizza['bilyZaklad']) ? 'checked' : ''; ?>>
                  </div>

                  <!-- 4. Další příznaky -->
                  <div class="prepinac-skupina-obal">
                    <div class="skupina-alergen">
                      <input type="checkbox" class="alergen-checkbox" id="chk_na_miste" name="flag_na_miste" <?php echo !empty($upravovanaPizza['naMiste']) ? 'checked' : ''; ?>>
                      <label class="alergen-stitek tlacitko-vlastnost-stitek" for="chk_na_miste">Na místě</label>

                      <input type="checkbox" class="alergen-checkbox" id="chk_s_sebou" name="flag_s_sebou" <?php echo !empty($upravovanaPizza['sSebou']) ? 'checked' : ''; ?>>
                      <label class="alergen-stitek tlacitko-vlastnost-stitek" for="chk_s_sebou">S sebou</label>

                      <input type="checkbox" class="alergen-checkbox" id="chk_doporucujeme" name="flag_doporucujeme" <?php echo !empty($upravovanaPizza['doporucujeme']) ? 'checked' : ''; ?>>
                      <label class="alergen-stitek tlacitko-vlastnost-stitek" for="chk_doporucujeme">DOPORUČUJEME</label>

                      <input type="checkbox" class="alergen-checkbox" id="chk_nejprodavanejsi" name="flag_nejprodavanejsi" <?php echo !empty($upravovanaPizza['nejprodavanejsi']) ? 'checked' : ''; ?>>
                      <label class="alergen-stitek tlacitko-vlastnost-stitek" for="chk_nejprodavanejsi">NEJPRODÁVANĚJŠÍ</label>
                    </div>
                  </div>

                </div>
              </div>

              <!-- NAHRAVANI FOTKY -->
              <div class="formular-skupina" style="margin-top: 15px;">
                <div class="custom-file-upload-wrapper">
                  <input type="file" id="fotka_soubor_vstup" name="fotka_soubor" accept="image/*" class="custom-file-input-hidden" onchange="aktualizovatNazevSouboru(this, 'napis-nazev-souboru-pizza')">
                  <button type="button" class="tlacitko-custom-upload" onclick="document.getElementById('fotka_soubor_vstup').click()">
                    Vybrat fotku
                  </button>
                  <span id="napis-nazev-souboru-pizza" class="napis-soubor-stav">
                    <?php echo !empty($upravovanaPizza['obrazek']) ? htmlspecialchars(basename($upravovanaPizza['obrazek'])) : 'Soubor nevybrán'; ?>
                  </span>
                </div>
              </div>

              <div style="display: flex; gap: 15px; margin-top: 25px;">
                <button type="submit" name="ulozit_pizzu_stisknuto" class="tlacitko tlacitko-cervene">
                  <?php echo $upravovanaPizza ? 'Uložit změny pizzy' : 'Přidat pizzu do menu'; ?>
                </button>
                <?php if ($upravovanaPizza): ?>
                  <a href="administrace.php" class="tlacitko tlacitko-sede">Zrušit úpravu</a>
                <?php endif; ?>
              </div>
            </form>

          </div>

        </div>

        <!-- OVLÁDACÍ LIŠTA: STÁLÉ PIZZY/PIZZA TÝDNE + HLEDÁNÍ VLEVO, DLAŽDICE/SEZNAM VPRAVO -->
        <div class="admin-ovladaci-lista-pizzy">
          
          <div class="admin-ovladaci-leva-cast">
            <!-- Switch: Stálé pizzy / Pizza týdne (Vzájemné vyloučení) -->
            <div class="mini-kapsle-prepinac" id="sw-filtr-pizz-tydne">
              <button type="button" class="mini-kapsle-tlacitko aktivni" data-filtr="stale" onclick="prepnoutFiltrTydne('stale')">Stálé pizzy (<?php echo count($pizzyStaleSeznam); ?>)</button>
              <button type="button" class="mini-kapsle-tlacitko" data-filtr="tydne" onclick="prepnoutFiltrTydne('tydne')">Pizza týdne (<?php echo count($pizzyTydneSeznam); ?>)</button>
            </div>

            <!-- Vyhledávání podle názvu pizzy -->
            <div class="admin-vyhledavani-obal">
              <input type="text" id="vstup-hledat-pizzu" class="admin-vyhledavani-vstup" placeholder="Hledat pizzu podle názvu..." onkeyup="naZmenuHledani()">
            </div>
          </div>

          <!-- Switch: Dlaždice / Seznam (zarovnaný doprava) -->
          <div class="mini-kapsle-prepinac" id="sw-pohled-pizz">
            <button type="button" class="mini-kapsle-tlacitko aktivni" data-pohled="dlazdice" onclick="prepnoutPohledPizz('dlazdice')">Dlaždice</button>
            <button type="button" class="mini-kapsle-tlacitko" data-pohled="seznam" onclick="prepnoutPohledPizz('seznam')">Seznam</button>
          </div>

        </div>

        <!-- DLAŽDICE / SEZNAM PIZZ -->
        <div class="pizzy-mrizka-kontejner pohled-dlazdice" id="tabulka-pizzy-telo">
          <?php foreach ($pizzySeznam as $p): ?>
            <div class="pizza-dlazdice" data-tydne="<?php echo !empty($p['pizzaTydne']) ? '1' : '0'; ?>">
              <div class="pizza-dlazdice-foto-obal">
                <div class="ciselny-odznak"><?php echo $p['cislo']; ?></div>
                <img src="<?php echo htmlspecialchars($p['obrazek']); ?>" alt="<?php echo htmlspecialchars($p['nazev']); ?>" class="pizza-dlazdice-fotka">
                <?php if (!empty($p['pizzaTydne'])): ?>
                  <span class="stitek-pizza-tydne-karta">PIZZA TÝDNE</span>
                <?php endif; ?>
              </div>
              <div class="pizza-dlazdice-obsah">
                
                <!-- TEXTOVÝ BLOK SEZNAMU (NÁZEV, INGREDIENCE, CENA A TLAČÍTKA) -->
                <div class="seznam-textovy-blok">
                  <div class="pizza-dlazdice-zahlavi">
                    <div class="bunka-nazev" style="display: flex; align-items: center; gap: 10px;">
                      <strong class="pizza-dlazdice-nazev"><span class="seznam-cislo-prefiks"><?php echo $p['cislo']; ?>.&nbsp;</span><?php echo htmlspecialchars($p['nazev']); ?></strong>
                      <?php if (!empty($p['pizzaTydne'])): ?>
                        <span class="stitek-pizza-tydne" title="Pizza týdne">PIZZA TÝDNE</span>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="pizza-dlazdice-slozeni">
                    <small><?php echo htmlspecialchars($p['slozeni']); ?></small>
                  </div>
                  <?php if (!empty($p['alergeny_cisla'])): ?>
                    <div class="pizza-dlazdice-alergeny">
                      <small>Alergeny: <?php echo implode(', ', $p['alergeny_cisla']); ?></small>
                    </div>
                  <?php endif; ?>
                  <div class="pizza-dlazdice-pata">
                    <span class="pizza-dlazdice-cena"><?php echo $p['cena']; ?>&nbsp;Kč</span>

                    <!-- TLAČÍTKA AKCÍ NA STEJNÉM ŘÁDKU JAKO CENA (ZAROVNANÁ VPRAVO) -->
                    <div class="pizza-dlazdice-akce">
                      <a href="administrace.php?upravit_pizzu=<?php echo $p['id']; ?>" class="tlacitko-ikona-ramcek tlacitko-ikona-upravit" title="Upravit pizzu <?php echo htmlspecialchars($p['nazev']); ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                      </a>
                      <form method="POST" action="administrace.php" onsubmit="return confirm('Opravdu chcete smazat pizzu <?php echo htmlspecialchars($p['nazev']); ?>?');" style="margin: 0;">
                        <input type="hidden" name="id_pizzy_smazat" value="<?php echo $p['id']; ?>">
                        <button type="submit" name="smazat_pizzu_stisknuto" class="tlacitko-ikona-ramcek tlacitko-ikona-smazat" title="Smazat pizzu <?php echo htmlspecialchars($p['nazev']); ?>">
                          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                          </svg>
                        </button>
                      </form>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          <?php endforeach; ?>
        </div>

      </div>

      <!-- ====================================================================
           ZÁLOŽKA 2: INGREDIENCE
           ==================================================================== -->
      <div id="sekce-zalozka-ingredience" class="admin-zalozka-obsah <?php echo $upravovanaSurovina ? 'aktivni' : ''; ?>">
        
        <h2 class="sekce-nadpis-hlavni">SEZNAM INGREDIENCÍ A SUROVIN (<?php echo count($surovinySeznam); ?>)</h2>

        <!-- FILTRAČNÍ ŘÁDEK PRO SUROVINY BEZ BOXU -->
        <div class="admin-filtr-radek-cisty">
          <div class="mini-kapsle-prepinac" id="sw-filtr-surovin">
            <button type="button" class="mini-kapsle-tlacitko aktivni" data-filtr="vse" onclick="filtrovatTabulkuSurovin('vse')">Všechny (<?php echo count($surovinySeznam); ?>)</button>
            <button type="button" class="mini-kapsle-tlacitko" data-filtr="syry" onclick="filtrovatTabulkuSurovin('syry')">Sýry</button>
            <button type="button" class="mini-kapsle-tlacitko" data-filtr="maso" onclick="filtrovatTabulkuSurovin('maso')">Maso & Uzeniny</button>
            <button type="button" class="mini-kapsle-tlacitko" data-filtr="zelenina" onclick="filtrovatTabulkuSurovin('zelenina')">Zelenina & Bylinky</button>
          </div>

          <input type="text" id="vstup-hledat-surovinu" class="admin-vyhledavani-vstup" placeholder="Hledat ingredienci..." onkeyup="filtrovatTabulkuSurovinText()">
        </div>

        <!-- TEXTOVÉ TLAČÍTKO "+ PŘIDAT NOVOU INGREDIENCI" (BÍLÁ IKONA I TEXT) -->
        <button type="button" class="tlacitko-text-pridat" onclick="zobrazitFormularSurovinu()">
          <span class="ikona-plus-bila">+</span>
          <span style="color: #ffffff !important; font-weight: 800;"><?php echo $upravovanaSurovina ? 'Upravit surovinu: ' . htmlspecialchars($upravovanaSurovina['nazev']) : 'Přidat novou ingredienci'; ?></span>
        </button>

        <!-- FORMULÁŘ PRO PRIDANI / UPRAVU INGREDIENCE -->
        <div id="obal-formular-surovina" class="admin-formular-box-inline" style="<?php echo $upravovanaSurovina ? 'display:block;' : 'display:none;'; ?>">

          <form method="POST" action="administrace.php" enctype="multipart/form-data">
            <input type="hidden" name="id_suroviny" value="<?php echo $upravovanaSurovina['id'] ?? ''; ?>">
            <input type="hidden" name="stavajici_obrazek_suroviny" value="<?php echo $upravovanaSurovina['obrazek'] ?? ''; ?>">

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
              <div class="formular-skupina">
                <label>Název ingredience:</label>
                <input type="text" name="nazev_suroviny" required placeholder="Např. Mozzarella di Bufala" value="<?php echo htmlspecialchars($upravovanaSurovina['nazev'] ?? ''); ?>">
              </div>
              <div class="formular-skupina">
                <label>Kategorie:</label>
                <select name="kategorie_suroviny">
                  <option value="syry" <?php echo ($upravovanaSurovina['kategorie'] ?? '') === 'syry' ? 'selected' : ''; ?>>Sýry</option>
                  <option value="maso" <?php echo ($upravovanaSurovina['kategorie'] ?? '') === 'maso' ? 'selected' : ''; ?>>Maso & Uzeniny</option>
                  <option value="zelenina" <?php echo ($upravovanaSurovina['kategorie'] ?? '') === 'zelenina' ? 'selected' : ''; ?>>Zelenina & Bylinky</option>
                  <option value="omacky" <?php echo ($upravovanaSurovina['kategorie'] ?? '') === 'omacky' ? 'selected' : ''; ?>>Omáčky & Těsto</option>
                  <option value="specialni" <?php echo ($upravovanaSurovina['kategorie'] ?? '') === 'specialni' ? 'selected' : ''; ?>>Speciální</option>
                </select>
              </div>
            </div>

            <div class="formular-skupina">
              <label>Původ ingredience (vlajka / země):</label>
              <input type="text" name="puvod_suroviny" placeholder="Neapol, Itálie" value="<?php echo htmlspecialchars($upravovanaSurovina['puvod'] ?? 'Itálie'); ?>">
            </div>

            <div class="formular-skupina">
              <label>Popis ingredience:</label>
              <textarea name="popis_suroviny" rows="2" placeholder="Tradiční čerstvý sýr z buvolího mléka..."><?php echo htmlspecialchars($upravovanaSurovina['popis'] ?? ''); ?></textarea>
            </div>

            <div class="formular-skupina" style="margin-top: 15px;">
              <div class="custom-file-upload-wrapper">
                <input type="file" id="fotka_suroviny_vstup" name="fotka_suroviny_soubor" accept="image/*" class="custom-file-input-hidden" onchange="aktualizovatNazevSouboru(this, 'napis-nazev-souboru-surovina')">
                <button type="button" class="tlacitko-custom-upload" onclick="document.getElementById('fotka_suroviny_vstup').click()">
                  Vybrat fotku
                </button>
                <span id="napis-nazev-souboru-surovina" class="napis-soubor-stav">
                  <?php echo !empty($upravovanaSurovina['obrazek']) ? htmlspecialchars(basename($upravovanaSurovina['obrazek'])) : 'Soubor nevybrán'; ?>
                </span>
              </div>
            </div>

            <!-- EU ALERGENY PRO SUROVINU -->
            <?php
              $zaskrtnuteAlergenyS = $upravovanaSurovina['alergeny_cisla'] ?? [];
            ?>
            <div class="formular-skupina">
              <label>Alergeny EU (zaškrtni číslo):</label>
              <div class="skupina-alergen">
                <?php foreach ($seznamEuAlergen as $cislo => $nazevAlergenu): ?>
                  <?php $jeZaskrtnuto = in_array($cislo, $zaskrtnuteAlergenyS); ?>
                  <input type="checkbox" class="alergen-checkbox" id="alergen_sur_<?php echo $cislo; ?>" name="alergeny_cisla_suroviny[]" value="<?php echo $cislo; ?>" <?php echo $jeZaskrtnuto ? 'checked' : ''; ?>>
                  <label class="alergen-stitek" for="alergen_sur_<?php echo $cislo; ?>">
                    <span class="alergen-cislo"><?php echo $cislo; ?></span>
                    <span><?php echo $nazevAlergenu; ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 25px;">
              <button type="submit" name="ulozit_surovinu_stisknuto" class="tlacitko tlacitko-cervene">
                <?php echo $upravovanaSurovina ? 'Uložit změny suroviny' : 'Přidat ingredienci'; ?>
              </button>
              <?php if ($upravovanaSurovina): ?>
                <a href="administrace.php" class="tlacitko tlacitko-sede">Zrušit</a>
              <?php endif; ?>
            </div>
          </form>

        </div>

        <!-- TABULKA INGREDIENCI -->
        <table class="tabulka-admin">
          <thead>
            <tr>
              <th>Fotka</th>
              <th>Název suroviny</th>
              <th>Kategorie</th>
              <th>Původ</th>
              <th>Alergeny EU</th>
              <th>Akce</th>
            </tr>
          </thead>
          <tbody id="tabulka-suroviny-telo">
            <?php foreach ($surovinySeznam as $s): ?>
              <tr data-kategorie="<?php echo htmlspecialchars($s['kategorie']); ?>">
                <td><img src="<?php echo htmlspecialchars($s['obrazek']); ?>" class="nahled-fotky" alt=""></td>
                <td>
                  <strong><?php echo htmlspecialchars($s['nazev']); ?></strong><br>
                  <small style="color: #aaa;"><?php echo htmlspecialchars($s['popis']); ?></small>
                </td>
                <td><span style="text-transform: uppercase; color: #f59e0b;"><?php echo htmlspecialchars($s['kategorie']); ?></span></td>
                <td><?php echo htmlspecialchars($s['puvod']); ?></td>
                <td><small><?php echo !empty($s['alergeny_cisla']) ? implode(', ', $s['alergeny_cisla']) : '–'; ?></small></td>
                <td>
                  <div style="display: flex; gap: 8px; align-items: center;">
                    <a href="administrace.php?upravit_surovinu=<?php echo $s['id']; ?>" class="tlacitko-ikona-ramcek tlacitko-ikona-upravit" title="Upravit ingredienci <?php echo htmlspecialchars($s['nazev']); ?>">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                      </svg>
                    </a>
                    <form method="POST" action="administrace.php" onsubmit="return confirm('Opravdu chcete smazat ingredienci <?php echo htmlspecialchars($s['nazev']); ?>?');" style="margin: 0;">
                      <input type="hidden" name="id_suroviny_smazat" value="<?php echo $s['id']; ?>">
                      <button type="submit" name="smazat_surovinu_stisknuto" class="tlacitko-ikona-ramcek tlacitko-ikona-smazat" title="Smazat ingredienci <?php echo htmlspecialchars($s['nazev']); ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <polyline points="3 6 5 6 21 6"></polyline>
                          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                          <line x1="10" y1="11" x2="10" y2="17"></line>
                          <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

      </div>

      <!-- ====================================================================
           ZÁLOŽKA 3: ZÁLOHY A VERZOVÁNÍ DATABÁZE
           ==================================================================== -->
      <div id="sekce-zalozka-zalohy" class="admin-zalozka-obsah">
        <h2 class="sekce-nadpis-hlavni">ZÁLOHY A VERZOVÁNÍ DATABÁZE (<?php echo count($seznamZaloh); ?>)</h2>

        <!-- INFO BANNER O AUTOMATICKÉM VERZOVÁNÍ -->
        <div style="background: rgba(46, 213, 115, 0.08); border: 1px solid rgba(46, 213, 115, 0.3); border-radius: 12px; padding: 18px 24px; margin-bottom: 26px; color: #e0e0e0; font-size: 0.95rem; line-height: 1.5;">
          <strong style="color: #2ed573; display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 1.05rem;">
            Bezpečnostní automatické verzování aktivní
          </strong>
          Kdykoliv v administraci přidáte, upravíte nebo smažete pizzu či ingredienci, systém automaticky vytvoří časovou kopii do složky záloh. Pokud dojde k nechtěnému přepsání, můžete se jediným kliknutím vrátit k libovolné starší verzi.
        </div>

        <!-- RYCHLÉ AKCE (3 KARTY) -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">
          
          <!-- KARTA 1: VYTVOŘIT ZÁLOHU TEĎ -->
          <div style="background: #161616; border: 1px solid #2a2a2a; border-radius: 14px; padding: 22px; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <h3 style="margin-top: 0; margin-bottom: 8px; font-size: 1.1rem; color: #ffffff;">Vytvořit zálohu teď</h3>
              <p style="color: #888888; font-size: 0.88rem; margin: 0 0 16px 0;">Okamžitě uloží aktuální stav všech pizz a surovin jako nový bod obnovy na disku.</p>
            </div>
            <form method="POST" action="administrace.php" style="margin: 0;">
              <button type="submit" name="vytvorit_zalohu_stisknuto" class="tlacitko tlacitko-cervene" style="width: 100%; justify-content: center; background: #2ed573; border-color: #2ed573; color: #000000; font-weight: 700;">
                + Vytvořit zálohu teď
              </button>
            </form>
          </div>

          <!-- KARTA 2: EXPORT DATABÁZE (STÁHNOUT DO PC) -->
          <div style="background: #161616; border: 1px solid #2a2a2a; border-radius: 14px; padding: 22px; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <h3 style="margin-top: 0; margin-bottom: 8px; font-size: 1.1rem; color: #ffffff;">Stáhnout zálohu (Export)</h3>
              <p style="color: #888888; font-size: 0.88rem; margin: 0 0 16px 0;">Stáhne kompletní soubor databáze přímo do vašeho počítače ve formátu JSON.</p>
            </div>
            <form method="POST" action="administrace.php" style="margin: 0;">
              <button type="submit" name="exportovat_databazi_stisknuto" class="tlacitko tlacitko-sede" style="width: 100%; justify-content: center; font-weight: 600;">
                Stáhnout databázi (.json)
              </button>
            </form>
          </div>

          <!-- KARTA 3: IMPORT DATABÁZE (NAHRÁT Z PC) -->
          <div style="background: #161616; border: 1px solid #2a2a2a; border-radius: 14px; padding: 22px; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <h3 style="margin-top: 0; margin-bottom: 8px; font-size: 1.1rem; color: #ffffff;">Nahrát zálohu (Import)</h3>
              <p style="color: #888888; font-size: 0.88rem; margin: 0 0 14px 0;">Nahrajte soubor .json od kolegy nebo ze zálohy a obnovte stávající stav.</p>
            </div>
            <form method="POST" action="administrace.php" enctype="multipart/form-data" style="margin: 0;" onsubmit="return confirm('Opravdu chcete nahrát tento soubor a nahradit stávající databázi? Aktuální data budou automaticky předem zálohována.');">
              <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <input type="file" name="soubor_json" accept=".json" required style="font-size: 0.82rem; color: #aaa; max-width: 100%;">
                <button type="submit" name="importovat_databazi_stisknuto" class="tlacitko tlacitko-cervene" style="padding: 8px 14px; font-size: 0.85rem; margin-top: 8px; width: 100%; justify-content: center;">
                  Nahrát a obnovit
                </button>
              </div>
            </form>
          </div>

        </div>

        <!-- SEZNAM HISTORIE ZÁLOH -->
        <h3 style="font-size: 1.25rem; color: #ffffff; margin-bottom: 16px;">Historie bodů obnovy na disku</h3>
        <?php if (empty($seznamZaloh)): ?>
          <div style="background: #161616; border: 1px solid #2a2a2a; border-radius: 12px; padding: 30px; text-align: center; color: #888888;">
            Zatím neexistují žádné uložené zálohy. Klikněte výše na <strong>Vytvořit zálohu teď</strong>, nebo se první vytvoří automaticky při příští úpravě.
          </div>
        <?php else: ?>
          <div style="background: #161616; border: 1px solid #2a2a2a; border-radius: 14px; overflow: hidden;">
            <table class="admin-tabulka-surovin" style="margin: 0; width: 100%;">
              <thead>
                <tr>
                  <th style="padding: 14px 20px;">Čas a datum zálohy</th>
                  <th style="padding: 14px 20px;">Počet pizz</th>
                  <th style="padding: 14px 20px;">Počet surovin</th>
                  <th style="padding: 14px 20px;">Velikost</th>
                  <th style="padding: 14px 20px; text-align: right;">Akce</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($seznamZaloh as $zaloha): ?>
                  <tr style="border-bottom: 1px solid #222222;">
                    <td style="padding: 14px 20px; font-weight: 600; color: #ffffff;">
                      <?php echo htmlspecialchars($zaloha['datum']); ?>
                      <span style="font-size: 0.8rem; color: #666; display: block; font-family: monospace; font-weight: normal; margin-top: 2px;">
                        <?php echo htmlspecialchars($zaloha['soubor']); ?>
                      </span>
                    </td>
                    <td style="padding: 14px 20px; color: #f59e0b; font-weight: 700;">
                      <?php echo $zaloha['pocet_pizz']; ?> pizz
                    </td>
                    <td style="padding: 14px 20px; color: #2ed573;">
                      <?php echo $zaloha['pocet_surovin']; ?> ingrediencí
                    </td>
                    <td style="padding: 14px 20px; color: #888888; font-size: 0.9rem;">
                      <?php echo htmlspecialchars($zaloha['velikost']); ?>
                    </td>
                    <td style="padding: 14px 20px; text-align: right;">
                      <form method="POST" action="administrace.php" style="margin: 0; display: inline-block;" onsubmit="return confirm('Opravdu chcete obnovit stav databáze ze dne <?php echo htmlspecialchars($zaloha['datum']); ?>?');">
                        <input type="hidden" name="nazev_zalohy" value="<?php echo htmlspecialchars($zaloha['soubor']); ?>">
                        <button type="submit" name="obnovit_zalohu_stisknuto" class="tlacitko tlacitko-cervene" style="padding: 7px 14px; font-size: 0.85rem; font-weight: 700; background: #e67e22; border-color: #e67e22;">
                          Obnovit tuto verzi
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

      </div>

    <?php endif; ?>

  </div>

  <script>
    // PREPINANI ZALOZEK (PIZZY vs INGREDIENCE)
    function prepnoutZalozku(zalozkaId) {
      document.querySelectorAll('.admin-zalozka-tlacitko').forEach(btn => btn.classList.remove('aktivni'));
      document.querySelectorAll('.admin-zalozka-obsah').forEach(obsah => obsah.classList.remove('aktivni'));

      const btns = document.querySelectorAll('.admin-zalozka-tlacitko');
      btns.forEach(btn => {
        const attr = btn.getAttribute('onclick') || '';
        if (attr.includes("'" + zalozkaId + "'")) {
          btn.classList.add('aktivni');
        }
      });

      const sekce = document.getElementById('sekce-zalozka-' + zalozkaId);
      if (sekce) {
        sekce.classList.add('aktivni');
      }
    }
    window.prepnoutZalozka = prepnoutZalozku;

    // ZOBRAZENI / SKRYTI FORMULARU PIZZY A SUROVINY
    function zobrazitFormularPizzu() {
      const obal = document.getElementById('obal-formular-pizza');
      if (obal) {
        obal.style.display = (obal.style.display === 'none' || obal.style.display === '') ? 'block' : 'none';
      }
    }

    function zobrazitFormularSurovinu() {
      const obal = document.getElementById('obal-formular-surovina');
      if (obal) {
        obal.style.display = (obal.style.display === 'none' || obal.style.display === '') ? 'block' : 'none';
      }
    }

    // TOGGLE STAR PIZZA TYDNE VE FORMULARI
    function prepnoutStarFormular() {
      const chk = document.getElementById('chk_pizza_tydne');
      const btn = document.getElementById('btn-star-formular');
      if (chk && btn) {
        chk.checked = !chk.checked;
        if (chk.checked) {
          btn.classList.add('aktivni');
        } else {
          btn.classList.remove('aktivni');
        }
      }
    }

    // INTERAKCE MEZI SWITCHEM (STÁLÉ PIZZY / PIZZA TÝDNE) A VYHLEDÁVÁNÍM (BOD 1 A 2)
    let aktivniStavSwitch = 'stale';

    function prepnoutFiltrTydne(typ) {
      aktivniStavSwitch = typ;
      
      // Vymažeme vyhledávací pole při přepnutí switche
      const input = document.getElementById('vstup-hledat-pizzu');
      if (input) input.value = '';

      naZmenuHledani();
    }

    function naZmenuHledani() {
      const input = document.getElementById('vstup-hledat-pizzu');
      const query = input ? input.value.trim().toLowerCase() : '';
      const switchContainer = document.getElementById('sw-filtr-pizz-tydne');

      if (switchContainer) {
        if (query !== '') {
          // Pokud uživatel píše do vyhledávání, switch vizuálně zneaktivníme
          switchContainer.querySelectorAll('.mini-kapsle-tlacitko').forEach(btn => btn.classList.remove('aktivni'));
        } else {
          // Pokud je hledání prázdné, obnovíme aktivní stav tlačítka podle aktivního switche
          switchContainer.querySelectorAll('.mini-kapsle-tlacitko').forEach(btn => {
            if (btn.getAttribute('data-filtr') === aktivniStavSwitch) {
              btn.classList.add('aktivni');
            } else {
              btn.classList.remove('aktivni');
            }
          });
        }
      }

      const rows = document.querySelectorAll('#tabulka-pizzy-telo .pizza-dlazdice');
      rows.forEach(row => {
        const nazevBunka = row.querySelector('.bunka-nazev');
        const textNazvu = nazevBunka ? nazevBunka.innerText.toLowerCase() : row.innerText.toLowerCase();
        const isTydne = row.getAttribute('data-tydne') === '1';

        let show = true;

        if (query !== '') {
          // Při aktivním vyhledávání hledá v celém menu bez ohledu na switch
          show = textNazvu.includes(query);
        } else {
          // Při aktivním přepínači: VZÁJEMNÉ VYLOUTČENÍ (BOD 2)
          if (aktivniStavSwitch === 'stale') {
            show = !isTydne; // Zobrazí POUZE stálé pizzy (které NEJSOU pizzou týdne)
          } else if (aktivniStavSwitch === 'tydne') {
            show = isTydne;  // Zobrazí POUZE pizzu týdne
          }
        }

        row.style.display = show ? 'flex' : 'none';
      });
    }

    // PŘEPÍNÁNÍ POHLEDU PIZZ (DLAŽDICE / SEZNAM)
    function prepnoutPohledPizz(pohled) {
      const kontejner = document.getElementById('tabulka-pizzy-telo');
      const switchBox = document.getElementById('sw-pohled-pizz');
      if (!kontejner) return;

      if (pohled === 'seznam') {
        kontejner.classList.remove('pohled-dlazdice');
        kontejner.classList.add('pohled-seznam');
      } else {
        kontejner.classList.remove('pohled-seznam');
        kontejner.classList.add('pohled-dlazdice');
      }

      if (switchBox) {
        switchBox.querySelectorAll('.mini-kapsle-tlacitko').forEach(btn => {
          if (btn.getAttribute('data-pohled') === pohled) {
            btn.classList.add('aktivni');
          } else {
            btn.classList.remove('aktivni');
          }
        });
      }

      try {
        localStorage.setItem('admin_pohled_pizz', pohled);
      } catch (e) {}
    }
    window.prepnoutPohledPizz = prepnoutPohledPizz;

    // INICIALIZACE FILTROVÁNÍ A NAČTENÍ POHLEDU PO NAČTENÍ STRÁNKY
    document.addEventListener('DOMContentLoaded', function() {
      naZmenuHledani();
      let ulozenyPohled = 'dlazdice';
      try {
        ulozenyPohled = localStorage.getItem('admin_pohled_pizz') || 'dlazdice';
      } catch (e) {}
      prepnoutPohledPizz(ulozenyPohled);
    });

    // FILTROVANI TABULKY SUROVIN
    function filtrovatTabulkuSurovin(kat) {
      const switchContainer = document.getElementById('sw-filtr-surovin');
      if (switchContainer) {
        switchContainer.querySelectorAll('.mini-kapsle-tlacitko').forEach(btn => btn.classList.remove('aktivni'));
        const btn = switchContainer.querySelector(`[data-filtr="${kat}"]`);
        if (btn) btn.classList.add('aktivni');
      }

      const rows = document.querySelectorAll('#tabulka-suroviny-telo tr');
      rows.forEach(row => {
        const rowKat = row.getAttribute('data-kategorie');
        if (kat === 'vse' || rowKat === kat) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }

    function filtrovatTabulkuSurovinText() {
      const query = document.getElementById('vstup-hledat-surovinu').value.toLowerCase();
      const rows = document.querySelectorAll('#tabulka-suroviny-telo tr');
      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
      });
    }

    // PREPINAC KAPSLE VE FORMULARI (MASO/PALIVOST/ZAKLAD)
    function prepnoutAdminKapsli(typ, hodnota) {
      const containerId = 'sw-admin-' + typ;
      const container = document.getElementById(containerId);
      if (!container) return;

      let keyA = 'masite', keyB = 'bezmase';
      if (typ === 'palivost') { keyA = 'paliva'; keyB = 'nepaliva'; }
      else if (typ === 'zaklad') { keyA = 'sugo'; keyB = 'bily'; }

      const chkA = document.getElementById('chk_' + keyA);
      const chkB = document.getElementById('chk_' + keyB);

      const buttons = container.querySelectorAll('.mini-kapsle-tlacitko');
      buttons.forEach(btn => btn.classList.remove('aktivni'));

      if (hodnota === 'optA') {
        if (chkA) chkA.checked = true;
        if (chkB) chkB.checked = false;
      } else {
        if (chkA) chkA.checked = false;
        if (chkB) chkB.checked = true;
      }

      buttons.forEach(btn => {
        if (btn.getAttribute('data-val') === hodnota) {
          btn.classList.add('aktivni');
        }
      });
    }

    // AKTUALIZACE TEXTU PRO CUSTOM NAHRÁVÁNÍ SOUBORU ("Soubor nevybrán" / název souboru)
    function aktualizovatNazevSouboru(vstup, targetId) {
      const el = document.getElementById(targetId);
      if (!el) return;
      if (vstup.files && vstup.files.length > 0) {
        el.textContent = vstup.files[0].name;
        el.style.color = '#ffffff';
      } else {
        el.textContent = 'Soubor nevybrán';
        el.style.color = '#aaaaaa';
      }
    }
  </script>

</body>
</html>
