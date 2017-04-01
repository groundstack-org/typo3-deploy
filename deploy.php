<?php
  $t3_version = "7.6.16";
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">

  <title>Typo3 deploy script</title>
  <meta name="description" content="The HTML5 Herald">

  <style>
    *, *:before, *:after {padding:0;margin:0;-webkit-box-sizing:inherit;-moz-box-sizing:inherit;box-sizing:inherit;}html,body,div,span,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,abbr,address,cite,code,del,dfn,em,img,ins,kbd,q,samp,small,strong,sub,sup,var,b,i,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,figcaption,figure,footer,header,hgroup,menu,nav,section,main,summary,time,mark,audio,video{margin:0;padding:0;border:0;outline:0;vertical-align:baseline;background:transparent;position: relative;}article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section,main{display:block}nav ul{list-style:none}blockquote,q{quotes:none}blockquote:before,blockquote:after,q:before,q:after{content:none}a{margin:0;padding:0;font-size:100%;vertical-align:baseline;background:transparent;text-decoration:none;}ins{background-color:#ff9;color:#000;text-decoration:none}mark{background-color:#ff9;color:#000;font-style:italic;font-weight:bold}del{text-decoration:line-through}abbr[title],dfn[title]{border-bottom:1px dotted;cursor:help}table{border-collapse:collapse;border-spacing:0;}hr{display:block;float:left;height:1px;border:0;border-top:1px solid #ccc;margin:1em 0;padding:0;width:100%;}input,select{ vertical-align: middle; outline: 0; } input:focus {outline: 0 none;}html, body { font-size: 100.1%; min-height: 100%; min-width: 310px; position: relative; width: 100%; -webkit-overflow-scrolling: touch; }html {height: 100%;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}body {line-height:1;height: auto;-webkit-text-size-adjust: none; -ms-text-size-adjust: none; text-size-adjust: none; overflow-x:hidden; -webkit-backface-visibility: hidden; -moz-backface-visibility: hidden; -ms-backface-visibility: hidden; -o-backface-visibility: hidden; backface-visibility: hidden; height: 100%; background-color: #FFF; }img { display: block; height: auto; max-width: 100%; }a { text-decoration: none; -webkit-transition: color 300ms; -moz-transition: color 300ms; -ms-transition: color 300ms; transition: color 300ms; }a img { border: none; }template, .template { display: none; opacity: 0; visibility: hidden; }.br-to-old { background: red; padding: 1%; position: relative; width: 100%; }.ie img[src*=".svg"] {width: 100%; }
    @media screen and (-ms-high-contrast: active), (-ms-high-contrast: none) {
      img[src*=".svg"] {width: 100%; }
    }

    body { font-family: sans-serif; }
    span { clear: both; float: left; min-width: 300px; padding: 10px 0; }
    div, #header, #main, #footer, #main-wrapper { float: left; width: 100%; }
    #main-wrapper { padding: 15px 6%; }
    #header { border-bottom: 2px solid; margin-bottom: 10px; }
    #main > span { padding: 0 6%; }
    #main > div { margin: 10px 0; padding: 5px; border: 1px solid; }
    .hidden { display: none; }
    .warning { background-color: orange; color: #fff; }
    .error { background-color: darkred; color; #fff; }
    .success { background-color: green; }
    .exists { background-color: grey; }
    .readyToTakeOff { border-top: 2px solid; margin-top: 10px; text-align: center; }
  </style>
</head>

<body>
  <div id="main-wrapper">
    <header id="header">
      <h1>Typo3 simple deployment</h1>
    </header>
    <main id="main">
<?php

$t3_src_dir_name = "typo3_sources";
$t3_version_dir = "typo3_src-{$t3_version}";
$t3_zip_file = "{$t3_version_dir}.tar.gz";
$typo3_source = "https://netcologne.dl.sourceforge.net/project/typo3/TYPO3%20Source%20and%20Dummy/TYPO3%20{$t3_version}/typo3_src-{$t3_version}.tar.gz";


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
    exec("tar -xzvf {$t3_zip_file} -C {$t3_src_dir_name}");
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
    echo "<span>Extraction faild!</span>";
  }

} else {
  echo "<span>File {$t3_zip_file} dosent exists!</span>";
}

?>
  </main>
  <footer id="footer">

  </footer>
</div>
<script></script>
</body>
</html>
