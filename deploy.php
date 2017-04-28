<?php
  require_once("resources/lib/Helper.php");
  require_once("resources/lib/Deployer.php");

  $deployer = new Deployer($_POST);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Typo3 simple deployer</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="resources/css/bootstrap.min.css" />
  <link rel="stylesheet" href="resources/css/bootstrap-responsive.min.css" />
  <link rel="stylesheet" href="resources/css/colorpicker.css" />
  <link rel="stylesheet" href="resources/css/datepicker.css" />
  <link rel="stylesheet" href="resources/css/uniform.css" />
  <link rel="stylesheet" href="resources/css/select2.css" />
  <link rel="stylesheet" href="resources/css/matrix-style.css" />
  <link rel="stylesheet" href="resources/css/matrix-media.css" />
  <link rel="stylesheet" href="resources/css/bootstrap-wysihtml5.css" />
  <link rel="stylesheet" href="resources/css/own.css" />
  <link href="resources/css/font-awesome.css" rel="stylesheet" />
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
</head>
<body>

<!--Header-part-->
<div id="header">
  <h1><a href="http://groundstack.de">Typo3 simple deployer</a></h1>
</div>
<!--close-Header-part-->

<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">Welcome User</span><b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="login.html"><i class="icon-key"></i> Log Out</a></li>
      </ul>
    </li>

    <li class=""><a title="" href="login.html"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
  </ul>
</div>

<!--sidebar-menu-->

<div id="sidebar"> <a href="#" class="visible-phone"><i class="icon icon-list"></i>Forms</a>
  <ul>
    <li class="active"><a href="#" class="typo3"><i class="icon icon-home"></i> <span>Typo3 install & change</span></a> </li>
    <li class="" style="display: none;"> <a href="#" class="theme"><i class="icon icon-list"></i> <span>Themes</span></a> </li>
    <li class=""><a href="#" class="readme"><i class="icon icon-file"></i> <span>Readme</span></a> </li>
  </ul>
</div>

<!--close-left-menu-stats-sidebar-->

