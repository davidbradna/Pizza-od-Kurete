/* ==========================================================================
   JAVASCRIPT PRO ADMINISTRACI (PIZZA OD KUŘETE)
   js/administrace.js
   Obsluha přepínání záložek, filtrů, vyhledávání a formulářů.
   ========================================================================== */

// 1. PŘEPÍNÁNÍ HLAVNÍCH ZÁLOŽEK (PIZZY vs INGREDIENCE vs ZÁLOHY)
function prepnoutZalozku(zalozkaId) {
  document.querySelectorAll('.admin-zalozka-tlacitko, .prepinac-zalozka-tlacitko, .horni-zalozka-btn').forEach(btn => btn.classList.remove('aktivni', 'je-aktivni'));
  document.querySelectorAll('.admin-zalozka-obsah').forEach(obsah => obsah.classList.remove('aktivni', 'je-aktivni'));

  const btns = document.querySelectorAll('.admin-zalozka-tlacitko, .prepinac-zalozka-tlacitko, .horni-zalozka-btn');
  btns.forEach(btn => {
    const attr = btn.getAttribute('onclick') || '';
    if (attr.includes("'" + zalozkaId + "'")) {
      btn.classList.add('aktivni', 'je-aktivni');
    }
  });

  const sekce = document.getElementById('sekce-zalozka-' + zalozkaId);
  if (sekce) {
    sekce.classList.add('aktivni', 'je-aktivni');
  }

  try {
    const url = new URL(window.location);
    url.searchParams.set('zalozka', zalozkaId);
    window.history.replaceState({}, '', url);
  } catch (e) {}
}
window.prepnoutZalozku = prepnoutZalozku;
window.prepnoutZalozka = prepnoutZalozku;

// 2. ZOBRAZENÍ / SKRYTÍ FORMULÁŘŮ PIZZY A SUROVINY
function zobrazitFormularPizzu() {
  const obal = document.getElementById('obal-formular-pizza');
  if (obal) {
    obal.style.display = (obal.style.display === 'none' || obal.style.display === '') ? 'block' : 'none';
  }
}
window.zobrazitFormularPizzu = zobrazitFormularPizzu;

function zobrazitFormularSurovinu() {
  const obal = document.getElementById('obal-formular-surovina');
  if (obal) {
    obal.style.display = (obal.style.display === 'none' || obal.style.display === '') ? 'block' : 'none';
  }
}
window.zobrazitFormularSurovinu = zobrazitFormularSurovinu;

// 3. PŘEPÍNAČ HVĚZDIČKY PIZZA TÝDNE VE FORMULÁŘI
function prepnoutStarFormular() {
  const chk = document.getElementById('chk_pizza_tydne');
  const btn = document.getElementById('btn-star-formular');
  if (chk && btn) {
    chk.checked = !chk.checked;
    if (chk.checked) {
      btn.classList.add('aktivni', 'je-aktivni');
    } else {
      btn.classList.remove('aktivni', 'je-aktivni');
    }
  }
}
window.prepnoutStarFormular = prepnoutStarFormular;

// 4. FILTROVÁNÍ: STÁLÉ PIZZY / PIZZA TÝDNE A VYHLEDÁVÁNÍ PODLE NÁZVU
let aktivniStavSwitch = 'stale';

function prepnoutFiltrTydne(typ) {
  aktivniStavSwitch = typ;
  try {
    localStorage.setItem('admin_filtr_pizz_tydne', typ);
    const url = new URL(window.location);
    url.searchParams.set('filtr_tydne', typ);
    window.history.replaceState({}, '', url);
  } catch (e) {}

  // Vymažeme vyhledávací pole při přepnutí switche
  const input = document.getElementById('vstup-hledat-pizzu');
  if (input) input.value = '';

  naZmenuHledani();
}
window.prepnoutFiltrTydne = prepnoutFiltrTydne;

