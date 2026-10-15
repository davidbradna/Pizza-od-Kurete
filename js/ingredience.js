/* ==========================================================================
   LOGIKA PRO STRÁNKU INGREDIENCE (JS/INGREDIENCE.JS)
   Všechny funkce a logika jsou bezpečně zapouzdřeny, aby nekolidovaly s aplikaci.
   ========================================================================== */

(function () {
  'use strict';

  // 1. FILTROVÁNÍ KARET INGREDIENCÍ PODLE KATEGORIE
  function filtrovatKategorii(kategorie) {
    const karty = document.querySelectorAll('.mrizka-ingredienci .karta-ingredience');
    const katCisty = (kategorie || 'vse').toLowerCase().trim()
      .normalize('NFD').replace(/[\u0300-\u036f]/g, '');

    let pocetViditelnych = 0;

    karty.forEach(karta => {
      const kartaKat = (karta.getAttribute('data-kategorie') || '').toLowerCase().trim()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '');

      let viditelna = false;
      if (katCisty === 'vse') {
        viditelna = true;
      } else if (kartaKat === katCisty) {
        viditelna = true;
      } else if (kartaKat.includes(katCisty) || katCisty.includes(kartaKat)) {
        viditelna = true;
      } else if (katCisty === 'syry' && kartaKat.includes('syr')) {
        viditelna = true;
      } else if (katCisty === 'maso' && (kartaKat.includes('mas') || kartaKat.includes('uzen'))) {
        viditelna = true;
      } else if (katCisty === 'zelenina' && (kartaKat.includes('zelen') || kartaKat.includes('bylin'))) {
        viditelna = true;
      } else if (katCisty === 'omacky' && (kartaKat.includes('omac') || kartaKat.includes('test'))) {
        viditelna = true;
      } else if (katCisty === 'specialni' && (kartaKat.includes('spec') || kartaKat.includes('ostat'))) {
        viditelna = true;
      }

      karta.style.display = viditelna ? '' : 'none';
      if (viditelna) pocetViditelnych++;
    });

    let prazdnaZprava = document.getElementById('ingredience-prazdna-zprava');
    const mrizka = document.getElementById('mrizka-ingredienci-kontejner');
    if (pocetViditelnych === 0) {
      if (!prazdnaZprava && mrizka) {
        prazdnaZprava = document.createElement('div');
        prazdnaZprava.id = 'ingredience-prazdna-zprava';
        prazdnaZprava.style.cssText = 'grid-column: 1/-1; text-align: center; padding: 40px; color: #888;';
        prazdnaZprava.innerHTML = '<h3>V této kategorii zatím nejsou žádné ingredience.</h3>';
        mrizka.appendChild(prazdnaZprava);
      }
      if (prazdnaZprava) prazdnaZprava.style.display = '';
    } else if (prazdnaZprava) {
      prazdnaZprava.style.display = 'none';
    }
  }

  // 2. NASTAVENÍ POSLUCHAČŮ PRO TLAČÍTKA ZÁLOŽEK KATEGORIÍ (A SKRYTÍ PRÁZDNÝCH)
  function nastavitFiltry() {
    const tlacitka = document.querySelectorAll('.ingredience-zalozka-tlacitko, .tlacitko-kategorie-surovin');
    const karty = document.querySelectorAll('.mrizka-ingredienci .karta-ingredience');

    // Skrýt záložky bez položek
    tlacitka.forEach(btn => {
      const kat = btn.getAttribute('data-kategorie') || 'syry';
      const katCisty = kat.toLowerCase().trim().normalize('NFD').replace(/[\u0300-\u036f]/g, '');

      let maPolozky = false;
      karty.forEach(karta => {
        const kartaKat = (karta.getAttribute('data-kategorie') || '').toLowerCase().trim().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        if (kartaKat === katCisty || kartaKat.includes(katCisty) || katCisty.includes(kartaKat) ||
           (katCisty === 'syry' && kartaKat.includes('syr')) ||
           (katCisty === 'maso' && (kartaKat.includes('mas') || kartaKat.includes('uzen'))) ||
           (katCisty === 'zelenina' && (kartaKat.includes('zelen') || kartaKat.includes('bylin'))) ||
           (katCisty === 'omacky' && (kartaKat.includes('omac') || kartaKat.includes('test'))) ||
           (katCisty === 'specialni' && (kartaKat.includes('spec') || kartaKat.includes('ostat')))) {
          maPolozky = true;
        }
      });

      if (!maPolozky) {
        btn.style.display = 'none';
        btn.classList.remove('aktivni');
      } else {
        btn.style.display = '';
      }
    });

    tlacitka.forEach(btn => {
      btn.onclick = function (e) {
        if (e && e.preventDefault) e.preventDefault();
        tlacitka.forEach(b => b.classList.remove('aktivni'));
        this.classList.add('aktivni');
        const kat = this.getAttribute('data-kategorie') || 'syry';
        filtrovatKategorii(kat);
      };
    });

    let aktivniBtn = document.querySelector('.ingredience-zalozka-tlacitko.aktivni:not([style*="display: none"])');
    if (!aktivniBtn) {
      aktivniBtn = document.querySelector('.ingredience-zalozka-tlacitko:not([style*="display: none"])');
      if (aktivniBtn) {
        tlacitka.forEach(b => b.classList.remove('aktivni'));
        aktivniBtn.classList.add('aktivni');
      }
    }

    if (aktivniBtn) {
      const kat = aktivniBtn.getAttribute('data-kategorie') || 'syry';
      filtrovatKategorii(kat);
    }
  }

  // 3. OTEVŘENÍ MODÁLNÍHO OKNA SE SEZNAMEM PIZZ PRO DANOU SUROVINU
  window.otevritModalPizzSuroviny = function (btn, event) {
    if (event) {
      if (event.stopPropagation) event.stopPropagation();
      if (event.preventDefault) event.preventDefault();
    }
    const karta = btn ? btn.closest('.karta-ingredience') : null;
    const obsahData = karta ? karta.querySelector('.pizzy-suroviny-obsah') : null;
    const cil = document.getElementById('modal-detail-suroviny-obsah');
    const modal = document.getElementById('modal-detail-suroviny');

    if (obsahData && cil && modal) {
      cil.innerHTML = obsahData.innerHTML;
      modal.classList.add('zobrazeno');
      modal.style.display = 'flex';
      if (document.body) document.body.style.overflow = 'hidden';
    }
  };

  // Kompatibilita se starším voláním
  window.zobrazitPizzySeSurovinou = function (idSuroviny, event) {
    if (event) {
      if (event.stopPropagation) event.stopPropagation();
      if (event.preventDefault) event.preventDefault();
    }
    const karta = document.getElementById('ingredience-' + idSuroviny) || document.getElementById(idSuroviny);
    const btn = karta ? karta.querySelector('.tlacitko-pizzy-surovina') : null;
    if (btn) {
      window.otevritModalPizzSuroviny(btn, event);
    }
  };

  // 4. ZAVŘENÍ MODÁLNÍHO OKNA
  window.zavritModalSuroviny = function (event) {
    if (event) {
      if (event.stopPropagation) event.stopPropagation();
      if (event.preventDefault) event.preventDefault();
    }
    const modal = document.getElementById('modal-detail-suroviny');
    if (modal) {
      modal.classList.remove('zobrazeno');
      modal.style.display = 'none';
      if (document.body) document.body.style.overflow = '';
    }
  };

  // 5. OBSLUHA KLÁVESY ESCAPE
  window.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      window.zavritModalSuroviny();
    }
  });

  // 6. POSUN NA INGREDIENCI PODLE HASH V URL (NAPŘ. ingredience.php#mozzarella)
  function posunoutPodleHash() {
    const hash = (window.location && window.location.hash) ? window.location.hash : '';
    if (!hash) return;
    const cistyId = decodeURIComponent(hash.replace(/^#/, ''));
    const prvek = document.getElementById(cistyId) || document.getElementById('ingredience-' + cistyId);
    if (prvek) {
      const kat = prvek.getAttribute('data-kategorie');
      if (kat) {
        const tlacitka = document.querySelectorAll('.ingredience-zalozka-tlacitko, .tlacitko-kategorie-surovin');
        tlacitka.forEach(b => {
          if (b.getAttribute('data-kategorie') === kat) {
            tlacitka.forEach(btn => btn.classList.remove('aktivni'));
            b.classList.add('aktivni');
            filtrovatKategorii(kat);
          }
        });
      }
      prvek.style.display = '';
      setTimeout(() => {
        if (prvek.scrollIntoView) {
          prvek.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }, 150);
    }
  }

  // 7. SPOLEHLIVÁ DELEGACE KLIKNUTÍ (PRO KŘÍŽEK, OVERLAY A TLAČÍTKA)
  document.addEventListener('click', function (e) {
    const closeBtn = e.target ? e.target.closest('#modal-detail-suroviny .zavrit-modal') : null;
    if (closeBtn) {
      window.zavritModalSuroviny(e);
      return;
    }

    if (e.target && e.target.id === 'modal-detail-suroviny') {
      window.zavritModalSuroviny(e);
      return;
    }
  });

  // 8. OVLÁDÁNÍ VELKÉHO HLAVNÍHO KARUSELU
  let karuselIndex = 0;
  let karuselTimer = null;

  function inicializovatHlavniKarusel() {
    const pas = document.getElementById('hlavni-karusel-pas');
    if (!pas) return;

    const snimky = pas.querySelectorAll('.hlavni-karusel-snimek');
    const pocet = snimky.length;
    const teckyObal = document.getElementById('hlavni-karusel-tecky');
    const btnPrev = document.getElementById('btn-karusel-prev');
    const btnNext = document.getElementById('btn-karusel-next');

    // Pokud je jen 1 snímek, skryjeme šipky a tečky
    if (pocet <= 1) {
      if (btnPrev) btnPrev.style.display = 'none';
      if (btnNext) btnNext.style.display = 'none';
      if (teckyObal) teckyObal.style.display = 'none';
      return;
    } else {
      if (btnPrev) btnPrev.style.display = 'flex';
      if (btnNext) btnNext.style.display = 'flex';
      if (teckyObal) teckyObal.style.display = 'flex';
    }

    // Vygenerovat navigační tečky
    if (teckyObal) {
      teckyObal.innerHTML = '';
      for (let i = 0; i < pocet; i++) {
        const t = document.createElement('button');
        t.type = 'button';
        t.className = 'hlavni-karusel-tecka' + (i === 0 ? ' aktivni' : '');
        t.setAttribute('aria-label', 'Přejít na snímek ' + (i + 1));
        t.onclick = function () { window.nastavitKaruselPizzy(i); };
        teckyObal.appendChild(t);
      }
    }

    function aktualizovatKarusel() {
      pas.style.transform = 'translateX(-' + (karuselIndex * 100) + '%)';
      if (teckyObal) {
        const tecky = teckyObal.querySelectorAll('.hlavni-karusel-tecka');
        tecky.forEach((t, i) => {
          t.classList.toggle('aktivni', i === karuselIndex);
        });
      }
    }

    window.posunoutKaruselPizzy = function (smer) {
      karuselIndex = (karuselIndex + smer + pocet) % pocet;
      aktualizovatKarusel();
      restartovatAutoplay();
    };

    window.nastavitKaruselPizzy = function (index) {
      karuselIndex = index;
      aktualizovatKarusel();
      restartovatAutoplay();
    };

    function restartovatAutoplay() {
      if (karuselTimer) clearInterval(karuselTimer);
      if (pocet <= 1) return;      
    }

    // Pozastavení autoplay při najetí myší
    const sekce = document.getElementById('hlavni-karusel-sekce');
    if (sekce) {
      sekce.addEventListener('mouseenter', function () {
        if (karuselTimer) clearInterval(karuselTimer);
      });
      sekce.addEventListener('mouseleave', function () {
        restartovatAutoplay();
      });

      // Podpora pro dotykový swipe na mobilech
      let dotykZacatekX = 0;
      sekce.addEventListener('touchstart', function (e) {
        if (e.changedTouches && e.changedTouches[0]) {
          dotykZacatekX = e.changedTouches[0].screenX;
        }
      }, { passive: true });

      sekce.addEventListener('touchend', function (e) {
        if (e.changedTouches && e.changedTouches[0]) {
          const dotykKonecX = e.changedTouches[0].screenX;
          const rozdil = dotykZacatekX - dotykKonecX;
          if (Math.abs(rozdil) > 40) {
            if (rozdil > 0) {
              window.posunoutKaruselPizzy(1);
            } else {
              window.posunoutKaruselPizzy(-1);
            }
          }
        }
      }, { passive: true });
    }

    restartovatAutoplay();
  }

  // 9. INICIALIZACE PO NAČTENÍ STRÁNKY
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      nastavitFiltry();
      posunoutPodleHash();
      inicializovatHlavniKarusel();
    });
  } else {
    nastavitFiltry();
    posunoutPodleHash();
    inicializovatHlavniKarusel();
  }

  window.addEventListener('hashchange', posunoutPodleHash);
})();
