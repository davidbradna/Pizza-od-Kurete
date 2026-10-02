/* ==========================================================================
   INTERAKTIVNI LOGIKA PRO WEB PIZZA OD KURETE
   Vsechny nazvy promennych, funkci a komentare jsou v cestine bez diakritiky
   ========================================================================== */

// 1. DATA PIZZ V JIDELNIM LISTKU (Nacitaji se dynamicky z databaze.json v PHP)
const seznamPizz = window.seznamPizzZDatabaze || [
  {
    id: 1,
    cislo: 1,
    nazev: "MARGHERITA",
    slozeni: "Rajcata San Marzano D.O.P., Mozzarella di Bufala, Italská Mouka Caputo 00, Čerstvá Bazalka a Špenát",
    ingredience: ["sugomarzano", "mozzarella", "spinacbazalka", "moukacaputo"],
    alergeny: "(1, 7)",
    cena: 165,
    sugo: true,
    bilyZaklad: false,
    nepaliva: true,
    pizzaTydne: false,
    nejprodavanejsi: true,
    doporucujeme: false,
    zlata: false,
    obrazek: "https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?q=80&w=500&auto=format&fit=crop"
  },
  {
    id: 2,
    cislo: 2,
    nazev: "PROSCIUTTO E FUNGHI",
    slozeni: "Rajcata San Marzano D.O.P., Mozzarella di Bufala, Italská Mouka Caputo 00",
    ingredience: ["sugomarzano", "mozzarella", "moukacaputo"],
    alergeny: "(1, 7)",
    cena: 175,
    sugo: true,
    bilyZaklad: false,
    nepaliva: true,
    pizzaTydne: true,
    nejprodavanejsi: true,
    doporucujeme: true,
    zlata: true,
    obrazek: "https://images.unsplash.com/photo-1534308983496-4fabb1a015ee?q=80&w=500&auto=format&fit=crop"
  },
  {
    id: 3,
    cislo: 3,
    nazev: "QUATTRO FORMAGGI",
    slozeni: "Mozzarella di Bufala, Italská Mouka Caputo 00",
    ingredience: ["mozzarella", "moukacaputo"],
    alergeny: "(1, 7)",
    cena: 185,
    sugo: false,
    bilyZaklad: true,
    nepaliva: true,
    pizzaTydne: false,
    nejprodavanejsi: false,
    doporucujeme: true,
    zlata: true,
    obrazek: "https://images.unsplash.com/photo-1573821663912-569905455b1c?q=80&w=500&auto=format&fit=crop"
  },
  {
    id: 4,
    cislo: 4,
    nazev: "DIAVOLA CHILI",
    slozeni: "Rajcata San Marzano D.O.P., Mozzarella di Bufala, Italská Mouka Caputo 00, Spaniol & Italské Chorizo",
    ingredience: ["sugomarzano", "mozzarella", "chorizospanelske", "moukacaputo"],
    alergeny: "(1, 7)",
    cena: 180,
    sugo: true,
    bilyZaklad: false,
    nepaliva: false,
    pizzaTydne: false,
    nejprodavanejsi: true,
    doporucujeme: false,
    zlata: false,
    obrazek: "https://images.unsplash.com/photo-1628840042765-356cda07504e?q=80&w=500&auto=format&fit=crop"
  },
  {
    id: 5,
    cislo: 5,
    nazev: "POLLO E SPINACI",
    slozeni: "Mozzarella di Bufala, Šťavnaté Kuřecí Maso, Italská Mouka Caputo 00, Čerstvá Bazalka a Špenát",
    ingredience: ["mozzarella", "kurecimaso", "spinacbazalka", "moukacaputo"],
    alergeny: "(1, 7)",
    cena: 190,
    sugo: false,
    bilyZaklad: true,
    nepaliva: true,
    pizzaTydne: true,
    nejprodavanejsi: false,
    doporucujeme: true,
    zlata: false,
    obrazek: "https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?q=80&w=500&auto=format&fit=crop"
  },
  {
    id: 6,
    cislo: 6,
    nazev: "HAWAI SPECIAL",
    slozeni: "Rajcata San Marzano D.O.P., Mozzarella di Bufala, Italská Mouka Caputo 00",
    ingredience: ["sugomarzano", "mozzarella", "moukacaputo"],
    alergeny: "(1, 7)",
    cena: 175,
    sugo: true,
    bilyZaklad: false,
    nepaliva: true,
    pizzaTydne: false,
    nejprodavanejsi: false,
    doporucujeme: false,
    zlata: false,
    obrazek: "https://images.unsplash.com/photo-1565299585323-38d6b0865b47?q=80&w=500&auto=format&fit=crop"
  }
];

// Stav kosiku
let kosik = [];
let aktivniKategorieFiltru = 'vse';

// Po nacteni dokumentu
document.addEventListener('DOMContentLoaded', () => {
  renderovatJidelniListek();
  nastavitPosluchaceUdalosti();
});

// Stav preklopnych switchu
const stavPrepinacu = {
  maso: 'vse',
  palivost: 'vse',
  zaklad: 'vse',
  misto: 'vse'
};

function nastavitPrepinac(idPrepinace, hodnota) {
  const container = document.getElementById(idPrepinace);
  if (!container) return;

  const typ = idPrepinace.replace('sw-', '');

  if (stavPrepinacu[typ] === hodnota) {
    stavPrepinacu[typ] = 'vse';
  } else {
    stavPrepinacu[typ] = hodnota;
  }

  const tlacitka = container.querySelectorAll('.mini-kapsle-tlacitko');
  tlacitka.forEach(btn => {
    if (btn.getAttribute('data-hodnota') === stavPrepinacu[typ]) {
      btn.classList.add('aktivni');
    } else {
      btn.classList.remove('aktivni');
    }
  });

  renderovatJidelniListek();
}

// Mapa EU alergenu
const MAPA_EU_ALERGENY = {
  1: 'Lepek (obiloviny)', 2: 'Korýši', 3: 'Vejce', 4: 'Ryby', 5: 'Arašídy', 6: 'Sója',
  7: 'Mléko a laktóza', 8: 'Ořechy', 9: 'Celer', 10: 'Hořčice', 11: 'Sezam',
  12: 'Oxid siřičitý', 13: 'Vlčí bob', 14: 'Měkkýši'
};

// Pomocna funkce pro ziskani pole cisel alergenu z pizzy
function ziskatAlergenyCisla(pizza) {
  if (pizza.alergeny_cisla && Array.isArray(pizza.alergeny_cisla) && pizza.alergeny_cisla.length > 0) {
    return pizza.alergeny_cisla.map(Number);
  }
  if (pizza.alergeny && typeof pizza.alergeny === 'string') {
    const nalezena = pizza.alergeny.match(/\d+/g);
    if (nalezena) {
      return nalezena.map(Number);
    }
  }
  return [];
}

