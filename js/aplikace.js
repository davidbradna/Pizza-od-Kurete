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

// Stav kosiku a rozvozu
let kosik = [];
let vybranyRozvoz = null;
let aktivniKategorieFiltru = 'vse';

// Seznam všech obcí a lokalit pro rozvoz (propojeno s cenou dopravy)
const SEZNAM_ROZVOZOVYCH_OBCI = [
  // Rychnov nad Kněžnou a okolí
  { obec: 'Rychnov nad Kněžnou', vzdalenost: 'Město', cena: 10, pobocka: 'rychnov' },
  { obec: 'Lipovka', vzdalenost: '3 km', cena: 21, pobocka: 'rychnov' },
  { obec: 'Lokot', vzdalenost: '3 km', cena: 21, pobocka: 'rychnov' },
  { obec: 'Dlouhá Ves', vzdalenost: '4 km', cena: 28, pobocka: 'rychnov' },
  { obec: 'Litohrady', vzdalenost: '4 km', cena: 28, pobocka: 'rychnov' },
  { obec: 'Panská Habrová', vzdalenost: '4 km', cena: 28, pobocka: 'rychnov' },
  { obec: 'Domašín', vzdalenost: '5 km', cena: 35, pobocka: 'rychnov' },
  { obec: 'Lupenice', vzdalenost: '5 km', cena: 35, pobocka: 'rychnov' },
  { obec: 'Roveň', vzdalenost: '5 km', cena: 35, pobocka: 'rychnov' },
  { obec: 'Slemeno', vzdalenost: '5 km', cena: 35, pobocka: 'rychnov' },
  { obec: 'Solnice', vzdalenost: '5 km', cena: 35, pobocka: 'rychnov' },
  { obec: 'Jahodov', vzdalenost: '6 km', cena: 42, pobocka: 'rychnov' },
  { obec: 'Jaroslav', vzdalenost: '6 km', cena: 42, pobocka: 'rychnov' },
  { obec: 'Peklo nad Zdobnicí', vzdalenost: '6 km', cena: 42, pobocka: 'rychnov' },
  { obec: 'Třebešov', vzdalenost: '6 km', cena: 42, pobocka: 'rychnov' },
  { obec: 'Javornice', vzdalenost: '7 km', cena: 49, pobocka: 'rychnov' },
  { obec: 'Javornice - Betlém', vzdalenost: '7 km', cena: 49, pobocka: 'rychnov' },
  { obec: 'Libel', vzdalenost: '7 km', cena: 49, pobocka: 'rychnov' },
  { obec: 'Lukavice', vzdalenost: '7 km', cena: 49, pobocka: 'rychnov' },
  { obec: 'Synkov', vzdalenost: '7 km', cena: 49, pobocka: 'rychnov' },
  { obec: 'Vamberk', vzdalenost: '7 km', cena: 49, pobocka: 'rychnov' },
  { obec: 'Černíkovice', vzdalenost: '8 km', cena: 56, pobocka: 'rychnov' },
  { obec: 'Doudleby nad Orlicí', vzdalenost: '8 km', cena: 56, pobocka: 'rychnov' },
  { obec: 'Ještětice', vzdalenost: '8 km', cena: 56, pobocka: 'rychnov' },
  { obec: 'Kvasiny', vzdalenost: '8 km', cena: 56, pobocka: 'rychnov' },
  { obec: 'Liberk', vzdalenost: '8 km', cena: 56, pobocka: 'rychnov' },
  { obec: 'Tutleky', vzdalenost: '8 km', cena: 56, pobocka: 'rychnov' },
  { obec: 'Hláska', vzdalenost: '9 km', cena: 63, pobocka: 'rychnov' },
  { obec: 'Lično', vzdalenost: '9 km', cena: 63, pobocka: 'rychnov' },
  { obec: 'Merklovice', vzdalenost: '9 km', cena: 63, pobocka: 'rychnov' },
  { obec: 'Bílý Újezd', vzdalenost: '10 km', cena: 70, pobocka: 'rychnov' },
  { obec: 'Brocná', vzdalenost: '10 km', cena: 70, pobocka: 'rychnov' },
  { obec: 'Byzhradec', vzdalenost: '10 km', cena: 70, pobocka: 'rychnov' },
  { obec: 'Častolovice', vzdalenost: '10 km', cena: 70, pobocka: 'rychnov' },
  { obec: 'Debřece', vzdalenost: '10 km', cena: 70, pobocka: 'rychnov' },
  { obec: 'Hroška', vzdalenost: '10 km', cena: 70, pobocka: 'rychnov' },
  { obec: 'Kostelec nad Orlicí', vzdalenost: '10 km', cena: 70, pobocka: 'rychnov' },
  { obec: 'Ledská', vzdalenost: '10 km', cena: 70, pobocka: 'rychnov' },
  { obec: 'Malá Ledská', vzdalenost: '10 km', cena: 70, pobocka: 'rychnov' },
  { obec: 'Přím', vzdalenost: '10 km', cena: 70, pobocka: 'rychnov' },
  { obec: 'Skuhrov nad Bělou', vzdalenost: '10 km', cena: 70, pobocka: 'rychnov' },
  { obec: 'Záměl', vzdalenost: '10 km', cena: 70, pobocka: 'rychnov' },

  // Ústí nad Orlicí a okolí
  { obec: 'Ústí nad Orlicí', vzdalenost: 'Město', cena: 10, pobocka: 'usti' },
  { obec: 'Černovír', vzdalenost: 'Zóna 2', cena: 50, pobocka: 'usti' },
  { obec: 'Dlouhá Třebová', vzdalenost: 'Zóna 2', cena: 50, pobocka: 'usti' },
  { obec: 'Dolní Libchavy', vzdalenost: 'Zóna 2', cena: 50, pobocka: 'usti' },
  { obec: 'Kerhartice', vzdalenost: 'Zóna 2', cena: 50, pobocka: 'usti' },
  { obec: 'Knapovec', vzdalenost: 'Zóna 2', cena: 50, pobocka: 'usti' },
  { obec: 'Lanšperk', vzdalenost: 'Zóna 2', cena: 50, pobocka: 'usti' },
  { obec: 'Česká Třebová', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' },
  { obec: 'České Libchavy', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' },
  { obec: 'Dolní Dobrouč', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' },
  { obec: 'Dolní Houžovec', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' },
  { obec: 'Džbánov', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' },
  { obec: 'Horní Houžovec', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' },
  { obec: 'Horní Libchavy', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' },
  { obec: 'Hrádek', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' },
  { obec: 'Jehnědí', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' },
  { obec: 'Orlické Podhůří', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' },
  { obec: 'Přívrat', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' },
  { obec: 'Rozsocha', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' },
  { obec: 'Rviště', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' },
  { obec: 'Říčky', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' },
  { obec: 'Skuhrov', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' },
  { obec: 'Sloupnice', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' },
  { obec: 'Sudislav nad Orlicí', vzdalenost: 'Zóna 3', cena: 80, pobocka: 'usti' }
];

function nacistKosikZeStorage() {
  try {
    const data = localStorage.getItem('pizza_kosik');
    if (data) {
      const parsed = JSON.parse(data);
      if (Array.isArray(parsed)) {
        const aktualniPizzy = window.seznamPizzZDatabaze || (typeof seznamPizz !== 'undefined' ? seznamPizz : []) || [];
        kosik = parsed.map(item => {
          if (!item || !item.pizza) return null;
          const cerstvaPizza = aktualniPizzy.find(p => String(p.id) === String(item.pizza.id));
          return {
            pizza: cerstvaPizza || item.pizza,
            pocet: parseInt(item.pocet, 10) || 1
          };
        }).filter(Boolean);
      }
    }
  } catch (e) {
    console.error('Chyba při načítání košíku z localStorage:', e);
    kosik = [];
  }
}

function ulozitKosikDoStorage() {
  try {
    localStorage.setItem('pizza_kosik', JSON.stringify(kosik));
  } catch (e) {
    console.error('Chyba při ukládání košíku do localStorage:', e);
  }
}

function nacistRozvozZeStorage() {
  try {
    const data = localStorage.getItem('pizza_rozvoz');
    if (data) {
      const parsed = JSON.parse(data);
      if (parsed && parsed.obec && typeof parsed.cena !== 'undefined') {
        vybranyRozvoz = parsed;
      }
    }
  } catch (e) {
    vybranyRozvoz = null;
  }
}

function ulozitRozvozDoStorage() {
  try {
    if (vybranyRozvoz) {
      localStorage.setItem('pizza_rozvoz', JSON.stringify(vybranyRozvoz));
    } else {
      localStorage.removeItem('pizza_rozvoz');
    }
  } catch (e) {
    console.error('Chyba při ukládání rozvozu do localStorage:', e);
  }
}

// Inicializace košíku a rozvozu při načtení skriptu
nacistKosikZeStorage();
nacistRozvozZeStorage();

function inicializovatAplikaci() {
  nacistKosikZeStorage();
  nacistRozvozZeStorage();
  renderovatJidelniListek();
  nastavitPosluchaceUdalosti();
  aktualizovatVzhledKosiku();
}

// Spuštění po načtení DOM nebo ihned, pokud je již DOM připraven
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', inicializovatAplikaci);
} else {
  inicializovatAplikaci();
}

window.addEventListener('pageshow', () => {
  nacistKosikZeStorage();
  nacistRozvozZeStorage();
  if (typeof renderovatJidelniListek === 'function') {
    renderovatJidelniListek();
  }
  aktualizovatVzhledKosiku();
  renderovatObsahKosiku();
});

document.addEventListener('visibilitychange', () => {
  if (!document.hidden) {
    nacistKosikZeStorage();
    nacistRozvozZeStorage();
    if (typeof renderovatJidelniListek === 'function') {
      renderovatJidelniListek();
    }
    aktualizovatVzhledKosiku();
    renderovatObsahKosiku();
  }
});

window.addEventListener('focus', () => {
  nacistKosikZeStorage();
  nacistRozvozZeStorage();
  if (typeof renderovatJidelniListek === 'function') {
    renderovatJidelniListek();
  }
  aktualizovatVzhledKosiku();
  renderovatObsahKosiku();
});

window.addEventListener('storage', (e) => {
  if (e.key === 'pizza_kosik' || e.key === 'pizza_rozvoz') {
    nacistKosikZeStorage();
    nacistRozvozZeStorage();
    if (typeof renderovatJidelniListek === 'function') {
      renderovatJidelniListek();
    }
    aktualizovatVzhledKosiku();
    renderovatObsahKosiku();
  }
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

// Pomocná funkce pro vyhledání suroviny podle názvu nebo ID
function najitSurovinuProNazev(nazevPolozky, pizza) {
  const suroviny = (window.seznamSurovinZDatabaze && window.seznamSurovinZDatabaze.length > 0)
    ? window.seznamSurovinZDatabaze
    : [];
  if (suroviny.length === 0 || !nazevPolozky) return null;

  const normalizovat = (text) => {
    return (text || '')
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .toLowerCase()
      .trim();
  };

  const hledanyText = normalizovat(nazevPolozky);
  if (!hledanyText) return null;

  // 1. Přesná shoda podle názvu suroviny
  let nalezena = suroviny.find(s => normalizovat(s.nazev) === hledanyText);
  if (nalezena) return nalezena;

  // 2. Přesná shoda podle ID
  nalezena = suroviny.find(s => normalizovat(s.id) === hledanyText);
  if (nalezena) return nalezena;

  // 3. Shoda v rámci ingrediencí přiřazených dané pizze
  if (pizza && Array.isArray(pizza.ingredience) && pizza.ingredience.length > 0) {
    const surovinyPizzy = suroviny.filter(s => pizza.ingredience.includes(s.id));
    if (hledanyText.length >= 4) {
      nalezena = surovinyPizzy.find(s => {
        const sNazev = normalizovat(s.nazev);
        const sId = normalizovat(s.id);
        return sNazev.includes(hledanyText) || hledanyText.includes(sNazev) || sId.includes(hledanyText) || hledanyText.includes(sId);
      });
      if (nalezena) return nalezena;
    }

    const stopSlova = new Set(['italska', 'italske', 'italsky', 'cerstva', 'cerstve', 'cerstvy', 'special', 'original', 'prave', 'pravy', 'prava', 'd.o.p', 'dop']);
    const slova = hledanyText.split(/[\s,.-]+/).filter(w => w.length >= 4 && !stopSlova.has(w));
    for (const slovo of slova) {
      nalezena = surovinyPizzy.find(s => {
        const sNazev = normalizovat(s.nazev);
        const sId = normalizovat(s.id);
        return sNazev.includes(slovo) || sId.includes(slovo);
      });
      if (nalezena) return nalezena;
    }
  }

  // 4. Obecná shoda v celém seznamu surovin
  if (hledanyText.length >= 4) {
    nalezena = suroviny.find(s => {
      const sNazev = normalizovat(s.nazev);
      const sId = normalizovat(s.id);
      return sNazev.includes(hledanyText) || hledanyText.includes(sNazev) || sId.includes(hledanyText) || hledanyText.includes(sId);
    });
    if (nalezena) return nalezena;
  }

  const stopSlova = new Set(['italska', 'italske', 'italsky', 'cerstva', 'cerstve', 'cerstvy', 'special', 'original', 'prave', 'pravy', 'prava', 'd.o.p', 'dop']);
  const slova = hledanyText.split(/[\s,.-]+/).filter(w => w.length >= 4 && !stopSlova.has(w));
  for (const slovo of slova) {
    nalezena = suroviny.find(s => {
      const sNazev = normalizovat(s.nazev);
      const sId = normalizovat(s.id);
      return sNazev.includes(slovo) || sId.includes(slovo);
    });
    if (nalezena) return nalezena;
  }

  return null;
}

// Pomocná funkce pro formátování složení pizzy s proklikem na velký náhled ingredience (seřazeno podle administrace)
function formatovatSlozeniPizzy(pizza) {
  if (!pizza) return '';
  const textSlozeni = pizza.slozeni || '';
  if (!textSlozeni.trim()) return '';

  const vsechnySuroviny = window.seznamSurovinZDatabaze || [];
  const indexMap = new Map();
  vsechnySuroviny.forEach((s, idx) => indexMap.set(s.id, idx));

  const casti = textSlozeni.split(',').map(cast => cast.trim()).filter(Boolean);
  
  // Namapujeme každou surovinu a určíme její pozici podle pořadí v administraci
  const polozky = casti.map(cast => {
    const surovina = najitSurovinuProNazev(cast, pizza);
    const poradi = (surovina && indexMap.has(surovina.id)) ? indexMap.get(surovina.id) : 9999;
    return {
      text: cast,
      surovina: surovina,
      poradi: poradi
    };
  });

  // Seřadíme přesně podle pořadí v administraci
  polozky.sort((a, b) => a.poradi - b.poradi);

  return polozky.map(p => {
    if (p.surovina && p.surovina.id) {
      return `<a href="javascript:void(0)" class="odkaz-ingredience-karta" onclick="otevritNahledIngredience('${encodeURIComponent(p.surovina.id)}', event, ${pizza.id})" title="Zobrazit detail ingredience">${p.text}</a>`;
    }
    return p.text;
  }).join(', ');
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
    // 0. Neaktivní pizza týdne se nesmí propisovat do menu (v menu smí být pouze aktivní pizza týdne)
    const jePizzaTydne = Boolean(pizza.pizzaTydne);
    const jeAktivniTydne = Boolean(pizza.aktivniPizzaTydne);
    if (jePizzaTydne && !jeAktivniTydne) {
      return false;
    }

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
    const pocetVKosiku = ziskatPocetPizzyVKosiku(pizza.id);

    return `
      <div class="karta-pizzy ${jeAktivniTydne ? 'karta-pizza-tydne' : ''}">
        ${!jeAktivniTydne ? `<div class="ciselny-odznak">${pizza.cislo}</div>` : ''}
        ${jeAktivniTydne ? `<span class="stitek-pizza-tydne-roh"><span class="hvezda-ikona-tydne">★</span> PIZZA TÝDNE</span>` : ''}
        ${(pizza.nejprodavanejsi || pizza.doporucujeme) ? `
          <div class="karta-pizzy-odznaky-vpravo">
            ${pizza.nejprodavanejsi ? `
              <span class="odznak-pizza-vlastnost odznak-nejprodavanejsi" title="Nejprodávanější pizza">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>
                </svg>
              </span>
            ` : ''}
            ${pizza.doporucujeme ? `
              <span class="odznak-pizza-vlastnost odznak-doporucujeme" title="Doporučujeme">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
              </span>
            ` : ''}
          </div>
        ` : ''}
        <div class="obrazek-pizzy-obal">
          <img src="${pizza.obrazek}" alt="${pizza.nazev}" loading="lazy" decoding="async">
        </div>
        <h3 class="nazev-pizzy">${pizza.nazev}${palivostHtml}</h3>
        <p class="slozeni-pizzy">${formatovatSlozeniPizzy(pizza)}</p>
        
        ${alergenyVypis ? `<div class="alergeny-klikaci-stitek">${alergenyVypis}</div>` : ''}

        <div class="patka-karty-pizzy">
          <span class="cena-pizzy">${pizza.cena},- Kč</span>
          ${(pizza.naMiste && !pizza.sSebou) ? `
            <span class="stitek-pouze-na-miste-patka">POUZE NA MÍSTĚ</span>
          ` : `
            <div class="obal-pridani-karta">
              <span class="pocet-v-kosiku-odznak ${pocetVKosiku > 0 ? 'viditelny' : ''}" data-id="${pizza.id}" onclick="odebratJednuPizzu(${pizza.id}, event)" title="Odebrat z košíku">
                <span class="pocet-cislo-text">${pocetVKosiku > 0 ? pocetVKosiku : ''}</span>
                <span class="pocet-minus-ikona">−</span>
              </span>
              <button class="tlacitko-pridat-plus" onclick="pridatDoKosiku(${pizza.id})" title="Přidat do nákupního seznamu">+</button>
            </div>
          `}
        </div>
      </div>
    `;
  }).join('');
}


// Nastaveni posluchacu pro tlacitka a checkboxes
function nastavitPosluchaceUdalosti() {
  // Checkbox filtry
  document.getElementById('filtr-sugo')?.addEventListener('change', renderovatJidelniListek);
  document.getElementById('filtr-bily')?.addEventListener('change', renderovatJidelniListek);
  document.getElementById('filtr-nepaliva')?.addEventListener('change', renderovatJidelniListek);

  // Tlacitka kategorii (Pizza tydne, nejprodavanejsi, doporucujeme)
  const tlacitkaKategorii = document.querySelectorAll('#jidelni-listek .tlacitko-kategorie');
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

// Funkce pro toast oznámení
let oznameniToastTimeout = null;
function zobrazitOznameni(zprava, typ = 'uspech') {
  if (typ === 'uspech') return; // Zelené hlášky úspěchu nebudeme zobrazovat
  let toast = document.getElementById('aplikace-toast-box');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'aplikace-toast-box';
    toast.style.cssText = 'position: fixed; bottom: 85px; right: 25px; background: #181818; border: 1px solid var(--barva-zelena-hlavni, #2ed573); color: #ffffff; padding: 12px 20px; border-radius: 12px; font-size: 0.95rem; font-weight: 600; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7); z-index: 99999; display: flex; align-items: center; gap: 10px; opacity: 0; transform: translateY(15px); transition: opacity 0.25s ease, transform 0.25s ease; pointer-events: none;';
    document.body.appendChild(toast);
  }

  if (typ === 'chyba') {
    toast.style.borderColor = '#ef4444';
  } else if (typ === 'upozorneni') {
    toast.style.borderColor = '#f59e0b';
  } else {
    toast.style.borderColor = 'var(--barva-zelena-hlavni, #2ed573)';
  }

  toast.innerHTML = `<span>🍕 ${zprava}</span>`;
  void toast.offsetWidth;
  toast.style.opacity = '1';
  toast.style.transform = 'translateY(0)';

  if (oznameniToastTimeout) clearTimeout(oznameniToastTimeout);
  oznameniToastTimeout = setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(15px)';
  }, 2500);
}
window.zobrazitOznameni = zobrazitOznameni;

// Funkce pro pridani pizzy do kosiku
function pridatDoKosiku(idPizzy) {
  const vsechnyPizzy = window.seznamPizzZDatabaze || (typeof seznamPizz !== 'undefined' ? seznamPizz : []) || [];
  const vybranaPizza = vsechnyPizzy.find(p => String(p.id) === String(idPizzy));
  if (!vybranaPizza) return;

  const stavajiciPolozka = kosik.find(item => item && item.pizza && String(item.pizza.id) === String(idPizzy));
  if (stavajiciPolozka) {
    stavajiciPolozka.pocet = (parseInt(stavajiciPolozka.pocet, 10) || 0) + 1;
  } else {
    kosik.push({ pizza: vybranaPizza, pocet: 1 });
  }

  ulozitKosikDoStorage();
  aktualizovatVzhledKosiku(idPizzy);
  renderovatObsahKosiku();

  if (typeof zobrazitOznameni === 'function') {
    zobrazitOznameni(`Pizza ${vybranaPizza.nazev} byla přidána do nákupního seznamu!`);
  }
}
window.pridatDoKosiku = pridatDoKosiku;

// Funkce pro přidání do košíku ze seznamu pizz v modálu suroviny
function pridatDoKosikuZeSuroviny(idPizzy, btn) {
  pridatDoKosiku(idPizzy);

  if (btn) {
    const puvodniText = btn.getAttribute('data-puvodni-text') || 'Přidat do seznamu';
    if (!btn.getAttribute('data-puvodni-text')) {
      btn.setAttribute('data-puvodni-text', puvodniText);
    }
    const pocet = ziskatPocetPizzyVKosiku(idPizzy);
    btn.innerHTML = `✓ Přidáno (${pocet} ks)`;
    btn.style.backgroundColor = 'var(--barva-zelena-hlavni, #2ed573)';
    btn.style.borderColor = 'var(--barva-zelena-hlavni, #2ed573)';
    btn.style.color = '#ffffff';

    clearTimeout(btn._resetTimeout);
    btn._resetTimeout = setTimeout(() => {
      btn.innerHTML = puvodniText;
      btn.style.backgroundColor = '';
      btn.style.borderColor = '';
      btn.style.color = '';
    }, 1800);
  }
}
window.pridatDoKosikuZeSuroviny = pridatDoKosikuZeSuroviny;

// Funkce pro odebrani jednoho kusu pizzy z karty (kliknuti na odznak s minusem)
function odebratJednuPizzu(idPizzy, event = null) {
  if (event) {
    event.stopPropagation();
  }
  const index = kosik.findIndex(item => item && item.pizza && String(item.pizza.id) === String(idPizzy));
  if (index === -1) return;

  if (kosik[index].pocet > 1) {
    kosik[index].pocet -= 1;
  } else {
    kosik.splice(index, 1);
  }

  ulozitKosikDoStorage();
  aktualizovatVzhledKosiku(idPizzy);
  renderovatObsahKosiku();
}
window.odebratJednuPizzu = odebratJednuPizzu;

// Funkce pro pridani jednoho kusu polozky v modalu kosiku
function pridatJedenKusVKosiku(index) {
  if (!kosik[index]) return;
  kosik[index].pocet += 1;
  const idPizzy = kosik[index].pizza ? kosik[index].pizza.id : null;
  ulozitKosikDoStorage();
  aktualizovatVzhledKosiku(idPizzy);
  renderovatObsahKosiku();
}
window.pridatJedenKusVKosiku = pridatJedenKusVKosiku;

// Funkce pro odebrani z kosiku v modalu
function odebratZKosiku(index) {
  if (!kosik[index]) return;
  const idPizzy = kosik[index].pizza ? kosik[index].pizza.id : null;
  if (kosik[index].pocet > 1) {
    kosik[index].pocet -= 1;
  } else {
    kosik.splice(index, 1);
  }
  ulozitKosikDoStorage();
  aktualizovatVzhledKosiku(idPizzy);
  renderovatObsahKosiku();
}
window.odebratZKosiku = odebratZKosiku;

// Zjištění počtu dané pizzy v košíku
function ziskatPocetPizzyVKosiku(idPizzy) {
  if (!Array.isArray(kosik)) return 0;
  const polozka = kosik.find(item => item && item.pizza && String(item.pizza.id) === String(idPizzy));
  return polozka ? (parseInt(polozka.pocet, 10) || 0) : 0;
}
window.ziskatPocetPizzyVKosiku = ziskatPocetPizzyVKosiku;

// Aktualizace čísla s počtem kusů v košíku u tlačítek plus na kartách pizz
function aktualizovatPocitadlaPizzNaKartach(zvyraznitId = null) {
  const odznaky = document.querySelectorAll('.pocet-v-kosiku-odznak');
  odznaky.forEach(odznak => {
    const idPizzy = odznak.getAttribute('data-id');
    if (!idPizzy) return;
    const pocet = ziskatPocetPizzyVKosiku(idPizzy);
    let cisloPrvek = odznak.querySelector('.pocet-cislo-text');

    if (!cisloPrvek) {
      odznak.innerHTML = `<span class="pocet-cislo-text"></span><span class="pocet-minus-ikona">−</span>`;
      cisloPrvek = odznak.querySelector('.pocet-cislo-text');
    }

    if (pocet > 0) {
      cisloPrvek.textContent = pocet;
      odznak.classList.add('viditelny');
      if (zvyraznitId && String(idPizzy) === String(zvyraznitId)) {
        odznak.classList.remove('pocet-pop');
        void odznak.offsetWidth;
        odznak.classList.add('pocet-pop');
      }
    } else {
      cisloPrvek.textContent = '';
      odznak.classList.remove('viditelny', 'pocet-pop');
    }
  });
}

// Aktualizace pocitadla kosiku (zobrazí se teprve po přidání první položky)
function aktualizovatVzhledKosiku(zvyraznitId = null) {
  const celkovyPocet = kosik.reduce((sum, item) => sum + item.pocet, 0);
  const pocitadlo = document.getElementById('pocitadlo-kosiku-cislo');
  const plovouciKosik = document.querySelector('.plovuci-kosik-tlacitko');
  if (pocitadlo) {
    pocitadlo.textContent = celkovyPocet;
  }
  if (plovouciKosik) {
    if (celkovyPocet > 0) {
      plovouciKosik.classList.add('viditelny');
      plovouciKosik.style.display = 'flex';
      plovouciKosik.classList.add('kosik-poskoceni');
      setTimeout(() => {
        plovouciKosik.classList.remove('kosik-poskoceni');
      }, 400);
    } else {
      plovouciKosik.classList.remove('viditelny');
      plovouciKosik.style.display = 'none';
    }
  }
  aktualizovatPocitadlaPizzNaKartach(zvyraznitId);
}

// Funkce pro živé vyhledávání obcí pro rozvoz v nákupním seznamu
function hledatRozvozVKosiku(dotaz) {
  const kontejner = document.getElementById('kosik-vysledky-obci');
  if (!kontejner) return;

  const cisty = (dotaz || '').toLowerCase().trim().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
  if (!cisty) {
    kontejner.style.display = 'none';
    kontejner.innerHTML = '';
    return;
  }

  const nalezene = SEZNAM_ROZVOZOVYCH_OBCI.filter(item => {
    const obecNorm = item.obec.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    return obecNorm.includes(cisty);
  });

  if (nalezene.length === 0) {
    kontejner.innerHTML = '<span style="color: #888; font-size: 0.85rem; padding: 6px 0; grid-column: 1/-1; text-align: center;">Žádná obec neodpovídá zadání.</span>';
    kontejner.style.display = 'flex';
    return;
  }

  kontejner.innerHTML = nalezene.map(item => `
    <span class="stitek-obec-polozka shoda" onclick="vybratRozvozVKosiku('${item.obec.replace(/'/g, "\\'")}', '${item.vzdalenost}', ${item.cena})" style="cursor: pointer; margin: 3px;" title="Zvolit ${item.obec} pro rozvoz">
      <strong>${item.obec}</strong>
      <span class="stitek-vzdalenost">${item.vzdalenost}</span>
      <span class="cena-obce-stitek">+${item.cena} Kč</span>
    </span>
  `).join('');
  kontejner.style.display = 'flex';
  posunoutRozvozKosikuNahoru();
}
window.hledatRozvozVKosiku = hledatRozvozVKosiku;

// --- Mobil: aby klávesnice nezakrývala výsledky vyhledávání obce v nákupním seznamu ---
function jeMobilniKlavesniceKosik() {
  const vstup = document.getElementById('kosik-vyhledat-obec');
  return Boolean(vstup && document.activeElement === vstup && window.innerWidth <= 768 && window.visualViewport);
}

// Přizpůsobí okno nákupního seznamu viditelné ploše nad klávesnicí
function prizpusobitKosikKlavesnici() {
  const pozadi = document.getElementById('modal-kosiku');
  const obsah = pozadi ? pozadi.querySelector('.modalni-okno-obsah') : null;
  if (!pozadi || !obsah) return;

  if (!jeMobilniKlavesniceKosik()) {
    pozadi.style.top = '';
    pozadi.style.height = '';
    pozadi.style.alignItems = '';
    pozadi.style.paddingTop = '';
    obsah.style.maxHeight = '';
    return;
  }

  const vv = window.visualViewport;
  pozadi.style.top = vv.offsetTop + 'px';
  pozadi.style.height = vv.height + 'px';
  pozadi.style.alignItems = 'flex-start';
  pozadi.style.paddingTop = '10px';
  obsah.style.maxHeight = Math.max(200, vv.height - 20) + 'px';
}

// Posune blok rozvozu k hornímu okraji okna, aby výsledky pod polem byly vidět
function posunoutRozvozKosikuNahoru() {
  if (!jeMobilniKlavesniceKosik()) return;
  prizpusobitKosikKlavesnici();
  const pozadi = document.getElementById('modal-kosiku');
  const obsah = pozadi ? pozadi.querySelector('.modalni-okno-obsah') : null;
  const blok = document.getElementById('kosik-rozvoz-blok');
  if (!obsah || !blok) return;
  const posun = blok.getBoundingClientRect().top - obsah.getBoundingClientRect().top + obsah.scrollTop - 8;
  obsah.scrollTop = Math.max(0, posun);
}

document.addEventListener('focusin', (e) => {
  if (e.target && e.target.id === 'kosik-vyhledat-obec') {
    // Počkáme, až se klávesnice vysune
    setTimeout(posunoutRozvozKosikuNahoru, 300);
  }
});

document.addEventListener('focusout', (e) => {
  if (e.target && e.target.id === 'kosik-vyhledat-obec') {
    // Zpoždění, aby se stihl zpracovat klik na vybranou obec
    setTimeout(prizpusobitKosikKlavesnici, 250);
  }
});

if (window.visualViewport) {
  window.visualViewport.addEventListener('resize', () => {
    if (jeMobilniKlavesniceKosik()) {
      posunoutRozvozKosikuNahoru();
    } else {
      prizpusobitKosikKlavesnici();
    }
  });
  window.visualViewport.addEventListener('scroll', () => {
    if (jeMobilniKlavesniceKosik()) {
      prizpusobitKosikKlavesnici();
    }
  });
}

// Výběr konkrétní obce pro rozvoz v košíku
function vybratRozvozVKosiku(obec, vzdalenost, cena) {
  vybranyRozvoz = { obec, vzdalenost, cena: Number(cena) };
  ulozitRozvozDoStorage();

  const vstup = document.getElementById('kosik-vyhledat-obec');
  if (vstup) {
    vstup.value = `${obec} (${vzdalenost}) - ${cena} Kč`;
  }
  const vysledky = document.getElementById('kosik-vysledky-obci');
  if (vysledky) {
    vysledky.style.display = 'none';
  }
  const smazatBtn = document.getElementById('kosik-smazat-rozvoz');
  if (smazatBtn) {
    smazatBtn.style.display = 'block';
  }

  renderovatObsahKosiku();
}
window.vybratRozvozVKosiku = vybratRozvozVKosiku;

// Zrušení vybraného rozvozu
function vymazatRozvozVKosiku() {
  vybranyRozvoz = null;
  ulozitRozvozDoStorage();

  const vstup = document.getElementById('kosik-vyhledat-obec');
  if (vstup) {
    vstup.value = '';
  }
  const vysledky = document.getElementById('kosik-vysledky-obci');
  if (vysledky) {
    vysledky.style.display = 'none';
  }
  const smazatBtn = document.getElementById('kosik-smazat-rozvoz');
  if (smazatBtn) {
    smazatBtn.style.display = 'none';
  }

  renderovatObsahKosiku();
}
window.vymazatRozvozVKosiku = vymazatRozvozVKosiku;

// Telefonní čísla poboček pro přímé volání z nákupního seznamu
const TELEFONY_POBOCEK_KOSIK = {
  rychnov: '739149142',
  usti: '774741818'
};

// Zjištění pobočky, která obsluhuje vybranou obec rozvozu
function ziskatPobockuVybranehoRozvozu() {
  if (!vybranyRozvoz) return null;
  const nalez = SEZNAM_ROZVOZOVYCH_OBCI.find(item => item.obec === vybranyRozvoz.obec);
  return nalez ? nalez.pobocka : null;
}

// Přepnutí vyskakovacího okna s výběrem provozovny pro volání v nákupním seznamu
// (při vybrané obci se rovnou volá pobočka, která obec obsluhuje)
function prepnoutPodoknoVolaniKosik(event) {
  if (event) {
    event.stopPropagation();
  }

  const pobocka = ziskatPobockuVybranehoRozvozu();
  if (pobocka && TELEFONY_POBOCEK_KOSIK[pobocka]) {
    window.location.href = 'tel:' + TELEFONY_POBOCEK_KOSIK[pobocka];
    return;
  }

  const box = document.getElementById('podokno-volani-kosik-box');
  if (box) {
    const jeZobrazen = box.classList.contains('zobrazit') || box.style.display === 'flex';
    if (jeZobrazen) {
      box.classList.remove('zobrazit');
      box.style.display = 'none';
    } else {
      box.classList.add('zobrazit');
      box.style.display = 'flex';
    }
  }
}
window.prepnoutPodoknoVolaniKosik = prepnoutPodoknoVolaniKosik;

// Renderování položek v modálním okně nákupního seznamu (včetně rozvozu a volání)
function renderovatObsahKosiku() {
  const kontejner = document.getElementById('seznam-polozek-kosik-modal');
  const celkovaCenaPrvek = document.getElementById('celkova-cena-kosik-modal');
  const rozvozBlok = document.getElementById('kosik-rozvoz-blok');
  const volaniSekce = document.getElementById('kosik-volani-sekce-obal');
  const vstupRozvoz = document.getElementById('kosik-vyhledat-obec');
  const smazatRozvozBtn = document.getElementById('kosik-smazat-rozvoz');
  const rozvozBadge = document.getElementById('kosik-rozvoz-vybrana-cena-badge');
  if (!kontejner) return;

  if (kosik.length === 0) {
    kontejner.innerHTML = '<p style="color: #888; text-align: center; padding: 25px 0;">Váš nákupní seznam je zatím prázdný.</p>';
    if (celkovaCenaPrvek) celkovaCenaPrvek.textContent = 'Celkem: 0 Kč';
    if (rozvozBlok) rozvozBlok.style.display = 'none';
    if (volaniSekce) volaniSekce.style.display = 'none';
    return;
  }

  if (rozvozBlok) rozvozBlok.style.display = 'block';
  if (volaniSekce) volaniSekce.style.display = 'block';

  let cenaPizz = 0;
  let celkovyPocetKs = 0;
  kontejner.innerHTML = kosik.map((item, index) => {
    const cenaPolozky = item.pizza.cena * item.pocet;
    cenaPizz += cenaPolozky;
    celkovyPocetKs += item.pocet;
    return `
      <div class="polozka-kosiku">
        <div class="polozka-kosiku-info">
          <strong class="polozka-kosiku-nazev">${item.pizza.nazev}</strong>
          <span class="polozka-kosiku-jednotka">(${item.pizza.cena} Kč / ks)</span>
        </div>
        <div class="polozka-kosiku-ovladani">
          <div class="polozka-kosiku-pocitadlo">
            <button type="button" class="tlacitko-kosik-mnozstvi" onclick="odebratZKosiku(${index})" title="Odebrat 1 ks">-</button>
            <span class="polozka-kosiku-pocet-text">${item.pocet} ks</span>
            <button type="button" class="tlacitko-kosik-mnozstvi" onclick="pridatJedenKusVKosiku(${index})" title="Přidat 1 ks">+</button>
          </div>
          <strong class="polozka-kosiku-cena-celkem">${cenaPolozky} Kč</strong>
        </div>
      </div>
    `;
  }).join('');

  // Synchronizace vstupního pole a tlačítek rozvozu
  if (vstupRozvoz && !document.activeElement.isSameNode(vstupRozvoz)) {
    if (vybranyRozvoz) {
      vstupRozvoz.value = `${vybranyRozvoz.obec} (${vybranyRozvoz.vzdalenost}) - ${vybranyRozvoz.cena} Kč`;
    } else {
      vstupRozvoz.value = '';
    }
  }
  if (smazatRozvozBtn) {
    smazatRozvozBtn.style.display = vybranyRozvoz ? 'block' : 'none';
  }
  if (rozvozBadge) {
    if (vybranyRozvoz) {
      rozvozBadge.textContent = `+${vybranyRozvoz.cena} Kč`;
      rozvozBadge.style.display = 'inline-block';
    } else {
      rozvozBadge.style.display = 'none';
    }
  }

  const cenaRozvozu = vybranyRozvoz ? (Number(vybranyRozvoz.cena) || 0) : 0;
  const CENA_KRABICE = 10;
  const cenaKrabic = vybranyRozvoz ? celkovyPocetKs * CENA_KRABICE : 0;
  const celkovaKonecnaCena = cenaPizz + cenaKrabic + cenaRozvozu;

  if (celkovaCenaPrvek) {
    if (vybranyRozvoz) {
      celkovaCenaPrvek.innerHTML = `
        <div class="kosik-rozpis-ceny">
          <div class="kosik-rozpis-polozka">
            <span>Pizzy (${celkovyPocetKs} ks):</span>
            <strong style="color: #ffffff;">${cenaPizz} Kč</strong>
          </div>
          <div class="kosik-rozpis-polozka">
            <span>Krabice (${celkovyPocetKs} × ${CENA_KRABICE} Kč):</span>
            <strong style="color: #10b981;">+${cenaKrabic} Kč</strong>
          </div>
          <div class="kosik-rozpis-polozka">
            <span>Rozvoz (${vybranyRozvoz.obec}):</span>
            <strong style="color: #10b981;">+${cenaRozvozu} Kč</strong>
          </div>
        </div>
        <div class="kosik-celkovy-soucet">
          Celkem: ${celkovaKonecnaCena} Kč
        </div>
      `;
    } else {
      celkovaCenaPrvek.innerHTML = `
        <div class="kosik-celkovy-soucet">
          Celkem: ${celkovaKonecnaCena} Kč
        </div>
      `;
    }
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
    if (idModalu === 'modal-kosiku') {
      const box = document.getElementById('podokno-volani-kosik-box');
      if (box) {
        box.classList.remove('zobrazit');
        box.style.display = 'none';
      }
    }
  }
}
window.otvoritModal = otvoritModal;
window.zavritModal = zavritModal;

// Zavírání libovolného modálního okna prostým kliknutím na pozadí (overlay) a zavření podokna volání
document.addEventListener('click', (e) => {
  if (e.target && e.target.classList && e.target.classList.contains('modalni-okno-pozadi')) {
    e.target.classList.remove('zobrazeno');
  }
  const box = document.getElementById('podokno-volani-kosik-box');
  const btn = e.target ? e.target.closest('#btn-otevrit-volani-kosik') : null;
  if (box && !btn && !e.target.closest('#podokno-volani-kosik-box')) {
    box.classList.remove('zobrazit');
    box.style.display = 'none';
  }
});

// Stav karuselu ingrediencí v modálním okně
let aktivniKaruselIngredienci = {
  suroviny: [],
  aktivniIndex: 0,
  nazevPizzy: ''
};

// Pomocná funkce pro získání všech surovin konkrétní pizzy
function ziskatSurovinyPizzy(pizza) {
  if (!pizza) return [];
  const vsechnySuroviny = window.seznamSurovinZDatabaze || [];
  const nalezene = [];
  const nalezenaId = new Set();

  // 1. Z pole pizza.ingredience
  if (Array.isArray(pizza.ingredience)) {
    pizza.ingredience.forEach(id => {
      const s = vsechnySuroviny.find(sur => sur.id === id);
      if (s && !nalezenaId.has(s.id)) {
        nalezene.push(s);
        nalezenaId.add(s.id);
      }
    });
  }

    // 2. Ze slovního popisu pizza.slozeni (propojí i suroviny, které v poli ingredience chyběly)
  if (pizza.slozeni) {
    const casti = pizza.slozeni.split(',');
    casti.forEach(cast => {
      const trimmed = cast.trim();
      if (!trimmed) return;
      const s = najitSurovinuProNazev(trimmed, pizza);
      if (s && !nalezenaId.has(s.id)) {
        nalezene.push(s);
        nalezenaId.add(s.id);
      }
    });
  }

  // Seřadíme nalezené suroviny přesně podle pořadí v administraci
  const indexMap = new Map();
  vsechnySuroviny.forEach((s, idx) => indexMap.set(s.id, idx));
  nalezene.sort((a, b) => {
    const posA = indexMap.has(a.id) ? indexMap.get(a.id) : 9999;
    const posB = indexMap.has(b.id) ? indexMap.get(b.id) : 9999;
    return posA - posB;
  });

  return nalezene;
}

// Pomocná funkce pro doplnění vlajky k zemi původu, pokud chybí
function zformatovatPuvodSVlajkou(puvod) {
  if (!puvod || typeof puvod !== 'string') return '';
  puvod = puvod.trim();
  if (!puvod) return '';

  if (/\uD83C[\uDDE6-\uDDFF]/.test(puvod) || /\p{Extended_Pictographic}/u.test(puvod)) {
    return puvod;
  }

  const norm = puvod.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');

  if (norm.includes('ital') || norm.includes('neapol') || norm.includes('kampan') || norm.includes('parma') || norm.includes('sicil') || norm.includes('rim')) {
    return '🇮🇹 ' + puvod;
  }
  if (norm.includes('cesk') || norm.includes('morav') || norm.includes('lokal') || norm.includes('cr') || norm.includes('czech')) {
    return '🇨🇿 ' + puvod;
  }
  if (norm.includes('spanel') || norm.includes('esp')) {
    return '🇪🇸 ' + puvod;
  }
  if (norm.includes('franc')) {
    return '🇫🇷 ' + puvod;
  }
  if (norm.includes('reck')) {
    return '🇬🇷 ' + puvod;
  }
  if (norm.includes('nemec') || norm.includes('germ')) {
    return '🇩🇪 ' + puvod;
  }
  if (norm.includes('rakous')) {
    return '🇦🇹 ' + puvod;
  }
  if (norm.includes('sloven')) {
    return '🇸🇰 ' + puvod;
  }
  if (norm.includes('polsk')) {
    return '🇵🇱 ' + puvod;
  }
  if (norm.includes('angli') || norm.includes('brit') || norm.includes('uk')) {
    return '🇬🇧 ' + puvod;
  }
  if (norm.includes('usa') || norm.includes('americ')) {
    return '🇺🇸 ' + puvod;
  }

  return '🇮🇹 ' + puvod;
}

// Vykreslení obsahu modálního okna náhledu ingredience (karusel)
function vykreslitObsahNahleduIngredience(sAnimaci) {
  const telo = document.getElementById('modal-nahled-ingredience-telo');
  if (!telo) return;

  const celkem = aktivniKaruselIngredienci.suroviny.length;
  if (celkem === 0) return;

  if (aktivniKaruselIngredienci.aktivniIndex >= celkem) aktivniKaruselIngredienci.aktivniIndex = 0;
  if (aktivniKaruselIngredienci.aktivniIndex < 0) aktivniKaruselIngredienci.aktivniIndex = celkem - 1;

  const surovina = aktivniKaruselIngredienci.suroviny[aktivniKaruselIngredienci.aktivniIndex];
  if (!surovina) return;

  const index = aktivniKaruselIngredienci.aktivniIndex;
  const nazevPizzy = aktivniKaruselIngredienci.nazevPizzy;

  // Šipky se zobrazí pouze pokud má pizza více než 1 surovinu
  const sipkyHtml = celkem > 1 ? `
    <button type="button" class="nahled-karusel-sipka nahled-karusel-vlevo" onclick="posunoutKaruselIngredienci(-1, event)" aria-label="Předchozí surovina" title="Předchozí surovina (šipka vlevo)">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6"></polyline>
      </svg>
    </button>
    <button type="button" class="nahled-karusel-sipka nahled-karusel-vpravo" onclick="posunoutKaruselIngredienci(1, event)" aria-label="Další surovina" title="Další surovina (šipka vpravo)">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="9 18 15 12 9 6"></polyline>
      </svg>
    </button>
  ` : '';

  // Tečky / indikátor
  const teckyHtml = celkem > 1 ? `
    <div class="nahled-karusel-tecky-obal">
      ${aktivniKaruselIngredienci.suroviny.map((s, i) => `
        <button type="button" class="nahled-karusel-tecka ${i === index ? 'aktivni' : ''}" onclick="nastavitIndexKaruseluIngredienci(${i}, event)" title="${s.nazev}"></button>
      `).join('')}
    </div>
  ` : '';

  // Info lišta pizzy
  const pizzaInfoHtml = nazevPizzy ? `
    <div class="nahled-ingredience-meta-lista">
      <span class="nahled-pizza-nazev-odznak">🍕 ${nazevPizzy}</span>
      ${celkem > 1 ? `<span class="nahled-pocitadlo-text">${index + 1} z ${celkem}</span>` : ''}
    </div>
  ` : (celkem > 1 ? `
    <div class="nahled-ingredience-meta-lista">
      <span class="nahled-pocitadlo-text">${index + 1} z ${celkem}</span>
    </div>
  ` : '');

  telo.innerHTML = `
    <div class="nahled-ingredience-obal ${sAnimaci ? 'animace-prepnuti' : ''}">
      <div class="nahled-ingredience-foto-obal">
        <img src="${surovina.obrazek}" alt="${surovina.nazev}" class="nahled-ingredience-velka-fotka">
        ${surovina.puvod ? `<span class="odznak-puvodu nahled-puvod">${zformatovatPuvodSVlajkou(surovina.puvod)}</span>` : ''}
        ${sipkyHtml}
        ${teckyHtml}
      </div>
      <div class="nahled-ingredience-popis-blok">
        ${pizzaInfoHtml}
        <h2 class="nahled-ingredience-nazev">${surovina.nazev}</h2>
        <p class="nahled-ingredience-popis">${surovina.popis || ''}</p>
      </div>
    </div>
  `;
}

// Otevření velkého náhledu obrázku ingredience s fotkou, názvem a popisem dole (karusel pro konkrétní pizzu)
function otevritNahledIngredience(idSuroviny, e, pizzaId) {
  if (e && e.preventDefault) e.preventDefault();
  if (e && e.stopPropagation) e.stopPropagation();

  const cistyId = decodeURIComponent(idSuroviny);
  const suroviny = window.seznamSurovinZDatabaze || [];
  const pizzy = window.seznamPizzZDatabaze || seznamPizz || [];

  let vybranaPizza = null;
  let seznamSurovinKaruselu = [];

  if (pizzaId) {
    vybranaPizza = pizzy.find(p => p.id == pizzaId);
  }

  if (vybranaPizza) {
    seznamSurovinKaruselu = ziskatSurovinyPizzy(vybranaPizza);
  }

  // Pokud nebyla nalezena pizza nebo suroviny, použijeme všechny suroviny
  if (!seznamSurovinKaruselu || seznamSurovinKaruselu.length === 0) {
    seznamSurovinKaruselu = [...suroviny];
  }

  // Hledání indexu kliknuté suroviny v nalezeném seznamu
  let index = seznamSurovinKaruselu.findIndex(s => s.id === cistyId || (s.id && s.id.toLowerCase() === cistyId.toLowerCase()));
  if (index === -1) {
    const globalniSurovina = suroviny.find(s => s.id === cistyId || (s.id && s.id.toLowerCase() === cistyId.toLowerCase()));
    if (globalniSurovina) {
      seznamSurovinKaruselu.unshift(globalniSurovina);
      index = 0;
    } else {
      index = 0;
    }
  }

  aktivniKaruselIngredienci = {
    suroviny: seznamSurovinKaruselu,
    aktivniIndex: index,
    nazevPizzy: vybranaPizza ? vybranaPizza.nazev : ''
  };

  vykreslitObsahNahleduIngredience(false);
  inicializovatSwipeNahledIngredience();
  otvoritModal('modal-obrazek-ingredience');
}
window.otevritNahledIngredience = otevritNahledIngredience;

function posunoutKaruselIngredienci(smer, e) {
  if (e && e.preventDefault) e.preventDefault();
  if (e && e.stopPropagation) e.stopPropagation();

  const celkem = aktivniKaruselIngredienci.suroviny.length;
  if (celkem <= 1) return;

  aktivniKaruselIngredienci.aktivniIndex = (aktivniKaruselIngredienci.aktivniIndex + smer + celkem) % celkem;
  vykreslitObsahNahleduIngredience(true);
}
window.posunoutKaruselIngredienci = posunoutKaruselIngredienci;

function nastavitIndexKaruseluIngredienci(novyIndex, e) {
  if (e && e.preventDefault) e.preventDefault();
  if (e && e.stopPropagation) e.stopPropagation();

  const celkem = aktivniKaruselIngredienci.suroviny.length;
  if (novyIndex < 0 || novyIndex >= celkem) return;

  aktivniKaruselIngredienci.aktivniIndex = novyIndex;
  vykreslitObsahNahleduIngredience(true);
}
window.nastavitIndexKaruseluIngredienci = nastavitIndexKaruseluIngredienci;

// Inicializace touch swipe pro mobilní zařízení
let touchStartXIngredience = 0;
let touchStartYIngredience = 0;

function inicializovatSwipeNahledIngredience() {
  const modalOkno = document.getElementById('modal-obrazek-ingredience');
  if (!modalOkno || modalOkno.dataset.swipeAktivni) return;
  modalOkno.dataset.swipeAktivni = 'true';

  modalOkno.addEventListener('touchstart', (e) => {
    if (e.touches && e.touches.length === 1) {
      touchStartXIngredience = e.touches[0].clientX;
      touchStartYIngredience = e.touches[0].clientY;
    }
  }, { passive: true });

  modalOkno.addEventListener('touchend', (e) => {
    if (e.changedTouches && e.changedTouches.length === 1) {
      const touchEndX = e.changedTouches[0].clientX;
      const touchEndY = e.changedTouches[0].clientY;
      const diffX = touchEndX - touchStartXIngredience;
      const diffY = touchEndY - touchStartYIngredience;

      // Horizontální swipe (více než 35px a znatelně vodorovnější než svislý pohyb)
      if (Math.abs(diffX) > 35 && Math.abs(diffX) > Math.abs(diffY) * 1.3) {
        if (diffX < 0) {
          // Swipe vlevo -> další surovina
          posunoutKaruselIngredienci(1);
        } else {
          // Swipe vpravo -> předchozí surovina
          posunoutKaruselIngredienci(-1);
        }
      }
    }
  }, { passive: true });
}

// Klávesová navigace: Escape zavírá, šipky vlevo/vpravo listují karuselem
window.addEventListener('keydown', (e) => {
  const modal = document.getElementById('modal-obrazek-ingredience');
  const jeOtevren = modal && modal.classList.contains('zobrazeno');

  if (e.key === 'Escape') {
    document.querySelectorAll('.modalni-okno-pozadi.zobrazeno').forEach(m => m.classList.remove('zobrazeno'));
  } else if (jeOtevren && (e.key === 'ArrowLeft' || e.key === 'ArrowUp')) {
    e.preventDefault();
    posunoutKaruselIngredienci(-1);
  } else if (jeOtevren && (e.key === 'ArrowRight' || e.key === 'ArrowDown')) {
    e.preventDefault();
    posunoutKaruselIngredienci(1);
  }
});

// Stav karuselu pro provozovny
const stavKaruselu = {
  'karusel-rychnov': { aktualniIndex: 0, celkem: 2 },
  'karusel-usti': { aktualniIndex: 0, celkem: 2 }
};

// Funkce pro posun karuselu pomoci sipek
function posunoutKarusel(idKaruselu, smer) {
  const container = document.getElementById(idKaruselu);
  const snimky = container ? container.querySelectorAll('.karusel-snimek') : [];
  const pocetSnimku = snimky.length || 2;

  if (!stavKaruselu[idKaruselu]) {
    stavKaruselu[idKaruselu] = { aktualniIndex: 0, celkem: pocetSnimku };
  }
  stavKaruselu[idKaruselu].celkem = pocetSnimku;
  
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
  const container = document.getElementById(idKaruselu);
  const snimky = container ? container.querySelectorAll('.karusel-snimek') : [];
  const pocetSnimku = snimky.length || 2;

  if (!stavKaruselu[idKaruselu]) {
    stavKaruselu[idKaruselu] = { aktualniIndex: 0, celkem: pocetSnimku };
  }
  stavKaruselu[idKaruselu].celkem = pocetSnimku;
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
  let byloZaskrolovano = false;
  let listaBylaSkryta = false;
  const minimalniPosunProReakci = 6;

  // Kešování elementů pro scroll listener (eliminace zbytečných DOM dotazů při každém ticku)
  const horniLista = document.querySelector('.horni-kontakty-lista');
  const menuOdkazy = document.getElementById('navigace-odkazy-menu');
  const btnMenuMobil = document.getElementById('btn-mobil-menu');
  const boxVolani = document.getElementById('podokno-volani-box');
  const btnVolaniOtevrit = document.getElementById('btn-otevrit-volani');

  function obslouzitScroll() {
    const soucasnyScrollY = window.pageYOffset || document.documentElement.scrollTop || 0;

    if (!horniLista) {
      ticking = false;
      return;
    }

    const jeZaskrolovano = soucasnyScrollY > 80;
    if (byloZaskrolovano !== jeZaskrolovano) {
      byloZaskrolovano = jeZaskrolovano;
      horniLista.classList.toggle('skrolovalo-se', jeZaskrolovano);
    }

    // Jsme-li úplně nahoře, lišta je vždy viditelná
    if (soucasnyScrollY <= 20) {
      if (listaBylaSkryta) {
        horniLista.classList.remove('lista-skryta');
        listaBylaSkryta = false;
      }
      posledniScrollY = soucasnyScrollY;
      ticking = false;
      return;
    }

    const rozdil = soucasnyScrollY - posledniScrollY;

    if (Math.abs(rozdil) >= minimalniPosunProReakci) {
      if (rozdil > 0) {
        // Scroll DOWN -> Skrýt lištu
        if (!listaBylaSkryta) {
          horniLista.classList.add('lista-skryta');
          listaBylaSkryta = true;
        }
        
        // Pokud je otevřené mobilní menu nebo telefonní okénko, zavřeme je
        if (menuOdkazy && menuOdkazy.classList.contains('mobil-otevreno')) {
          menuOdkazy.classList.remove('mobil-otevreno');
          if (btnMenuMobil) btnMenuMobil.classList.remove('aktivni');
        }

        if (boxVolani && boxVolani.classList.contains('zobrazit')) {
          boxVolani.classList.remove('zobrazit');
          if (btnVolaniOtevrit) btnVolaniOtevrit.classList.remove('aktivni');
        }
      } else {
        // Scroll UP -> Zobrazit lištu
        if (listaBylaSkryta) {
          horniLista.classList.remove('lista-skryta');
          listaBylaSkryta = false;
        }
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
}
window.prepnoutPobocku = prepnoutPobocku;

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
    // Aktualizovat i karusel vybrané provozovny
    if (typeof aktualizovatZobrazenySnimek === 'function') {
      aktualizovatZobrazenySnimek('karusel-' + vybranaPobocka);
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
window.aktualizovatZobrazeniPobocky = aktualizovatZobrazeniPobocky;

// 6. OBSLUHA VYSKAKOVACÍHO PODOKNA VOLÁNÍ V PODSTRÁNKOVÉ HLAVIČCE
function prepnoutPodoknoVolani(event) {
  if (event) {
    event.stopPropagation();
  }
  const box = document.getElementById('podokno-volani-box');
  const btn = document.getElementById('btn-otevrit-volani');
  const btnAppka = document.getElementById('btn-appka-volani');
  if (box) {
    box.classList.toggle('zobrazit');
    if (btn) btn.classList.toggle('aktivni', box.classList.contains('zobrazit'));
    if (btnAppka) btnAppka.classList.toggle('aktivni', box.classList.contains('zobrazit'));
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
  const btnAppka = document.getElementById('btn-appka-volani');
  if (box && box.classList.contains('zobrazit')) {
    if (!box.contains(e.target) && e.target !== btnVolani && (!btnVolani || !btnVolani.contains(e.target)) && e.target !== btnAppka && (!btnAppka || !btnAppka.contains(e.target))) {
      box.classList.remove('zobrazit');
      if (btnVolani) btnVolani.classList.remove('aktivni');
      if (btnAppka) btnAppka.classList.remove('aktivni');
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

// Tlačítko UKÁZAT NA MAPĚ v sekci Provozovny (otevře mapy.com v novém okně)
function ukazatNaMapeProvozovnu(pobocka) {
  let url = 'https://mapy.com/';
  if (pobocka === 'rychnov') {
    url = 'https://mapy.com/s/hecazafoju';
  } else if (pobocka === 'usti') {
    url = 'https://mapy.com/s/jomatotube';
  }
  window.open(url, '_blank', 'noopener,noreferrer');
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





