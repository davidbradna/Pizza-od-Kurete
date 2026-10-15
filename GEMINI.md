# Pravidla projektu

## Seznam souborů pro FTP (Kritické pravidlo)
Po **každé provedené úpravě kódu** VŽDY na konci odpovědi uveď jasný a přehledný seznam všech změněných nebo nově vytvořených souborů, které je nutné nahrát/přehrát na FTP server.
- Uváděj přesné relativní cesty k souborům (např. `index.php`, `styl.css`, `js/aplikace.js` apod.).
- Pokud se skripty udržují duplicitně v kořeni i ve složce `js/` (např. `aplikace.js` i `js/aplikace.js`), uveď oba.

## Striktní pravidla vývoje
1. **Absolutní zákaz nepřidávat nic bez vyžádání (Kritické pravidlo):** V celém projektu NIKDY nepřidávat žádné prvky, o které uživatel výslovně nepožádal. To se týká ZEJMÉNA jakýchkoliv IKON a EMOJI (včetně emoji v tabech, tlačítkách, seznamech či nadpisech), změn fontů, typografie, barev, dekorací, stínů, velikostí písma, rozestupů, textů, tlačítek či doplňkových funkcí. Dělat striktně a výhradně to, co je přímo v zadání, bez jakýchkoliv dekorativních příkras.
2. **Ochrana globálních stylů (Impact Check):** Při úpravě jakékoliv sekce nemazat, nepřejmenovávat ani globálně neměnit sdílené třídy (např. `.nadpis-sekce`, `.text-popis`, `.kontejner`, `.tlacitko`). Nové styly tvořit jako specifické/vnořené.
3. **Dotazy bez instrukcí ke změně:** Pokud se uživatel pouze ptá bez výslovné instrukce ke změně, kód neměnit, pouze odpovědět.
