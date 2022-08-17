1. Kopie der `.env` mit dem Name `.env.local` anlegen
2. Daten in der `.env.local` anpassen
   1. `APP_SECRET`: mit Passwort-Tool generieren lassen. Mindestlänge 64 Zeichen.
   2. `INSTALL_PASSWORD`: über contao/install anlegen und aus der `system/config/localconfig.php` kopieren und die `localconfig.php` anschließend löschen.
3. composer u oder composer i ausführen
4. Contao-Config bitte in /config/config.yml eintragen. In diese Datei keine sicherheitsrelevanten (Install_PW ...) Daten einfügen. Sie wird von git verfolgt.

## Theme - Webpack
- Source-Dateien befinden sich im Order /layout
- Distribution-Dateien befinden sich im Ordner /public/layout
- CSS, JS wird mittels webpack encore generiert. 
- Alle Dateien im Distribution-Ordner werden überschrieben!

1. node modules installieren: `npm install --save-dev` auf der Konsole ausführen. NPM muss bereits installiert sein.
2. `webpack.config.browsersync.js.default` kopieren und `.default` am Ende löschen
3. Daten in die `webpack.config.browsersync.js` eintragen.
4. `npm run watch` ausführen. (Entwicklungsmodus)
5. `npm run build` ausführen um Dateien für das Live-System zu erzeugen.
6. `/public/layout` in das Live-System einspielen.

## Going Live
`.env.local` auf dem Livesystem erstellen und relevante Daten eintragen. Installpasswort erneut generieren!