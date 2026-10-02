/* ==========================================================================
   LOGIKA PRO STRANKU INGREDIENCE (INGREDIENCE.HTML)
   Vsechny nazvy promennych, funkci a komentare jsou v cestine bez diakritiky
   ========================================================================== */

// Data surovin a pizz s pribehem, puvodem a propojenim (Nacitaji se z databaze.json)
const seznamSurovin = window.seznamSurovinZDatabaze || [
  {
    id: 'sugomarzano',
    nazev: 'Rajcata San Marzano D.O.P.',
    kategorie: 'omacky',
    puvod: '🇮🇹 Neapol, Italie',
    popis: 'Pravá italská rajčata pěstovaná na úrodné vulkanické půdě v podhůří Vesuvu. Mají přirozeně sladkou chuť, nízkou kyselost a hustou dužinu, což vytváří dokonalý základ pro naše sugo.',
    alergeny: 'Bez alergenů',
    obrazek: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?q=80&w=600&auto=format&fit=crop',
    pizzy: ['MARGHERITA', 'PROSCIUTTO E FUNGHI', 'DIAVOLA CHILI', 'HAWAI SPECIAL']
  },
  {
    id: 'mozzarella',
    nazev: 'Mozzarella di Bufala',
    kategorie: 'syry',
    puvod: '🇮🇹 Kampánie, Italie',
    popis: 'Tradiční čerstvý sýr z buvolího mléka s chráněným označením původu (D.O.P.). Je neuvěřitelně krémový, šťavnatý a při pečení v peci vytvoří jemně rozteklou zlatavou vrstvu.',
    alergeny: 'Alergen 7 (mléko)',
    obrazek: 'https://images.unsplash.com/photo-1559561853-08451507cbe7?q=80&w=600&auto=format&fit=crop',
    pizzy: ['MARGHERITA', 'PROSCIUTTO E FUNGHI', 'QUATTRO FORMAGGI', 'DIAVOLA CHILI', 'POLLO E SPINACI', 'HAWAI SPECIAL']
  },
  {
    id: 'kurecimaso',
    nazev: 'Šťavnaté Kuřecí Maso',
    kategorie: 'maso',
    puvod: '🇨🇿 Lokální české farmy',
    popis: 'Čerstvá kuřecí prsa dodávaná každé ráno od prověřených regionálních farmářů. Maso marinujeme v olivovém oleji a čerstvých bylinkách pro maximální křehkost a šťavnatost.',
    alergeny: 'Bez alergenů',
    obrazek: 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?q=80&w=600&auto=format&fit=crop',
    pizzy: ['POLLO E SPINACI']
  },
  {
    id: 'moukacaputo',
    nazev: 'Italská Mouka Caputo 00',
    kategorie: 'testo',
    puvod: '🇮🇹 Neapol, Italie',
    popis: 'Špičková pšeničná mouka mletá z nejkvalitnějších zrn. Naše těsto kyne a zraje minimálně 48 hodin. Výsledkem je lehká, snadno stravitelná pizza s nadýchaným a křupavým okrajem.',
    alergeny: 'Alergen 1 (lepek)',
    obrazek: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=600&auto=format&fit=crop',
    pizzy: ['MARGHERITA', 'PROSCIUTTO E FUNGHI', 'QUATTRO FORMAGGI', 'DIAVOLA CHILI', 'POLLO E SPINACI', 'HAWAI SPECIAL']
  },
  {
    id: 'chorizospanelske',
    nazev: 'Spaniol & Italské Chorizo',
    kategorie: 'maso',
    puvod: '🇪🇸 Španělsko / 🇮🇹 Itálie',
    popis: 'Tradiční pikantní klobása sušená na vzduchu, ochucená uzenou sladkou i pálivou paprikou. Při pečení uvolňuje aromatický červený olej, který dodává pizze neodolatelný říz.',
    alergeny: 'Bez alergenů',
    obrazek: 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=600&auto=format&fit=crop',
    pizzy: ['DIAVOLA CHILI']
  },
  {
    id: 'spinacbazalka',
    nazev: 'Čerstvá Bazalka a Špenát',
    kategorie: 'zelenina',
    puvod: '🇨🇿 Čerstvá sklioze',
    popis: 'Lístky čerstvé bazalky přidávané až po upečení pro zachování výrazného esenciálního aroma. Špenátové listy jsou jemně spařené s česnekem.',
    alergeny: 'Bez alergenů',
    obrazek: 'https://images.unsplash.com/photo-1608683134044-22c976a238bb?q=80&w=600&auto=format&fit=crop',
    pizzy: ['MARGHERITA', 'POLLO E SPINACI']
  }
];

