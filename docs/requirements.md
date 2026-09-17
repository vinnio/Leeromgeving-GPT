# Fase 1 — inventarisatie en requirements

## Bewijsbasis (17 september 2026)

De openbare startpagina van `https://lessen.hondenschoolvanstal.nl/` is onderzocht. Aantoonbaar: Moodle-branding; een online omgeving bij de cursussen; video’s, opdrachten voor feedback en de mogelijkheid vragen aan trainers te stellen; zichtbare kaarten voor Puppyschool, Basiscursus, Reactieve hond en Kleuterschool; een Nederlandstalige publieke interface en een inlogfunctie. De publieke bron verwijst tevens naar Moodle en naar een privacyverklaring. De opgegeven installatiegegevens (Moodle 5.2+, RemUI, RemUI Blocks, Edwiser Course Formats, Datalynx) zijn opdrachtgeverinformatie, maar zijn niet via de openbare sessie technisch gevalideerd.

## Onbekend, dus niet als feit gemodelleerd

Geen geauthenticeerde toegang, export, database-dump of Moodle-back-up is aangeleverd. Concrete cursussecties, ingeschreven gebruikers, exacte roltoewijzingen, huidige categorieboom, aanvullende plugins, quizinstellingen, Datalynx-configuraties en e-mailinstellingen vereisen een testexport of een beheerderstoegang. De bestaande categorie-ID’s 4 (NL) en 19 (EN) worden uitsluitend als migratiemapping bewaard, niet als harde applicatie-ID.

## MVP acceptatiecriteria

- Een beheerder installeert de applicatie op shared hosting en meldt zich aan.
- Beheerder maakt categorie, trainer en cursus; trainer kan alleen toegewezen cursussen wijzigen.
- Cursist registreert, vraagt toegang aan en ziet pas na goedkeuring beschermde inhoud.
- Cursist opent een les en markeert die voltooid; voortgang wordt blijvend geregistreerd.
- Alle mutaties gebruiken server-side rechtencontrole, CSRF en prepared statements.

## Gefaseerde scope

Fase 2–3 is in deze versie operationeel. Quizzen met vraagbank, opdrachten met beoordeling, berichten/notificaties, rapportages, certificaten, Datalynx-formulieren, Moodle-importwizard, SMTP-queue, back-up/herstel en plugininstallatie zijn ontworpen als vervolgmodules en worden nergens als werkend aangeboden.
