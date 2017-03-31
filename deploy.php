<?php
error_reporting(E_ALL);
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

$t3_src_dir_name = "typo3_sources/";
$t3_version = "7.6.16";
$t3_version_dir = "typo3_src-{$t3_version}";
$t3_zip_file = "{$t3_version_dir}.tar.gz";
$typo3_source = "https://netcologne.dl.sourceforge.net/project/typo3/TYPO3%20Source%20and%20Dummy/TYPO3%20{$t3_version}/typo3_src-{$t3_version}.tar.gz";

if(!file_exists($t3_src_dir_name)) {
  mkdir($t3_src_dir_name);
}

if (!file_exists($t3_zip_file)) {
  downloadFile($typo3_source, $t3_zip_file);
}

if (file_exists($t3_zip_file)) {
  echo "File {$t3_zip_file} exists<br />";

  if(!file_exists($t3_src_dir_name . $t3_version_dir . "/typo3")) {
    exec("tar -xzvf {$t3_zip_file} -C {$t3_src_dir_name}");
  }

  if (file_exists($t3_src_dir_name . $t3_version_dir . "/typo3")) {
    echo "Successfully extracted!<br />";
    unlink($t3_zip_file);

    if(!file_exists("typo3")) {
      mkdir("typo3");
    }

    if(!file_exists("typo3/typo3conf")) {
      mkdir("typo3/typo3conf/");
    }

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

    if(!file_exists("typo3_config")) {
      mkdir("typo3_config");
    }

    if (!file_exists("typo3_config/typo3_db.php")) {
      file_put_contents("typo3_config/typo3_db.php", "
<?php
\$GLOBALS['TYPO3_CONF_VARS']['DB']['database'] = 'DATABASENAME';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['host'] = 'localhost';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['password'] = 'DATABASEUSERPASSWORT';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['username'] = 'DATABASEKUSERNAME';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['socket'] = '';

// \$GLOBALS['TYPO3_CONF_VARS']['BE']['installToolPassword'] = '';

      ");
    }

    if(!file_exists("typo3/humans.txt")) {
      downloadFile("https://raw.githubusercontent.com/Teisi/typo3-deploy/master/humans.txt", "typo3/humans.txt");
    }
    if(!file_exists("typo3/robots.txt")) {
      downloadFile("https://raw.githubusercontent.com/Teisi/typo3-deploy/master/robots.txt");
    }
    if(!file_exists("typo3/.htaccess")) {
      downloadFile("https://raw.githubusercontent.com/Teisi/typo3-deploy/master/humans.txt", "typo3/humans.txt");
    }

    unlink("typo3/typo3_src");
    unlink("typo3/typo3");
    unlink("typo3/index.php");

    if(symlink("../" . $t3_src_dir_name . $t3_version_dir . "/", "typo3/typo3_src")) {
      echo "Symlink typo3_src created.<br />";

      if (symlink("typo3_src/typo3/", "typo3/typo3")) {
        echo "Symlink typo3 created.<br />";

        if(symlink("typo3_src/index.php", "typo3/index.php")) {
          echo "Symlink index.php created.<br />";
        } else {
          echo "Cant create symlink index.php!<br />";
        }
      } else {
        echo "Cant create symlink typo3!<br />";
      }
    } else {
      echo "Cant create symlink typo3_src!<br />";
    }
  } else {
    echo "Extraction faild!<br />";
  }
} else {
  echo "File {$t3_zip_file} dosent exists!<br />";
}
