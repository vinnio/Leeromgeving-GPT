# Deployen via GitHub naar Hostinger

Deze repository gebruikt de branch **`master`**. Koppel die éénmalig in hPanel:

1. Ga naar **Websites** → kies `hondenschool.vanstal.nl` (of het gewenste subdomein) → **Dashboard**.
2. Open **Advanced → Git** en kies **Connect with GitHub**.
3. Autoriseer Hostinger voor `vinnio/Leeromgeving-GPT`, selecteer de branch `master` en kies een lege doelmap. Gebruik bijvoorbeeld `public_html/leeromgeving` voor een subdomein of aparte map.
4. Zet **Auto-deployment** aan. Elke push naar `master` wordt dan automatisch uitgerold.
5. Stel de document-root van het (sub)domein in op de map `public/` van deze repository. Als hPanel geen document-root per subdomein ondersteunt, plaats dan alleen de inhoud van `public/` in de publieke map en houd `app/`, `config/`, `database/` en `storage/` erboven.
6. Maak op Hostinger de database aan, zorg dat `storage/` schrijfbaar is voor PHP en doorloop eenmaal `install.php`.

## Dagelijks bijwerken

Wijzigingen lokaal: `git add .`, `git commit -m "omschrijving"`, `git push`. Hostinger rolt ze automatisch uit. Databaseconfiguratie staat in `config/local.php` en wordt bewust niet naar GitHub gestuurd.

Let op: een Git-deployment kan bestanden in de gekozen doelmap overschrijven. Bewaar uploads en productiedata daarom buiten die doelmap of in de uitgesloten `storage/`-map.