// Pomocna funkce pro vygenerovani hezke SVG ikony papricky
function ziskatSvgPapricku(velikost = 19) {
  return `<svg class="ikona-chilli-svg" viewBox="0 0 24 24" width="${velikost}" height="${velikost}" fill="none" style="vertical-align: -2px; display: inline-block; filter: drop-shadow(0 2px 4px rgba(220, 38, 38, 0.35)); flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg">
    <path d="M17.8 7.3C16.6 5.8 14.8 5 13 5.2c-2.4.3-4.5 1.7-5.9 3.6-2.5 3.3-3.4 7.7-2.3 11.7.3 1 .9 1.9 1.8 2.4.8.4 1.7.4 2.5 0 .8-.5 1.4-1.2 1.7-2.1.7-2.1 1.9-3.9 3.7-5.2 1.7-1.3 3.9-1.9 6-1.5.8.1 1.6-.2 2.1-.8.5-.6.6-1.5.1-2.1-.9-1.6-2.4-2.9-4.1-3.7z" fill="url(#gradChilliRed)"/>
    <path d="M16.5 8C15.5 7 14 6.5 12.6 6.7c-2 .3-3.8 1.4-5 3-1.6 2.1-2.4 4.8-2.4 7.5.3-2.3 1.1-4.6 2.5-6.5 1.2-1.6 2.8-2.7 4.7-3 1.4-.2 2.7.1 3.7.8.2.1.5 0 .6-.2.1-.2 0-.4-.2-.5z" fill="#ff9999" opacity="0.6"/>
    <path d="M15.5 3.2c.7-.7 1.7-1.1 2.7-1.1.4 0 .7.3.7.7s-.3.7-.7.7c-.6 0-1.2.2-1.7.7-.4.4-.6 1-.7 1.5-.1.4-.4.6-.8.6s-.7-.3-.7-.7c.1-.9.5-1.8 1.2-2.4z" fill="#22c55e"/>
    <path d="M12.5 5.5c.8-.4 1.7-.5 2.6-.3.6.1 1.1.4 1.5.8.3.2.4.6.3.9-.1.3-.4.5-.8.5-.4-.2-.8-.4-1.2-.5-.6-.1-1.3 0-1.8.3-.3.2-.7.1-.9-.1-.2-.3-.1-.7.2-.9z" fill="#16a34a"/>
    <defs>
      <linearGradient id="gradChilliRed" x1="6" y1="6" x2="21" y2="21" gradientUnits="userSpaceOnUse">
        <stop offset="0%" stop-color="#ff4444"/>
        <stop offset="50%" stop-color="#e50914"/>
        <stop offset="100%" stop-color="#990000"/>
      </linearGradient>
    </defs>
  </svg>`;
}

function vytvoritChilliIkony(uroven, velikost = 19) {
  uroven = parseInt(uroven, 10);
  if (uroven <= 0) return '';
  const popisek = (uroven === 2) ? 'Extra pálivé' : 'Mírně pálivé';
  let svgList = '';
  for (let i = 0; i < uroven; i++) {
    svgList += ziskatSvgPapricku(velikost);
  }
  return `<span class="ikona-palivosti-nazev" title="${popisek}" style="display: inline-flex; align-items: center; gap: 3px; margin-left: 6px; border: none; background: transparent; padding: 0; vertical-align: middle;">${svgList}</span>`;
}

// Pomocna funkce pro ziskani maximalni urovne palivosti pizzy podle surovin (0, 1 nebo 2)
function ziskatPalivostPizzy(pizza) {
  const surovinyDb = window.seznamSurovinZDatabaze || [];
  if (!pizza || !pizza.ingredience || !Array.isArray(pizza.ingredience) || pizza.ingredience.length === 0) {
    return 0;
  }
  let maxPalivost = 0;
  pizza.ingredience.forEach(idSur => {
    const sur = surovinyDb.find(s => s.id === idSur);
    if (sur && sur.palivost) {
      const p = parseInt(sur.palivost, 10);
      if (p > maxPalivost) {
        maxPalivost = p;
      }
    }
  });
  return maxPalivost;
}

// Funkce pro vykresleni pizz v mrizce
function renderovatJidelniListek() {
  const mrizkaPrvek = document.getElementById('mrizka-pizz-kontejner');
  if (!mrizkaPrvek) return;

  const aktualniPizzy = window.seznamPizzZDatabaze || seznamPizz;

  // Řazení: Aktivní Pizza týdne VŽDY na 1. pozici, ostatní podle čísla pizzy
  const serazenePizzy = [...aktualniPizzy].sort((a, b) => {
    const aAktivni = Boolean(a.aktivniPizzaTydne);
    const bAktivni = Boolean(b.aktivniPizzaTydne);
    if (aAktivni && !bAktivni) return -1;
    if (!aAktivni && bAktivni) return 1;
    return (parseInt(a.cislo, 10) || 0) - (parseInt(b.cislo, 10) || 0);
  });

  const filtrovanePizzy = serazenePizzy.filter(pizza => {
    // 1. Maso / bez masa
    const jeMasita = pizza.masite === true || (pizza.bezmase === false);
    const jeBezmasna = pizza.bezmase === true || (pizza.masite === false);

    if (stavPrepinacu.maso === 'masite' && !jeMasita) return false;
    if (stavPrepinacu.maso === 'bezmase' && !jeBezmasna) return false;

    // 2. Palivost
    const urovenPalivosti = ziskatPalivostPizzy(pizza);
    const jePaliva = pizza.paliva === true || (pizza.nepaliva === false) || (urovenPalivosti > 0);
    const jeNepaliva = (pizza.nepaliva === true || (pizza.paliva === false)) && urovenPalivosti === 0;

    if (stavPrepinacu.palivost === 'paliva' && !jePaliva) return false;
    if (stavPrepinacu.palivost === 'nepaliva' && !jeNepaliva) return false;

    // 3. Zaklad
    const jeSugo = pizza.sugo === true || (pizza.bilyZaklad === false);
    const jeBily = pizza.bilyZaklad === true || (pizza.sugo === false);

    if (stavPrepinacu.zaklad === 'sugo' && !jeSugo) return false;
    if (stavPrepinacu.zaklad === 'bily' && !jeBily) return false;

    // 4. Misto / Rozvoz
    if (stavPrepinacu.misto === 'v_pizzerii' && pizza.naMiste === false) return false;
    if (stavPrepinacu.misto === 'rozvoz' && pizza.sSebou === false) return false;

    // 5. Kategorie tlacitek (nejprodavanejsi, doporucujeme)
    if (aktivniKategorieFiltru === 'nejprodavanejsi' && !pizza.nejprodavanejsi) return false;
    if (aktivniKategorieFiltru === 'doporucujeme' && !pizza.doporucujeme) return false;

    return true;
  });

  if (filtrovanePizzy.length === 0) {
    mrizkaPrvek.innerHTML = `
      <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #888;">
        <h3>Žádná pizza neodpovídá vybraným filtrům.</h3>
        <p>Zkuste upravit nastavení přepínačů.</p>
      </div>
    `;
    return;
  }

  mrizkaPrvek.innerHTML = filtrovanePizzy.map(pizza => {
    // Alergeny text z cisel nebo ze slova
    const alergenyCisla = ziskatAlergenyCisla(pizza);
    let alergenyVypis = '';
    if (alergenyCisla.length > 0) {
      alergenyVypis = `Alergeny: (${alergenyCisla.join(', ')})`;
    } else if (pizza.alergeny) {
      alergenyVypis = `Alergeny: ${pizza.alergeny}`;
    }

    // Palivost ikona (1 nebo 2 kvalitní vektorové papričky bez rámečku)
    const palivostUroven = ziskatPalivostPizzy(pizza);
    const palivostHtml = vytvoritChilliIkony(palivostUroven, 19);
    const jeAktivniTydne = Boolean(pizza.aktivniPizzaTydne);

    return `
      <div class="karta-pizzy ${jeAktivniTydne ? 'karta-pizza-tydne' : ''}">
        ${!jeAktivniTydne ? `<div class="ciselny-odznak">${pizza.cislo}</div>` : ''}
        ${jeAktivniTydne ? `<span class="stitek-pizza-tydne-roh"><span class="hvezda-ikona-tydne">★</span> PIZZA TÝDNE</span>` : ''}
        <div class="obrazek-pizzy-obal" onclick="otvoritDetailPizzy(${pizza.id})" style="cursor: pointer;" title="Zobrazit detail pizzy">
          <img src="${pizza.obrazek}" alt="${pizza.nazev}" loading="lazy">
        </div>
        <h3 class="nazev-pizzy" onclick="otvoritDetailPizzy(${pizza.id})" style="cursor: pointer;">${pizza.nazev}${palivostHtml}</h3>
        <p class="slozeni-pizzy">${pizza.slozeni}</p>
        
        ${alergenyVypis ? `<div class="alergeny-klikaci-stitek" onclick="otvoritAlergenyPizzy(${pizza.id}); event.stopPropagation();" title="Zobrazit alergeny pizzy">${alergenyVypis}</div>` : ''}

        <div class="patka-karty-pizzy">
          <span class="cena-pizzy">${pizza.cena},- Kč</span>
          <button class="tlacitko-pridat-plus" onclick="pridatDoKosiku(${pizza.id})" title="Přidat do nákupního seznamu">+</button>
        </div>
      </div>
    `;
  }).join('');
}

