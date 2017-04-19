# English
## Overview

Typo3 PHP simple deployment tool to automatically download Typo3 and create symlinks / robots.txt / .htaccess / humans.txt.<br />
Creates typo3_config dir which includes a typo3_db.php with example database data.<br />
Creates a AdditionalConfiguration.php in typo3conf/ to include of the typo3_db.php.<br />
Creates 'FIRST_INSTALL' file in documentroot for the first installation of Typo3.<br />

Your database access data and your installtool password are stored in the file typo3_config/typo3_db.php.<br />

Your existing files won't be overwritten during the deployment process.<br />

### Installation

1. Download the [**deploy.php**](https://raw.githubusercontent.com/Teisi/typo3-deploy/master/deploy.php) and upload it to your document root (e.g. httpdocs).<br />
2. Start the deployment in your browser www.example.com/deploy.php.<br />
3. **Delete** the deploy.php from your server after successful Installation.<br />
4. Open your domain in your browser and install Typo3.<br />
5. Have fun.<br />

![example picture of the deploy tool](resources/images/typo3-simple-deploy.jpg?raw=true "Title")

#### Dirs befor installation e. g.
/etc/<br />
/logs/<br />
/httpdocs/ (<- this is document root)<br />
/httpdocs/cgi-bin/<br />

#### Dirs after installation e. g.
/etc/<br />
/logs/<br />
/httpdocs/<br />
/httpdocs/typo3conf/<br />
/typo3_config/<br />
/typo3_sources/<br />

#### Folders are created outside the document root:
If you don't like this, create a folder e. g. "typo3" in your /httpdocs/ (e. g. /httpdocs/typo3/) and link your document root to this folder ("typo3"). Then put the deploy.php in this folder ("typo3").

## Issues
- Language switch back after form send


# Deutsch

## Was macht es
Typo3 PHP simple deployment tool lädt automatisch die ausgewählte Typo3 Version herunter. Außerdem legt es gleich default robots.txt - .htaccess - humans.txt an.<br />
Es erstellt einen Ordner "typo3_config" außerhalb des documentroot, in diesem wird eine Datei "typo3_db.php" erstellt die die Datenbank Zugangsdaten enthält.<br />
Außerdem legt es gleich den Ordner "typo3conf" an. Darin wird eine "AdditionalConfiguration.php" angelegt welche den Zugriff auf die Datei "typo3_db.php" ermöglicht.<br />
Zudem wird die "FIRST_INSTALL" Datei erstellt, damit mit der Typo3 Installation sofort begonnen werden kann.<br />

Deine bestehenden Dateien werden beim deployment Vorgang nicht gelöscht.<br />

### Installation

1. Download [**deploy.php**](https://raw.githubusercontent.com/Teisi/typo3-deploy/master/deploy.php) und uploade diese in dein documentroot(z. B. httpdocs).<br />
2. Rufe die deploy.php in deinem Browser auf z. B. www.example.com/deploy.php.<br />
3. **Lösche** die deploy.php von deinem Server nach erfolgreichem deployment.<br />
4. Rufe deine Domain im Browser auf und führe die Typo3 Installation durch z.B. www.example.com.<br />
5. Have fun.<br />

![example picture of the deploy tool](resources/images/typo3-simple-deploy.jpg?raw=true "Title")

#### Ordner die zum Beispiel vor dem deployment auf deinem Server liegen:
/etc/<br />
/logs/<br />
/httpdocs/ (<- this is document root)<br />
/httpdocs/cgi-bin/<br />

#### Ordner die nach dem deployment auf deinem Server liegen sollten:
/etc/<br />
/logs/<br />
/httpdocs/<br />
/httpdocs/typo3conf/<br />
/typo3_config/<br />
/typo3_sources/<br />

#### Ordner werden default mässig ausserhalb des documentroot angelegt:
Wenn du dies nicht willst, erstelle in deinem documentroot einen neuen leeren Ordner z. B. "typo3" (z. B. /httpdocs/typo3/) und setze z. B. in Plesk den documentroot auf diesen erstellten Ordner (hier: "typo3"). Lege dann die Datei deploy.php in diesem ab und rufe sie auf bzw. folge oben genannte Schritte.

## bekannte Fehler
- Sprache wird auf englisch zurückgestellt nach dem absenden des Formulars
