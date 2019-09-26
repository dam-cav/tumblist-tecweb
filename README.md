# Progetto Tecnologie Web, "TumbList"

![](img/w3c_html5.png)![](img/w3c_css3.png)

Progetto per il corso di Tecnologie Web per la laurea triennale in Informatica presso [Università degli Studi di Padova](https://www.unipd.it/), inverno 2018.

Il progetto consiste in un sito tramite il quale gli utenti possono scrivere e pubblicare articoli nel formato del "decalogo", usato tipicamente come clickbait da svariati siti.

A scopo puramente didattico il sito è stato costruito da zero senza l'utilizzo di framework o codice prefornito.

Tutte le pagine del sito passano il test del [Validatore di Markup di W3C](https://validator.w3.org/) e del [Validatore di CSS di W3C](https://jigsaw.w3.org/css-validator/).
Le pagine sono inoltre strutturate in modo da essere correttamente ripercorribili da utenti non vedenti tramite screen reader, funzionanti anche in mancanza di Javascript e in generale applicano criteri di accessibilità imposti dai vincoli del corso.

## Tecnologie utilizzate

- HTML 5
- CSS 3  (FlexBox)
- PHP 7
- mySQL
- Javascript

## Particolarità

### Accessibilità

- Sito interamente Responsive.
- Possibilità di scalare la dimensione del testo per l'intero sito.
- Elementi del sito completamente percorribili tramite il tasto `Tab`.
- Possibilità di inserire l'attributo *alt* anche per le immagini caricate dagli utenti.
- Colori ad alto contrasto per utenti daltonici.
- Bottone "Torna Su" all'interno delle pagine visibile solo in caso di scorrimento.
- Funzionamento senza Javascript.
- Compatibile con le versioni dei browser che supportano FlexBox.
- Validazione W3C di tutte le pagine.

### Funzioni

- CSS di stampa per risparmio di inchiostro.
- Supporto a Tag Open Graph di facebook per le preview dei link inclusi (come documentati nell'inverno 2018).
- Pannello di amministrazione.
- Messaggi di errore/conferma per le azioni eseguite.
- Pagina del profilo con log delle proprie azioni e delle azioni di altri sui propri articoli.
- Possibilità di bannare utenti.
- Sistema di ruoli per gli utenti: utente admin/utente comune/utente bannato.
- Articoli più recenti mostrati in home page.
- Possibilità di caricare avatar utente.
- Possibilità di impostare una biografia da parte di un utente.
- Cambio password.
- Eliminazione account.
- Articoli privati, pubblicabili a creazione completata.
- Modifica degli articoli.
- Riordino dei paragrafi in un articolo.
- Possibilità di commentare un articolo.
- Divisione in categorie degli articoli.
- Ricerca di articoli.
- Sistema di votazione e segnalazione degli articoli.

### Sicurezza

- Protezione da Injection: Query protette da *prepared statement*.
- Protezione da Injection: Strip dei tag inseriti nei testi dagli utenti.
- Protezione da Injection: Limitazione dei caratteri utilizzabili nei nomi utente.
- Crittografia con hashing SHA-512 per le password.
- Limitazioni alla lunghezza minima delle password.
- Messaggi di conferma prima di azioni pericolose (es: eliminazioni, elezione di nuovi amministratori).
- Autoreindirizzamento in caso di accesso ad  url non consentiti (es: pannello admin se utenti comuni).

### Prestazioni

- Un unico CSS per tutte le pagine.
- Un unico CSS per dispositivi mobili.
- Icone caricabili tramite definizioni svg includibili come unico file nella pagina tramite PHP.
- Icone caricabili da un unica immagine tramite ritaglio via CSS.

### Codice

- Singleton per la connessione al database tramite PHP.
- File unici includibili per sezioni ripetute tra le varie pagine (es: menu, header, footer).