// Funkce pro zobrazeni vyskakovaciho okna alergenu (JENOM obrazek pizzy, seznam alergenu a tlacitko pridat do seznamu)
function otvoritAlergenyPizzy(idPizzy) {
  const vsechnyPizzy = window.seznamPizzZDatabaze || seznamPizz;
  const pizza = vsechnyPizzy.find(p => p.id == idPizzy);
  if (!pizza) return;

  const kontejner = document.getElementById('obsah-detailu-pizzy-modal');
  if (!kontejner) return;

  // Vyhledani EU alergenu
  const alergenyCisla = ziskatAlergenyCisla(pizza);
  let alergenyHtml = '';
  if (alergenyCisla.length > 0) {
    alergenyHtml = alergenyCisla.map(num => `
      <span style="display: inline-flex; align-items: center; gap: 8px; background: #7f1d1d; color: #fff; padding: 6px 14px; border-radius: 20px; font-size: 0.9rem; font-weight: 700; border: 1px solid #991b1b;">
        <span style="background: #e53e3e; width: 22px; height: 22px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800;">${num}</span>
        ${MAPA_EU_ALERGENY[num] || 'Alergen ' + num}
      </span>
    `).join(' ');
  } else {
    alergenyHtml = `<span style="color: #aaa;">Bez specifikovaných alergenů.</span>`;
  }

  kontejner.innerHTML = `
    <div style="width: 100%; max-height: 220px; overflow: hidden; border-radius: 12px; margin-bottom: 20px; border: 1px solid #333;">
      <img src="${pizza.obrazek}" style="width: 100%; height: 100%; object-fit: cover;" alt="${pizza.nazev}">
    </div>

    <div style="margin-bottom: 25px;">
      <h3 style="color: #fff; font-size: 1.1rem; margin-bottom: 12px; text-transform: uppercase; border-bottom: 1px solid #333; padding-bottom: 6px;">Obsažené alergeny:</h3>
      <div style="display: flex; flex-wrap: wrap; gap: 10px;">
        ${alergenyHtml}
      </div>
    </div>

    <div style="text-align: center; padding-top: 15px; border-top: 1px solid #333;">
      <button class="tlacitko tlacitko-cervene" style="width: 100%; padding: 12px 20px; font-size: 1rem;" onclick="pridatDoKosiku(${pizza.id}); zavritModal('modal-detail-pizzy');">
        + Přidat do seznamu
      </button>
    </div>
  `;

  otvoritModal('modal-detail-pizzy');
}

