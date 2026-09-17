# Architectuur

`public/` bevat uitsluitend de front-controller, installer en statische assets. `config/`, `app/`, `database/`, `docs/` en `storage/` horen boven de webroot. De front-controller routeert expliciete acties naar server-side handlers. `app/Core.php` bevat infrastructuur: configuratie, PDO, sessie, CSRF, autorisatie, validatie en auditlogging. Data blijft relationeel in MySQL/MariaDB; bestanden worden in een volgende iteratie via een geautoriseerde downloadcontroller ontsloten.

Rollen kennen rechten via `roles`, `permissions` en `role_permissions`; per-cursusrechten zijn begrensd via trainerstoewijzing en inschrijving. De huidige pluginmap is een veilige registratielocatie: er is nog geen upload/installatie van uitvoerbare plugins.

## Kernentiteiten

Users → enrollments → courses → sections → activities → activity_progress. Courses behoren tot categories en kunnen meerdere course_trainers hebben. Iedere mutatie kan in audit_logs worden vastgelegd. De migratie `001_initial.sql` bevat foreign keys en indexen.
