# DESIGN SYSTÉM: PŘEHLED PRVKŮ A BAREVNÝCH VARIANT
*Pizza od Kuřete – verze 1.2.0*

Tento dokument slouží jako přehled všech základních stavebních prvků webu a jejich barevných variant. Všechny prvky mají jednotný tvar a velikost, a liší se pouze přidanou barevnou variantou.

---

### Zlaté pravidlo systému (Základní prvek + Barevná varianta)
Když chcete změnit barvu libovolného prvku, stačí k němu přiřadit příslušnou barevnou variantu (např. `prepinac-kapsle prepinac-kapsle-cerveny`). Změna barvy jednoho prvku nikdy neovlivní ostatní.

---

## 1. Medailonek pizzy na webu & Dlaždice v administraci (`css/karty-a-seznamy.css`)

| Prvek | Podprvky a součásti | Popis a chování |
| :--- | :--- | :--- |
| **Medailonek na webu:**<br>`.medailonek-pizzy`<br>*(alias `.karta-pizzy`)* | • `.medailonek-pizzy-cislo` (číslo v rohu)<br>• `.medailonek-pizzy-fotka-obal` (vycentrovaná fotka)<br>• `.medailonek-pizzy-nazev` (font Britannic)<br>• `.medailonek-pizzy-slozeni`<br>• `.medailonek-pizzy-alergeny`<br>• `.medailonek-pizzy-cena`<br>• `.medailonek-pizzy-tlacitko-pridat` (kulaté +) | Karta v jídelním lístku na veřejném webu. Obsahuje fotku s padajícím stínem, cenu a kulaté červené tlačítko **+** pro vložení do objednávky.<br>`.medailonek-pizzy-zlaty` (pro Pizzu týdne). |
| **Dlaždice v administraci:**<br>`.admin-pizza-dlazdice`<br>*(alias `.pizza-dlazdice`)* | • `.admin-pizza-dlazdice-fotka-obal` (obdélníková fotka)<br>• `.odznak-pizza-cislo-foto` (červené číslo v rohu fotky)<br>• `.odznak-pizza-tydne` (zlatý štítek Pizza týdne)<br>• `.admin-pizza-dlazdice-nazev`<br>• `.admin-pizza-dlazdice-slozeni`<br>• `.admin-pizza-dlazdice-cena`<br>• `.tlacitko-ikona-bila` (tužka) + `.tlacitko-ikona-cervena` (koš) | Karta pizzy v administraci pro správce. Fotka pokrývá celý horní pruh, v rozích má štítky a v patičce čtvercové ikony pro úpravu a smazání. |
| **Řádek seznamu v adminu:**<br>`.radek-pizza-seznam` | • `.radek-pizza-seznam-nazev` (např. 1. MARGHERITA)<br>• `.radek-pizza-seznam-cena`<br>• Ikona tužky a koše | Kompaktní vodorovný řádek bez fotky a surovin pro rychlou správu velkého počtu pizz. |

---

## 2. Přepínače a volby (`css/prepinace.css`)

| Základní prvek | Dostupné barevné varianty | Kde se na webu používá |
| :--- | :--- | :--- |
| **`.prepinac-kapsle`** | `.prepinac-kapsle-bily`<br>`.prepinac-kapsle-cerveny`<br>`.prepinac-kapsle-zeleny`<br>`.prepinac-kapsle-zlaty`<br>`.prepinac-kapsle-oranzovy` | Přepínač **Stálé pizzy / Pizza týdne**, přepínač **Dlaždice / Seznam**, formulářové volby (Maso, Pálivost, Základ). |
| **`.prepinac-zalozka-tlacitko`** | *aktivní stav: červená* | Horní velké záložky: **PIZZY**, **INGREDIENCE**, **ZÁLOHY & VERZOVÁNÍ**. |
| **`.stitek-vyber-polozka`** | `.je-vybrano-zlate`<br>`.je-vybrano-bile`<br>`.je-vybrano-cervene`<br>`.je-vybrano-zelene` | Klikací bubliny ve formuláři pro výběr **ingrediencí** a **alergenů (1 až 14)**. |

---

## 3. Tlačítka a ikony (`css/tlacitka.css`)

| Základní prvek | Dostupné barevné varianty | Kde se na webu používá |
| :--- | :--- | :--- |
| **`.tlacitko`** | `.tlacitko-cervene`<br>`.tlacitko-sede`<br>`.tlacitko-zelene`<br>`.tlacitko-oranzove` | Akční tlačítka: **Uložit změny** (červené), **Zrušit / Zpět na web** (šedé), **Vytvořit zálohu** (zelené), **Obnovit verzi** (oranžové). |
| **`.tlacitko-pruh-pridat`** | `.tlacitko-pruh-zeleny`<br>`.tlacitko-pruh-cerveny`<br>`.tlacitko-pruh-zlaty` | Široký podbarvený pruh **+ Přidat novou pizzu** mezi nadpisem a filtrem. |
| **`.tlacitko-ikona-ctverec`** | `.tlacitko-ikona-bila`<br>`.tlacitko-ikona-cervena`<br>`.tlacitko-ikona-zelena`<br>`.tlacitko-ikona-zlata` | Čtvercová tlačítka s ikonou: **Bílé s černou tužkou** (úprava), **Červené s bílým košem** (smazání). |

---

## 4. Vstupní textová a číselná pole (`css/vstupni-pole.css`)

| Základní prvek | Účelové varianty | Vzhled a chování |
| :--- | :--- | :--- |
| **`.vstup-pole`** | `.vstup-pole-hledani`<br>`.vstup-pole-nazev`<br>`.vstup-pole-cena`<br>`.vstup-pole-cislo` | Jednotný tmavý vzhled (`#161616`), jemný rámeček (`#333333`), šedý placeholder, při kliknutí zlatý rámeček. |
| **`.vyber-fotky-tlacitko`** | `.vyber-fotky-nazev-souboru` | Tlačítko "Vybrat fotku" pro výběr obrázku + šedý popisek s názvem souboru. |

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
