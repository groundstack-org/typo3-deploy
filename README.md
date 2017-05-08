# English
## Overview

Typo3 PHP simple deployment tool to automatically download Typo3 and create symlinks / robots.txt / .htaccess / humans.txt.<br />
Creates typo3_config dir which includes a typo3_db.php with example database data.<br />
Creates a AdditionalConfiguration.php in typo3conf/ to include of the typo3_db.php.<br />
Creates 'FIRST_INSTALL' file in documentroot for the first installation of Typo3.<br />

Your database access data and your installtool password are stored in the file typo3_config/typo3_db.php.<br />

Your existing files won't be overwritten during the deployment process.<br />

### Installation simple way

1. Download the [**getTypo3Deployer.php**](https://raw.githubusercontent.com/Teisi/typo3-deploy/master/getTypo3Deployer.php) and upload it to your document root (e.g. httpdocs).<br />
2. Start the deployment installation in your browser www.example.com/getTypo3Deployer.php.<br />
3. Have fun.<br />

### Installation default way
1. Download the [**deploy.php**](https://github.com/Teisi/typo3-deploy/archive/master.zip) and upload it to your document root (e.g. httpdocs).<br />
2. Unzip it than you can open the deployment tool - open your browser 'www.yourDomain.de/typo3-deploy-master'. (You can change the folder name (typo3-deploy-master) to what you like e. g. 'deploy' than open your browser 'www.yourDomain.de/deploy/index.php'.)
3. Have fun.<br />

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
If you don't like this, create a folder e. g. "typo3" in your /httpdocs/ (e. g. /httpdocs/typo3/) and link your document root to this folder ("typo3"). Then follow the installation instruction.

## Issues
- Language switch not ready


# Deutsch

## Was macht es
Typo3 PHP simple deployment tool lädt automatisch die ausgewählte Typo3 Version herunter. Außerdem legt es gleich default robots.txt - .htaccess - humans.txt an.<br />
Es erstellt einen Ordner "typo3_config" außerhalb des documentroot, in diesem wird eine Datei "typo3_db.php" erstellt die die Datenbank Zugangsdaten enthält.<br />
Außerdem legt es gleich den Ordner "typo3conf" an. Darin wird eine "AdditionalConfiguration.php" angelegt welche den Zugriff auf die Datei "typo3_db.php" ermöglicht.<br />
Zudem wird die "FIRST_INSTALL" Datei erstellt, damit mit der Typo3 Installation sofort begonnen werden kann.<br />

Deine bestehenden Dateien werden beim deployment Vorgang nicht gelöscht.<br />

### Installation einfacher Weg

1. Download [**getTypo3Deployer.php**](https://raw.githubusercontent.com/Teisi/typo3-deploy/master/getTypo3Deployer.php) und uploade diese in deinen documentroot (e.g. httpdocs).<br />
2. Start doe deployment Installation in deinem Browser www.example.com/getTypo3Deployer.php.<br />
3. Have fun.<br />

### Installation default Weg
1. Download [**deploy.php**](https://github.com/Teisi/typo3-deploy/archive/master.zip) und uploade es in deinen documentroot (e.g. httpdocs).<br />
2. Nachdem du es entpackt hast, kannst du das Tool unter 'www.yourDomain.de/typo3-deploy-master' aufrufen. (Den Namen des Tools (typo3-deploy-master) kannst du ändern zu was du willst z. B. 'deploy' dann würde der Aufruf so aussehen 'www.yourDomain.de/deploy'.)
3. Have fun.<br />

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
Wenn du dies nicht willst, erstelle in deinem documentroot einen neuen leeren Ordner z. B. "typo3" (z. B. /httpdocs/typo3/) und setze z. B. in Plesk den documentroot auf diesen erstellten Ordner (hier: "typo3"). Dann folge der Installationsanleitung.

## bekannte Fehler
- Sprache ändern fehlt
