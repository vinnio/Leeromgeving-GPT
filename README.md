# Van Stal Academie

Zelfstandig hostbare PHP 8.2+/MySQL 8+ of MariaDB 10.6+ leeromgeving voor Hondenschool Van Stal. Geen Composer, Node-server of externe dienst is vereist.

## Installeren op Hostinger

1. Maak een lege MySQL/MariaDB-database en gebruiker aan in hPanel.
2. Upload alle bestanden. Stel bij voorkeur de document-root in op de map `public/`. Als dat niet kan, plaats de inhoud van `public/` in de publieke map en pas de paden in `public/index.php` en `public/install.php` aan.
3. Maak `storage/uploads` en `storage/logs` schrijfbaar voor PHP (meestal 0750/0770). Deze map hoort buiten de webroot te blijven.
4. Open `https://jouwdomein.nl/install.php`. De wizard controleert de omgeving, maakt het schema aan en schrijft `config/local.php`.
5. Verwijder na installatie de installatieroute niet: deze is vergrendeld zodra de configuratie bestaat. Maak direct een databaseback-up.

## Wat nu werkt

Installer, PDO-database, Argon2id-login, CSRF, rate-limiting, rollen/rechten, cursuscategorieën, cursussen, secties, lespagina’s/links/downloads, zelfinschrijving met optionele goedkeuring, deelnemersoverzicht, voortgang en auditlog. Beheerder en trainer kunnen vanuit de interface cursussen en lesonderdelen beheren.

De verdere roadmap, geïnspecteerde huidige situatie en niet-geverifieerde punten staan in `docs/`.
