# Moodle-migratie (voorbereiding)

Voer een migratie altijd eerst op een kopie van de database uit. Verzamel per Moodle-site: versie, pluginlijst, `.mbz`-back-ups, bestanden, XML-vraagbanken, CSV-gebruikers, categorie-/cursusmapping en een export van inschrijvingen/resultaten.

De eerstvolgende importmodule valideert uploads en toont vóór schrijven een mapping en een lijst met niet-ondersteunde activiteiten. CSV-gebruikers, Moodle XML-vragen en een beperkte `.mbz`-reader zijn gepland. Wachtwoordhashes worden niet overgenomen zonder aantoonbare compatibiliteit; het veilige standaardpad is een resetmail.
