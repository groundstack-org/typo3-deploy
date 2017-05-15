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
  private $deployerFileConfig;
  private $deployerFileConfigPath;

  function __construct($config = false) {
    $this->initSession();
    $this->helper = new Helper();
    $this->documentRoot = $this->helper->getDocumentRoot();
    $this->t3_src_dir_name = "../typo3_sources";
    $this->t3_config_date = date("Ymd_His");
    $this->t3_path_to_source_file = "https://netcologne.dl.sourceforge.net/project/typo3/TYPO3%20Source%20and%20Dummy/TYPO3%20";
    $this->initDeployerFileConfig();

    if($config && is_array($config)){
      $this->config = $this->helper->escape_input($config);
      $this->initConfig($this->config);
    }
  }

  /**
   * [initConfig description]
   * @param  [type] $config [description]
   * @return [type]         [description]
   */
  public function initConfig($config) {
    if(isset($config['t3_version'])) {
      $this->t3_version = $config['t3_version'];
    }

    $this->t3_version_dir = "typo3_src-{$this->t3_version}";

    switch ($config['formtype']) {
      case 't3install':
        $this->t3_zip_file = "{$this->t3_version_dir}.tar.gz";
        $this->typo3_source = "https://netcologne.dl.sourceforge.net/project/typo3/TYPO3%20Source%20and%20Dummy/TYPO3%20{$this->t3_version}/{$this->t3_zip_file}";
        break;

      case 'deletedeployment':
        break;

      case 't3sourcedelete':
        break;

      case 'deletetypo3temp':
        break;

      case 'ajaxpost':
        break;

      case 'login':
        break;

      case 'logout':
        break;

      default:
        echo "No specifyed form. Available forms are: t3install, deletedeployment, t3sourcedelete, deletetypo3temp, ajaxpost!";
        break;
    }

    if (empty($this->t3_version)) {
      return false;
    }
    return false;
  }

  /**
   * [getConfig description]
   * @return (array) [description]
   */
  public function getConfig() {
    return $this->config;
  }

  /**
   * [userSetPassword description]
   * @param  [type] $pw [description]
   * @return [type]     [description]
   */
  public function userSetPassword($pw) {
    if (!file_exists($this->documentRoot."/../typo3_config/deployer_config.php")) {
      $this->helper->createFile("deployer_config.php", $this->documentRoot."/../typo3_config/", "<?php return array( 'config' => array( 'login_pw' => '".md5($pw)."' ) );");
      return true;
    } else {
      return false;
    }
  }

  /**
   * [loginForm description]
   * @return [type] [description]
   */
  public function userLoginForm() {
    echo "
    <form id='form-login' class='userlogin' method='POST' >
      <input type='hidden' name='formtype' value='login' />
      <label class='control-label' for='user-pw' data-translate=''>Login</label>
      <div class='controls'>
        <input id='user-pw' class='input-small span2' type='password' name='login_pw' value=''>
      </div>
    </form>
    ";
  }

  /**
   * [userLogoutForm description]
   * @return [type] [description]
   */
  public function userLogoutForm() {
    echo "
    <form id='form-logout' class='userlogin'>
      <input type='hidden' name='formtype' value='logout' />
      <input type='hidden' name='deploymentfolder' value='".__DIR__."' />
      <label class='control-label' for='user-pw' data-translate=''>Logout</label>
    </form>
    ";
  }

  /**
   * [userLogout description]
   * @return [type] [description]
   */
  public function userLogout() {
    session_destroy();
    echo "<div id='alert-logedout'>You were logedout!</div>";
    echo "<script>setTimeout(function() { location.reload(); }, 4000);</script>";
    exit();
  }

  /**
   * [initDeployerFileConfig description]
   * @return [type] [description]
   */
  public function initDeployerFileConfig() {
    $this->$deployerFileConfigPath = file_exists($this->documentRoot."/../typo3_config/deployer_config.php") ? $this->documentRoot."/../typo3_config/deployer_config.php" : false;

    if($this->$deployerFileConfigPath) {
      $this->$deployerFileConfig = include_once($this->$deployerFileConfigPath);
    }
  }

  /**
   * [userLoginCheck description]
   * @return [type] [description]
   */
  public function userLoginCheck() {
    if($_SESSION['login_pw'] == $this->deployerFileConfig['config']['login_pw'] && $_SESSION['browser'] == $_SERVER['HTTP_USER_AGENT'] && $_SESSION['ip'] == $_SERVER['REMOTE_ADDR']) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * [initSession description]
   * @param  boolean $pw [description]
   * @return [type]      [description]
   */
  public function initSession($pw = false) {
    $this->config['login_pw'] = $pw ? $pw : ($this->config['login_pw'] ? $this->config['login_pw'] : false);
    if ($this->config['login_pw']) {
      session_start();
      $_SESSION['login_pw'] = md5($this->config['login_pw']);
      $_SESSION['browser'] = $_SERVER['HTTP_USER_AGENT'];
      $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
      $this->userLogoutForm();
      return true;
    } else {
      $this->userLoginForm();
      return false;
    }
  }

  /**
   * deletes the Typo3 simple deployment app
   * @return (bool)
   */
  public function deleteDeployment() {
    if ($this->config['formtype'] == 'deletedeployment') {
      if ($this->helper->deleteDir($this->config['deploymentfolder'])) {
        echo "<h1>Deployment tool successfully deleted!</h1>";
        echo "<h3>Script reloads after 5 seconds, to test if it is really deleted.</h3>";
        echo "<script>var timeout = setTimeout('location.reload(true);',5000);</script>";
      } else {
        echo "<span class='error'>Deployment tool not deleted! Error occurred!</span>";
      }
    } else {
      return false;
    }
  }

  /**
   * [typo3SourceDelete description]
   * @return [type] [description]
   */
  public function typo3SourceDelete() {
    if($this->config['formtype'] == 't3sourcedelete') {
      $this->index = 0;
      for ($i=0; $i <= $this->config['t3sourcesanz']; $i++) {
        if ($this->config['typo3Source_'.$this->index]) {
          if($this->helper->deleteDir($this->documentRoot."/".$this->t3_src_dir_name."/".$this->config['typo3Source_'.$this->index])) {
            echo "<span class='successful'>Successfully deleted ".$this->config['typo3Source_'.$this->index]."</span>";
          } else {
            echo "<span class='error'>Can't delete ".$this->config['typo3Source_'.$this->index]."</span>";
          }
        }
        $this->index = $this->index + 1;
      }
    }
  }

  /**
   * prepares the installation for Typo3 (symlink, DB-Data, .htaccess, robots.txt ... )
   * @return [bool]
   */
  public function t3install_completeinstall() {
    if($this->config['t3_install_function'] == 'completeinstall') {
      $documentRoot = $this->documentRoot;

      echo "<div id='completeinstall' class='result'>";

      $this->config['t3_install_function'] = 'downloadextractlink';
      if($this->t3install_downloadextractlink()) {
        echo "<span class='successful'>Download and symlinks successfully created.</span>";
      } else {
        echo "<span class='error'>Download or symlinks not created!</span>";
      }

      if($this->createDir("typo3conf")) {
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

            if($this->helper->createDir('typo3_config', $documentRoot.'/../')) {
              echo "<span class='successful'>Dir 'typo3_config' successfully created.</span>";
              $typo3configPath = $documentRoot.'/../typo3_config';

              if(!file_exists($typo3configPath."/typo3_db_{$currentDateTime}.php")) {
                $v = explode(".",$this->t3_version);
                switch ($v[0]) {
                  case 6:
                    $this->helper->addDbVersion7($this->config['t3_db_name'], $this->config['t3_db_host'], $this->config['t3_db_password'], $this->config['t3_db_user'], $this->config['t3_db_socket'], $this->config['t3_install_tool'], $currentDateTime);
                    break;
                  case 7:
                    $this->helper->addDbVersion7($this->config['t3_db_name'], $this->config['t3_db_host'], $this->config['t3_db_password'], $this->config['t3_db_user'], $this->config['t3_db_socket'], $this->config['t3_install_tool'], $currentDateTime);
                    break;
                  default:
                    $this->helper->addDbVersion8($this->config['t3_db_name'], $this->config['t3_db_host'], $this->config['t3_db_password'], $this->config['t3_db_user'], $this->config['t3_db_socket'], $this->config['t3_install_tool'], $currentDateTime);
                }
              }
            } else {
              echo "<span class='error'>Dir 'typo3_config' could not be created!</span>";
            }

          } else {
            echo "<span class='error'>File 'AdditionalConfiguration.php' could not be created!</span>";
          }
        }
      } else {
        echo "<span class='error'>Dir 'typo3conf' could not be created!</span>";
      }

      if (copy($this->config['deploymentfolder']."/resources/files/robots.txt", $documentRoot."/robots.txt")) {
        echo "<span class='successful'>File 'robots.txt' successfully created.</span>";
      } else {
        echo "<span class='warning'>File 'robots.txt' could not be created!</span>";
      }

      if (copy($this->config['deploymentfolder']."/resources/files/.htaccess", $documentRoot."/.htaccess")) {
        echo "<span class='successful'>File '.htaccess' successfully created.</span>";
      } else {
        echo "<span class='warning'>File '.htaccess' could not be created!</span>";
      }

      if (copy($this->config['deploymentfolder']."/resources/files/humans.txt", $documentRoot."/humans.txt")) {
        echo "<span class='successful'>File 'humans.txt' successfully created.</span>";
      } else {
        echo "<span class='warning'>File 'humans.txt' could not be created!</span>";
      }

      if (!file_exists($documentRoot."/FIRST_INSTALL")) {
        if(file_put_contents($documentRoot."/FIRST_INSTALL", "") != false) {
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
          if($this->helper->extractZipFile($documentRoot."/".$this->t3_src_dir_name."/".$this->t3_zip_file, $documentRoot."/".$this->t3_src_dir_name)) {
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
    if($this->config['t3_install_function'] == 'downloadextractlink') {
      echo "<div id='downloadextractlink' class='result'>";

      $this->config['t3_install_function'] = 'downloadextract';
      if ($this->t3install_downloadextract()) {

        $this->config['t3_install_function'] = 'onlysymlink';
        if($this->t3install_onlysymlink()) {
          return true;
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
   * creates the nessasary symlinks for Typo3
   * @return (bool)
   */
  public function t3install_onlysymlink() {
    if($this->config['t3_install_function'] == 'onlysymlink') {
      echo "<div id='onlysymlink' class='result'>";
      if($this->helper->createSymlink(realpath($this->documentRoot)."/typo3_src", "{$this->t3_src_dir_name}/{$this->t3_version_dir}")) {
        if ($this->helper->createSymlink(realpath($this->documentRoot)."/typo3", "typo3_src/typo3")) {
          if ($this->helper->createSymlink(realpath($this->documentRoot)."/index.php", "typo3_src/index.php")) {
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

  public function deleteTypo3Temp() {
    if($this->config['formtype'] == 'deletetypo3temp') {
      if(file_exists($this->documentRoot."/typo3temp/")) {
        if($this->helper->deleteDir($this->documentRoot."/typo3temp/")) {
          $this->helper->createDir("typo3temp");
          file_put_contents($this->getDocumentRoot()."/typo3temp/index.html", " ");
          $this->helper->createDir("typo3temp/assets");
          $this->helper->createDir("typo3temp/assets/compressed");
          $this->helper->createDir("typo3temp/assets/css");
          $this->helper->createDir("typo3temp/assets/js");
          $this->helper->createDir("typo3temp/assets/images");
          $this->helper->createDir("typo3temp/assets/images/_processed_");
          $this->helper->createDir("typo3temp/var");
          $this->helper->createDir("typo3temp/var/charset");
          $this->helper->createDir("typo3temp/var/Cache");
          $this->helper->createDir("typo3temp/var/locks");
          $this->helper->createFile("typo3temp/var/.htaccess", $fileContent = false);
          file_put_contents($this->getDocumentRoot()."/typo3temp/var/.htaccess", "
  # This file restricts access to the typo3temp/var/ directory. It is
  # meant to protect temporary files which could contain sensible
  # information. Please do not touch.

  # Apache < 2.3
  <IfModule !mod_authz_core.c>
  	Order allow,deny
  	Deny from all
  	Satisfy All
  </IfModule>

  # Apache ≥ 2.3
  <IfModule mod_authz_core.c>
  	Require all denied
  </IfModule>");
          echo "<span class='successful'>Typo3temp folder successfully deleted!</span>";
          return true;
        } else {
          echo "<span class='error'>Typo3temp folder could not be deleted!</span>";
          return false;
        }
      } else {
        echo "<span class='error'>Typo3 temp folder 'typo3temp' dos not exist!</span>";
        return false;
      }
    }
  }

  public function handleAjax() {
    if($this->config['formtype'] == 'ajaxpost') {
      switch ($this->config['ajax_function']) {
        case 'getTypo3Sources':
          return $this->helper->getDirList();
          break;

        case 'getTypo3TempDir':
          return $this->helper->getTypo3TempDir();
          break;

        default:
          echo "<span class='error'>No specifyed ajax_function. Available ajax_function are: getTypo3Sources, getTypo3TempDir!</span>";
          break;
      }
    }
  }
}