<div id="content" class="deploy-typo3">
  <div id="content-header">
    <div id="breadcrumb"> <a href="deploy.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>  <a href="#" class="current typo3">Typo3 install & change</a> </div>
    <h1>Typo3 deployer</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span6">

        <!-- form to delete deployment script -->
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Delete deployment after success!</h5>
          </div>
          <div id="delete-deployment" class="widget-content nopadding">
            <form id="form-delete-deployment" class="form-horizontal" method="post" action="<?php echo htmlentities(urlencode($_SERVER['PHP_SELF'])); ?>">
              <input type="hidden" name="formtype" value="deletedeployment" />
              <div class="form-actions">
                <button type="submit" class="btn btn-danger" type="submit" name="sent" value="Senden" data-translate="_send">Delete!</button>
              </div>
            </form>
          </div>
        </div>

        <!-- form to install or change Typo3 installation -->
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Typo3 Install or change</h5>
          </div>
          <div class="widget-content nopadding">
            <form action="#" method="post" class="form-horizontal ajax_form">
              <input type="hidden" name="formtype" value="t3install" />

              <div id="t3version" class="control-group">
                <label for="t3_version" class="control-label">
                  <span data-translate="_yourversion">Enter your desired version:</span>
                </label>
                <div class="controls">
                  <select id="t3_version">
                    <option value="7.6.16">7.6.16</option>
                    <option value="8.7.1">8.7.1</option>
                  </select>
                </div>
              </div>

              <div id="t3function" class="control-group">
                <label for="text_function_id" class="control-label">
                  <span data-translate="_t3function">Please choose:</span>
                </label>
                <div class="controls">
                  <select id="text_function_id">
                    <option value="completeinstall" selected>Full Install</option>
                    <option value="onlysymlink">Only change symlink</option>
                    <option value="downloadextract">Only download and extract</option>
                    <option value="downloadextractlink">Download, extract and change symlink</option>
                  </select>
                </div>
              </div>

              <div class="control-group display">
                <label class="control-label" for="db-name" data-translate="_databasename">Database name</label>
                <div class="controls">
                  <input id="db-name" class="input-big span8" type="text" name="t3_db_name" value="databaseName">
                  <span class="help-block" data-translate="_databaseisstored">Database Access data are stored in 'typo3_config/typo3_db.php'.</span>
                </div>
              </div>
              <div class="control-group display">
                <label class="control-label" for="db-user" data-translate="_databaseuser">Database username</label>
                <div class="controls">
                  <input id="db-user" class="input-big span8" type="text" name="t3_db_user" value="databaseUser">
                </div>
              </div>
              <div class="control-group display">
                <label class="control-label" for="db-password" data-translate="_databaseuserpassword">Database userpassword</label>
                <div class="controls">
                  <input id="db-password" class="input-big span8" type="password" name="t3_db_password" value="databasePasswort">
                </div>
              </div>
              <div class="control-group display">
                <label class="control-label" for="db-host"data-translate="_databasehost">Database host</label>
                <div class="controls">
                  <input id="db-host" class="input-big span8" type="text" name="t3_db_host" value="localhost">
                </div>
              </div>
              <div class="control-group display">
                <label class="control-label" for="db-socket" data-translate="_databasesocket">Database socket</label>
                <div class="controls">
                  <input id="db-socket" class="input-big span8" type="text" name="t3_db_socket" value="">
                </div>
              </div>
              <div class="control-group display">
                <label class="control-label">
                  <label class="label label-install-tool-pw" for="install-tool-pw" data-translate="_installpassword">Install Tool password</label>
                </label>
                <div class="controls">
                  <input type="password" class="input-big span8" id="install-tool-pw" name="t3_install_tool" value="" />
                  <a href="#" class="btn btn-danger form-btn" id="generate-install-pw" data-translate="_generatepassword">Generate a password</a>
                  <div class="left" id="install-tool-pw-element"></div>
                  <span class="help-block" data-translate="_installtoolstoredin">Install Tool password is stored in 'typo3_config/typo3_db.php'.</span>
                </div>
              </div>
              <div class="form-actions">
                <button id="submit" class="btn btn-success" type="submit" name="sendt3install" value="Senden" data-translate="_send">Send</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="span6">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>List all local Typo3 sources</h5>
          </div>
          <div class="widget-content">
            <div class="controls">
              <?php
                // $deployer->helper->getDirList();
              ?>
              <span class="help-block" data-translate="_t3functiondelete_existsversions">Typo3 versions which exists in "../typo3_sources/":</span>
            </div>
          </div>
        </div>

        <!-- form to delete existing Typo3 sources -->
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Delete unused Typo3 source</h5>
          </div>
          <div class="widget-content nopadding">
            <form class="form-horizontal" method="post" action="#">
              <input type="hidden" name="formtype" value="t3sourcedelete" />
              <div class="control-group">
                <label for="normal" class="control-label">Typo3 source</label>
                <div class="controls">
                  <input type="text" class="span8 mask text">
                  <span class="help-block" data-translate="_t3functiondelete_existsversions">Typo3 versions which exists in "../typo3_sources/":</span>
                </div>
              </div>
              <div class="form-actions">
                <button id="submitdelete" class="btn btn-success" type="submit" name="sendt3versiondelete" value="Senden" data-translate="_senddelete">Delete</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>



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

<!--Footer-part-->
<div class="row-fluid">
  <div id="footer" class="span12">Developed by <a href="https://www.facebook.com/profile.php?id=100007889897625" title="Facebook">Christian Hackl</a> from <a href="http://groundstack.de" title="GroundStack">GroundStack</a></div>
</div>

<!--end-Footer-part-->
<script src="resources/javascript/jquery.min.js"></script>
<script src="resources/javascript/jquery.ui.custom.js"></script>
<script src="resources/javascript/bootstrap.min.js"></script>
<script src="resources/javascript/bootstrap-colorpicker.js"></script>
<script src="resources/javascript/bootstrap-datepicker.js"></script>
<script src="resources/javascript/masked.js"></script>
<script src="resources/javascript/jquery.uniform.js"></script>
<script src="resources/javascript/select2.min.js"></script>
<script src="resources/javascript/matrix.js"></script>
<script src="resources/javascript/matrix.form_common.js"></script>
<script src="resources/javascript/wysihtml5-0.3.0.js"></script>
<script src="resources/javascript/jquery.peity.min.js"></script>
<script src="resources/javascript/bootstrap-wysihtml5.js"></script>
<script src="resources/javascript/pGenerator.min.js"></script>
<script src="resources/javascript/typo3-simple-deploy.js"></script>
<script>
	$('.textarea_editor').wysihtml5();
</script>
</body>
</html>
