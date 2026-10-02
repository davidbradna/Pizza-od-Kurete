# DESIGN SYSTÉM: PŘEHLED PRVKŮ A BAREVNÝCH VARIANT
*Pizza od Kuřete – verze 1.2.0*

Tento dokument slouží jako přehled všech základních stavebních prvků webu a jejich barevných variant. Všechny prvky mají jednotný tvar a velikost, a liší se pouze přidanou barevnou variantou.

---

### Zlaté pravidlo systému (Základní prvek + Barevná varianta)
Když chcete změnit barvu libovolného prvku, stačí k němu přiřadit příslušnou barevnou variantu (např. `prepinac-kapsle prepinac-kapsle-cerveny`). Změna barvy jednoho prvku nikdy neovlivní ostatní.

---

## 1. Přepínače a volby (`css/prepinace.css`)

| Základní prvek | Dostupné barevné varianty | Kde se na webu používá |
| :--- | :--- | :--- |
| **`.prepinac-kapsle`** | `.prepinac-kapsle-bily`<br>`.prepinac-kapsle-cerveny`<br>`.prepinac-kapsle-zeleny`<br>`.prepinac-kapsle-zlaty`<br>`.prepinac-kapsle-oranzovy` | Přepínač **Stálé pizzy / Pizza týdne**, přepínač **Dlaždice / Seznam**, formulářové volby (Maso, Pálivost, Základ). |
| **`.prepinac-zalozka-tlacitko`** | *aktivní stav: červená* | Horní velké záložky: **PIZZY**, **INGREDIENCE**, **ZÁLOHY & VERZOVÁNÍ**. |
| **`.stitek-vyber-polozka`** | `.je-vybrano-zlate`<br>`.je-vybrano-bile`<br>`.je-vybrano-cervene`<br>`.je-vybrano-zelene` | Klikací bubliny ve formuláři pro výběr **ingrediencí** a **alergenů (1 až 14)**. |

---

## 2. Tlačítka a ikony (`css/tlacitka.css`)

| Základní prvek | Dostupné barevné varianty | Kde se na webu používá |
| :--- | :--- | :--- |
| **`.tlacitko`** | `.tlacitko-cervene`<br>`.tlacitko-sede`<br>`.tlacitko-zelene`<br>`.tlacitko-oranzove` | Akční tlačítka: **Uložit změny** (červené), **Zrušit / Zpět na web** (šedé), **Vytvořit zálohu** (zelené), **Obnovit verzi** (oranžové). |
| **`.tlacitko-pruh-pridat`** | `.tlacitko-pruh-zeleny`<br>`.tlacitko-pruh-cerveny`<br>`.tlacitko-pruh-zlaty` | Široký podbarvený pruh **+ Přidat novou pizzu** mezi nadpisem a filtrem. |
| **`.tlacitko-ikona-ctverec`** | `.tlacitko-ikona-bila`<br>`.tlacitko-ikona-cervena`<br>`.tlacitko-ikona-zelena`<br>`.tlacitko-ikona-zlata` | Čtvercová tlačítka s ikonou: **Bílé s černou tužkou** (úprava), **Červené s bílým košem** (smazání). |

---

## 3. Vstupní textová a číselná pole (`css/vstupni-pole.css`)

| Základní prvek | Účelové varianty | Vzhled a chování |
| :--- | :--- | :--- |
| **`.vstup-pole`** | `.vstup-pole-hledani`<br>`.vstup-pole-nazev`<br>`.vstup-pole-cena`<br>`.vstup-pole-cislo` | Jednotný tmavý vzhled (`#161616`), jemný rámeček (`#333333`), šedý placeholder, při kliknutí zlatý rámeček. |
| **`.vyber-fotky-tlacitko`** | `.vyber-fotky-nazev-souboru` | Tlačítko "Vybrat fotku" pro výběr obrázku + šedý popisek s názvem souboru. |

---

## 4. Zobrazení položek a tabulky (`css/karty-a-seznamy.css`)

| Základní prvek | Pohled / Styl | Popis |
| :--- | :--- | :--- |
| **`.karta-pizza-dlazdice`** | Dlaždice (Grid) | Karta pizzy s velkou fotkou nahoře, názvem, cenou a ikonami tužky a koše. |
| **`.pohled-seznam`** | Řádkový seznam (List) | Kompaktní vodorovné řádky s formátem **1. MARGHERITA**, cenou vpravo a akčními ikonami (bez fotky a surovin). |
| **`.tabulka-administrace`** | Tabulka s tmavým záhlavím | Univerzální přehledná tabulka pro **Ingredience** a historii **Záloh databáze**. |

---

## 5. Zprávy a informační štítky (`css/stitky-a-zpravy.css`)

| Základní prvek | Barevné varianty | Použití |
| :--- | :--- | :--- |
| **`.oznameni-pruh`** | `.oznameni-zelene`<br>`.oznameni-cervene`<br>`.oznameni-oranzove` | Pruhové hlášky nahoře (úspěšné uložení pizzy / chybové hlášení). |
| **`.odznak-verze`** | Tmavý se zlatým textem | Štítek **v1.2.0** v horní liště administrace. |
| **`.odznak-pizza-tydne`** | Zlatý gradient | Štítek **PIZZA TÝDNE** v pravém horním rohu fotky na dlaždici. |

---

## 6. Globální paleta barev (`css/barvy-a-vzhled.css`)

| Název proměnné | Kód barvy | Použití v design systému |
| :--- | :--- | :--- |
| **`--barva-cervena-hlavni`** | `#e50914` | Červená tlačítka, ikona koše, aktivní záložky, červené varianty přepínačů. |
| **`--barva-zelena-hlavni`** | `#2ed573` | Pruh "+ Přidat novou pizzu", zelená oznámení, zelená tlačítka. |
| **`--barva-zlata-hlavni`** | `#f59e0b` | Hlavní nadpisy, cena pizzy, štítek Pizza týdne, orámování při najetí myší. |
| **`--barva-oranzova-hlavni`** | `#e67e22` | Tlačítka a varianty pro obnovu záloh databáze. |
| **`--barva-pozadi-karet`** | `#161616` | Tmavé sjednocené pozadí pro všechny dlaždice, karty a vstupní pole. |
| **`--barva-ramecku-vstupu`** | `#333333` | Jednotný decentní rámeček pro vyhledávání, vstupy a přepínače. |