// Funkce pro zobrazeni detailniho okna pizzy s ingrediencemi a alergeny
function otvoritDetailPizzy(idPizzy) {
  const vsechnyPizzy = window.seznamPizzZDatabaze || seznamPizz;
  const surovinyDb = window.seznamSurovinZDatabaze || [];
  const pizza = vsechnyPizzy.find(p => p.id == idPizzy);
  if (!pizza) return;

  const kontejner = document.getElementById('obsah-detailu-pizzy-modal');
  if (!kontejner) return;

  // Vyhledani ingredienci
  let ingredienceSeznamHtml = '';
  if (pizza.ingredience && pizza.ingredience.length > 0) {
    const obsazeneSuroviny = surovinyDb.filter(s => pizza.ingredience.includes(s.id));
    if (obsazeneSuroviny.length > 0) {
      ingredienceSeznamHtml = obsazeneSuroviny.map(s => {
        const surP = parseInt(s.palivost || 0, 10);
        const surPapricky = vytvoritChilliIkony(surP, 16);
        return `
        <div style="background: #222; padding: 10px 14px; border-radius: 8px; border: 1px solid #333; display: flex; align-items: center; justify-content: space-between;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <img src="${s.obrazek}" style="width: 35px; height: 35px; object-fit: cover; border-radius: 50%;" alt="">
            <strong style="color: #fff; font-size: 0.95rem;">${s.nazev}${surPapricky}</strong>
          </div>
          <span style="font-size: 0.8rem; color: #f59e0b; font-weight: bold;">${s.puvod || ''}</span>
        </div>
      `;
      }).join('');
    }
  }

  // Vyhledani EU alergenu
  const alergenyCisla = ziskatAlergenyCisla(pizza);
  let alergenyHtml = '';
  if (alergenyCisla.length > 0) {
    alergenyHtml = alergenyCisla.map(num => `
      <span style="display: inline-flex; align-items: center; gap: 6px; background: #7f1d1d; color: #fff; padding: 4px 10px; border-radius: 15px; font-size: 0.85rem; font-weight: 700;">
        <span style="background: #e53e3e; width: 20px; height: 20px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">${num}</span>
        ${MAPA_EU_ALERGENY[num] || 'Alergen ' + num}
      </span>
    `).join(' ');
  } else {
    alergenyHtml = `<span style="color: #aaa;">Bez specifikovaných alergenů</span>`;
  }

  // Palivost ikona pro modal
  const palivostUroven = ziskatPalivostPizzy(pizza);
  const palivostModalHtml = vytvoritChilliIkony(palivostUroven, 22);

  kontejner.innerHTML = `
    <div style="text-align: center; margin-bottom: 20px;">
      <div style="font-size: 0.9rem; color: #f59e0b; font-weight: 800; letter-spacing: 1px; margin-bottom: 5px;">PIZZA Č. ${pizza.cislo}</div>
      <h2 class="nadpis-sekce" style="font-size: 2rem; margin: 0; display: inline-flex; align-items: center; justify-content: center;">${pizza.nazev}${palivostModalHtml}</h2>
      <p style="color: #aaa; font-size: 1.05rem; margin-top: 8px;">${pizza.slozeni}</p>
    </div>

    <div style="width: 100%; max-height: 220px; overflow: hidden; border-radius: 12px; margin-bottom: 25px; border: 1px solid #333;">
      <img src="${pizza.obrazek}" style="width: 100%; height: 100%; object-fit: cover;" alt="${pizza.nazev}">
    </div>

    <div style="margin-bottom: 20px;">
      <h4 style="color: #fff; font-size: 1.05rem; margin-bottom: 10px; text-transform: uppercase; border-bottom: 1px solid #333; padding-bottom: 5px;">Pravé ingredience na pizze:</h4>
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 10px;">
        ${ingredienceSeznamHtml || `<p style="color: #888;">${pizza.slozeni}</p>`}
      </div>
    </div>

    <div style="margin-bottom: 25px;">
      <h4 style="color: #fff; font-size: 1.05rem; margin-bottom: 10px; text-transform: uppercase; border-bottom: 1px solid #333; padding-bottom: 5px;">Obsažené EU Alergeny:</h4>
      <div style="display: flex; flex-wrap: wrap; gap: 8px;">
        ${alergenyHtml}
      </div>
    </div>

    <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 20px; border-top: 1px solid #333;">
      <div>
        <span style="font-size: 1.8rem; font-weight: 800; color: #f59e0b;">${pizza.cena} Kč</span>
        ${pizza.cenaRozvoz ? `<small style="color: #888; display: block;">Rozvoz: ${pizza.cenaRozvoz} Kč</small>` : ''}
      </div>
      <button class="tlacitko tlacitko-cervene" onclick="pridatDoKosiku(${pizza.id}); zavritModal('modal-detail-pizzy');">
        + Přidat do košíku
      </button>
    </div>
  `;

  otvoritModal('modal-detail-pizzy');
}


// Nastaveni posluchacu pro tlacitka a checkboxes
function nastavitPosluchaceUdalosti() {
  // Checkbox filtry
  document.getElementById('filtr-sugo')?.addEventListener('change', renderovatJidelniListek);
  document.getElementById('filtr-bily')?.addEventListener('change', renderovatJidelniListek);
  document.getElementById('filtr-nepaliva')?.addEventListener('change', renderovatJidelniListek);

  // Tlacitka kategorii (Pizza tydne, nejprodavanejsi, doporucujeme)
  const tlacitkaKategorii = document.querySelectorAll('.tlacitko-kategorie');
  tlacitkaKategorii.forEach(tlacitko => {
    tlacitko.addEventListener('click', (e) => {
      const kategorie = e.target.getAttribute('data-kategorie');
      
      if (aktivniKategorieFiltru === kategorie) {
        aktivniKategorieFiltru = 'vse';
        e.target.classList.remove('aktivni');
      } else {
        tlacitkaKategorii.forEach(btn => btn.classList.remove('aktivni'));
        aktivniKategorieFiltru = kategorie;
        e.target.classList.add('aktivni');
      }
      renderovatJidelniListek();
    });
  });
}

// Funkce pro pridani pizzy do kosiku
function pridatDoKosiku(idPizzy) {
  const vybranaPizza = seznamPizz.find(p => p.id === idPizzy);
  if (!vybranaPizza) return;

  const stavajiciPolozka = kosik.find(item => item.pizza.id === idPizzy);
  if (stavajiciPolozka) {
    stavajiciPolozka.pocet += 1;
  } else {
    kosik.push({ pizza: vybranaPizza, pocet: 1 });
  }

  aktualizovatVzhledKosiku();
  zobrazitOznameni(`Pizza ${vybranaPizza.nazev} byla pridana do kosiku!`);
}

// Funkce pro odebrani z kosiku
function odebratZKosiku(index) {
  if (kosik[index].pocet > 1) {
    kosik[index].pocet -= 1;
  } else {
    kosik.splice(index, 1);
  }
  aktualizovatVzhledKosiku();
  renderovatObsahKosiku();
}

// Aktualizace pocitadla kosiku
function aktualizovatVzhledKosiku() {
  const celkovyPocet = kosik.reduce((sum, item) => sum + item.pocet, 0);
  const pocitadlo = document.getElementById('pocitadlo-kosiku-cislo');
  if (pocitadlo) {
    pocitadlo.textContent = celkovyPocet;
  }
}

// Renderovani polozek v modalnim okne kosiku
function renderovatObsahKosiku() {
  const kontejner = document.getElementById('seznam-polozek-kosik-modal');
  const celkovaCenaPrvek = document.getElementById('celkova-cena-kosik-modal');
  if (!kontejner) return;

  if (kosik.length === 0) {
    kontejner.innerHTML = '<p style="color: #888; text-align: center;">Váš košík je zatím prázdný.</p>';
    if (celkovaCenaPrvek) celkovaCenaPrvek.textContent = 'Celkem: 0 Kč';
    return;
  }

  let celkovaCena = 0;
  kontejner.innerHTML = kosik.map((item, index) => {
    const cenaPolozky = item.pizza.cena * item.pocet;
    celkovaCena += cenaPolozky;
    return `
      <div class="polozka-kosiku">
        <div>
          <strong>${item.pizza.nazev}</strong> (${item.pizza.cena} Kč / ks)
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
          <span>${item.pocet} ks</span>
          <strong>${cenaPolozky} Kč</strong>
          <button onclick="odebratZKosiku(${index})" style="background:#e50914; color:#fff; border:none; border-radius:4px; padding:3px 8px; cursor:pointer;">-</button>
        </div>
      </div>
    `;
  }).join('');

  if (celkovaCenaPrvek) {
    celkovaCenaPrvek.textContent = `Celkem: ${celkovaCena} Kč`;
  }
}

// Funkce pro modalni okna
function otvoritModal(idModalu) {
  const okno = document.getElementById(idModalu);
  if (okno) {
    okno.classList.add('zobrazeno');
    if (idModalu === 'modal-kosiku') {
      renderovatObsahKosiku();
    }
  }
}

