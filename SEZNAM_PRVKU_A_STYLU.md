# PŘEHLED VŠECH PRVKŮ A STYLŮ WEBU (DESIGN SYSTÉM)
*Pizza od Kuřete – verze 1.2.0*

Tento dokument slouží jako srozumitelný přehled všech částí administrace a webu. Vše je pojmenováno v čisté češtině tak, aby bylo na první pohled jasné, co prvek dělá a kde se mění jeho barva nebo chování.

---

## 1. Struktura souborů ve složce `css/`

| Soubor | Co obsahuje a k čemu slouží |
| :--- | :--- |
| **`css/barvy-a-vzhled.css`** | Seznam všech barev webu, písma a rámečků. Zde se mění barvy na 1 řádku. |
| **`css/prepinace.css`** | Všechny typy přepínačů (záložky nahoře, stálé/týdne, dlaždice/seznam, vlastnosti). |
| **`css/tlacitka.css`** | Všechna tlačítka (přidat pizzu, uložit, zrušit, bílá tužka, červený koš). |
| **`css/vstupni-pole.css`** | Políčka pro psaní textu, čísel, vyhledávání a tlačítko pro výběr fotky. |
| **`css/karty-a-seznamy.css`** | Vzhled dlaždic pizz, řádků v seznamu, tabulky ingrediencí a tabulky záloh. |
| **`css/stitky-a-zpravy.css`** | Štítek verze v1.2.0, označení Pizza týdne, zelené a červené zprávy o uložení. |
| **`css/administrace.css`** | Hlavní propojovací soubor, který všechny výše uvedené části skládá dohromady. |

---

## 2. Přepínače a volby (`css/prepinace.css`)

| Název prvku v CSS | Kde se nachází a co dělá | Aktivní stav |
| :--- | :--- | :--- |
| **`.prepinac-hlavni-zalozky`** | Horní velké záložky: **PIZZY (16)** \| **INGREDIENCE (7)** \| **ZÁLOHY (1)** | `.je-aktivni` |
| **`.prepinac-filtr-nabidky`** | Filtr pod formulářem: **Stálé pizzy** vs **Pizza týdne** | `.je-aktivni` |
| **`.prepinac-pohledu-zobrazeni`** | Volba zobrazení vpravo: **Dlaždice** vs **Seznam** | `.je-aktivni` |
| **`.prepinac-vlastnosti-pizzy`** | Volby ve formuláři: Masité/Bezmasé, Pálivá/Nepálivá, Sugo/Bílý | `.je-aktivni` |
| **`.stitek-vyber-suroviny`** | Klikací bubliny pro výběr ingrediencí do pizzy | `.je-vybrano` |
| **`.stitek-vyber-alergenu`** | Klikací očíslované bubliny pro alergeny (1 až 14) | `.je-vybrano` |

---

## 3. Tlačítka a ikony (`css/tlacitka.css`)

| Název prvku v CSS | Vzhled a funkce |
| :--- | :--- |
| **`.tlacitko-zeleny-pruh-pridat`** | Nízký zeleně podbarvený pruh **+ Přidat novou pizzu** |
| **`.tlacitko-cervene-hlavni`** | Výrazné červené tlačítko pro uložení změn nebo přidání pizzy do menu |
| **`.tlacitko-sede-vedlejsi`** | Tmavě šedé tlačítko pro zrušení úpravy nebo odkaz Zpět na web |
| **`.tlacitko-ikona-tuzka-upravit`** | Bílé čtvercové tlačítko s černou tužkou (otevře editaci) |
| **`.tlacitko-ikona-kos-smazat`** | Červené čtvercové tlačítko s bílým košem (smaže položku) |
| **`.tlacitko-hvezda-pizza-tydne`** | Tlačítko ve formuláři se žlutou hvězdičkou pro označení Pizza týdne |
| **`.tlacitko-oranzove-obnova`** | Oranžové tlačítko pro obnovení stavu ze zálohy |

---

## 4. Textová a číselná políčka (`css/vstupni-pole.css`)

| Název prvku v CSS | Použití |
| :--- | :--- |
| **`.pole-vyhledavani-pizz`** | Tmavé pole pro rychlé hledání pizzy podle názvu |
| **`.pole-text-nazev`** | Širší políčko pro zadání názvu pizzy (např. MARGHERITA) |
| **`.pole-cislo-cena`** | Užší políčko pro zadání ceny v Kč |
| **`.pole-cislo-poradi`** | Úzké políčko pro pořadové číslo pizzy (1, 2, 3...) |
| **`.vyber-fotky-tlacitko`** | Tlačítko "Vybrat fotku" pro nahrání obrázku z počítače |
| **`.vyber-fotky-nazev-souboru`** | Šedý text vedle tlačítka s názvem vybraného souboru fotky |

---

## 5. Zobrazení položek (`css/karty-a-seznamy.css`)

| Název prvku v CSS | Vzhled a struktura |
| :--- | :--- |
| **`.karta-pizza-dlazdice`** | Samostatná dlaždice pizzy s velkou fotkou nahoře a údaji dole |
| **`.radek-pizza-seznam`** | Kompaktní vodorovný řádek s formátem **1. MARGHERITA**, cenou a ikonami |
| **`.tabulka-surovin-radek`** | Řádek v tabulce správy ingrediencí |
| **`.tabulka-zaloh-radek`** | Řádek v historii bodů obnovy na disku |

---

## 6. Rychlá změna barev (`css/barvy-a-vzhled.css`)

| Název barvy (proměnná) | Kód barvy | Co tato barva obarvuje |
| :--- | :--- | :--- |
| **`--barva-cervena`** | `#e50914` | Hlavní červená tlačítka (Uložit, Přidat do menu) a červený koš |
| **`--barva-zelena-uspech`** | `#2ed573` | Podbarvení pruhu "+ Přidat novou pizzu" a hlášky o úspěšné změně |
| **`--barva-zlata-akcent`** | `#f59e0b` | Zlatá hvězdička Pizza týdne, orámování aktivních prvků, nadpisy |
| **`--barva-oranzova-obnova`** | `#e67e22` | Tlačítka pro obnovu záloh ze záložky verzování |
| **`--barva-pozadi-karet`** | `#161616` | Tmavé pozadí všech karet, dlaždic a vyhledávacího pole |
| **`--barva-ramecku`** | `#2a2a2a` | Jemné oddělovací linky a rámečky boxů |
