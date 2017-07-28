<?php
  require_once("resources/lib/Helper.php");
  require_once("resources/lib/Deployer.php");

  $deployer = new Deployer($_POST);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">

  <title>Typo3 deploy script</title>
  <meta name="description" content="The Typo3 simple deploy script.">
  <link rel="stylesheet" href="resources/css/typo3-simple-deploy.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>

<body>
  <div id="main-wrapper">
    <header id="header">
      <h1>Typo3 simple deployment</h1>
      <select id="lang">
          <option>English</option>
          <option>German</option>
      </select>
      <button id="btn-refresh" class="form-btn" type="button">Refresh this page!</button>
    </header>
    <main id="main">

      /* form to delete deployment script */
      <div id="delete-deployment" class="widget-content nopadding">
        <strong data-translate="_aftersuccess">After success:</strong>
        <form id="form-delete-deployment" class="form-horizontal" method="post" action="<?php echo htmlentities(urlencode($_SERVER['PHP_SELF'])); ?>">
          <div class="control-group">
            <div class="controls">
              <input type="hidden" name="formtype" value="deletedeployment" />
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-success" type="submit" name="sent" value="Senden" data-translate="_send">Send</button>
          </div>
        </form>
      </div>

      /* form to install or change Typo3 installation */
      <form id="ajax_form_test" method="post">
        <input type="text" class="t3_version" name="t3_version" id="text_id" value="7.6.16" autofocus min="5" maxlength="8" required />
        <button id="submit" class="form-btn submit" type="submit" name="sendt3install" value="Senden" data-translate="_send">Send</button>
      </form>

      <div id="form">
        <form id="form-t3-install" class="ajax_form" method="post">
          <input type="hidden" name="formtype" value="t3install" />
					<div class="choose-version">
						<label class="t3_version_label" for="text_id">
              <span data-translate="_yourversion">Enter your desired version:</span><br />
              <span class="info" data-translate="_pleaseuseform">(Please use this form: 6.2.12)</span>
            </label>
						<input type="text" class="t3_version" name="t3_version" id="text_id" value="7.6.16" autofocus min="5" maxlength="8" required />
					</div>
          <div class="choose-function">
						<label class="t3_function_label" for="text_function_id">
              <span data-translate="_t3function">Please choose:</span>
            </label>
            <div class="dropdown dropdown-dark">
              <select class="t3_function dropdown-select" name="t3_function" id="text_function_id" required>
                <option value="completeinstall" selected>First Install</option>
                <option value="onlysymlink">Only change symlink</option>
                <option value="downloadextract">Only download and extract</option>
                <option value="downloadextractlink">Download, extract and change symlink</option>
              </select>
            </div>
					</div>
          <div class="form-db-data">
            <label>
              <span data-translate="_databaseisstored">Database Access data are stored in 'typo3_config/typo3_db.php'.</span>
            </label>
            <label class="label label-name" for="db-name" data-translate="_databasename">Database name</label>
            <input id="db-name" class="input" type="text" name="t3_db_name" value="databaseName">
            <label class="label label-name" for="db-name" data-translate="_databaseuser">Database username</label>
            <input id="db-user" class="input" type="text" name="t3_db_user" value="databaseUser">
            <label class="label label-name" for="db-password" data-translate="_databaseuserpassword">Database userpassword</label>
            <input id="db-password" class="input" type="password" name="t3_db_password" value="databasePasswort">
            <label class="label label-name" for="db-host"data-translate="_databasehost">Database host</label>
            <input id="db-host" class="input" type="text" name="t3_db_host" value="localhost">
            <label class="label label-name" for="db-socket" data-translate="_databasesocket">Database socket</label>
            <input id="db-socket" class="input" type="text" name="t3_db_socket" value="">
          </div>
          <div class="form-install-tool">
            <label data-translate="_installtoolstoredin">Install Tool password is stored in 'typo3_config/typo3_db.php'.</label>
            <label class="label label-install-tool-pw" for="install-tool-pw" data-translate="_installpassword">Install Tool password</label>
            <input type="password" class="input left form-control" id="install-tool-pw" name="t3_install_tool" value="" />
            <a href="#" class="btn btn-danger form-btn" id="generate-install-pw" data-translate="_generatepassword">Generate a password</a>
            <div class="left" id="install-tool-pw-element"></div>
          </div>
					<div class="from_submit">
						<button id="submit" class="form-btn submit" type="submit" name="sendt3install" value="Senden" data-translate="_send">Send</button>
					</div>
  			</form>
      </div>

      /* lists all existing Typo3 sources in folder typo3_sources */
      <div class="list-typo3-sources">
        <?php
          $t3_sources_dir = '../typo3_sources/';
        ?>
        <p><span data-translate="_t3functiondelete_existsversions">Typo3 versions which exists in "../typo3_sources/":</span></p>
        <ul class="list-versions">
          <?php
            $deployer->helper->getDirList();
          ?>
        </ul>
      </div>

      /* form to delete existing Typo3 sources */
      <div id="form_2">
        <form class="form-horizontal" method="post" action="<?php echo htmlentities(urlencode($_SERVER['PHP_SELF'])); ?>">
          <input type="hidden" name="formtype" value="t3sourcedelete" />
  				<ul class="">
            <li class="choose-function_delete">
  						<label class="t3_function_delete_label" for="text_function_delete_id"><span data-translate="_t3functiondelete">Here you can specify and delete the Typo3 version you no longer need:</span></label>
              <div class="dropdown dropdown-dark">
                <select class="t3_function_delete dropdown-select" name="t3_function_delete" id="text_function_delete_id" required>
                  <?php
                    // function listOptionSources($t3_sources_dir) {
                    //   $optiondir = dir($t3_sources_dir);
                    //   while(($f = $optiondir->read()) != false) {
                    //       if($f != "." && $f != "..") {
                    //          echo "<option value='".$f."'>".$f."</option>";
                    //       }
                    //   }
                    //   $optiondir->close();
                    // }
                    // listOptionSources($t3_sources_dir);
                  ?>
                </select>
              </div>
  					</li>
            <li class="from_submit">
              <button id="submitdelete" class="form-btn submit" type="submit" name="sendt3versiondelete" value="Senden" data-translate="_senddelete">Delete</button>
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
        // $deployer->t3install_onlysymlink();
      ?>