function zavritModal(idModalu) {
  const okno = document.getElementById(idModalu);
  if (okno) {
    okno.classList.remove('zobrazeno');
  }
}

// Stav karuselu pro provozovny
const stavKaruselu = {
  'karusel-rychnov': { aktualniIndex: 0, celkem: 3 },
  'karusel-usti': { aktualniIndex: 0, celkem: 3 }
};

// Funkce pro posun karuselu pomoci sipek
function posunoutKarusel(idKaruselu, smer) {
  if (!stavKaruselu[idKaruselu]) return;
  
  const karusel = stavKaruselu[idKaruselu];
  karusel.aktualniIndex += smer;
  
  if (karusel.aktualniIndex < 0) {
    karusel.aktualniIndex = karusel.celkem - 1;
  } else if (karusel.aktualniIndex >= karusel.celkem) {
    karusel.aktualniIndex = 0;
  }
  
  aktualizovatZobrazenySnimek(idKaruselu);
}

// Funkce pro presny skok na tecku
function nastavitSnimekKaruselu(idKaruselu, index) {
  if (!stavKaruselu[idKaruselu]) return;
  stavKaruselu[idKaruselu].aktualniIndex = index;
  aktualizovatZobrazenySnimek(idKaruselu);
}

// Aktualizace posunu pasu a aktivni tecky
function aktualizovatZobrazenySnimek(idKaruselu) {
  const index = stavKaruselu[idKaruselu].aktualniIndex;
  const pas = document.querySelector(`#${idKaruselu} .karusel-snimky-pas`);
  const tecky = document.querySelectorAll(`#${idKaruselu} .karusel-tecka`);

  if (pas) {
    pas.style.transform = `translateX(-${index * 100}%)`;
  }

  tecky.forEach((tecka, i) => {
    if (i === index) {
      tecka.classList.add('aktivni');
    } else {
      tecka.classList.remove('aktivni');
    }
  });
}

/* --------------------------------------------------------------------------
   LOGIKA PRO PREPINANI POBOCEK (RYCHNOV N. K. / USTI N. O.)
   -------------------------------------------------------------------------- */
let vybranaPobocka = localStorage.getItem('vybranaPobocka') || 'rychnov';

document.addEventListener('DOMContentLoaded', () => {
  aktualizovatZobrazeniPobocky();

  // Chytrá plovoucí horní lišta (skrýt při scroll down, zobrazit při scroll up z jakéhokoliv místa)
  let posledniScrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
  let ticking = false;
  const minimalniPosunProReakci = 6;

  function obslouzitScroll() {
    const soucasnyScrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
    const horniLista = document.querySelector('.horni-kontakty-lista');

    if (!horniLista) {
      ticking = false;
      return;
    }

    if (soucasnyScrollY > 80) {
      horniLista.classList.add('skrolovalo-se');
    } else {
      horniLista.classList.remove('skrolovalo-se');
    }

    // Jsme-li úplně nahoře, lišta je vždy viditelná
    if (soucasnyScrollY <= 20) {
      horniLista.classList.remove('lista-skryta');
      posledniScrollY = soucasnyScrollY;
      ticking = false;
      return;
    }

    const rozdil = soucasnyScrollY - posledniScrollY;

    if (Math.abs(rozdil) >= minimalniPosunProReakci) {
      if (rozdil > 0) {
        // Scroll DOWN -> Skrýt lištu
        horniLista.classList.add('lista-skryta');
        
        // Pokud je otevřené mobilní menu nebo telefonní okénko, zavřeme je
        const menu = document.getElementById('navigace-odkazy-menu');
        const btnMenu = document.getElementById('btn-mobil-menu');
        if (menu && menu.classList.contains('mobil-otevreno')) {
          menu.classList.remove('mobil-otevreno');
          if (btnMenu) btnMenu.classList.remove('aktivni');
        }

        const box = document.getElementById('podokno-volani-box');
        const btnVolani = document.getElementById('btn-otevrit-volani');
        if (box && box.classList.contains('zobrazit')) {
          box.classList.remove('zobrazit');
          if (btnVolani) btnVolani.classList.remove('aktivni');
        }
      } else {
        // Scroll UP -> Zobrazit lištu
        horniLista.classList.remove('lista-skryta');
      }
      posledniScrollY = soucasnyScrollY;
    }

    ticking = false;
  }

  window.addEventListener('scroll', () => {
    if (!ticking) {
      window.requestAnimationFrame(obslouzitScroll);
      ticking = true;
    }
  }, { passive: true });
});

function prepnoutPobocku(mesto) {
  vybranaPobocka = mesto;
  localStorage.setItem('vybranaPobocka', mesto);
  aktualizovatZobrazeniPobocky();
  zobrazitOznameni(mesto === 'rychnov' ? 'Přepnuto na pobočku Rychnov n. K.' : 'Přepnuto na pobočku Ústí n. O.');
}

function aktualizovatZobrazeniPobocky() {
  // 1. Cerveny i Cernobily Kapslovy prepinac (data-aktivni posouva bily oválek)
  const kapsleKontejnery = document.querySelectorAll('.kapsle-prepinac-pobocky, .kapsle-prepinac-cernobily');
  kapsleKontejnery.forEach(kapsle => {
    kapsle.setAttribute('data-aktivni', vybranaPobocka);
  });

  const polozkyKapsle = document.querySelectorAll('.kapsle-polozka, .kapsle-polozka-cb');
  polozkyKapsle.forEach(btn => {
    const mestoAttr = btn.getAttribute('data-pobocka-kod');
    if (mestoAttr === vybranaPobocka) {
      btn.classList.add('aktivni');
    } else {
      btn.classList.remove('aktivni');
    }
  });

  // 2. Telefon pod switchem v hlavičce s čistě bílou SVG ikonou
  const horniKontaktPrvek = document.getElementById('horni-aktivni-kontakt');
  if (horniKontaktPrvek) {
    const jePodstranka = horniKontaktPrvek.closest('.navigace-prava-cast') !== null;
    const ikonaBilyMobil = `<svg width="16" height="16" viewBox="0 0 24 24" fill="#ffffff" style="margin-right: 6px; vertical-align: -2px;"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.02-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>`;
    const tridaOdkazu = jePodstranka ? 'navigace-telefon-stitek' : 'telefonni-odkaz';
    
    if (vybranaPobocka === 'rychnov') {
      horniKontaktPrvek.innerHTML = `
        <a href="tel:739149142" class="${tridaOdkazu}">${ikonaBilyMobil}<span>739 149 142</span></a>
      `;
    } else {
      horniKontaktPrvek.innerHTML = `
        <a href="tel:774741818" class="${tridaOdkazu}">${ikonaBilyMobil}<span>774 741 818</span></a>
      `;
    }
  }

  // 2b. Aktualizace plovuciho tlacitka volani vpravo dole
  const plovuciVolatPrvek = document.getElementById('plovuci-volat-tlacitko');
  if (plovuciVolatPrvek) {
    if (vybranaPobocka === 'rychnov') {
      plovuciVolatPrvek.setAttribute('href', 'tel:739149142');
      plovuciVolatPrvek.setAttribute('title', 'Volat rozvoz Rychnov (739 149 142)');
    } else {
      plovuciVolatPrvek.setAttribute('href', 'tel:774741818');
      plovuciVolatPrvek.setAttribute('title', 'Volat rozvoz Ústí (774 741 818)');
    }
  }

  // 3. Rozvoz sekce
  const rozvozRychnov = document.getElementById('rozvoz-karta-rychnov');
  const rozvozUsti = document.getElementById('rozvoz-karta-usti');
  if (rozvozRychnov && rozvozUsti) {
    if (vybranaPobocka === 'rychnov') {
      rozvozRychnov.style.display = 'flex';
      rozvozUsti.style.display = 'none';
    } else {
      rozvozRychnov.style.display = 'none';
      rozvozUsti.style.display = 'flex';
    }
  }

  // 4. Provozovny sekce
  const provozovnaRychnov = document.getElementById('provozovna-karta-rychnov');
  const provozovnaUsti = document.getElementById('provozovna-karta-usti');
  if (provozovnaRychnov && provozovnaUsti) {
    if (vybranaPobocka === 'rychnov') {
      provozovnaRychnov.style.display = 'flex';
      provozovnaUsti.style.display = 'none';
    } else {
      provozovnaRychnov.style.display = 'none';
      provozovnaUsti.style.display = 'flex';
    }
  }

  // 5. Tlacitko v modalnim okne kosiku
  const kosikVolatKontejner = document.getElementById('kosik-volat-tlacitko-kontejner');
  if (kosikVolatKontejner) {
    if (vybranaPobocka === 'rychnov') {
      kosikVolatKontejner.innerHTML = `
        <a href="tel:739149142" class="tlacitko tlacitko-cervene">Volat pro objednávku Rychnov (739 149 142)</a>
      `;
    } else {
      kosikVolatKontejner.innerHTML = `
        <a href="tel:774741818" class="tlacitko tlacitko-cervene">Volat pro objednávku Ústí (774 741 818)</a>
      `;
    }
  }
}

