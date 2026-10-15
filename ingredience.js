/* ==========================================================================
   LOGIKA PRO STRÁNKU INGREDIENCE (JS/INGREDIENCE.JS)
   Všechny názvy proměnných, funkcí a komentáře jsou v češtině bez diakritiky
   ========================================================================== */

// Data surovin a pizz s příběhem, původem a propojením (Načítají se z databáze)
const seznamSurovin = window.seznamSurovinZDatabaze || [];
let aktivniKategorieSurovin = 'vse';

document.addEventListener('DOMContentLoaded', () => {
  renderovatIngredience();
  nastavitFiltrySurovin();
  posunoutNaIngredienciPodleHash();
});

window.addEventListener('hashchange', () => {
  posunoutNaIngredienciPodleHash();
});

function posunoutNaIngredienciPodleHash() {
  const hash = window.location.hash;
  if (!hash) return;
  const cistyId = decodeURIComponent(hash.replace(/^#/, ''));
  let cilPrvek = document.getElementById(cistyId) || document.getElementById(`ingredience-${cistyId}`);

  if (!cilPrvek && aktivniKategorieSurovin !== 'vse') {
    aktivniKategorieSurovin = 'vse';
    const tlacitka = document.querySelectorAll('.tlacitko-kategorie-surovin');
    tlacitka.forEach(b => {
      if (b.getAttribute('data-kategorie') === 'vse') b.classList.add('aktivni');
      else b.classList.remove('aktivni');
    });
    renderovatIngredience();
    cilPrvek = document.getElementById(cistyId) || document.getElementById(`ingredience-${cistyId}`);
  }

  if (cilPrvek) {
    setTimeout(() => {
      cilPrvek.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 120);
  }
}

function renderovatIngredience() {
  const mrizka = document.getElementById('mrizka-ingredienci-kontejner');
  if (!mrizka) return;

  const vsetkyPizzy = window.seznamPizzZDatabaze || [];

  const filtrovane = seznamSurovin.filter(surovina => {
    if (aktivniKategorieSurovin === 'vse') return true;
    return surovina.kategorie === aktivniKategorieSurovin;
  });

  mrizka.innerHTML = filtrovane.map(s => {
    // Dynamické nalezení pizz pro danou surovinu
    const pizzySeSurovinou = vsetkyPizzy.filter(p => p.ingredience && p.ingredience.includes(s.id));
    const pocetPizz = pizzySeSurovinou.length;

    return `
      <div class="karta-ingredience" id="ingredience-${s.id}" onclick="otevritNahledIngredience('${s.id}')" title="Klikněte pro zobrazení velkého náhledu">
        <div class="fotka-ingredience-obal">
          <img src="${s.obrazek}" alt="${s.nazev}" loading="lazy">
          <span class="odznak-puvodu">${s.puvod}</span>
        </div>
        <div class="obsah-ingredience">
          <h3 class="nazev-ingredience">${s.nazev}</h3>
          <p class="popis-ingredience">${s.popis}</p>
          <button class="tlacitko-pizzy-surovina" onclick="event.stopPropagation(); zobrazitPizzySeSurovinou('${s.id}')">
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
        <button type="button" class="tlacitko tlacitko-cervene tlacitko-pridat-ze-suroviny" onclick="pridatDoKosikuZeSuroviny(${pizza.id}, this)" style="padding: 6px 15px; font-size: 0.85rem; flex-shrink: 0; cursor: pointer; border: none;">Přidat do seznamu</button>
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
window.zavritModalSuroviny = zavritModalSuroviny;

// Stav karuselu ingrediencí na stránce ingrediencí
let aktivniKaruselIngredienci = {
  suroviny: [],
  aktivniIndex: 0
};

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

  const teckyHtml = celkem > 1 ? `
    <div class="nahled-karusel-tecky-obal">
      ${aktivniKaruselIngredienci.suroviny.map((s, i) => `
        <button type="button" class="nahled-karusel-tecka ${i === index ? 'aktivni' : ''}" onclick="nastavitIndexKaruseluIngredienci(${i}, event)" title="${s.nazev}"></button>
      `).join('')}
    </div>
  ` : '';

  const infoListaHtml = celkem > 1 ? `
    <div class="nahled-ingredience-meta-lista">
      <span class="nahled-pizza-nazev-odznak">🌱 Suroviny Pizza od Kuřete</span>
      <span class="nahled-pocitadlo-text">${index + 1} z ${celkem}</span>
    </div>
  ` : '';

  telo.innerHTML = `
    <div class="nahled-ingredience-obal ${sAnimaci ? 'animace-prepnuti' : ''}">
      <div class="nahled-ingredience-foto-obal">
        <img src="${surovina.obrazek}" alt="${surovina.nazev}" class="nahled-ingredience-velka-fotka">
        ${surovina.puvod ? `<span class="odznak-puvodu nahled-puvod">${surovina.puvod}</span>` : ''}
        ${sipkyHtml}
        ${teckyHtml}
      </div>
      <div class="nahled-ingredience-popis-blok">
        ${infoListaHtml}
        <h2 class="nahled-ingredience-nazev">${surovina.nazev}</h2>
        <p class="nahled-ingredience-popis">${surovina.popis || ''}</p>
      </div>
    </div>
  `;
}

function otevritNahledIngredience(idSuroviny) {
  const filtrovane = seznamSurovin.filter(s => {
    if (aktivniKategorieSurovin === 'vse') return true;
    return s.kategorie === aktivniKategorieSurovin;
  });
  const aktivniSeznam = filtrovane.length > 0 ? filtrovane : seznamSurovin;
  let index = aktivniSeznam.findIndex(s => s.id === idSuroviny);
  if (index === -1) index = 0;

  aktivniKaruselIngredienci = {
    suroviny: aktivniSeznam,
    aktivniIndex: index
  };

  vykreslitObsahNahleduIngredience(false);
  inicializovatSwipeNahledIngredience();

  const modal = document.getElementById('modal-obrazek-ingredience');
  if (modal) modal.classList.add('zobrazeno');
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

function zavritModalObrazkuIngredience() {
  const modal = document.getElementById('modal-obrazek-ingredience');
  if (modal) modal.classList.remove('zobrazeno');
}
window.zavritModalObrazkuIngredience = zavritModalObrazkuIngredience;

// Zavírání libovolného modálního okna prostým kliknutím na pozadí (overlay)
document.addEventListener('click', (e) => {
  if (e.target && e.target.classList && e.target.classList.contains('modalni-okno-pozadi')) {
    e.target.classList.remove('zobrazeno');
  }
});

// Touch swipe pro mobilní zařízení na stránce ingrediencí
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

      if (Math.abs(diffX) > 35 && Math.abs(diffX) > Math.abs(diffY) * 1.3) {
        if (diffX < 0) {
          posunoutKaruselIngredienci(1);
        } else {
          posunoutKaruselIngredienci(-1);
        }
      }
    }
  }, { passive: true });
}

// Zavírání modálních oken klávesou Escape a šipky pro listování
window.addEventListener('keydown', (e) => {
  const modal = document.getElementById('modal-obrazek-ingredience');
  const jeOtevren = modal && modal.classList.contains('zobrazeno');

  if (e.key === 'Escape') {
    zavritModalSuroviny();
    zavritModalObrazkuIngredience();
  } else if (jeOtevren && (e.key === 'ArrowLeft' || e.key === 'ArrowUp')) {
    e.preventDefault();
    posunoutKaruselIngredienci(-1);
  } else if (jeOtevren && (e.key === 'ArrowRight' || e.key === 'ArrowDown')) {
    e.preventDefault();
    posunoutKaruselIngredienci(1);
  }
});
