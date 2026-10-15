---
description: Striktní pravidla pro úpravy kódu a komunikaci
globs: ["*"]
---

# Striktní pravidla vývoje

1. **Absolutní zákaz nepřidávat nic bez vyžádání (Kritické pravidlo):** V celém projektu NIKDY nepřidávat žádné prvky, o které uživatel výslovně nepožádal. To se týká ZEJMÉNA jakýchkoliv IKON a EMOJI (včetně emoji v tabech, tlačítkách, seznamech či nadpisech), změn fontů, typografie, barev, dekorací, stínů, velikostí písma, rozestupů, textů, tlačítek či doplňkových funkcí. Dělat striktně a výhradně to, co je přímo v zadání, bez jakýchkoliv dekorativních příkras.
2. **Hlášení souborů k přehrání na FTP:** Vždy po každé změně výslovně a přehledně vypsat seznam všech souborů, které byly upraveny a které je nutné přehrát (nahrát na hosting / server).
3. **Otázky a potvrzování změn:** Když se uživatel zeptá na otázku bez výslovné instrukce změnit kód, kód neměnit, ale pouze odpovědět na otázku. Při jakékoliv nejistotě, zda je změna požadována, si nechat nejprve potvrdit, že uživatel změnu skutečně chce.
4. **Ochrana globálních a sdílených stylů (Impact Check):** Při úpravě jakékoliv konkrétní sekce je přísně zakázáno mazat, přejmenovávat nebo globálně měnit sdílené třídy (např. `.nadpis-sekce`, `.text-popis`, `.kontejner`, `.tlacitko`). Před smazáním jakéhokoliv CSS pravidla ověřit, zda není použito v jiných částech webu. Nové styly pro sekce tvořit výhradně jako specifické/vnořené, aby se nepoškodil zbytek webu.
