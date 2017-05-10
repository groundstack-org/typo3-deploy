<?php

/**
 * [__construct description]
 * @param (array) $config [description]
 * @param (string) $t3_version [description]
 * @param (string) $t3_src_dir_name [description]
 * @param (string) $t3_version_dir [description]
 * @param (string) $t3_zip_file [description]
 * @param (string) $typo3_source [description]
 * @param (string) $t3_config_date [description]
 * @param (string) $t3_function [description]
 * @param (string) $t3_path_to_source_file [description]
 * @param (string) $classPath [description]
 */
class Deployer extends Helper {

  private $config;
  private $t3_version;
  private $t3_src_dir_name;
  private $t3_version_dir;
  private $t3_zip_file;
  private $typo3_source;
  private $t3_config_date;
  private $t3_function;
  private $t3_path_to_source_file;
  private $documentRoot;
  private $index;

  function __construct($config=false) {
    $this->helper = new Helper();
    $this->documentRoot = $this->helper->getDocumentRoot();
    $this->t3_src_dir_name = "../typo3_sources";
    $this->t3_config_date = date("Ymd_His");
    $this->t3_path_to_source_file = "https://netcologne.dl.sourceforge.net/project/typo3/TYPO3%20Source%20and%20Dummy/TYPO3%20";

    if($config && is_array($config)){
      $this->config = $this->helper->escape_input($config);
      $this->initConfig($this->config);
      $this->deleteDeployment();
    }
  }

  /**
   * [getConfig description]
   * @return (array) [description]
   */
  public function getConfig() {
    return $this->config;
  }

  /**
   * [initConfig description]
   * @param  [type] $config [description]
   * @return [type]         [description]
   */
  public function initConfig($config) {
    $this->t3_version = $config['t3_version'];
    $this->t3_version_dir = "typo3_src-{$this->t3_version}";

    switch ($config['formtype']) {
      case 't3install':
        $this->t3_zip_file = "{$this->t3_version_dir}.tar.gz";
        $this->typo3_source = "https://netcologne.dl.sourceforge.net/project/typo3/TYPO3%20Source%20and%20Dummy/TYPO3%20{$this->t3_version}/{$this->t3_zip_file}";
        break;
        
      case 'deletedeployment':
        $this->deleteDeployment();
        break;

      case 't3sourcedelete':
        $this->index = 0;
        for ($i=0; $i <= $this->config['t3sourcesanz']; $i++) {
          if ($this->config['typo3Source_'.$this->index]) {
            $this->helper->deleteDir($this->helper->getDocumentRoot()."/".$this->t3_src_dir_name."/".$this->config['typo3Source_'.$this->index]);
          }
          $this->index = $this->index + 1;
        }
        break;

      case 'ajaxpost':
        $this->handleAjax();
        break;

      default:
        echo "No specifyed form. Available forms are: t3install, deletedeployment, t3sourcedelete, ajaxpost!";
        break;
    }

    if (empty($this->t3_version)) {
      return false;
    }
    return false;
  }

  /**
   * deletes the Typo3 simple deployment app
   * @return (bool)
   */
  public function deleteDeployment() {
    if(isset($this->config['sendt3versiondelete'])) {
      echo __DIR__;

      // if (chmod (__DIR__, 0777)) {
      //   # code...
      // }
      //
      //
      // echo "<div id='deletedeployment' class='result'>";
      // if ($this->helper->deleteDir("/resources")) {
      //   if (unlink("index.php")) {
      //     echo "<span class='success'>File successfully deleted!</span>";
      //   } else {
      //     echo "<span class='error'>Can't delete Typo3 deployer 'index.php'!</span>";
      //   }
      // } else {
      //   echo "<span class='error'>Can't delete Typo3 deployer 'resources' folder!</span>";
      // }


      // echo "</div>";
      // exit();
    }
    return false;
  }

