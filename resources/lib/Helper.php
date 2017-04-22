<?php
/**
 *
 */
class Helper {

  function __construct() {

  }

  public function deleteFile($filename) {
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

  public function deleteDir($src) {
    $fileType = filetype($filename);

    switch ($fileType) {
      case 'dir':
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if(($file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if(is_dir($full)) {
                    $this->rrmdir($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        if(rmdir($src)) {
          echo "<span class='successful'>Dir {$src} successfully deleted.</span>";
          return true;
        } else {
          echo "<span class='error'>Dir {$src} not deleted!</span>";
          return false;
        }
        break;
      default:
        echo "<span class='error'>File: {$filename} is filetype {$filetype}</span>";
        return false;
        break;
    }

  }

  public function deleteLink($filename) {
    $fileType = filetype($filename);

    switch($fileType) {
      case "link":
        unlink($filename);
        echo "<span class='successful'>Link: {$filename} successfully deleted!</span>";
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

  public function createSymlink($filename, $target) {
    $tmpFilename = escape_input($filename);
    $tmpTarget = escape_input($target);

    if(deleteLink($tmpFilename) && symlink($tmpTarget, $tmpFilename)) {
      echo "<span class='successful'>Link: {$tmpFilename} successfully created.</span>";
    } else {
      echo "<span class='error'>Link: {$tmpFilename} not created!</span>";
    }
  }

  public function downloadExternalFile($pathToExternalFile, $filename, $pathToSafeFile = "/") {
    $newfname = $pathToSafeFile;
    $url = $pathToExternalFile.$filename;
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
    if(file_exists($pathToSafeFile.$filename)) {
      echo "<span class='successful'>File: {$filename} successfully downloaded to {$pathToSafeFile}.</span>";
      return true;
    } else {
      echo "<span class='error'>File: {$url} not downloaded!</span>";
      return false;
    }
  }

  public function extractZipFile($pathToZipFile, $pathToExtract = "") {
    $phar = new PharData($pathToZipFile);
    $phar->extractTo($pathToExtract);
  }

  public function escape_input($data) {
    $tmpArray = is_array($data) ? $data : array($data);
    foreach ($tmpArray as &$arr) {
      $tmp_str_replace_orig = array('"', "'", "<", ">", " ");
      $tmp_str_replace_target = array('', "", "", "", "");
      $arr = str_replace($tmp_str_replace_orig, $tmp_str_replace_target, htmlspecialchars(stripslashes(trim($arr))));
    }
    return $tmpArray;
  }
}
