<?php
/**
 * [__construct description]
 */
class Helper {

  function __construct() {

  }

  /**
   * return the path to this class (helper.php) file
   * @return (string)
   */
  public function getClassPath() {
    return dirname((new ReflectionClass(static::class))->getFileName());
  }

  /**
   * return the path to the parent class file
   * @return (string)
   */
  public function getParentClassPath() {
    return dirname((new ReflectionClass(static::class))->getFileName()) . "../";
  }

  /**
   * deletes a file
   * @param (string) $filename - path to file incl. filename
   * @return (bool)
   */
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

  /**
   * deletes a folder recursive
   * @param (string) $src - path to dir incl. dirname
   * @return (bool)
   */
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

  /**
   * deletes a symlink file
   * @param (string) $filename - path to symlink incl. symlinkname
   * @return (bool)
   */
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

  /**
   * creates a symlink
   * @param (string) $filename - name of the symlink
   * @param (string) $target - path to the file where the symlink links to
   * @return (bool)
   */
  public function createSymlink($filename, $target) {
    $tmpFilename = escape_input($filename);
    $tmpTarget = escape_input($target);

    if(deleteLink($tmpFilename) && symlink($tmpTarget, $tmpFilename)) {
      echo "<span class='successful'>Link: {$tmpFilename} successfully created.</span>";
      return true;
    } else {
      echo "<span class='error'>Link: {$tmpFilename} not created!</span>";
      return false;
    }
  }

  /**
   * downloadExternalFile() - downloads a file from an external source
   * @param (string) $pathToExternalFile - path to external file (url) without filename
   * @param (string) $filename - name of the external file
   * @param (string) $pathToSafeFile - optional - path where to safe the file
   * @return (bool)
   */
  public function downloadExternalFile($pathToExternalFile, $filename, $pathToSafeFile = false) {
    $pathToSafeFile = $pathToSafeFile ? $pathToSafeFile : $this->getParentClassPath();
    echo "          classPath: " . $this->classPath;
    echo "       path to safe:  " . $pathToSafeFile;
    if (file_exists($pathToSafeFile.$filename)) {
      echo "<span class='warning'>File: {$filename} already exists in {$pathToSafeFile}.</span>";
      return false;
    } else {
      $newfname = $pathToSafeFile;
      $file = fopen($pathToExternalFile, 'rb');
      if($file) {
          $newf = fopen($filename, 'wb');
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
      echo "  nachDownload";
      if(file_exists($pathToSafeFile.$filename)) {
        echo "<span class='successful'>File: {$filename} successfully downloaded to {$pathToSafeFile}.</span>";
        return true;
      } else {
        echo "<span class='error'>File: {$pathToSafeFile}{$filename} not downloaded!</span>";
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
  public function extractZipFile($pathToZipFile, $pathToExtract = ".") {
    $phar = new PharData($pathToZipFile);
    $phar->extractTo($pathToExtract);
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
}