  /**
   * prepares the installation for Typo3 (symlink, DB-Data, .htaccess, robots.txt ... )
   * @return [bool]
   */
  public function t3install_completeinstall() {
    if($this->config['t3_install_function'] == 'completeinstall') {
      $documentRoot = $this->getDocumentRoot();

      echo "<div id='completeinstall' class='result'>";

      // downloadextractlink();

      if($this->createDir("typo3conf", $documentRoot."/")) {
        $pathToTypo3conf = $documentRoot."/typo3conf";
        $currentDateTime = $this->t3_config_date;

        echo "<span class='successful'>Dir 'typo3conf' successfully created.</span>";

        if(!file_exists($pathToTypo3conf."/AdditionalConfiguration.php")) {
          if(file_put_contents($pathToTypo3conf."/AdditionalConfiguration.php", "
<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}
\$databaseCredentialsFile = PATH_site . './../typo3_config/typo3_db_{$currentDateTime}.php';
if (file_exists(\$databaseCredentialsFile)) {
    require_once (\$databaseCredentialsFile);
}
          ") ) {
            echo "<span class='successful'>File 'AdditionalConfiguration.php' successfully created.</span>";
            if($this->helper->createDir('typo3_config', $documentRoot.'/')) {
              echo "<span class='successful'>Dir 'typo3_config' successfully created.</span>";
              $typo3configPath = $documentRoot.'/typo3_config';


              if (!file_exists($typo3configPath."/typo3_db_{$currentDateTime}.php")) {


                $v = explode(".",$this->t3_version);
                switch ($v[0]) {
                  case 6:
                    // addDbVersion7($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool, $currentDateTime);
                    break;
                  case 7:
                    // addDbVersion7($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool, $currentDateTime);
                    break;
                  default:
                    // addDbVersion8($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool, $currentDateTime);
                }
              }




            } else {
              echo "<span class='error'>Dir 'typo3conf' could not be created!</span>";
            }
          } else {
            echo "<span class='error'>File 'AdditionalConfiguration.php' could not be created!</span>";
          }
        }
      } else {
        echo "<span class='error'>Dir 'typo3conf' could not be created!</span>";
      }

      if (copy($documentRoot."/resources/files/robots.txt", $documentRoot."/robots.txt")) {
        echo "<span class='successful'>File 'robots.txt' successfully created.</span>";
      } else {
        echo "<span class='warning'>File 'robots.txt' could not be created!</span>";
      }

      if (copy($documentRoot."/resources/files/.htaccess", $documentRoot."/.htaccess")) {
        echo "<span class='successful'>File '.htaccess' successfully created.</span>";
      } else {
        echo "<span class='warning'>File '.htaccess' could not be created!</span>";
      }

      if (copy($documentRoot."/resources/files/humans.txt", $documentRoot."/humans.txt")) {
        echo "<span class='successful'>File 'humans.txt' successfully created.</span>";
      } else {
        echo "<span class='warning'>File 'humans.txt' could not be created!</span>";
      }

      if (!file_exists($documentRoot."FIRST_INSTALL")) {
        if(file_put_contents($documentRoot."FIRST_INSTALL", "") != false) {
          echo "<span class='warning'>Security risk! If typo3 is not installed after this, please delete 'FIRST_INSTALL'!</span>";
          echo "<span class='successful'>File 'FIRST_INSTALL' successfully created.</span>";
          echo "<span class='warning'>Security risk! If typo3 is not installed after this, please delete 'FIRST_INSTALL'!</span>";
        } else {
          echo "<span class='error'>File 'FIRST_INSTALL' could not be created!</span>";
        }
      }

      echo "</div>";
    }
  }

  /**
   * download the Typo3 source and extract it, than delets the downloaded zip source
   * @return (bool)
   */
  public function t3install_downloadextract() {
    if($this->config['t3_install_function'] == 'downloadextract') {
      $documentRoot = $this->getDocumentRoot();

      echo "<div id='downloadextract' class='result'>";
      if($this->helper->downloadExternalFile($this->typo3_source, $this->t3_zip_file)) {
        $pathToZipFile = $documentRoot."/".$this->t3_src_dir_name."/".$this->t3_version_dir;

        if(file_exists($pathToZipFile)) {
          if($this->helper->deleteDir($pathToZipFile)) {
            if($this->helper->extractZipFile($documentRoot."/".$this->t3_src_dir_name."/".$this->t3_zip_file, $documentRoot."/../typo3_sources/")) {
              if ($this->helper->deleteFile($documentRoot."/".$this->t3_src_dir_name."/".$this->t3_zip_file)) {
                return true;
              } else {
                return false;
              }
            } else {
              return false;
            }
          }
        } else {
          if($this->helper->extractZipFile($documentRoot."/".$this->t3_src_dir_name."/".$this->t3_zip_file, $documentRoot."/../typo3_sources/")) {
            if ($this->helper->deleteFile($documentRoot."/".$this->t3_src_dir_name."/".$this->t3_zip_file)) {
              return true;
            } else {
              return false;
            }
          } else {
            return false;
          }
        }
      }
      echo "</div>";
    }
  }

  /**
   * download the Typo3 source and extract it, than delets the downloaded zip source.
   * Creates the nessasary symlinks for Typo3
   * @return (bool)
   */
  public function t3install_downloadextractlink() {
    if($this->config['t3_function'] == 'downloadextractlink') {
      echo "<div id='downloadextractlink' class='result'>";
      if ($this->t3install_downloadextract()) {
        if ($this->t3install_onlysymlink()) {

        } else {
          return false;
        }
      } else {
        return false;
      }
    }
    echo "</div>";
  }

  /**
   * creates the nessasary symlinks for Typo3
   * @return (bool)
   */
  public function t3install_onlysymlink() {
    if($this->config['t3_install_function'] == 'onlysymlink') {
      echo "<div id='onlysymlink' class='result'>";
      if($this->helper->createSymlink(realpath($this->helper->getDocumentRoot())."/typo3_src", $this->documentRoot."/{$this->t3_src_dir_name}/{$this->t3_version_dir}")) {
        if ($this->helper->createSymlink(realpath($this->helper->getDocumentRoot())."/typo3", $this->documentRoot."/typo3_src/typo3")) {
          if ($this->helper->createSymlink(realpath($this->helper->getDocumentRoot())."/index.php", $this->documentRoot."/typo3_src/index.php")) {
            return true;
          } else {
            return false;
          }
        } else {
          return false;
        }
      } else {
        return false;
      }
      echo "</div>";
    }
  }

  public function handleAjax() {
    switch ($this->config['ajax_function']) {
      case 'getTypo3Sources':
        return $this->helper->getDirList();
        break;
      default:
        echo "No specifyed ajax_function. Available ajax_function are: getTypo3Sources!";
        break;
    }
  }
}
