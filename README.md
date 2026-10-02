# Pizza od Kuřete 🍕

Moderní webová prezentace a administrační systém pro pizzerii **Pizza od Kuřete** (Rychnov nad Kněžnou & Ústí nad Orlicí).

## 🚀 Architektura a technologie
- **Backend:** PHP (modulární skripty, centrální konfigurace `nastaveni.php`)
- **Databáze:** JSON úložiště v dedikovaném adresáři `data/`
  - `data/pizzy.json` – správa menu pizz
  - `data/ingredience.json` – správa surovin a alergenů
  - `data/zalohy/` – automatické zálohování a verzování
- **Frontend:** HTML5, moderní modulární CSS Design Systém v `css/`, čistý JavaScript v `js/`
- **Verzování:** Git & GitHub (`https://github.com/davidbradna/Pizza-od-Kurete`)

## 📂 Struktura projektu
```
├── administrace.php     # Administrační rozhraní (pizzy, ingredience, zálohy)
├── index.php            # Hlavní veřejná stránka jídelního lístku
├── ingredience.php      # Veřejná prezentace surovin a původu
├── databaze.php         # Vrstva pro čtení, zápis a zálohování databází
├── nastaveni.php        # Centrální konfigurace (hesla, pobočky, verze)
├── css/                 # Modulární CSS Design Systém (české názvosloví)
├── js/                  # JavaScript logika aplikace a administrace
├── data/                # Databázové JSON soubory
│   ├── pizzy.json
│   ├── ingredience.json
│   └── zalohy/
└── media/               # Obrázky, loga a grafické podklady
```
