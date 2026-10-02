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

// Funkce pro vykresleni pizz v mrizce
function renderovatJidelniListek() {
  const mrizkaPrvek = document.getElementById('mrizka-pizz-kontejner');
  if (!mrizkaPrvek) return;

  const aktualniPizzy = window.seznamPizzZDatabaze || seznamPizz;

  const filtrovanePizzy = aktualniPizzy.filter(pizza => {
    // 1. Maso / bez masa
    const jeMasita = pizza.masite === true || (pizza.bezmase === false);
    const jeBezmasna = pizza.bezmase === true || (pizza.masite === false);

    if (stavPrepinacu.maso === 'masite' && !jeMasita) return false;
    if (stavPrepinacu.maso === 'bezmase' && !jeBezmasna) return false;

    // 2. Palivost
    const jePaliva = pizza.paliva === true || (pizza.nepaliva === false);
    const jeNepaliva = pizza.nepaliva === true || (pizza.paliva === false);

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

    return `
      <div class="karta-pizzy">
        <div class="ciselny-odznak ${pizza.zlata ? 'odznak-zlata' : ''}">${pizza.cislo}</div>
        <div class="obrazek-pizzy-obal" onclick="otvoritDetailPizzy(${pizza.id})" style="cursor: pointer;" title="Zobrazit detail pizzy">
          <img src="${pizza.obrazek}" alt="${pizza.nazev}" loading="lazy">
        </div>
        <h3 class="nazev-pizzy" onclick="otvoritDetailPizzy(${pizza.id})" style="cursor: pointer;">${pizza.nazev}</h3>
        <p class="slozeni-pizzy">${pizza.slozeni}</p>
        
        ${alergenyVypis ? `<div class="alergeny-klikaci-stitek" onclick="otvoritAlergenyPizzy(${pizza.id}); event.stopPropagation();" title="Zobrazit alergeny pizzy">${alergenyVypis}</div>` : ''}

        <div class="patka-karty-pizzy">
          <span class="cena-pizzy">${pizza.cena},- Kč</span>
          <button class="tlacitko-pridat-plus" onclick="pridatDoKosiku(${pizza.id})" title="Přidat do košíku">+</button>
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
      ingredienceSeznamHtml = obsazeneSuroviny.map(s => `
        <div style="background: #222; padding: 10px 14px; border-radius: 8px; border: 1px solid #333; display: flex; align-items: center; justify-content: space-between;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <img src="${s.obrazek}" style="width: 35px; height: 35px; object-fit: cover; border-radius: 50%;" alt="">
            <strong style="color: #fff; font-size: 0.95rem;">${s.nazev}</strong>
          </div>
          <span style="font-size: 0.8rem; color: #f59e0b; font-weight: bold;">${s.puvod || ''}</span>
        </div>
      `).join('');
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

  kontejner.innerHTML = `
    <div style="text-align: center; margin-bottom: 20px;">
      <div style="font-size: 0.9rem; color: #f59e0b; font-weight: 800; letter-spacing: 1px; margin-bottom: 5px;">PIZZA Č. ${pizza.cislo}</div>
      <h2 class="nadpis-sekce" style="font-size: 2rem; margin: 0;">${pizza.nazev}</h2>
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

  // Posluchac scrollovani pro jemne zkompaktneni hlavicky
  window.addEventListener('scroll', () => {
    const lista = document.querySelector('.horni-kontakty-lista');
    if (window.scrollY > 80) {
      lista?.classList.add('skrolovalo-se');
    } else {
      lista?.classList.remove('skrolovalo-se');
    }
  });
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
    const ikonaBilyMobil = `<svg width="18" height="18" viewBox="0 0 24 24" fill="#ffffff" style="margin-right: 6px; vertical-align: -2px;"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.02-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>`;
    if (vybranaPobocka === 'rychnov') {
      horniKontaktPrvek.innerHTML = `
        <a href="tel:739149142" class="telefonni-odkaz">${ikonaBilyMobil}739 149 142</a>
      `;
    } else {
      horniKontaktPrvek.innerHTML = `
        <a href="tel:774741818" class="telefonni-odkaz">${ikonaBilyMobil}774 741 818</a>
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