// 6. OBSLUHA VYSKAKOVACÍHO PODOKNA VOLÁNÍ V PODSTRÁNKOVÉ HLAVIČCE
function prepnoutPodoknoVolani(event) {
  if (event) {
    event.stopPropagation();
  }
  const box = document.getElementById('podokno-volani-box');
  const btn = document.getElementById('btn-otevrit-volani');
  if (box) {
    box.classList.toggle('zobrazit');
    if (btn) btn.classList.toggle('aktivni', box.classList.contains('zobrazit'));
  }
  // Pokud otevřeme volání, zavřeme mobilní menu
  const menu = document.getElementById('navigace-odkazy-menu');
  const btnMenu = document.getElementById('btn-mobil-menu');
  if (menu && menu.classList.contains('mobil-otevreno')) {
    menu.classList.remove('mobil-otevreno');
    if (btnMenu) btnMenu.classList.remove('aktivni');
  }
}
window.prepnoutPodoknoVolani = prepnoutPodoknoVolani;

// Obsluha podokna volání u velkého tlačítka v sekci Rozvoz
function prepnoutPodoknoVolaniRozvoz(event) {
  if (event) {
    event.stopPropagation();
  }
  const box = document.getElementById('podokno-volani-rozvoz-box');
  const btn = document.getElementById('btn-rozvoz-volani');
  if (!box) return;

  const jeOtevreno = box.classList.contains('zobrazit');
  if (jeOtevreno) {
    box.classList.remove('zobrazit');
  } else {
    box.classList.add('zobrazit');
  }
}
window.prepnoutPodoknoVolaniRozvoz = prepnoutPodoknoVolaniRozvoz;

// 7. OBSLUHA MOBILNÍHO HAMBURGER MENU
function prepnoutMobilMenu(event) {
  if (event) {
    event.stopPropagation();
  }
  const menu = document.getElementById('navigace-odkazy-menu');
  const btn = document.getElementById('btn-mobil-menu');
  if (menu) {
    menu.classList.toggle('mobil-otevreno');
    if (btn) btn.classList.toggle('aktivni', menu.classList.contains('mobil-otevreno'));
  }
  // Pokud otevřeme mobilní menu, zavřeme podokno volání
  const box = document.getElementById('podokno-volani-box');
  const btnVolani = document.getElementById('btn-otevrit-volani');
  if (box && box.classList.contains('zobrazit')) {
    box.classList.remove('zobrazit');
    if (btnVolani) btnVolani.classList.remove('aktivni');
  }
}
window.prepnoutMobilMenu = prepnoutMobilMenu;

// Kliknutí mimo podokno nebo menu je automaticky zavře
document.addEventListener('click', (e) => {
  const box = document.getElementById('podokno-volani-box');
  const btnVolani = document.getElementById('btn-otevrit-volani');
  if (box && box.classList.contains('zobrazit')) {
    if (!box.contains(e.target) && e.target !== btnVolani && !btnVolani.contains(e.target)) {
      box.classList.remove('zobrazit');
      if (btnVolani) btnVolani.classList.remove('aktivni');
    }
  }

  const boxRozvoz = document.getElementById('podokno-volani-rozvoz-box');
  const btnRozvoz = document.getElementById('btn-rozvoz-volani');
  if (boxRozvoz && boxRozvoz.classList.contains('zobrazit')) {
    if (!boxRozvoz.contains(e.target) && e.target !== btnRozvoz && !btnRozvoz.contains(e.target)) {
      boxRozvoz.classList.remove('zobrazit');
    }
  }

  const menu = document.getElementById('navigace-odkazy-menu');
  const btnMenu = document.getElementById('btn-mobil-menu');
  if (menu && menu.classList.contains('mobil-otevreno')) {
    if (!menu.contains(e.target) && e.target !== btnMenu && !btnMenu.contains(e.target)) {
      menu.classList.remove('mobil-otevreno');
      if (btnMenu) btnMenu.classList.remove('aktivni');
    }
  }
});

// Pomocná funkce pro odstranění diakritiky (háčků a čárek)
function odstranitDiakritiku(text) {
  if (!text) return '';
  const mapa = {
    'á': 'a', 'č': 'c', 'ď': 'd', 'é': 'e', 'ě': 'e', 'í': 'i', 'ň': 'n', 'ó': 'o',
    'ř': 'r', 'š': 's', 'ť': 't', 'ú': 'u', 'ů': 'u', 'ý': 'y', 'ž': 'z',
    'Á': 'a', 'Č': 'c', 'Ď': 'd', 'É': 'e', 'Ě': 'e', 'Í': 'i', 'Ň': 'n', 'Ó': 'o',
    'Ř': 'r', 'Š': 's', 'Ť': 't', 'Ú': 'u', 'Ů': 'u', 'Ý': 'y', 'Ž': 'z'
  };
  let vysledek = String(text).replace(/[áčďéěíňóřšťúůýžÁČĎÉĚÍŇÓŘŠŤÚŮÝŽ]/g, function(match) {
    return mapa[match] || match;
  });
  try {
    vysledek = vysledek.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
  } catch (e) {}
  return vysledek.toLowerCase().trim();
}
window.odstranitDiakritiku = odstranitDiakritiku;

