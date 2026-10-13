# Nyílt Nap – regisztrációs rendszer

Sima PHP + MySQL projekt, külön frontenddel és backenddel. Tabletre és mobilra is reszponzív felület.

## Funkciók
- Regisztráció teljes névvel, korábbi iskolával, egy vagy több érdeklődési szakmával, bejárós/koleszos státusszal és végzős státusszal.
- MySQL adatbázisban tárolt jelentkezések, automatikus egyedi azonosítóval. Azonos névvel is többször lehet jelentkezni.
- Admin belépés, összes regisztráció listája, szakmánkénti statisztikák, végzős/koleszos összesítések, jelentkezés törlése.
- PDO prepared statementek, CSRF tokenek, HTML-kimenet escape-elése, admin session védelem.

## Szükséges
- PHP 8.1+ `pdo_mysql` és `mbstring` kiterjesztéssel
- MySQL vagy MariaDB
- Helyi fejlesztéshez XAMPP ajánlott

## Beüzemelés XAMPP-pal
1. Másold a `nyilt_nap_regisztracio` mappát a `C:\xampp\htdocs\` mappába.
2. Indítsd el a XAMPP Control Panelben az Apache és MySQL szolgáltatást.
3. Nyisd meg a phpMyAdmin felületet: `http://localhost/phpmyadmin`.
4. Importáld a projekt `database.sql` fájlját. Ez létrehozza a `nyilt_nap` adatbázist és a minta szakmákat.
5. Ellenőrizd a `config.php` fájlban a `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` adatokat. Alap XAMPP-nál általában a `root` felhasználó jelszava üres.
6. A regisztrációs oldal: `http://localhost/nyilt_nap_regisztracio/`
7. Admin: `http://localhost/nyilt_nap_regisztracio/admin/login.php`
   - Felhasználónév: `admin`
   - Jelszó: `ValtoztasdMeg123!`

## Személyre szabás beadás előtt
1. `config.php`: cseréld le a `SCHOOL_NAME` értékét az iskola nevére.
2. `database.sql`: cseréld le a minta szakmákat az iskola tényleges képzéseire, majd importáld újra (vagy vedd fel őket a `trades` táblába).
3. `assets/style.css`: a `:root` változókban megváltoztathatod a színeket.
4. Változtasd meg az admin jelszót a `config.php` fájlban.

## GitHub
A projekt gyökerében:
```bash
git init
git add .
git commit -m "Nyílt napi regisztrációs rendszer"
git branch -M main
git remote add origin https://github.com/FELHASZNALONEV/nyilt-nap-regisztracio.git
git push -u origin main
```
A `FELHASZNALONEV` és a távoli repository cím helyére a saját adataid kerüljenek.

## Fontos publikálás előtt
- A demo admin jelszót kötelező megváltoztatni.
- Ne tölts fel valódi jelentkezői adatokat vagy adatbázismentést a GitHubra.
- GitHub Pages önmagában nem futtat PHP-t vagy MySQL-t; publikáláshoz PHP-t és MySQL-t támogató tárhely kell.
- Iskolai éles használatnál egyeztessetek a tanárral az adatkezelési tájékoztatóról, hozzáférésekről és adatmegőrzésről.