function naZmenuHledani() {
  const input = document.getElementById('vstup-hledat-pizzu');
  const query = input ? input.value.trim().toLowerCase() : '';
  const switchContainer = document.getElementById('sw-filtr-pizz-tydne');

  if (switchContainer) {
    if (query !== '') {
      // Pokud uživatel píše do vyhledávání, switch vizuálně zneaktivníme
      switchContainer.querySelectorAll('.mini-kapsle-tlacitko, .prepinac-tlacitko').forEach(btn => btn.classList.remove('aktivni', 'je-aktivni'));
    } else {
      // Pokud je hledání prázdné, obnovíme aktivní stav tlačítka podle aktivního switche
      switchContainer.querySelectorAll('.mini-kapsle-tlacitko, .prepinac-tlacitko').forEach(btn => {
        if (btn.getAttribute('data-filtr') === aktivniStavSwitch) {
          btn.classList.add('aktivni', 'je-aktivni');
        } else {
          btn.classList.remove('aktivni', 'je-aktivni');
        }
      });
    }
  }

  const rows = document.querySelectorAll('#tabulka-pizzy-telo .pizza-dlazdice, #tabulka-pizzy-telo .admin-pizza-dlazdice');
  rows.forEach(row => {
    const nazevBunka = row.querySelector('.bunka-nazev, .admin-pizza-dlazdice-nazev, .karta-pizza-dlazdice-nazev, .pizza-dlazdice-nazev');
    const textNazvu = nazevBunka ? nazevBunka.innerText.toLowerCase() : row.innerText.toLowerCase();
    const isTydne = row.getAttribute('data-tydne') === '1';

    let show = true;

    if (query !== '') {
      // Při aktivním vyhledávání hledá v celém menu bez ohledu na switch
      show = textNazvu.includes(query);
    } else {
      // Při aktivním přepínači: VZÁJEMNÉ VYLOUČENÍ
      if (aktivniStavSwitch === 'stale') {
        show = !isTydne; // Zobrazí POUZE stálé pizzy
      } else if (aktivniStavSwitch === 'tydne') {
        show = isTydne;  // Zobrazí POUZE pizzu týdne
      }
    }

    if (show) {
      row.style.display = '';
      row.classList.remove('skryto');
    } else {
      row.style.display = 'none';
      row.classList.add('skryto');
    }
  });
}
window.naZmenuHledani = naZmenuHledani;

// 5. PŘEPÍNÁNÍ POHLEDU PIZZ (DLAŽDICE / SEZNAM)
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
    switchBox.querySelectorAll('.mini-kapsle-tlacitko, .prepinac-tlacitko').forEach(btn => {
      if (btn.getAttribute('data-pohled') === pohled) {
        btn.classList.add('aktivni', 'je-aktivni');
      } else {
        btn.classList.remove('aktivni', 'je-aktivni');
      }
    });
  }

  try {
    localStorage.setItem('admin_pohled_pizz', pohled);
  } catch (e) {}

  naZmenuHledani();
}
window.prepnoutPohledPizz = prepnoutPohledPizz;

// 6. FILTROVÁNÍ SUROVIN A VYHLEDÁVÁNÍ
let aktivniKatSurovin = 'vse';

function filtrovatTabulkuSurovin(kat) {
  aktivniKatSurovin = kat;
  const input = document.getElementById('vstup-hledat-surovinu');
  if (input) input.value = '';

  naZmenuHledaniSurovin();
}
window.filtrovatTabulkuSurovin = filtrovatTabulkuSurovin;

function filtrovatTabulkuSurovinText() {
  naZmenuHledaniSurovin();
}
window.filtrovatTabulkuSurovinText = filtrovatTabulkuSurovinText;

function naZmenuHledaniSurovin() {
  const input = document.getElementById('vstup-hledat-surovinu');
  const query = input ? input.value.trim().toLowerCase() : '';
  const switchContainer = document.getElementById('sw-filtr-surovin');

  if (switchContainer) {
    if (query !== '') {
      switchContainer.querySelectorAll('.mini-kapsle-tlacitko, .prepinac-tlacitko').forEach(btn => btn.classList.remove('aktivni', 'je-aktivni'));
    } else {
      switchContainer.querySelectorAll('.mini-kapsle-tlacitko, .prepinac-tlacitko').forEach(btn => {
        if (btn.getAttribute('data-filtr') === aktivniKatSurovin) {
          btn.classList.add('aktivni', 'je-aktivni');
        } else {
          btn.classList.remove('aktivni', 'je-aktivni');
        }
      });
    }
  }

  const rows = document.querySelectorAll('#tabulka-suroviny-telo .surovina-karta-polozka, #tabulka-suroviny-telo tr');
  rows.forEach(row => {
    const text = row.innerText.toLowerCase();
    const rowKat = row.getAttribute('data-kategorie') || '';

    let show = true;

    if (query !== '') {
      show = text.includes(query);
    } else {
      if (aktivniKatSurovin !== 'vse') {
        show = (rowKat === aktivniKatSurovin);
      }
    }

    if (show) {
      row.style.display = '';
      row.classList.remove('skryto');
    } else {
      row.style.display = 'none';
      row.classList.add('skryto');
    }
  });
}
window.naZmenuHledaniSurovin = naZmenuHledaniSurovin;