// Funkce pro živé vyhledávání / filtrování obcí v seznamu
function filtrovatObceVOkne(kontejnerId, dotaz) {
  const kontejner = document.getElementById(kontejnerId);
  if (!kontejner) return false;

  const polozky = kontejner.querySelectorAll('.stitek-obec-polozka');
  const hledanyText = odstranitDiakritiku(dotaz);

  if (!hledanyText) {
    kontejner.style.display = 'none';
    polozky.forEach(function(el) {
      el.classList.add('skryto');
      el.classList.remove('shoda');
    });
    return false;
  }

  let nalezeno = false;
  polozky.forEach(function(el) {
    const dataObec = el.getAttribute('data-obec') || '';
    const textObec = el.textContent || '';
    const nazevObce1 = odstranitDiakritiku(dataObec);
    const nazevObce2 = odstranitDiakritiku(textObec);
    
    if (nazevObce1.includes(hledanyText) || nazevObce2.includes(hledanyText)) {
      el.classList.remove('skryto');
      el.classList.add('shoda');
      nalezeno = true;
    } else {
      el.classList.add('skryto');
      el.classList.remove('shoda');
    }
  });

  kontejner.style.display = nalezeno ? 'flex' : 'none';
  return nalezeno;
}
window.filtrovatObceVOkne = filtrovatObceVOkne;

// Funkce pro rozbalení/přepnutí mapy rozvozu přímo na hlavní stránce
function prepnoutPobockuRozvozMapy(pobocka, neprefiltrovat) {
  const prepinac = document.getElementById('prepinac-rozvoz-mapa');
  const mapaRychnov = document.getElementById('rozvoz-mapa-svg-rychnov');
  const mapaUsti = document.getElementById('rozvoz-mapa-svg-usti');
  const vstupHledani = document.getElementById('vstup-hledat-obec-rozvoz');

  if (prepinac) {
    prepinac.setAttribute('data-aktivni', pobocka);
    const tlacitka = prepinac.querySelectorAll('.kapsle-polozka');
    tlacitka.forEach(function(btn) {
      if ((pobocka === 'rychnov' && btn.textContent.includes('Rychnov')) ||
          (pobocka === 'usti' && btn.textContent.includes('Ústí'))) {
        btn.classList.add('aktivni');
      } else {
        btn.classList.remove('aktivni');
      }
    });
  }

  if (pobocka === 'rychnov') {
    if (mapaRychnov) mapaRychnov.style.display = 'flex';
    if (mapaUsti) mapaUsti.style.display = 'none';
  } else {
    if (mapaRychnov) mapaRychnov.style.display = 'none';
    if (mapaUsti) mapaUsti.style.display = 'flex';
  }

  // Přefiltrovat podle zadaného textu
  if (!neprefiltrovat && vstupHledani && vstupHledani.value) {
    filtrovatAktivniRozvozObce(vstupHledani.value);
  }
}
window.prepnoutPobockuRozvozMapy = prepnoutPobockuRozvozMapy;

// Živé vyhledávání obcí v aktivní pobočce nebo v mobilním boxíku Cena dopravy
function filtrovatAktivniRozvozObce(dotaz, jeZMobilu) {
  const cistyDotaz = odstranitDiakritiku(dotaz);

  if (jeZMobilu) {
    filtrovatObceVOkne('vysledky-obci-mobil', cistyDotaz);
    return;
  }

  // Na desktopu: zkontrolujeme shodu pro obě pobočky
  const prepinac = document.getElementById('prepinac-rozvoz-mapa');
  const aktivniPobocka = prepinac ? (prepinac.getAttribute('data-aktivni') || 'rychnov') : 'rychnov';

  const nalezRychnov = filtrovatObceVOkne('vysledky-obci-rychnov', cistyDotaz);
  const nalezUsti = filtrovatObceVOkne('vysledky-obci-usti', cistyDotaz);

  // Pokud hledáme vesnici, která je v druhé pobočce (např. Hnátnice v Ústí, když je otevřen Rychnov),
  // automaticky přepneme pobočku, aby ji uživatel hned viděl na mapě i ve výsledcích!
  if (cistyDotaz && !nalezRychnov && nalezUsti && aktivniPobocka !== 'usti') {
    prepnoutPobockuRozvozMapy('usti', true);
    filtrovatObceVOkne('vysledky-obci-usti', cistyDotaz);
    const rychnovKontejner = document.getElementById('vysledky-obci-rychnov');
    if (rychnovKontejner) rychnovKontejner.style.display = 'none';
  } else if (cistyDotaz && nalezRychnov && !nalezUsti && aktivniPobocka !== 'rychnov') {
    prepnoutPobockuRozvozMapy('rychnov', true);
    filtrovatObceVOkne('vysledky-obci-rychnov', cistyDotaz);
    const ustiKontejner = document.getElementById('vysledky-obci-usti');
    if (ustiKontejner) ustiKontejner.style.display = 'none';
  } else {
    // Skryjeme neaktivní kontejner
    const neaktivniId = aktivniPobocka === 'rychnov' ? 'vysledky-obci-usti' : 'vysledky-obci-rychnov';
    const neaktivniKontejner = document.getElementById(neaktivniId);
    if (neaktivniKontejner) neaktivniKontejner.style.display = 'none';
  }

  // Zvýraznění v SVG mapě
  const vsechnyMapoveTexty = document.querySelectorAll('.mapa-text-obec');
  vsechnyMapoveTexty.forEach(function(el) {
    const text = odstranitDiakritiku(el.textContent);
    if (cistyDotaz && text.includes(cistyDotaz)) {
      el.classList.add('shoda');
    } else {
      el.classList.remove('shoda');
    }
  });
}
window.filtrovatAktivniRozvozObce = filtrovatAktivniRozvozObce;

// Rozbalení / sbalení celého velkého bloku mapy rozvozu
function prepnoutRozbaleniRozvozoveMapy() {
  const blok = document.getElementById('rozvoz-mapa-velky-blok');
  const btn = document.getElementById('btn-toggle-rozvoz-mapa');
  if (!blok) return;

  const jeSkryto = blok.style.display === 'none' || !blok.style.display;
  if (jeSkryto) {
    blok.style.display = 'flex';
    if (btn) btn.classList.add('otevreno');
  } else {
    blok.style.display = 'none';
    if (btn) btn.classList.remove('otevreno');
  }
}
window.prepnoutRozbaleniRozvozoveMapy = prepnoutRozbaleniRozvozoveMapy;

