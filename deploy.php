<!doctype html>
<html>
<head>
  <meta charset="utf-8">

  <title>Typo3 deploy script</title>
  <meta name="description" content="The Typo3 simple deploy script.">
  <link rel="stylesheet" href="https://rawgit.com/Teisi/typo3-deploy/dev/resources/css/typo3-simple-deploy.css">
  <script>
		var js_var = "delete";
		var deleteScript = function() {
			window.location.href = "deploy.php?php_var=" + escape(js_var);
		}
	</script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>

<body>
  <div id="main-wrapper">
    <header id="header">
      <h1>Typo3 simple deployment</h1>
    </header>
    <main id="main">
      <div id="form">
        <p>
    			<strong>Nach Abschluss:</strong><br />
    			Diese Datei unbedingt manuel löschen! ODER <button class="btn-delete form-btn" onclick="deleteScript()">Lösche mich!</button><br />
    		</p>
        <form id="form-t3-install" method="post" action="<?php echo htmlentities(urlencode($_SERVER['PHP_SELF'])); ?>">
  				<ul class="">
  					<li class="choose-version">
  						<label class="t3_version_label" for="text_id">Hier Ihre gewünschte Version angeben:<br /><span class="info">(Bitte in dieser Form: 6.2.12)</label>
  						<input type="text" class="t3_version" name="t3_version" id="text_id" value="7.6.16" autofocus min="5" maxlength="8" required />
  					</li>
  					<li style="display: none;">
  						<label class="">Was möchten Sie machen?</label>
  						<fieldset>
  							<input type="radio" id="full" name="selection" value="full" checked="checked" required><label for="mc">Komplett Installation</label><br />
  							<input type="radio" id="only-symlink" name="selection" value="symlinks"><label for="vi">Nur Symlinks erstellen</label><br />
  							<input type="radio" id="only-download" name="selection" value="download"><label for="ae">Nur Typo3 downloaden und entpacken</label>
  						</fieldset>
  					</li>
            <li class="form-db-data">
              <label>Datenbank Zugangsdaten werden in 'typo3_config/typo3_db.php' gespeichert.</label><br /><br />
              <label class="label label-name" for="db-name">Datenbank Name</label><br />
              <input id="db-name" class="input" type="text" name="t3_db_name" value="databaseName"><br />
              <label class="label label-name" for="db-name">Datenbank Benutzername</label><br />
              <input id="db-user" class="input" type="text" name="t3_db_user" value="databaseUser"><br />
              <label class="label label-name" for="db-password">Datenbank Benutzerpassword (character '&' not allowed)</label><br />
              <input id="db-password" class="input" type="password" name="t3_db_password" value="databasePasswort"><br />
              <label class="label label-name" for="db-host">Datenbank Host</label><br />
              <input id="db-host" class="input" type="text" name="t3_db_host" value="localhost"><br />
              <label class="label label-name" for="db-socket">Datenbank Socket</label><br />
              <input id="db-socket" class="input" type="text" name="t3_db_socket" value=""><br />
            </li>
            <li class="form-install-tool">
              <label>Install Tool Password wird in 'typo3_config/typo3_db.php' gespeichert.</label><br /><br />
              <label class="label label-install-tool-pw" for="install-tool-pw">Install Tool Password (character '&' not allowed)</label><br />
              <input type="password" class="input left form-control" id="install-tool-pw" name="t3_install_tool" value="" /> <br /><br />
              <a href="#" class="btn btn-danger form-btn" id="generate-install-pw" >Generate a password</a>
              <div class="left" id="install-tool-pw-element"></div>
            </li>
  					<li class="from_submit">
  						<button id="submit" class="form-btn" type="submit" name="sent" value="Senden">Send</button>
  					</li>
  				</ul>
  			</form>
      </div>
      <div class="loading">
        <div>
          <div class="c1"></div>
          <div class="c2"></div>
          <div class="c3"></div>
          <div class="c4"></div>
        </div>
        <span>...loading...</span>
      </div>
<?php
$t3_version = "empty";

