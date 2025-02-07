Használt verziók:

PHP 8.2.7
Laravel Framework 10.48.28
Bootstrap 5.0.2
SASS 1.84.0
jQuery 3.6.2

Készítettem az adatbázis létrehozásához migrációt: database/migrations

Az oldal újratöltés nélküli kérésekhez többféle megoldást is használtam - így talán széleskörűbb a megoldás is - remélem, ez nem baj.

Telepítés: Az .env fájl és az adatbázis létrehozása után migrálhatjuk a táblákat a 'php artisan migrate' paranccsal.
A stílusokhoz SASS-t használtam - ezt a 'sass --watch resources/sass/app.scss:public/css/app.css' paranccsal tudjuk figyeltetni.
Az .scss fájl a 'resources/sass/app.scss' útvonalon érhető el.

Esetleges telepítések: 'composer install', 'npm install', 'npm install -g sass'

Az eredeti JSON fájlok a 'public/json' mappában vannak.

Remélem, nem felejtettem ki semmit!
