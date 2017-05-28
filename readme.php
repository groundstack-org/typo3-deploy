<!-- for ajax use -->
<div id="content" class="deploy-readme">
  <div id="content-header">
    <div id="breadcrumb"> <a href="deploy.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current readme">Deployer readme</a> </div>
    <h1>Deployer Readme</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <h2>English</h2>
        <h3>Overview</h3>

        Typo3 PHP simple deployment tool to automatically download Typo3 and create symlinks / robots.txt / .htaccess / humans.txt.<br />
        Creates typo3_config dir which includes a typo3_db.php with example database data.<br />
        Creates a AdditionalConfiguration.php in typo3conf/ to include of the typo3_db.php.<br />
        Creates 'FIRST_INSTALL' file in documentroot for the first installation of Typo3.<br />

        Your database access data and your installtool password are stored in the file typo3_config/typo3_db.php.<br />

        Your existing files won't be overwritten during the deployment process.<br />

        <h4>Installation</h4>

        1. Download the <a href="https://github.com/Teisi/typo3-deploy/archive/master.zip" title="from github">deployment</a> and upload it to your document root (e.g. httpdocs).<br />
        2. Start the deployment in your browser www.example.com/deploy.php.<br />
        3. **Delete** the deploy.php from your server after successful Installation.<br />
        4. Open your domain in your browser and install Typo3.<br />
        5. Have fun.<br />

        <h5>Dirs befor installation e. g.</h5>
        /etc/<br />
        /logs/<br />
        /httpdocs/ (<- this is document root)<br />
        /httpdocs/cgi-bin/<br />

        <h5>Dirs after installation e. g.</h5>
        /etc/<br />
        /logs/<br />
        /httpdocs/<br />
        /httpdocs/typo3conf/<br />
        /typo3_config/<br />
        /typo3_sources/<br />

        <h5>Folders are created outside the document root:</h5>
        If you don't like this, create a folder e. g. "typo3" in your /httpdocs/ (e. g. /httpdocs/typo3/) and link your document root to this folder ("typo3"). Then put the deploy.php in this folder ("typo3").

        <h3>Issues</h3>
        - Language switch back after form send


        <h2>Deutsch</h2>

        <h3>Was macht es</h3>
        Typo3 PHP simple deployment tool lädt automatisch die ausgewählte Typo3 Version herunter. Außerdem legt es gleich default robots.txt - .htaccess - humans.txt an.<br />
        Es erstellt einen Ordner "typo3_config" außerhalb des documentroot, in diesem wird eine Datei "typo3_db.php" erstellt die die Datenbank Zugangsdaten enthält.<br />
        Außerdem legt es gleich den Ordner "typo3conf" an. Darin wird eine "AdditionalConfiguration.php" angelegt welche den Zugriff auf die Datei "typo3_db.php" ermöglicht.<br />
        Zudem wird die "FIRST_INSTALL" Datei erstellt, damit mit der Typo3 Installation sofort begonnen werden kann.<br />

        Deine bestehenden Dateien werden beim deployment Vorgang nicht gelöscht.<br />

        <h4>Installation</h4>

        1. Download <a href="https://github.com/Teisi/typo3-deploy/archive/master.zip" title="from github">deployment</a> und uploade diese in dein documentroot(z. B. httpdocs).<br />
        2. Rufe die deploy.php in deinem Browser auf z. B. www.example.com/deploy.php.<br />
        3. **Lösche** die deploy.php von deinem Server nach erfolgreichem deployment.<br />
        4. Rufe deine Domain im Browser auf und führe die Typo3 Installation durch z.B. www.example.com.<br />
        5. Have fun.<br />

        <h5>Ordner die zum Beispiel vor dem deployment auf deinem Server liegen:</h5>
        /etc/<br />
        /logs/<br />
        /httpdocs/ (<- this is document root)<br />
        /httpdocs/cgi-bin/<br />

        <h5>Ordner die nach dem deployment auf deinem Server liegen sollten:</h5>
        /etc/<br />
        /logs/<br />
        /httpdocs/<br />
        /httpdocs/typo3conf/<br />
        /typo3_config/<br />
        /typo3_sources/<br />

        <h5>Ordner werden default mässig ausserhalb des documentroot angelegt:</h5>
        <p>
          Wenn du dies nicht willst, erstelle in deinem documentroot einen neuen leeren Ordner z. B. "typo3" (z. B. /httpdocs/typo3/) und setze z. B. in Plesk den documentroot auf diesen erstellten Ordner (hier: "typo3"). Lege dann die Datei deploy.php in diesem ab und rufe sie auf bzw. folge oben genannte Schritte.
        </p>

        <h3>bekannte Fehler</h3>
        - Sprache wird auf englisch zurückgestellt nach dem absenden des Formulars
      </div>
    </div>
  </div>
</div>