<?php
// $deployer->t3install_completeinstall();

$t3_version = "empty";



if(isset($_POST['sent'])) {
  $t3_version = $t3_db_user = $t3_db_name = $t3_db_user = $t3_db_host = $t3_db_socket = $t3_install_tool = $t3_function = "";

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
    $t3_function = escape_input($_POST['t3_function']);

    $t3_install_tool = escape_input($_POST['t3_install_tool']);
    $t3_install_tool = md5($t3_install_tool);
  }

  $t3_src_dir_name = "../typo3_sources";
  $t3_version_dir = "typo3_src-{$t3_version}";
  $t3_zip_file = "{$t3_version_dir}.tar.gz";
  $typo3_source = "https://netcologne.dl.sourceforge.net/project/typo3/TYPO3%20Source%20and%20Dummy/TYPO3%20{$this->t3_version}/";
  $t3_config_date = date("Ymd_His");

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

  function addDbVersion7($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool, $t3_config_date) {
    $str = "../typo3_config/typo3_db_" . $t3_config_date . ".php";
    if(file_exists($str)) {
      echo "<span class='exists'>File: {$str} already exists.</span><br />";
    } else {
      file_put_contents((string)$str, "
<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}
\$GLOBALS['TYPO3_CONF_VARS']['DB']['database'] = '{$t3_db_name}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['host'] = '{$t3_db_host}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['password'] = '{$t3_db_password}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['username'] = '{$t3_db_user}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['socket'] = '{$t3_db_socket}';

\$GLOBALS['TYPO3_CONF_VARS']['BE']['installToolPassword'] = '{$t3_install_tool}';

\$GLOBALS['TYPO3_CONF_VARS']['GFX']['im_path'] = ''; // e.g. OSX Mamp '/Applications/MAMP/Library/bin/'
      ");
      echo "<span class='success'>File {$str} is created.</span>";
    }
  }

  function addDbVersion8($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool, $t3_config_date) {
    $str = "../typo3_config/typo3_db_" . $t3_config_date . ".php";
    if(file_exists($str)) {
      echo "<span class='exists'>File: {$str} already exists.</span><br />";
    } else {
      file_put_contents($str, "
<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}
\$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['charset'] = 'utf8';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['driver'] = 'mysqli';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = '{$t3_db_name}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] = '{$t3_db_host}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = '{$t3_db_password}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = '{$t3_db_user}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['unix_socket'] = '{$t3_db_socket}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['port'] = 3306;

\$GLOBALS['TYPO3_CONF_VARS']['BE']['installToolPassword'] = '{$t3_install_tool}';

\$GLOBALS['TYPO3_CONF_VARS']['GFX']['im_path'] = '';
      ");
      echo "<span clas='success'>File {$str} is created.</span><br />";
    }
  }

  if($t3_function == "firstinstall") {
    check_file_dir("dir", "../typo3_config");
    check_file_dir("dir", "typo3conf");
    check_file_dir("dir", $t3_src_dir_name);

    check_file_dir("external_file", "humans.txt", "https://raw.githubusercontent.com/Teisi/typo3-deploy/master/humans.txt");
    check_file_dir("external_file", "robots.txt", "https://raw.githubusercontent.com/Teisi/typo3-deploy/master/robots.txt");
    check_file_dir("external_file", ".htaccess", "https://raw.githubusercontent.com/Teisi/typo3-deploy/master/.htaccess");

    check_file_dir("external_file", $t3_zip_file, $typo3_source);

    if (file_exists($t3_zip_file)) {
      echo "<span class='warning'>File {$t3_zip_file} exists</span>";

      if(!file_exists($t3_src_dir_name . "/" . $t3_version_dir . "/typo3")) {
        // unarchive from the tar
        $phar = new PharData($t3_zip_file);
        $phar->extractTo($t3_src_dir_name);

        // exec("tar -xzvf {$t3_zip_file} -C {$t3_src_dir_name}");
      }

      if (file_exists($t3_src_dir_name . "/" . $t3_version_dir . "/typo3")) {
        echo "<span class='success'>Successfully extracted!</span><br />";
        deleteFile("", $t3_zip_file);

        if(!file_exists("typo3conf/AdditionalConfiguration.php")) {
          file_put_contents("typo3conf/AdditionalConfiguration.php", "
    <?php
    if (!defined('TYPO3_MODE')) {
    	die('Access denied.');
    }
    \$databaseCredentialsFile = PATH_site . './../typo3_config/typo3_db_{$t3_config_date}.php';
    if (file_exists(\$databaseCredentialsFile)) {
        require_once (\$databaseCredentialsFile);
    }
          ");
        }

        if (!file_exists("FIRST_INSTALL")) {
          file_put_contents("FIRST_INSTALL", "");
        }

        if (!file_exists("../typo3_config/typo3_db_{$t3_config_date}.php")) {
          $v = explode(".",$t3_version);
          switch ($v[0]) {
            case 6:
              addDbVersion7($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool, $t3_config_date);
              break;
            case 7:
              addDbVersion7($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool, $t3_config_date);
              break;
            default:
              addDbVersion8($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool, $t3_config_date);
          }
        }

        deleteFile("", "typo3_src");
        deleteFile("", "typo3");
        deleteFile("", "index.php");

        check_file_dir("symlink", "typo3_src", $t3_src_dir_name . "/" . $t3_version_dir . "/");
        check_file_dir("symlink", "typo3", "typo3_src/typo3/");
        check_file_dir("symlink", "index.php", "typo3_src/index.php");

        if(file_exists("index.php")) {
          echo "<div class='readyToTakeOff'><span class=''>Have fun :)</span></div>";
        }

      } else {
        echo "<span class='error'>Extraction faild!</span>";
      }

    } else {
      echo "<span class='warning'>File {$t3_zip_file} dosent exists!</span>";
    }
  } else if($t3_function == "onlysymlink") {
    deleteFile("", "typo3_src");
    deleteFile("", "typo3");
    deleteFile("", "index.php");

    check_file_dir("symlink", "typo3_src", $t3_src_dir_name . "/" . $t3_version_dir . "/");
    check_file_dir("symlink", "typo3", "typo3_src/typo3/");
    check_file_dir("symlink", "index.php", "typo3_src/index.php");
  } else if($t3_function == "downloadextract") {
    check_file_dir("dir", $t3_src_dir_name);
    check_file_dir("external_file", $t3_zip_file, $typo3_source);

    if (file_exists($t3_zip_file)) {
      echo "<span class='warning'>File {$t3_zip_file} exists</span>";

      if(!file_exists($t3_src_dir_name . "/" . $t3_version_dir . "/typo3")) {
        // unarchive from the tar
        $phar = new PharData($t3_zip_file);
        $phar->extractTo($t3_src_dir_name);
      }

      if(file_exists($t3_src_dir_name . "/" . $t3_version_dir . "/typo3")) {
        echo "<span class='success'>Successfully extracted!</span><br />";
        deleteFile("", $t3_zip_file);
        echo "<div class='readyToTakeOff'><span class=''>Have fun :)</span></div>";
      }
    }

  } else if($t3_function == "downloadextractlink") {
    check_file_dir("dir", $t3_src_dir_name);
    check_file_dir("external_file", $t3_zip_file, $typo3_source);

    if (file_exists($t3_zip_file)) {
      echo "<span class='warning'>File {$t3_zip_file} exists</span>";

      if(!file_exists($t3_src_dir_name . "/" . $t3_version_dir . "/typo3")) {
        // unarchive from the tar
        $phar = new PharData($t3_zip_file);
        $phar->extractTo($t3_src_dir_name);
      }

      if(file_exists($t3_src_dir_name . "/" . $t3_version_dir . "/typo3")) {
        echo "<span class='success'>Successfully extracted!</span><br />";
        deleteFile("", $t3_zip_file);
      }
    }

    deleteFile("", "typo3_src");
    deleteFile("", "typo3");
    deleteFile("", "index.php");

    check_file_dir("symlink", "typo3_src", $t3_src_dir_name . "/" . $t3_version_dir . "/");
    check_file_dir("symlink", "typo3", "typo3_src/typo3/");
    check_file_dir("symlink", "index.php", "typo3_src/index.php");

    if(file_exists("index.php")) {
      echo "<div class='readyToTakeOff'><span class=''>Have fun :)</span></div>";
    }
  }
}
?>
  </main>
  <footer id="footer">
    <p>Developed by <a href="https://www.facebook.com/profile.php?id=100007889897625" title="developed by">Christian Hackl</a> from <a href="http://groundstack.de" title="Created by">groundstack.de</a></p>
  </footer>
</div>
<script src="resources/javascript/pGenerator.min.js"></script>
<script src="resources/javascript/typo3-simple-deploy.js"></script>
<script>
 (function($) {
   $("#form-t3-install, #form-t3-delete").attr("action", "deploy.php");
   $("#btn-refresh").on("click touchend", function() {
     window.location.href = window.location.href;
   });
 })(jQuery);

</script>
</body>
</html>
