# Marrison Addon JetEngine

**Sette moduli gratuiti per le griglie, le query e i campi dinamici di JetEngine.**

Marrison Addon JetEngine (MAJ) è un fork di [Marrison Addon](https://github.com/marrisonlab/marrison-addon), il plugin originale, più completo e sempre gratuito. MAJ raccoglie le funzionalità dedicate a JetEngine in un plugin autonomo.

[Scarica lo ZIP installabile](marrison-addon-jetengine.zip) · [Release su GitHub](https://github.com/marrisonlab/marrison-addon-jetengine/releases) · [Guida ai moduli](#guida-ai-moduli)

> **Zero payload per i moduli spenti.** MAJ carica i file PHP di un modulo e registra i suoi hook solo quando il modulo è attivo e le dipendenze sono disponibili. Un modulo spento non aggiunge CSS o JavaScript al sito. Il nucleo di MAJ gestisce il pannello e gli aggiornamenti.

## Installazione

**Requisiti** · WordPress 6.0 o superiore, PHP 7.4 o superiore e JetEngine attivo. Alcuni moduli richiedono anche Elementor o WooCommerce.

1. Scarica lo ZIP installabile. In WordPress apri **Plugin → Aggiungi nuovo → Carica plugin**, seleziona lo ZIP e attiva **Marrison Addon JetEngine**.
2. Apri **MAJ** nel menu di WordPress. I moduli partono tutti spenti.
3. Accendi i moduli che ti servono e salva. La scheda di ciascun modulo indica se manca una dipendenza.

Puoi usare MAJ insieme a Marrison Addon. Le impostazioni di attivazione sono indipendenti. Per passare un modulo a MAJ, spegnilo nel plugin originale e poi accendilo in MAJ. I widget e le configurazioni salvate conservano gli stessi identificatori. Se il modulo è attivo nell'originale, quest'ultimo ha la precedenza.

## Guida ai moduli

| Modulo | Cosa aggiunge | Oltre a JetEngine |
| --- | --- | --- |
| [Listing Grid Title](#listing-grid-title) | Titolo sopra una griglia | Elementor |
| [Listing Columns](#listing-columns) | Pulsanti per cambiare il numero di colonne | Elementor |
| [Query Fallback](#query-fallback) | Query di riserva per la griglia | Elementor |
| [Dynamic SVG](#dynamic-svg) | SVG inline da un campo dinamico | Estensione PHP DOM |
| [Recently Viewed Products](#recently-viewed-products) | Prodotti visitati di recente nel browser | WooCommerce |
| [Read More](#read-more) | Testo espandibile con un pulsante | Elementor |
| [Content Ticker](#content-ticker) | Testo scorrevole da una query o da voci manuali | Elementor |

Prima di configurare un modulo, attivalo in **MAJ** e salva.

### Listing Grid Title

1. In Elementor, seleziona il widget **Listing Grid** e apri **Contenuto → Listing Grid Title**.
2. Attiva **Show title**, scrivi il titolo o scegli un tag dinamico. Imposta il tag HTML, ad esempio `H2`, e aggiungi un link se serve.
3. In **Stile → Listing Grid Title** regola tipografia, colori e spaziatura. Il titolo appare sopra la griglia.

### Listing Columns

1. Seleziona una **Listing Grid** normale e assegna **Avanzato → ID CSS → `catalogo`**.
2. Aggiungi il widget **Listing Columns** vicino alla griglia. In **Listing CSS selector** scrivi `#catalogo`. Puoi usare una classe condivisa per controllare più griglie.
3. Imposta il numero minimo, massimo e iniziale di colonne per dispositivo. Con **Remember selection** il browser ricorda la scelta del visitatore.
4. Personalizza pulsanti e icone e prova il cambio di colonne sul sito. Sotto **Minimum viewport width** la griglia usa il proprio layout responsive. Il modulo è pensato per griglie regolari. Slider e masonry sono esclusi.

### Query Fallback

1. In **JetEngine → Query Builder**, salva due **Posts Query** distinte. Ad esempio, usa i prodotti della categoria corrente come query principale e una selezione di prodotti in evidenza come riserva.
2. Nella **Listing Grid** di Elementor apri **Custom Query**, attiva **Use Custom Query** e seleziona la query principale.
3. Attiva **Enable Query Fallback** e scegli la query di riserva in **Fallback Query**.
4. Scegli **Only when empty** per usare la riserva quando la principale restituisce zero elementi. Scegli **Fill missing items** per aggiungere elementi senza duplicati fino al limite della query principale.

Usalo con griglie a pagina singola. Il fallback si sospende con filtri applicati, paginazione, Load More, Lazy Load, modalità archivio o numero casuale di risultati.

### Dynamic SVG

1. Associa un SVG locale della Libreria media a un campo **Media** di JetEngine, con valore ID o URL.
2. Nel template del listing aggiungi **Dynamic Field** e seleziona quel campo come sorgente.
3. Attiva **Filter field output**. In **Callback**, scegli **Marrison – Inline SVG** per mantenere i colori del file o **Marrison – Inline SVG (Current Color)** per far ereditare al disegno il colore del testo.
4. Controlla il risultato sul sito. Il modulo verifica e sanitizza il file prima di inserirlo inline. [Guida a Dynamic Field](https://crocoblock.com/knowledge-base/jetengine/dynamic-field-widget-overview/)

### Recently Viewed Products

1. In **JetEngine → Query Builder**, crea una **Posts Query** con **Post Type → Products**. Disattiva **Cache Query**, perché il risultato dipende dal cookie del visitatore.
2. In **Post & Page → Post In**, seleziona la macro **Recently Viewed Products**. Di default **Limit** è `12` ed **Exclude current product** è **Yes**.
3. In **Order By**, scegli l'opzione che mantiene l'ordine degli ID di **Post In** per visualizzare prima il prodotto visitato più di recente.
4. Nella **Listing Grid** seleziona un template per i prodotti e collega la query con **Custom Query → Use Custom Query**. [Guida alle macro in Query Builder](https://crocoblock.com/knowledge-base/jetengine/macros-for-query-builder/)

Per provarlo, visita tre schede prodotto nello stesso browser. Con la griglia sulla terza scheda e l'esclusione attiva, vedrai le prime due in ordine inverso di visita. Con lo storico vuoto la griglia restituisce zero prodotti.

### Read More

1. In Elementor, apri un widget **Text Editor** o **Dynamic Field** con un testo abbastanza lungo.
2. In **Contenuto → Marrison — Read More**, attiva **Enable Read More** e scegli quante righe mostrare a testo chiuso.
3. Personalizza le etichette di apertura e chiusura, ad esempio «Leggi di più» e «Leggi di meno». In **Stile → Read More** regola l'aspetto del pulsante.
4. Verifica il testo pubblicato a diverse larghezze. Il pulsante appare quando il contenuto supera le righe impostate.

### Content Ticker

1. Aggiungi il widget **Ticker** in Elementor e apri **Contenuto → Source**.
2. Con **Manual (Repeater)** aggiungi le voci in **Ticker Items**, ciascuna con un testo e un link facoltativo.
3. Per alimentarlo da JetEngine, scegli **JetEngine Query** e seleziona la query. Indica il campo o la macro da usare per testo e link. Per un elenco di articoli, puoi usare `post_title` e `permalink`.
4. Imposta icona separatrice, direzione, durata dello scorrimento e pausa al passaggio del mouse. Controlla l'anteprima prima di pubblicare.

## Aggiornamenti da GitHub

Dalla versione **1.0.1**, MAJ cerca le nuove release stabili su GitHub. Quando è disponibile una versione più recente, aggiornala dalla voce del plugin in **Plugin installati**. L'updater verifica lo SHA-256 del pacchetto e prepara i file con il nome della cartella già installata.

A partire dalla versione 1.0.2, la versione compare **nel nome del file ZIP**. La cartella interna dei nuovi pacchetti è sempre **`marrison-addon-jetengine/`**, senza numero di versione.

```text
marrison-addon-jetengine-X.Y.Z.zip
└── marrison-addon-jetengine/
    ├── marrison-addon-jet.php
    ├── assets/
    ├── includes/
    └── languages/
```

Per un aggiornamento manuale usa lo ZIP allegato alla release. Controlla che la sua cartella interna coincida con quella presente in `wp-content/plugins/` prima di caricarlo. Se hai già un'installazione con un nome diverso, usa l'aggiornamento integrato oppure prepara una copia dello ZIP con la stessa cartella dell'installazione. In WordPress seleziona **Sostituisci l'attuale con quella caricata** quando compare. Gli archivi automatici **Source code (zip)** di GitHub sono destinati alla consultazione dei sorgenti.

### Preparare una release

1. Allinea la versione nell'intestazione PHP e in `Marrison_Addon_Jet::VERSION`.
2. Comprimi la cartella [marrison-addon-jetengine](marrison-addon-jetengine/). Apri lo ZIP e verifica che l'unica cartella alla radice sia `marrison-addon-jetengine/` e che contenga direttamente il file PHP principale.
3. Pubblica una release stabile con tag `vX.Y.Z` e allega `marrison-addon-jetengine-X.Y.Z.zip`. Usa lo stesso contenuto per lo ZIP installabile nella radice del repository.

## Lingue e licenza

La lingua sorgente è l'inglese. Il plugin include le traduzioni in ucraino e arabo, caricate in base alla lingua di WordPress o del profilo utente nel pannello. I cataloghi sono nella [cartella languages](marrison-addon-jetengine/languages/).

Puoi aggiornare i cataloghi con WP-CLI dalla radice del repository.

```sh
cd marrison-addon-jetengine
wp i18n make-pot . languages/marrison-addon-jet.pot --domain=marrison-addon-jet
wp i18n make-mo languages
```

Licenza [GPL-3.0-or-later](https://www.gnu.org/licenses/gpl-3.0.html), ereditata da Marrison Addon.