// 7. PŘEPÍNÁNÍ POHLEDU SUROVIN (DLAŽDICE / SEZNAM)
function prepnoutPohledSurovin(pohled) {
  const kontejner = document.getElementById('tabulka-suroviny-telo');
  const switchBox = document.getElementById('sw-pohled-surovin');
  if (!kontejner) return;

  if (pohled === 'seznam') {
    kontejner.classList.remove('pohled-dlazdice');
    kontejner.classList.add('pohled-seznam');
  } else {
    kontejner.classList.remove('pohled-seznam');
    kontejner.classList.add('pohled-dlazdice');
  }

  if (switchBox) {
    switchBox.querySelectorAll('.mini-kapsle-tlacitko, .prepinac-tlacitko').forEach(btn => {
      if (btn.getAttribute('data-pohled') === pohled) {
        btn.classList.add('aktivni', 'je-aktivni');
      } else {
        btn.classList.remove('aktivni', 'je-aktivni');
      }
    });
  }

  try {
    localStorage.setItem('admin_pohled_surovin', pohled);
  } catch (e) {}

  naZmenuHledaniSurovin();
}
window.prepnoutPohledSurovin = prepnoutPohledSurovin;

// 8. PŘEPÍNAČ KAPSLE VE FORMULÁŘI (MASO / PÁLIVOST / ZÁKLAD)
function prepnoutAdminKapsli(typ, hodnota) {
  const containerId = 'sw-admin-' + typ;
  const container = document.getElementById(containerId);
  if (!container) return;

  let keyA = 'masite', keyB = 'bezmase';
  if (typ === 'palivost') { keyA = 'paliva'; keyB = 'nepaliva'; }
  else if (typ === 'zaklad') { keyA = 'sugo'; keyB = 'bily'; }

  const chkA = document.getElementById('chk_' + keyA);
  const chkB = document.getElementById('chk_' + keyB);

  const buttons = container.querySelectorAll('.mini-kapsle-tlacitko, .prepinac-tlacitko');
  buttons.forEach(btn => btn.classList.remove('aktivni', 'je-aktivni'));

  if (hodnota === 'optA') {
    if (chkA) chkA.checked = true;
    if (chkB) chkB.checked = false;
  } else {
    if (chkA) chkA.checked = false;
    if (chkB) chkB.checked = true;
  }

  buttons.forEach(btn => {
    if (btn.getAttribute('data-val') === hodnota) {
      btn.classList.add('aktivni', 'je-aktivni');
    }
  });
}
window.prepnoutAdminKapsli = prepnoutAdminKapsli;

// 9. AKTUALIZACE TEXTU PRO NAHRÁVÁNÍ SOUBORU ("Soubor nevybrán" / název souboru)
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
window.aktualizovatNazevSouboru = aktualizovatNazevSouboru;

// 10. PŘEPÍNAČ PÁLIVOSTI VE FORMULÁŘI SUROVINY (3 MOŽNOSTI: 0 / 1 / 2)
function nastavitPalivostSuroviny(level) {
  const input = document.getElementById('vstup_palivost_suroviny');
  if (input) input.value = level;

  const container = document.getElementById('sw-surovina-palivost');
  if (container) {
    container.querySelectorAll('.mini-kapsle-tlacitko, .prepinac-tlacitko').forEach(btn => {
      if (parseInt(btn.getAttribute('data-val'), 10) === parseInt(level, 10)) {
        btn.classList.add('aktivni', 'je-aktivni');
      } else {
        btn.classList.remove('aktivni', 'je-aktivni');
      }
    });
  }
}
window.nastavitPalivostSuroviny = nastavitPalivostSuroviny;

// 11. INICIALIZACE PO NAČTENÍ STRÁNKY
document.addEventListener('DOMContentLoaded', function() {
  let defaultFiltrTydne = 'stale';
  try {
    const urlParams = new URLSearchParams(window.location.search);
    const urlFiltr = urlParams.get('filtr_tydne');
    if (urlFiltr === 'tydne' || urlFiltr === 'stale') {
      defaultFiltrTydne = urlFiltr;
    } else {
      defaultFiltrTydne = localStorage.getItem('admin_filtr_pizz_tydne') || 'stale';
    }
  } catch (e) {}
  prepnoutFiltrTydne(defaultFiltrTydne);

  let ulozenyPohled = 'dlazdice';
  try {
    ulozenyPohled = localStorage.getItem('admin_pohled_pizz') || 'dlazdice';
  } catch (e) {}
  prepnoutPohledPizz(ulozenyPohled);

  naZmenuHledaniSurovin();
  let ulozenyPohledSurovin = 'dlazdice';
  try {
    ulozenyPohledSurovin = localStorage.getItem('admin_pohled_surovin') || 'dlazdice';
  } catch (e) {}
  prepnoutPohledSurovin(ulozenyPohledSurovin);
});

