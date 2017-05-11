<?php

/**
 * [__construct description]
 */
class Helper {

  function __construct() {

  }

  /**
   * return the path to the document root
   * @return (string) document root path
   */
  public function getDocumentRoot() {
    return $_SERVER["DOCUMENT_ROOT"];
  }

  private function deleteDirs($src) {
    $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if(($file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if(is_dir($full)) {
                  $this->deleteDirs($full);
                }
                else {
                  unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
  }

  /**
   * deletes a folder recursive
   * @param (string) $src - path to dir incl. dirname
   * @return (bool)
   */
  public function deleteDir($src) {
    $src = $this->escape_input($src);
    $src = $src[0];

    $fileType = filetype($src);
    switch ($fileType) {
      case 'dir':
        $this->deleteDirs($src);
        if(!file_exists($src)) {
          echo "<span class='successful'>Dir {$src} successfully deleted.</span>";
          return true;
        } else {
          echo "<span class='error'>Dir {$src} not deleted!</span>";
          return false;
        }
        break;
      default:
        echo "<span class='error'>File: {$src} is filetype {$filetype}</span>";
        if(file_exists($src)) {
          echo "<span class='error'>But file exists!</span>";
        } else {
          echo "<span class='warning'>File not deleted, file not exists!</span>";
        }
        return false;
        break;
    }
  }

  /**
   * deletes a file
   * @param (string) $filename - path to file incl. filename
   * @return (bool)
   */
  public function deleteFile($filename) {
    $filename = $this->escape_input($filename);
    $filename = $filename[0];

    $fileType = filetype($filename);

    switch($fileType) {
      case "file":
        unlink($filename);
        echo "<span class='successful'>File: {$filename} successfully deleted!</span>";
        return true;
        break;
      default:
        echo "<span class='error'>File: {$filename} is filetype {$filetype}</span>";
        if(file_exists($filename)) {
          return false;
        }
        return true;
    }
  }

  /**
   * deletes a symlink file
   * @param (string) $filename - path to symlink incl. symlinkname
   * @return (bool)
   */
  public function deleteLink($filename) {
    $filename = $this->escape_input($filename);
    $filename = $filename[0];

    $fileType = filetype($filename);

    switch($fileType) {
      case "link":
        unlink($filename);
        echo "<span class='successful'>Link: {$filename} successfully deleted!</span>";
        return true;
        break;
      default:
        if(file_exists($filename)) {
          echo "<span class='error'>Link: {$filename} is filetype {$filetype}! File is not deleted!</span>";
          return false;
        } else {
          echo "<span class='success'>Link: {$filename} doesn't exists!</span>";
          return true;
        }
    }
  }

  /**
   * creates a symlink
   * @param (string) $filename - name of the symlink
   * @param (string) $target - path to the file where the symlink links to
   * @return (bool)
   */
  public function createSymlink($filename, $target) {
    $filename = $this->escape_input($filename);
    $filename = $filename[0];
    $target = $this->escape_input($target);
    $target = $target[0];

    if($this->deleteLink($filename) && symlink($target, $filename)) {
      echo "<span class='successful'>Link: {$filename} successfully created.</span>";
      return true;
    } else {
      echo "<span class='error'>Link: {$filename} not created!</span>";
      return false;
    }
  }

  /**
   * creates a folder(dir)
   * @param (string) $dirName - name of the folder without slashes (default - DOCUMENT_ROOT)
   * @param (string) $pathToDir - path where the folder creates with ending slash
   * @return (bool)
   */
  public function createDir($dirName, $pathToDir = false) {
    $pathToDir = $pathToDir ? $pathToDir : $this->getDocumentRoot();

    $pathToDir = $this->escape_input($pathToDir);
    $pathToDir = $pathToDir[0];
    $dirName = $this->escape_input($dirName);
    $dirName = $dirName[0];

    if(!dir($pathToDir.$dirName)){
      if(mkdir($pathToDir.$dirName)) {
        echo "<span class='successful'>Folder: {$dirName} successfully created.</span>";
        return true;
      } else {
        echo "<span class='error'>Folder: {$dirName} can't created!</span>";
        return false;
      }
    } else {
      echo "<span class='successful'>Folder: {$dirName} in {$pathToDir} already exists!</span>";
      return true;
    }
  }

  /**
   * [createFile creates a file if it dosen't exists]
   * @param  (string) $filename filename
   * @param  (string) $pathToFile path to the file to write
   * @param  (string) $fileContent content of file to write
   * @return [bool]        [description]
   */
  public function createFile($filename, $pathToFile = false, $fileContent = false) {
    $pathToFile = $pathToFile ? dir($pathToFile) : $this->getDocumentRoot();
    $fileContent = $fileContent ? $fileContent : " ";

    $filename = $this->escape_input($filename);
    $filename = $filename[0];
    $pathToFile = $this->escape_input($pathToFile);
    $pathToFile = $pathToFile[0];

    $file = fopen($pathToFile.$filename,"x");
    if ($file != false) { // if true return resource
      fclose($file);
      echo "<span class='successful'>File: {$filename} in '{$pathToFile}' successfully created.</span>";
      return true;
    } else {
      echo "<span class='error'>File: {$filename} in '{$pathToFile}' can't created!</span>";
      return false;
    }
  }

  /**
   * downloadExternalFile() - downloads a file from an external source
   * @param (string) $pathToExternalFile - path to external file (url) without filename
   * @param (string) $filename - name of the external file
   * @param (string) $pathToSafeFile - optional - path where to safe the file, with ending slash
   * @return (bool)
   */
  public function downloadExternalFile($pathToExternalFile, $filename, $pathToSafeFile = false) {
    if(!$pathToSafeFile){
      if($this->createDir("typo3_sources", $this->getDocumentRoot()."/../")) {
        $pathToSafeFile = $this->getDocumentRoot()."/../typo3_sources/";
      }
    } else {
      $pathToSafeFile = $pathToSafeFile;
    }

    $pathToExternalFile = $this->escape_input($pathToExternalFile);
    $pathToExternalFile = $pathToExternalFile[0];
    $filename = $this->escape_input($filename);
    $filename = $filename[0];
    $pathToSafeFile = $this->escape_input($pathToSafeFile);
    $pathToSafeFile = $pathToSafeFile[0];

    if (file_exists($pathToSafeFile.$filename)) {
      echo "<span class='warning'>File: {$filename} already exists in {$pathToSafeFile}.</span>";
      return true;
    } else {
      $newfname = $pathToSafeFile.$filename;
      $file = fopen($pathToExternalFile, 'rb');
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
      if(file_exists($pathToSafeFile.$filename)) {
        echo "<span class='successful'>File: {$filename} successfully downloaded to '{$pathToSafeFile}{$filename}'!</span>";
        return true;
      } else {
        echo "<span class='error'>File: {$pathToSafeFile}{$filename} can't downloaded from '{$pathToExternalFile}'!</span>";
        return false;
      }
    }
  }

  /**
   * extracts a zip file
   * @param  [string] $pathToZipFile - path to zip file incl. zip file name
   * @param  [string] $pathToExtract - path where to extract the zip file
   * @return [bool]
   */
  public function extractZipFile($pathToZipFile, $pathToExtract = false) {
    $pathToZipFile = $this->escape_input($pathToZipFile);
    $pathToZipFile = $pathToZipFile[0];
    $pathToExtract = $this->escape_input($pathToExtract);
    $pathToExtract = $pathToExtract[0];

    $pathToExtract = $pathToExtract ? $pathToExtract : $this->getDocumentRoot()."/../typo3_sources/";

    if (file_exists($pathToZipFile)) {
      $phar = new PharData($pathToZipFile);
      if($phar->extractTo($pathToExtract)) {
        echo "<span class='successful'>ZipFile: {$pathToZipFile} successfully extracted!</span>";
        return true;
      } else {
        echo "<span class='error'>ZipFile: {$pathToZipFile} not extracted! ZipFile corrupt?</span>";
        return false;
      }
    } else {
      echo "<span class='error'>ZipFile: {$pathToZipFile} not extracted! File dosen't exist</span>";
      return false;
    }
  }

  /**
   * escape's a given string or array
   * @param  [string or array] $data - array to escape
   * @return [array] - returns the escaped array
   */
  public function escape_input($data) {
    $tmpArray = is_array($data) ? $data : array($data);
    foreach ($tmpArray as &$arr) {
      $tmp_str_replace_orig = array('"', "'", "<", ">", " ");
      $tmp_str_replace_target = array('', "", "", "", "");
      $arr = str_replace($tmp_str_replace_orig, $tmp_str_replace_target, htmlspecialchars(stripslashes(trim($arr))));
    }

    return $tmpArray;
  }

  /**
   * [getDirList description]
   * @param  (string) $t3_sources_dir [description]
   * @return [type]        [description]
   */
  public function getDirList($t3_sources_dir = false) {
    $listdir = $t3_sources_dir ? dir($t3_sources_dir) : $this->getDocumentRoot()."/../typo3_sources";
    $scanDir = scandir($listdir);

    echo "<form id='form-delete-typo3source' class='form-horizontal' method='post' action='#'>";
    echo "<input type='hidden' name='t3_version' value='' />";
    echo "<input type='hidden' name='formtype' value='t3sourcedelete' />";

    echo "<ul id='dirlist'>";

    $i = 0;
    foreach ($scanDir as $k => $v) {
      if($v != "." && $v != "..") {
        echo "<li><label for='typo3Source_{$i}' class='control control--checkbox'>{$v}
            <input type='checkbox' id='typo3Source_{$i}' name='typo3Source_{$i}' form='form-delete-typo3source' value='{$v}'>
            <div class='control__indicator'></div>
          </label></li>";
		    $i = $i + 1;
      }
    }

    echo "</ul>";
    echo "<input type='hidden' name='t3sourcesanz' value='{$i}' id='t3sourcesanz' />";
    echo "<div class='form-actions'>";
    echo "<button id='submitdelete' class='btn btn-success' type='submit' name='sendt3versiondelete' value='Senden' data-translate='_senddelete'>Delete source(s)</button>";
    echo "</div></from>";
  }

  public function addDbVersion7($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool, $currentDateTime) {
    $str = $this->getDocumentRoot()."/../typo3_config/typo3_db_{$currentDateTime}.php";
    if(file_exists($str)) {
      echo "<span class='exists'>File: {$str} already exists.</span>";
      return false;
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

  public function addDbVersion8($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool, $currentDateTime) {
    $str = $this->getDocumentRoot()."/../typo3_config/typo3_db_{$currentDateTime}.php";
    if(file_exists($str)) {
      echo "<span class='exists'>File: {$str} already exists.</span>";
      return false;
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

\$GLOBALS['TYPO3_CONF_VARS']['GFX']['im_path'] = ''; // e.g. OSX Mamp '/Applications/MAMP/Library/bin/'
      ");
      echo "<span clas='success'>File {$str} is created.</span>";
    }
  }
}