let aktivniKategorieSurovin = 'vse';

document.addEventListener('DOMContentLoaded', () => {
  renderovatIngredience();
  nastavitFiltrySurovin();
});

function renderovatIngredience() {
  const mrizka = document.getElementById('mrizka-ingredienci-kontejner');
  if (!mrizka) return;

  const vsetkyPizzy = window.seznamPizzZDatabaze || [];

  const filtrovane = seznamSurovin.filter(surovina => {
    if (aktivniKategorieSurovin === 'vse') return true;
    return surovina.kategorie === aktivniKategorieSurovin;
  });

  mrizka.innerHTML = filtrovane.map(s => {
    // Dynamicke nalezeni pizz pro danou surovinu
    const pizzySeSurovinou = vsetkyPizzy.filter(p => p.ingredience && p.ingredience.includes(s.id));
    const pocetPizz = pizzySeSurovinou.length;

    return `
      <div class="karta-ingredience">
        <div class="fotka-ingredience-obal">
          <img src="${s.obrazek}" alt="${s.nazev}" loading="lazy">
          <span class="odznak-puvodu">${s.puvod}</span>
        </div>
        <div class="obsah-ingredience">
          <h3 class="nazev-ingredience">${s.nazev}</h3>
          <p class="popis-ingredience">${s.popis}</p>
          <span class="stitek-alergen-maly">${s.alergeny}</span>
          <button class="tlacitko-pizzy-surovina" onclick="zobrazitPizzySeSurovinou('${s.id}')">
            V jakých pizzách ji ochutnáte? (${pocetPizz})
          </button>
        </div>
      </div>
    `;
  }).join('');
}

function nastavitFiltrySurovin() {
  const tlacitka = document.querySelectorAll('.tlacitko-kategorie-surovin');
  tlacitka.forEach(btn => {
    btn.addEventListener('click', (e) => {
      tlacitka.forEach(b => b.classList.remove('aktivni'));
      e.target.classList.add('aktivni');
      aktivniKategorieSurovin = e.target.getAttribute('data-kategorie');
      renderovatIngredience();
    });
  });
}

function zobrazitPizzySeSurovinou(idSuroviny) {
  const surovina = seznamSurovin.find(s => s.id === idSuroviny);
  if (!surovina) return;

  const vsetkyPizzy = window.seznamPizzZDatabaze || [];
  const nalezenePizzy = vsetkyPizzy.filter(p => p.ingredience && p.ingredience.includes(idSuroviny));

  let seznamHTML = '';
  if (nalezenePizzy.length === 0) {
    seznamHTML = '<p style="color: #888;">Zatím žádná pizza v menu neobsahuje tuto surovinu.</p>';
  } else {
    seznamHTML = nalezenePizzy.map(pizza => `
      <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #333;">
        <div>
          <strong style="color: #fff; font-size: 1.1rem;">🍕 ${pizza.nazev} (${pizza.cena} Kč)</strong><br>
          <small style="color: #aaa;">${pizza.slozeni}</small>
        </div>
        <a href="index.php#jidelni-listek" class="tlacitko tlacitko-cervene" style="padding: 6px 15px; font-size: 0.85rem;">Objednat na menu</a>
      </div>
    `).join('');
  }

  const modalObsah = document.getElementById('modal-detail-suroviny-obsah');
  if (modalObsah) {
    modalObsah.innerHTML = `
      <h2 class="nadpis-sekce" style="font-size: 1.6rem; color: #f59e0b;">${surovina.nazev}</h2>
      <p style="color: #ccc; margin-bottom: 20px;">Tuto poctivou surovinu najdete v následujících pizzách z našeho jídelníčku:</p>
      <div>${seznamHTML}</div>
    `;
    const modal = document.getElementById('modal-detail-suroviny');
    if (modal) modal.classList.add('zobrazeno');
  }
}

function zavritModalSuroviny() {
  const modal = document.getElementById('modal-detail-suroviny');
  if (modal) modal.classList.remove('zobrazeno');
}