// 12. CHYTRÁ DETEKCE VLASTNOSTÍ PIZZY PODLE VYBRANÝCH INGREDIENCÍ
function aktualizovatVlastnostiPizzyPodleIngredienci() {
  const checkedBoxes = document.querySelectorAll('.ingr-volba-checkbox:checked');
  
  let hasMeat = false;
  let isSpicy = false;
  let hasSugo = false;
  let hasWhiteBase = false;
  const collectedAllergens = new Set();

  const spicyKeywords = ['chilli', 'chili', 'jalape', 'pikant', 'feferon', 'habanero', 'páliv', 'paliv', 'tabasco', 'cayenn', 'chorizo', 'diavolo', 'peperoncino'];
  const sugoKeywords = ['sugo', 'rajčat', 'rajcat', 'marzano', 'pomodoro'];
  const whiteKeywords = ['smetan', 'bílý', 'bily', 'ricotta', 'creme', 'mascarpone', 'gorgonzola'];

  checkedBoxes.forEach(box => {
    const kat = box.getAttribute('data-kategorie') || '';
    const nazev = (box.getAttribute('data-nazev') || '').toLowerCase();
    const popis = (box.getAttribute('data-popis') || '').toLowerCase();
    const palivost = parseInt(box.getAttribute('data-palivost') || '0', 10);
    const textAll = nazev + ' ' + popis;

    // 1. Maso
    if (kat === 'maso') {
      hasMeat = true;
    }

    // 2. Pálivost (podle nastavené pálivosti ingredience nebo klíčových slov)
    if (palivost > 0 || spicyKeywords.some(kw => textAll.includes(kw))) {
      isSpicy = true;
    }

    // 3. Základ
    if (sugoKeywords.some(kw => textAll.includes(kw))) {
      hasSugo = true;
    }
    if (whiteKeywords.some(kw => textAll.includes(kw))) {
      hasWhiteBase = true;
    }

    // 4. Alergeny
    try {
      const alergeny = JSON.parse(box.getAttribute('data-alergeny') || '[]');
      if (Array.isArray(alergeny)) {
        alergeny.forEach(num => collectedAllergens.add(parseInt(num, 10)));
      }
    } catch (e) {}
  });

  // Přepnutí switche Masa: Masité (optA) vs Bezmasé (optB)
  prepnoutAdminKapsli('maso', hasMeat ? 'optA' : 'optB');

  // Přepnutí switche Pálivosti: Pálivá (optA) vs Nepálivá (optB)
  prepnoutAdminKapsli('palivost', isSpicy ? 'optA' : 'optB');

  // Přepnutí switche Základu: Sugo (optA) vs Bílý (optB)
  if (hasSugo) {
    prepnoutAdminKapsli('zaklad', 'optA');
  } else if (hasWhiteBase && !hasSugo) {
    prepnoutAdminKapsli('zaklad', 'optB');
  }

  // Automatické označení EU alergenů
  document.querySelectorAll('input[name="alergeny_cisla_pizza[]"]').forEach(chk => {
    const val = parseInt(chk.value, 10);
    chk.checked = collectedAllergens.has(val);
  });
}
window.aktualizovatVlastnostiPizzyPodleIngredienci = aktualizovatVlastnostiPizzyPodleIngredienci;

