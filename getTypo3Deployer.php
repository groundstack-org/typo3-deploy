<html>
<head>
  <style>
    body span { float: left; padding: 5px 15px; width: 100%; }
  </style>
</head>
<body>
<?php

  function getDocumentRoot() {
    return $_SERVER["DOCUMENT_ROOT"];
  }

  function createDir($dirName, $pathToDir = false) {
    $pathToDir = $pathToDir ? $pathToDir : getDocumentRoot();

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

  function downloadExternalFile($pathToExternalFile, $filename, $pathToSafeFile = false) {
    if(!$pathToSafeFile){
      if(createDir("deploy")) {
        $pathToSafeFile = getDocumentRoot();
      }
    } else {
      $pathToSafeFile = $pathToSafeFile;
    }

    $pathToExternalFile = $pathToExternalFile.$filename;

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
        echo "<span class='error'>File: {$filename} can't downloaded from '{$pathToExternalFile}{$filename}'!</span>";
        return false;
      }
    }
  }

  function extractZipFile($pathToZipFile, $pathToExtract = false) {

    $pathToExtract = $pathToExtract ? $pathToExtract : getDocumentRoot();

    if (file_exists($pathToZipFile)) {
      $zip = new ZipArchive;
      if ($zip->open($pathToZipFile) === TRUE) {
        $zip->extractTo($pathToExtract);
        $zip->close();
        echo "<span class='successful'>ZipFile: {$pathToZipFile} successfully extracted!</span>";
      	return true;
      } else {
        echo "<span class='error'>ZipFile: {$pathToZipFile} not extracted! File dosen't exist</span>";
      	return false;
      }
    } else {
      echo "<span class='error'>ZipFile: {$pathToZipFile} not extracted! File dosen't exist</span>";
      return false;
    }
  }

echo "<span>Downloading and installing 'Typo3 simple deployment'</span>";
if(downloadExternalFile('https://github.com/Teisi/typo3-deploy/archive/', 'master.zip')) {
  if(extractZipFile(getDocumentRoot()."master.zip")) {
    echo "<span>That script should be deleted! Now trys to delete itself!</span>";
    unlink(getDocumentRoot()."master.zip");
    if(rename(getDocumentRoot()."typo3-deploy-master", getDocumentRoot()."deploy")) {
      $url = $_SERVER['SERVER_NAME'];
      echo "<span>Now you can open <a href='//{$url}/deploy/index.php' title='click me'>Typo3 deployment</a>.</span>";
    }


    unlink(getDocumentRoot()."/".basename(__FILE__));
    echo "<span>Please reload this page to check if this file is deleted! If not, please delete it!</span>";
  }
}
?>
</bod>
</html>
