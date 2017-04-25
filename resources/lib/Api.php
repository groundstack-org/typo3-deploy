<?php
/**
 *
 */
class Api extends Deployer {

  private $deployer;
  private $config;

  function __construct($config=false) {

    if($config && is_array($config)){
      $this->deployer = new Deployer($config);
      $this->api($config['formtype']);
    } else {
      echo "Please specify your API Data..";
    }
  }

  private function api($callMethod) {
    switch ($callMethod) {
      case 't3install':
        switch ($this->config['t3_function']) {
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
        # code...
        break;

      default:
        # code...
        break;
    }
  }
}