// 13. RYCHLÉ PŘEPÍNÁNÍ VLASTNOSTÍ PIZZY (PLAMÍNEK A SRDÍČKO) BEZ PROBLIKNUTÍ A POSKAKOVÁNÍ
async function rychlePrepnoutVlastnost(event, btn, idPizzy, vlastnost) {
  if (event) {
    event.preventDefault();
    event.stopPropagation();
  }
  if (!btn) return;

  const jeAktivni = btn.classList.contains('aktivni');
  const novyStav = !jeAktivni;

  // Okamžitá plynulá vizuální změna na všech tlačítkách stejné pizzy (pro dlaždice i seznam)
  const allButtons = document.querySelectorAll(`button[data-id="${idPizzy}"][data-vlastnost="${vlastnost}"]`);
  allButtons.forEach(b => {
    if (novyStav) {
      b.classList.add('aktivni');
      const svg = b.querySelector('svg');
      if (svg) svg.setAttribute('fill', 'currentColor');
    } else {
      b.classList.remove('aktivni');
      const svg = b.querySelector('svg');
      if (svg) svg.setAttribute('fill', 'none');
    }
  });

  // Odeslání požadavku na pozadí přes AJAX
  try {
    const formData = new FormData();
    formData.append('prepnout_vlastnost_pizzy_stisknuto', '1');
    formData.append('id_pizzy_prepnout', idPizzy);
    formData.append('vlastnost', vlastnost);
    formData.append('ajax', '1');

    await fetch('administrace.php', {
      method: 'POST',
      body: formData
    });
  } catch (err) {
    console.error('Chyba při ukládání vlastnosti:', err);
  }
}
window.rychlePrepnoutVlastnost = rychlePrepnoutVlastnost;

// 14. RYCHLÁ AKTIVACE PIZZY TÝDNE (HVĚZDIČKA) BEZ PROBLIKNUTÍ STRÁNKY
async function rychleAktivovatPizzuTydne(event, btn, idPizzy) {
  if (event) {
    event.preventDefault();
    event.stopPropagation();
  }
  if (!btn) return;

  // Vizuálně vypneme všechny ostatní hvězdičky a zapneme tuto
  document.querySelectorAll('.tlacitko-ikona-aktivni').forEach(b => {
    b.classList.remove('aktivni');
    const svg = b.querySelector('svg');
    if (svg) svg.setAttribute('fill', 'none');
  });

  btn.classList.add('aktivni');
  const svg = btn.querySelector('svg');
  if (svg) svg.setAttribute('fill', 'currentColor');

  // Aktualizujeme štítky na fotce
  document.querySelectorAll('.pizza-dlazdice').forEach(card => {
    const stitek = card.querySelector('.stitek-pizza-tydne-karta');
    if (stitek) {
      const btnInCard = card.querySelector(`.tlacitko-ikona-aktivni[data-id="${idPizzy}"]`);
      if (btnInCard) {
        stitek.style.background = '#f59e0b';
        stitek.style.color = '#000000';
        stitek.innerText = '★ AKTIVNÍ PIZZA TÝDNE';
      } else {
        stitek.style.background = 'linear-gradient(135deg, #d97706, #b45309)';
        stitek.style.color = '#ffffff';
        stitek.innerText = 'PIZZA TÝDNE';
      }
    }
  });

  // Odeslání požadavku na pozadí přes AJAX
  try {
    const formData = new FormData();
    formData.append('aktivovat_pizzu_tydne_stisknuto', '1');
    formData.append('id_pizzy_aktivovat_tydne', idPizzy);
    formData.append('ajax', '1');

    await fetch('administrace.php', {
      method: 'POST',
      body: formData
    });
  } catch (err) {
    console.error('Chyba při aktivaci pizzy týdne:', err);
  }
}
window.rychleAktivovatPizzuTydne = rychleAktivovatPizzuTydne;

// 12. CHYTRÁ PLOVOUCÍ HORNÍ LIŠTA (Skrýt při scroll down, zobrazit při scroll up z jakéhokoliv místa)
(function() {
  let posledniScrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
  let ticking = false;
  const minimalniPosunProReakci = 6; // Drobná tolerance proti chvění

  function obslouzitScroll() {
    const soucasnyScrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
    const horniLista = document.querySelector('.horni-kontakty-lista');

    if (!horniLista) {
      ticking = false;
      return;
    }

    // Jsme-li úplně nahoře, lišta je vždy viditelná
    if (soucasnyScrollY <= 20) {
      horniLista.classList.remove('admin-lista-skryta');
      posledniScrollY = soucasnyScrollY;
      ticking = false;
      return;
    }

    const rozdil = soucasnyScrollY - posledniScrollY;

    if (Math.abs(rozdil) >= minimalniPosunProReakci) {
      if (rozdil > 0) {
        // Scroll DOWN -> Skrýt lištu
        horniLista.classList.add('admin-lista-skryta');
      } else {
        // Scroll UP -> Zobrazit lištu
        horniLista.classList.remove('admin-lista-skryta');
      }
      posledniScrollY = soucasnyScrollY;
    }

    ticking = false;
  }

  window.addEventListener('scroll', function() {
    if (!ticking) {
      window.requestAnimationFrame(obslouzitScroll);
      ticking = true;
    }
  }, { passive: true });
})();
