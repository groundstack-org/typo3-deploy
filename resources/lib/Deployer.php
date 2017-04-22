<?php
/**
 *
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

  function __construct($config=false) {
    $this->t3_src_dir_name = "../typo3_sources";
    $this->t3_config_date = date("Ymd_His");
    $this->t3_path_to_source_file = "https://netcologne.dl.sourceforge.net/project/typo3/TYPO3%20Source%20and%20Dummy/TYPO3%20";
    $this->helper = new Helper();

    if($config && is_array($config)){
      $this->config = $this->helper->escape_input($config);
      $this->initConfig($this->config);
      $this->deleteDeployment();
    }
  }

  public function initConfig($config) {
    $this->t3_version = $config['t3_version'];
    $this->t3_version_dir = "typo3_src-{$this->t3_version}";

    switch ($this->config['formtype']) {
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

  public function deleteDeployment() {
    if(isset($this->config['sendt3versiondelete'])) {
      echo "<span class='success'>File successfully deleted!</span>";
      $this->helper->deleteDir("/resources");
      unlink("deploy.php");
      exit();
    }
    return false;
  }

  public function t3install_completeinstall() {
    if($config['t3_function'] == 'completeinstall') {
      echo "bla";
    }
  }

  public function t3install_onlysymlink() {
    if($config['t3_function'] == 'onlysymlink') {
      echo "<div id='onlysymlink' class='result'>";
      $this->helper->createSymlink("typo3_src", "{$this->t3_src_dir_name}/{$this->t3_version_dir}");
      $this->helper->createSymlink("typo3", "typo3_src/typo3");
      $this->helper->createSymlink("index.php", "typo3_src/index.php");
      echo "</div>";
    }
  }

  public function t3install_downloadextract() {
    if($config['t3_function'] == 'downloadextract') {
      if($this->helper->downloadExternalFile($this->typo3_source, $this->t3_zip_file)) {
          $this->helper->extractZipFile($this->t3_zip_file);
          return true;
      }
    }
  }

  public function t3install_downloadextractlink() {
    if($config['t3_function'] == 'downloadextractlink') {
      if ($this->t3install_downloadextract()) {
        if ($this->t3install_onlysymlink()) {

        }
      }
    }
  }
}

?>
