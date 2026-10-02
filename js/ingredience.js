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
    // Dynamické nalezení pizz pro danou surovinu
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
