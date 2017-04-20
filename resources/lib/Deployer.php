<?php
/**
 *
 */
class Deployer {

  private $config;
  private $t3_version;
  private $t3_src_dir_name;
  private $t3_version_dir;
  private $t3_zip_file;
  private $typo3_source;
  private $t3_config_date;
  private $t3_function;

  function __construct($config) {
    $this->t3_src_dir_name = "../typo3_sources";
    $this->t3_config_date = date(">Ymd_His");
    $this->config = $config;
    $this->initConfig($config);
  }

  public function initConfig($config) {
    $this->t3_version = $this->escape_input($config['t3_version']);
    $this->t3_version_dir = "typo3_src-{$config['t3_version']}";


    switch ($this->config['formtype']) {
      case 't3install':
        $this->t3_zip_file = "{$this->t3_version_dir}.tar.gz";
        $this->typo3_source = "https://netcologne.dl.sourceforge.net/project/typo3/TYPO3%20Source%20and%20Dummy/TYPO3%20{$this->t3_version}/{$this->t3_zip_file}";

        switch ($config['t3_function']) {
          case 'completeinstall':
            # code...
            break;

          case 'onlysymlink':
            # code...
            break;

          case 'downloadextract':
            # code...
            break;

          case 'downloadextractlink':
            # code...
            break;

          default:
            # code...
            break;
        }
        return true;
        break;

      case 'deletedeployment':
        # code...
        return true;
        break;

      case 't3sourcedelete':
        $this->rrmdir($src);
        return true;
        break;

      default:
        return false;
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
      $this->rrmdir("/resources");
      unlink("deploy.php");
      exit();
    }
    return false;
  }



  private function escape_input($data) {
    $tmp_str_replace_orig = array('"', "'", "<", ">", " ");
    $tmp_str_replace_target = array('', "", "", "", "");
    return str_replace($tmp_str_replace_orig, $tmp_str_replace_target, htmlspecialchars(stripslashes(trim($data))));
  }

  private function rrmdir($src) {
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
    rmdir($src);
  }
}

?>