if (isset($_GET['php_var'])) {
    unlink("deploy.php");
}
if(isset($_POST['sent'])) {
  $t3_version = $t3_db_user = $t3_db_name = $t3_db_user = $t3_db_host = $t3_db_socket = $t3_install_tool = "";

  function escape_input($data) {
    $tmp_str_replace_orig = array('"', "'", "<", ">", " ");
    $tmp_str_replace_target = array('', "", "", "", "");
    return str_replace($tmp_str_replace_orig, $tmp_str_replace_target, htmlspecialchars(stripslashes(trim($data))));
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $t3_version = escape_input($_POST['t3_version']);

    $t3_db_user = escape_input($_POST['t3_db_user']);
    $t3_db_name = escape_input($_POST['t3_db_name']);
    $t3_db_password = escape_input($_POST['t3_db_password']);
    $t3_db_host = escape_input($_POST['t3_db_host']);
    $t3_db_socket = escape_input($_POST['t3_db_socket']);

    $t3_install_tool = escape_input($_POST['t3_install_tool']);
    $t3_install_tool = md5($t3_install_tool);
  }

  $t3_src_dir_name = "typo3_sources";
  $t3_version_dir = "typo3_src-{$t3_version}";
  $t3_zip_file = "{$t3_version_dir}.tar.gz";
  $typo3_source = "https://netcologne.dl.sourceforge.net/project/typo3/TYPO3%20Source%20and%20Dummy/TYPO3%20{$t3_version}/{$t3_zip_file}";

  function downloadFile($url, $path) {
      $newfname = $path;
      $file = fopen($url, 'rb');
      if($file) {
          $newf = fopen($newfname, 'wb');
          if($newf) {
              while(!feof($file)) {
                  fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
              }
          }
      }
      if ($file) {
          fclose($file);
      }
      if ($newf) {
          fclose($newf);
      }
  }

  function deleteFile($path, $filename) {
    $filepath = $path.$filename;
    echo "<div class='delete-file-dir'>";
    echo "<span class=''>Try to delete: '$filepath'...</span>";
    unlink($filepath);

    if(!file_exists($filepath)) {
      echo "<span class='success'>Successfully deleted: '$filepath' or dosen't exists.</span>";
    } else {
      echo "<span class='warning'>Can't delete: '$filepath'!</span>";
    }

    echo "</div>";
  }

  function createSymlink($target, $link) {
    echo "<div class='create-symlink'>";
    echo "<span class=''>Try to create symlink: '{$link}' to '{$target}'...</span>";
    symlink($target, $link);

    if(file_exists($link)) {
      echo "<span class='success'>Successfully created symlink '{$link}'.</span>";
    } else {
      echo "<span class='warning'>Can't create symlink '{$link}'.</span>";
    }

    echo "</div>";
  }

  function createDir($dirname, $path = "") {
    $filepath = $path.$dirname;
    echo "<div class='create-dir'>";
    echo "<span class=''>Try to create dir: '$filepath'...</span>";
    mkdir($filepath);

    if(file_exists($filepath)) {
      echo "<span class='success'>Successfully created dir '$filepath'.</span>";
    } else {
      echo "<span class='warning'>Can't create dir '$filepath'.</span>";
      exit();
    }

    echo "</div>";
  }
  function createFile($filename, $path = "") {
    echo "<div class='create-file'>";
    echo "<span class=''>Try to create file: '{$path}{$file}'...</span>";

    if(file_exists($file)) {
      echo "<span class='success'>Successfully created file '{$path}{$file}'.</span>";
    } else {
      echo "<span class='warning'>Can't create file '{$path}{$file}'!</span>";
    }

    echo "</div>";
  }
  function createExternalFile($url, $path_filename) {
    echo "<div class='download-external-file'>";
    echo "<span class=''>Try to download file: '{$url}'...</span>";
    downloadFile($url, $path_filename);

    if(file_exists($path_filename)) {
      echo "<span class='success'>Successfully downloaded file '{$path_filename}'.</span>";
    } else {
      echo "<span class='warning'>Can't download file '{$path_filename}'!</span>";
    }

    echo "</div>";
  }

  function check_file_dir($filetype = "dir", $file, $path = "") {
    $filepath = $path.$file;
    echo "<div class='check-file-dir'>";
    if(file_exists($filepath) && $filetype != "external_file") {
      echo "<span class='exists'>File / Dir '{$filepath}' already exists!</span>";
      echo "</div>";
      return true;
    } else {
      if($filetype === "dir") {
        createDir($file, $path);
      } else if($filetype === "file") {
        createFile($file, $path);
      } else if($filetype === "symlink") {
        createSymlink($path, $file);
      } else if($filetype === "external_file") {
        if(file_exists($file)) {
          echo "<span class='exists'>File / Dir '{$file}' already exists!</span>";
        } else {
          createExternalFile($path, $file);
        }
      } else {
        echo "<span class='error'>Wrong filetype: {$filetype}!</span>";
      }
      echo "</div>";
    }
  }

  function addDbVersion7($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool) {
    file_put_contents("typo3_config/typo3_db.php", "
<?php
\$GLOBALS['TYPO3_CONF_VARS']['DB']['database'] = '{$t3_db_name}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['host'] = '{$t3_db_host}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['password'] = '{$t3_db_password}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['username'] = '{$t3_db_user}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['socket'] = '{$t3_db_socket}';

\$GLOBALS['TYPO3_CONF_VARS']['BE']['installToolPassword'] = '{$t3_install_tool}';

    ");
  }

  function addDbVersion8($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool) {
    file_put_contents("typo3_config/typo3_db.php", "
<?php
\$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['charset'] = 'utf8';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['driver'] = 'mysqli';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = '{$t3_db_name}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] = '{$t3_db_host}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = '{$t3_db_password}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = '{$t3_db_user}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['unix_socket'] = '{$t3_db_socket}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['port'] = 3306;

\$GLOBALS['TYPO3_CONF_VARS']['BE']['installToolPassword'] = '{$t3_install_tool}';

    ");
  }

  check_file_dir("dir", "typo3_config");
  check_file_dir("dir", "typo3");
  check_file_dir("dir", "typo3conf", "typo3/");
  check_file_dir("dir", $t3_src_dir_name);

  check_file_dir("external_file", "typo3/humans.txt", "https://raw.githubusercontent.com/Teisi/typo3-deploy/master/humans.txt");
  check_file_dir("external_file", "typo3/robots.txt", "https://raw.githubusercontent.com/Teisi/typo3-deploy/master/robots.txt");
  check_file_dir("external_file", "typo3/.htaccess", "https://raw.githubusercontent.com/Teisi/typo3-deploy/master/.htaccess");

  check_file_dir("external_file", $t3_zip_file, $typo3_source);

  if (file_exists($t3_zip_file)) {
    echo "<span>File {$t3_zip_file} exists</span>";

    if(!file_exists($t3_src_dir_name . "/" . $t3_version_dir . "/typo3")) {
      // unarchive from the tar
      $phar = new PharData($t3_zip_file);
      $phar->extractTo($t3_src_dir_name);

      // exec("tar -xzvf {$t3_zip_file} -C {$t3_src_dir_name}");
    }

    if (file_exists($t3_src_dir_name . "/" . $t3_version_dir . "/typo3")) {
      echo "<span class='success'>Successfully extracted!</span>";
      deleteFile("", $t3_zip_file);

      if(!file_exists("typo3/typo3conf/AdditionalConfiguration.php")) {
        file_put_contents("typo3/typo3conf/AdditionalConfiguration.php", "
  <?php
  \$databaseCredentialsFile = PATH_site . './../typo3_config/typo3_db.php';
  if (file_exists(\$databaseCredentialsFile)) {
      require_once (\$databaseCredentialsFile);
  }
        ");
      }

      if (!file_exists("typo3/FIRST_INSTALL")) {
        file_put_contents("typo3/FIRST_INSTALL", "");
      }

      if (!file_exists("typo3_config/typo3_db.php")) {
        $v = explode(".",$t3_version);
        switch ($v[0]) {
          case 6:
            addDbVersion7($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool);
            break;
          case 7:
            addDbVersion7($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool);
            break;
          default:
            addDbVersion8($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool);
        }
      }

      deleteFile("typo3/", "typo3_src");
      deleteFile("typo3/", "typo3");
      deleteFile("typo3/", "index.php");

      check_file_dir("symlink", "typo3/typo3_src", "../" . $t3_src_dir_name . "/" . $t3_version_dir . "/");
      check_file_dir("symlink", "typo3/typo3", "typo3_src/typo3/");
      check_file_dir("symlink", "typo3/index.php", "typo3_src/index.php");

      if(file_exists("typo3/index.php")) {
        echo "<div class='readyToTakeOff'><span class=''>Have fun :)</span></div>";
      }

    } else {
      echo "<span class='error'>Extraction faild!</span>";
    }

  } else {
    echo "<span class='warning'>File {$t3_zip_file} dosent exists!</span>";
  }
}

?>
  </main>
  <footer id="footer">

  </footer>
</div>
<script src="https://rawgit.com/Teisi/typo3-deploy/dev/resources/javascript/pGenerator.min.js"></script>
<script src="https://rawgit.com/Teisi/typo3-deploy/dev/resources/javascript/typo3-simple-deploy.js"></script>
</body>
</html>