// Tlačítko UKÁZAT NA MAPĚ v sekci Provozovny
function ukazatNaMapeProvozovnu(pobocka) {
  const blok = document.getElementById('rozvoz-mapa-velky-blok');
  const btn = document.getElementById('btn-toggle-rozvoz-mapa');
  if (blok) {
    blok.style.display = 'flex';
    if (btn) btn.classList.add('otevreno');
  }
  prepnoutPobockuRozvozMapy(pobocka);
  const rozvozSekce = document.getElementById('rozvoz');
  if (rozvozSekce) {
    rozvozSekce.scrollIntoView({ behavior: 'smooth' });
  }
}
window.ukazatNaMapeProvozovnu = ukazatNaMapeProvozovnu;

function prepnoutRozbalovaciMapu(pobocka, scrollKMapam) {
  ukazatNaMapeProvozovnu(pobocka);
}
window.prepnoutRozbalovaciMapu = prepnoutRozbalovaciMapu;

// Inicializace tooltipu na mapě rozvozu při najetí myší na PC
function inicializovatMapuRozvozuTooltips() {
  const tooltip = document.getElementById('rozvoz-mapa-tooltip');
  const velkyBlok = document.getElementById('rozvoz-mapa-velky-blok');
  if (!tooltip || !velkyBlok) return;

  const tooltipNazev = tooltip.querySelector('.tooltip-obec-nazev');
  const tooltipKm = tooltip.querySelector('.tooltip-obec-km');
  const tooltipCena = tooltip.querySelector('.tooltip-obec-cena');

  // Vytvoříme databázi obcí podle štítků v DOM
  const dbObci = {};
  document.querySelectorAll('.stitek-obec-polozka').forEach(function(el) {
    const obec = el.getAttribute('data-obec') || '';
    const kmEl = el.querySelector('.stitek-vzdalenost');
    const cenaEl = el.querySelector('.cena-obce-stitek');
    if (obec && kmEl && cenaEl) {
      const klic = odstranitDiakritiku(obec);
      dbObci[klic] = {
        nazev: obec,
        km: kmEl.textContent.trim(),
        cena: cenaEl.textContent.trim()
      };
    }
  });

  // Aliasy pro zkrácené názvy na radarové mapě
  const aliasy = {
    'skuhrov n. b.': 'skuhrov nad belou',
    'skuhrov n.b.': 'skuhrov nad belou',
    'peklo n. z.': 'peklo nad zdobnici',
    'peklo n.z.': 'peklo nad zdobnici',
    'doudleby n. o.': 'doudleby nad orlici',
    'doudleby n.o.': 'doudleby nad orlici',
    'kostelec n. o.': 'kostelec nad orlici',
    'kostelec n.o.': 'kostelec nad orlici',
    'brandys n. o.': 'brandys nad orlici',
    'brandys n.o.': 'brandys nad orlici',
    'rychnov n.k.': { nazev: 'Rychnov n.K.', km: 'Město', cena: '+10 Kč' },
    'rychnov n. k.': { nazev: 'Rychnov n.K.', km: 'Město', cena: '+10 Kč' },
    'usti n.o.': { nazev: 'Ústí n.O.', km: 'Město', cena: '+10 Kč' },
    'usti n. o.': { nazev: 'Ústí n.O.', km: 'Město', cena: '+10 Kč' }
  };

  function ziskatDataObce(text) {
    const klic = odstranitDiakritiku(text);
    if (dbObci[klic]) return dbObci[klic];
    if (aliasy[klic]) {
      if (typeof aliasy[klic] === 'object') return aliasy[klic];
      if (dbObci[aliasy[klic]]) return dbObci[aliasy[klic]];
    }
    return null;
  }

  function updatePozice(e) {
    const blokRect = velkyBlok.getBoundingClientRect();
    const x = e.clientX - blokRect.left;
    const y = e.clientY - blokRect.top;
    tooltip.style.left = `${x}px`;
    tooltip.style.top = `${y}px`;
  }

  function zobrazitTooltip(e, data, el1, el2) {
    if (el1) el1.classList.add('hover-aktivni');
    if (el2) el2.classList.add('hover-aktivni');

    if (tooltipNazev) tooltipNazev.textContent = data.nazev;
    if (tooltipKm) tooltipKm.textContent = data.km;
    if (tooltipCena) tooltipCena.textContent = data.cena;

    tooltip.classList.add('aktivni');
    updatePozice(e);
  }

  function skrytTooltip(el1, el2) {
    if (el1) el1.classList.remove('hover-aktivni');
    if (el2) el2.classList.remove('hover-aktivni');
    tooltip.classList.remove('aktivni');
  }

  // Zaregistrujeme události na obou mapách
  const mapy = document.querySelectorAll('.rozvoz-mapa-svg-platno svg');
  mapy.forEach(function(svg) {
    // 1. Obce v okolí
    const texty = svg.querySelectorAll('.mapa-text-obec');
    texty.forEach(function(textEl) {
      const textObsah = textEl.textContent.trim();
      const data = ziskatDataObce(textObsah);
      if (!data) return;

      let prevCircle = textEl.previousElementSibling;
      while (prevCircle && prevCircle.tagName.toLowerCase() !== 'circle') {
        prevCircle = prevCircle.previousElementSibling;
      }

      textEl.addEventListener('mouseenter', function(e) { zobrazitTooltip(e, data, textEl, prevCircle); });
      textEl.addEventListener('mousemove', updatePozice);
      textEl.addEventListener('mouseleave', function() { skrytTooltip(textEl, prevCircle); });

      if (prevCircle) {
        prevCircle.addEventListener('mouseenter', function(e) { zobrazitTooltip(e, data, textEl, prevCircle); });
        prevCircle.addEventListener('mousemove', updatePozice);
        prevCircle.addEventListener('mouseleave', function() { skrytTooltip(textEl, prevCircle); });
      }
    });

    // 2. Středová města (Rychnov n.K. / Ústí n.O.)
    const mestoText = svg.querySelector('.mapa-popisek-mesto');
    const mestoBod = svg.querySelector('.mapa-bod-stred');
    if (mestoText) {
      const dataMesto = ziskatDataObce(mestoText.textContent.trim());
      if (dataMesto) {
        mestoText.addEventListener('mouseenter', function(e) { zobrazitTooltip(e, dataMesto, mestoText, mestoBod); });
        mestoText.addEventListener('mousemove', updatePozice);
        mestoText.addEventListener('mouseleave', function() { skrytTooltip(mestoText, mestoBod); });

        if (mestoBod) {
          mestoBod.addEventListener('mouseenter', function(e) { zobrazitTooltip(e, dataMesto, mestoText, mestoBod); });
          mestoBod.addEventListener('mousemove', updatePozice);
          mestoBod.addEventListener('mouseleave', function() { skrytTooltip(mestoText, mestoBod); });
        }
      }
    }
  });
}
window.inicializovatMapuRozvozuTooltips = inicializovatMapuRozvozuTooltips;

// Spuštění inicializace po načtení dokumentu
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', inicializovatMapuRozvozuTooltips);
} else {
  inicializovatMapuRozvozuTooltips();
}





