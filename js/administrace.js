/* ==========================================================================
   JAVASCRIPT PRO ADMINISTRACI (PIZZA OD KUŘETE)
   js/administrace.js
   Obsluha přepínání záložek, filtrů, vyhledávání a formulářů.
   ========================================================================== */

// 1. PŘEPÍNÁNÍ HLAVNÍCH ZÁLOŽEK (PIZZY vs INGREDIENCE vs ZÁLOHY)
function prepnoutZalozku(zalozkaId) {
  document.querySelectorAll('.admin-zalozka-tlacitko, .prepinac-zalozka-tlacitko').forEach(btn => btn.classList.remove('aktivni', 'je-aktivni'));
  document.querySelectorAll('.admin-zalozka-obsah').forEach(obsah => obsah.classList.remove('aktivni', 'je-aktivni'));

  const btns = document.querySelectorAll('.admin-zalozka-tlacitko, .prepinac-zalozka-tlacitko');
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
    const nazevBunka = row.querySelector('.bunka-nazev, .admin-pizza-dlazdice-nazev, .karta-pizza-dlazdice-nazev');
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

    row.style.display = show ? 'flex' : 'none';
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
}
window.prepnoutPohledPizz = prepnoutPohledPizz;

// 6. FILTROVÁNÍ TABULKY SUROVIN
function filtrovatTabulkuSurovin(kat) {
  const switchContainer = document.getElementById('sw-filtr-surovin');
  if (switchContainer) {
    switchContainer.querySelectorAll('.mini-kapsle-tlacitko, .prepinac-tlacitko').forEach(btn => btn.classList.remove('aktivni', 'je-aktivni'));
    const btn = switchContainer.querySelector(`[data-filtr="${kat}"]`);
    if (btn) btn.classList.add('aktivni', 'je-aktivni');
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
window.filtrovatTabulkuSurovin = filtrovatTabulkuSurovin;

function filtrovatTabulkuSurovinText() {
  const query = document.getElementById('vstup-hledat-surovinu').value.toLowerCase();
  const rows = document.querySelectorAll('#tabulka-suroviny-telo tr');
  rows.forEach(row => {
    const text = row.innerText.toLowerCase();
    row.style.display = text.includes(query) ? '' : 'none';
  });
}
window.filtrovatTabulkuSurovinText = filtrovatTabulkuSurovinText;

// 7. PŘEPÍNAČ KAPSLE VE FORMULÁŘI (MASO / PÁLIVOST / ZÁKLAD)
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

// 8. AKTUALIZACE TEXTU PRO NAHRÁVÁNÍ SOUBORU ("Soubor nevybrán" / název souboru)
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

// 9. INICIALIZACE PO NAČTENÍ STRÁNKY
document.addEventListener('DOMContentLoaded', function() {
  naZmenuHledani();
  let ulozenyPohled = 'dlazdice';
  try {
    ulozenyPohled = localStorage.getItem('admin_pohled_pizz') || 'dlazdice';
  } catch (e) {}
  prepnoutPohledPizz(ulozenyPohled);
});
