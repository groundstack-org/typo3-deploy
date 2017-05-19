<?php
/**
 * [__construct description]
 * @param boolean $config [description]
 */
class Api extends Deployer {

  private $deployer;
  private $config;

  function __construct($config=false) {
    if($config && is_array($config)){
      $this->deployer = new Deployer($config);
      $this->config = $this->deployer->getConfig();
      $this->api($config['formtype']);
    } else {
      echo "Please specify your API Data..";
    }
  }

  /**
   * [api description]
   * @param  [type] $callMethod [description]
   * @return [type]             [description]
   */
  private function api($callMethod) {
    switch ($callMethod) {
      case 't3install':
        switch ($this->config['t3_install_function']) {
          case 'completeinstall':
            $this->deployer->t3install_completeinstall();
            break;
          case 'onlysymlink':
            $this->deployer->t3install_onlysymlink();
            break;
          case 'downloadextract':
            $this->deployer->t3install_downloadextract();
            break;
          case 'downloadextractlink':
            $this->deployer->t3install_downloadextractlink();
            break;
          default:
            echo "<span class='error'>Available API options for t3_function are: completeinstall, onlysymlink, downloadextract, downloadextractlink!</span>";
            break;
        }
        break;

      case 'deletedeployment':
        $this->deployer->deleteDeployment();
        break;

      case 't3sourcedelete':
        $this->deployer->typo3SourceDelete();
        break;

      case 'deletetypo3temp':
        $this->deployer->deleteTypo3Temp();
        break;

      case 'ajaxpost':
        $this->deployer->handleAjax();
        break;

      case 'setTypo3FilePermissions':
        $this->deployer->setTypo3FilePermissions();
        break;

      case 'login':
        $this->deployer->userSetPassword($this->config['user_pw']);
        $this->deployer->initSession($this->config['user_pw']);
        $this->deployer->userLoginCheck();
        break;

      case 'logout':
        $this->deployer->userLogout();
        break;

      default:
        echo "<span class='error'>Available API options formtype are: t3install, deletedeployment, t3sourcedelete, deletetypo3temp, ajaxpost!</span>";
        break;
    }
  }
}
