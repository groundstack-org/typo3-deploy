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
  private $classPath;

  function __construct($config=false) {
    $this->helper = new Helper();
    $this->classPath = $this->helper->getClassPath();
    echo "string";
    print_r($this->classPath);
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

      case 'deletedeployment':
        $this->deleteDeployment();
        break;

      case 't3sourcedelete':
        $this->helper->deleteDir($this->t3_src_dir_name.$this->config['t3_function_delete']);
        break;

      default:
        echo "No specifyed form";
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
      echo "<span class='success'>File successfully deleted!</span>";
      $this->helper->deleteDir("/resources");
      unlink("deploy.php");
      exit();
    }
    return false;
  }

  /**
   * prepares the installation for Typo3 (symlink, DB-Data, .htaccess, robots.txt ... )
   * @return [bool]
   */
  public function t3install_completeinstall() {
    if($this->config['t3_function'] == 'completeinstall') {
      echo "bla";
    }
  }

  /**
   * creates the nessasary symlinks for Typo3
   * @return (bool)
   */
  public function t3install_onlysymlink() {
    if($this->config['t3_function'] == 'onlysymlink') {
      echo "<div id='onlysymlink' class='result'>";
      if($this->helper->createSymlink("typo3_src", "{$this->t3_src_dir_name}/{$this->t3_version_dir}")) {
        if ($this->helper->createSymlink("typo3", "typo3_src/typo3")) {
          if ($this->helper->createSymlink("index.php", "typo3_src/index.php")) {
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

  /**
   * download the Typo3 source and extract it, than delets the downloaded zip source
   * @return (bool)
   */
  public function t3install_downloadextract() {
    if($this->config['t3_function'] == 'downloadextract') {
      echo "<div id='downloadextract' class='result'>";
      if($this->helper->downloadExternalFile($this->typo3_source, $this->t3_zip_file)) {
        if($this->helper->extractZipFile($this->t3_zip_file)) {
          if ($this->helper->deleteFile($this->t3_zip_file)) {
            return true;
          } else {
            return false;
          }
        } else {
          return false;
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
        if ($this->helper->deleteFile($this->t3_zip_file)) {
          if ($this->t3install_onlysymlink()) {
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
    }
    echo "</div>";
  }
}

?>
