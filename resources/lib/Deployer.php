<?php
/**
 * Typo3 simple deployer
 * 
 * @package: Typo3 simple deployer
 * @author:  Christian Hackl 
 * @Date:    2017-12-15 12:29:03 
 * @link:    http://www.hauer-heinrich.de 
 * @license: MIT License
 * @Last     Modified by: Christian Hackl
 * @Last     Modified time: 2017-12-15 12:44:06
 */

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
    private $documentDomain;
    private $index;
    private $deployerFileConfig;
    private $deployerFileConfigPath;
    private $src;
    private $folderName;

    function __construct($config = false) {
        session_start();
        $this->initSession();

        $this->helper = new Helper();
        $this->documentRoot = $this->helper->getDocumentRoot();
        $this->documentDomain = $this->helper->getDocumentDomain();

        $this->t3_src_dir_name = "../typo3_sources";
        $this->t3_config_date = date("Ymd_His");
        $this->t3_path_to_source_file = "http://get.typo3.org/";

        if ($config && is_array($config)) {
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
        if (isset($config['t3_version'])) {
            $this->t3_version = $config['t3_version'];
        }

        $this->t3_version_dir = "typo3_src-{$this->t3_version}";

        switch ($config['formtype']) {
        case 't3install':
            $this->t3_zip_file = "{$this->t3_version_dir}.tar.gz";
            break;

        case 'deletedeployment':
            break;

        case 't3sourcedelete':
            break;

        case 'deletetypo3temp':
            break;

        case 'setTypo3FilePermissions':
            break;

        case 'ajaxpost':
            break;

        case 'login':
            break;

        case 'logout':
            break;

        case 't3sourcezip':
            break;

        default:
            echo "No specifyed form. Available forms are: t3install, deletedeployment, t3sourcedelete, t3sourcezip, deletetypo3temp, ajaxpost!";
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
     * [loginForm description]
     * @return [type] [description]
     */
    public function userLoginForm() {
        echo "
        <form id='form-login' class='userlogin' method='POST' >
        <input type='hidden' name='formtype' value='login' />
        <label class='control-label' for='user-pw' data-translate=''>Login</label>
        <input id='user-pw' class='input-small span2' type='password' name='user_pw' value=''>
        <div class='form-actions'>
            <button type='submit' class='btn btn-danger' name='sent' value='Login' data-translate='_login'>Login</button>
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
        <div class='form-actions'>
            <button type='submit' class='btn btn-danger' name='sent' value='Logout' data-translate='_logout'>Logout</button>
        </div>
        </form>
        ";
    }

    /**
     * [userLogout description]
     * @return [type] [description]
     */
    public function userLoggedout() {
        session_destroy();
        echo "<div id='alert-logedout'>You were logedout!</div>";
        echo "<script>setTimeout(function() { location.reload(); }, 2000);</script>";
        exit();
    }

    /**
     * [userSetPassword description]
     * @param  [type] $pw [description]
     * @return [type]     [description]
     */
    public function userSetPassword($pw) {
        if (!file_exists($this->documentRoot."/../typo3_config/deployer_config.php")) {
            $path = $this->documentRoot."/../typo3_config/";
            $string = "<?php return array( 'config' => array( 'login_pw' => '".md5($pw)."' ) );";
            $this->helper->createFile("deployer_config.php", $path, $string);
            return true;
        } else {
            return false;
        }
    }

    /**
     * [initDeployerFileConfig if deployer_config.php exists include it else set it to false]
     * @return [bool] [true or false]
     */
    public function initDeployerFileConfig() {
        $file = $this->documentRoot."/../typo3_config/deployer_config.php";
        $this->deployerFileConfigPath = file_exists($file) ? $file : false;

        if ($this->deployerFileConfigPath) {
            $this->deployerFileConfig = include_once($this->deployerFileConfigPath);
            return true;
        } else {
            return false;
        }
    }

    /**
     * [userLoginCheck description]
     * @return [type] [description]
     */
    public function userLoginCheck() {
        print_r("Session loginpw: ".$_SESSION['login_pw']);
        $login_pw = $this->initDeployerFileConfig() ? $this->deployerFileConfig['config']['login_pw'] : false;

        if (isset($_SESSION['login_pw']) && !empty($_SESSION['blah']) && $_SESSION['login_pw'] == $login_pw && $_SESSION['browser'] == $_SERVER['HTTP_USER_AGENT'] && $_SESSION['ip'] == $_SERVER['REMOTE_ADDR']) {
            $this->userLogoutForm();
            return true;
        } else {
            $this->userLoginForm();
            return false;
        }
    }

    /**
     * [initSession description]
     * @param  boolean $pw [description]
     * @return [type]      [description]
     */
    public function initSession($pw = false) {
        $_SESSION['login_pw'] = $pw ? md5($pw) : false;
        $_SESSION['browser'] = $_SESSION['browser'] ? $_SESSION['browser'] : $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['ip'] = $_SESSION['ip'] ? $_SESSION['ip'] : $_SERVER['REMOTE_ADDR'];
        $this->initDeployerFileConfig();
    }

    /**
     * deletes the Typo3 simple deployment app
     * @return (bool)
     */
    public function deleteDeployment() {
        if ($this->config['formtype'] === 'deletedeployment') {
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
        if ($this->config['formtype'] === 't3sourcedelete') {
            $this->index = 0;
            for ($i=0; $i <= $this->config['t3sourcesanz']; $i++) {
                if ($this->config['typo3Source_'.$this->index]) {
                    if ($this->helper->deleteDir($this->documentRoot."/".$this->t3_src_dir_name."/".$this->config['typo3Source_'.$this->index])) {
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
        if ($this->config['t3_install_function'] === 'completeinstall') {
            $documentRoot = $this->documentRoot;

            echo "<div id='completeinstall' class='result'>";

            $this->config['t3_install_function'] = 'downloadextractlink';
            if ($this->t3install_downloadextractlink()) {
                echo "<span class='successful'>Download and symlinks successfully created.</span>";
            } else {
                echo "<span class='error'>Download or symlinks not created!</span>";
            }

            if ($this->createDir("typo3conf")) {
                $pathToTypo3conf = $documentRoot."/typo3conf";
                $currentDateTime = $this->t3_config_date;
                $dbName = $this->config['t3_db_name'];

                echo "<span class='successful'>Dir 'typo3conf' successfully created.</span>";

                if (!file_exists($pathToTypo3conf."/AdditionalConfiguration.php")) {
                    if (file_put_contents($pathToTypo3conf."/AdditionalConfiguration.php", "
<?php
if (!defined('TYPO3_MODE')) {
  die('Access denied.');
}
/*
  !IMPORTANT!
  The file '\$databaseCredentialsFile' is not versioned and must be created and changed!
  For security reasons please create the database accesses outside the document roots!

  Die Datei '\$databaseCredentialsFile' wird nicht mit Versioniert und muss extra angelegt und geändert werden!
  aus Sicherheitsgründen bitte die Datenbank zugänge außerhalb des document roots anlegen!
*/


\$databaseCredentialsFile = PATH_site . './../typo3_config/typo3_{$dbName}.php';
if (file_exists(\$databaseCredentialsFile)) { require_once (\$databaseCredentialsFile); }

// Production / Live:
\$customChanges = array(
  'BE' => array(
    'compressionLevel' => '0',
    'lockSSL' => 0, // if https is on set this to 1
    'versionNumberInFilename' => 0,
  ),
  'FE' => array(
    'compressionLevel' => '0',
    'noPHPscriptInclude' => '1',
    'pageNotFound_handling' => '404.html',
    'pageUnavailable_handling' => '503.html',
    'disableNoCacheParameter' => 1
  ),
  'EXT' => array(
    'extConf' => array(
      // 'realurl' => serialize(array(
  		// 	'configFile' => 'typo3conf/ext/YOURTHEME/Resources/Private/Extensions/realurl/realurl_theme_conf.php',
  		// 	'enableAutoConf' => 1,
  		// 	'enableDevLog' => 0,
  		// 	'enableChashUrlDebug' => 0
			// ))
    )
  ),
  'SYS' => array(
    'UTF8filesystem' => 1,
    'clearCacheSystem' => 1,
    'enableDeprecationLog' => 0,
    'phpTimeZone' => 'Europe/Berlin',
    'systemLocale' => 'de_DE.UTF-8'
  )
);

\$GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive(\$GLOBALS['TYPO3_CONF_VARS'], (array)\$customChanges);


// Developement:
if(\TYPO3\CMS\Core\Utility\GeneralUtility::getApplicationContext()->isDevelopment()) {

  \$customDevelopmentChanges = array(
    'BE' => array(
      'compressionLevel' => '0',
      'lockSSL' => 0, // if https is on set this to 1
      'versionNumberInFilename' => 0,
    ),
    'FE' => array(
      'compressionLevel' => '0',
      'debug' => 1,
      'noPHPscriptInclude' => 1
    ),
    'EXT' => array(
      'extConf' => array(
        // 'realurl' => serialize(array(
    		// 	'configFile' => 'typo3conf/ext/YOURTHEME/Resources/Private/Extensions/realurl/realurl_theme_conf.php',
    		// 	'enableAutoConf' => 0,
    		// 	'enableDevLog' => 1,
    		// 	'enableChashUrlDebug' => 1
  			// ))
      )
    ),
    'SYS' => array(
      'displayErrors' => 1,
      'sqlDebug' => 1,
      'systemLog' => 'error_log',
      'systemLogLevel' => '2',
      'enableDeprecationLog' => 'file'
    )
  );

  \$GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive(\$GLOBALS['TYPO3_CONF_VARS'], (array)\$customDevelopmentChanges);
}

")) {
                        echo "<span class='successful'>File 'AdditionalConfiguration.php' successfully created.</span>";

                        if ($this->helper->createDir('typo3_config', $documentRoot.'/../')) {
                            echo "<span class='successful'>Dir 'typo3_config' successfully created.</span>";
                            $typo3configPath = $documentRoot.'/../typo3_config';

                            if (!file_exists($typo3configPath."/typo3_{$this->config['t3_db_name']}.php")) {
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
                $filepath = $this->documentRoot."/.htaccess";
                $this->helper->strReplaceInFile($filepath, "YOUR_DOMAIN", $this->documentDomain);
            } else {
                echo "<span class='warning'>File '.htaccess' could not be created!</span>";
            }

            if (copy($this->config['deploymentfolder']."/resources/files/humans.txt", $documentRoot."/humans.txt")) {
                echo "<span class='successful'>File 'humans.txt' successfully created.</span>";
            } else {
                echo "<span class='warning'>File 'humans.txt' could not be created!</span>";
            }

            if (!file_exists($documentRoot."/FIRST_INSTALL")) {
                if (file_put_contents($documentRoot."/FIRST_INSTALL", "") != false) {
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
        if ($this->config['t3_install_function'] == 'downloadextract') {
            $documentRoot = $this->getDocumentRoot();

            echo "<div id='downloadextract' class='result'>";
            if ($this->helper->downloadExternalFile($this->t3_path_to_source_file.$this->t3_version, $this->t3_version, "typo3_src-".$this->t3_version.".tar.gz")) {
                $pathToZipFile = $documentRoot."/".$this->t3_src_dir_name."/".$this->t3_version_dir;

                if (file_exists($pathToZipFile)) {
                    if ($this->helper->deleteDir($pathToZipFile)) {
                        if ($this->helper->extractZipFile($documentRoot."/".$this->t3_src_dir_name."/".$this->t3_zip_file, $documentRoot."/../typo3_sources/")) {
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
                    if ($this->helper->extractZipFile($documentRoot."/".$this->t3_src_dir_name."/".$this->t3_zip_file, $documentRoot."/".$this->t3_src_dir_name)) {
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
        if ($this->config['t3_install_function'] == 'downloadextractlink') {
            echo "<div id='downloadextractlink' class='result'>";

            $this->config['t3_install_function'] = 'downloadextract';
            if ($this->t3install_downloadextract()) {

                $this->config['t3_install_function'] = 'onlysymlink';
                if ($this->t3install_onlysymlink()) {
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
        if ($this->config['t3_install_function'] == 'onlysymlink') {
            echo "<div id='onlysymlink' class='result'>";
            if ($this->helper->createSymlink(realpath($this->documentRoot)."/typo3_src", "{$this->t3_src_dir_name}/{$this->t3_version_dir}")) {
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
        if ($this->config['formtype'] == 'deletetypo3temp') {
            if (file_exists($this->documentRoot."/typo3temp/")) {
                if ($this->helper->deleteDir($this->documentRoot."/typo3temp/")) {
                    echo "<span class='successful'>All in typo3temp folder successfully deleted!</span>";
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

    /**
     * [setTypo3FilePermissions trys to set default Typo3 file permissions]
     */
    public function setTypo3FilePermissions() {
        $typo3Files = array($this->documentRoot.'/typo3temp/compressor' => 2775,
            $this->documentRoot.'/typo3temp/cs' => 2775,
            $this->documentRoot.'/typo3temp/Cache' => 2775,
            $this->documentRoot.'/typo3temp/GB' => 2775,
            $this->documentRoot.'/typo3temp/llxml' => 2775,
            $this->documentRoot.'/typo3temp/locks' => 2775,
            $this->documentRoot.'/typo3temp/pics' => 2775,
            $this->documentRoot.'/typo3temp/sprites' => 2775,
            $this->documentRoot.'/typo3temp/temp' => 2775,
            $this->documentRoot.'/typo3temp/assets' => 2775,
            $this->documentRoot.'/typo3temp/assets/compressed' => 2775,
            $this->documentRoot.'/typo3temp/assets/css' => 2775,
            $this->documentRoot.'/typo3temp/assets/js' => 2775,
            $this->documentRoot.'/typo3temp/assets/images' => 2775,
            $this->documentRoot.'/typo3temp/assets/_processed_' => 2775,
            $this->documentRoot.'/typo3temp/var' => 2775,
            $this->documentRoot.'/typo3temp/var/Cache' => 2775,
            $this->documentRoot.'/typo3conf/' => 2775,
            $this->documentRoot.'/typo3conf/ext' => 2775,
            $this->documentRoot.'/typo3conf/l10n' => 2775,
            $this->documentRoot.'/uploads' => 2775,
            $this->documentRoot.'/uploads/media' => 2775,
            $this->documentRoot.'/uploads/pics' => 2775,
            $this->documentRoot.'/uploads/tf' => 2775,
            $this->documentRoot.'/fileadmin' => 2775,
            $this->documentRoot.'/fileadmin/_temp_' => 2775,
            $this->documentRoot.'/fileadmin/user_upload' => 2775,
            $this->documentRoot.'/fileadmin/user_upload/_temp_' => 2775,
            $this->documentRoot.'/fileadmin/user_upload/_temp_/importexport' => 2775,
            $this->documentRoot.'/' => 2775,
            $this->documentRoot.'/typo3temp' => 2775,
            $this->documentRoot.'/typo3temp/var/charset' => 2775,
            $this->documentRoot.'/typo3temp/var/locks' => 2775,
            $this->documentRoot.'/typo3temp/index.html' => 664,
            $this->documentRoot.'/uploads/index.html' => 664,
            $this->documentRoot.'/uploads/media/index.html' => 664,
            $this->documentRoot.'/uploads/pics/index.html' => 664,
            $this->documentRoot.'/uploads/tf/index.html' => 664,
            $this->documentRoot.'/fileadmin/_temp_/.htaccess' => 664,
            $this->documentRoot.'/fileadmin/_temp_/index.html' => 664,
            $this->documentRoot.'/fileadmin/user_upload/_temp_/index.html' => 664,
            $this->documentRoot.'/fileadmin/user_upload/_temp_/importexport/.htaccess' => 664,
            $this->documentRoot.'/fileadmin/user_upload/_temp_/importexport/index.html' => 664,
            $this->documentRoot.'/fileadmin/user_upload/index.html' => 664,
            $this->documentRoot.'/typo3temp/var/.htaccess' => 664
        );

        foreach ($typo3Files as $key => $value) {
            // dont work well
            // $this->helper->setFilePermissions($key, $value);
            $this->helper->testFilePermissions($key, $value);
        }
    }

    /**
     * [ zipfolder: zip a typo3_source dir ]
     */
    public function zipfolder() {
        if ($this->config['formtype'] == 't3sourcezip') {
            echo "<div id='zipfolder' class='result'>";

            $this->index = 0;
            for ($i=0; $i <= $this->config['t3sourcesanz']; $i++) {
                if ($this->config['typo3Source_'.$this->index]) {
                    if ($this->helper->zipdir($this->documentRoot."../typo3_sources/".$this->config['typo3Source_'.$this->index], $this->documentRoot."../typo3_sources/".$this->config['typo3Source_'.$this->index].".zip")) {
                        echo "<span class='successful'>Successfully zipped ".$this->config['typo3Source_'.$this->index]."</span>";
                        if ($this->helper->deleteDir($this->documentRoot."/".$this->t3_src_dir_name."/".$this->config['typo3Source_'.$this->index])) {
                            echo "<span class='successful'>Successfully deleted ".$this->config['typo3Source_'.$this->index]."</span>";
                        } else {
                            echo "<span class='error'>Can't delete ".$this->config['typo3Source_'.$this->index]."</span>";
                        }
                    } else {
                        echo "<span class='error'>Can't zip ".$this->config['typo3Source_'.$this->index]."</span>";
                    }
                }
                $this->index = $this->index + 1;
            }
            echo "</div>";
        }
    }

    /**
     * [ handle ajax requests ]
     */
    public function handleAjax() {
        if ($this->config['formtype'] === 'ajaxpost') {
            switch ($this->config['ajax_function']) {
            case 'getTypo3Sources':
                return $this->helper->getDirList();
                break;

            case 'getTypo3TempDir':
                return $this->helper->getTypo3TempDir();
                break;

            case 'setTypo3FilePermissions':
                return $this->helper->setTypo3FilePermissions();
                break;

            case 'createinstallfile':
                return $this->helper->createInstallToolFile();
                break;

            case 'getTypo3Json':
                return $this->helper->getTypo3Json();
                break;

            default:
                echo "<span class='error'>No specifyed ajax_function. Available ajax_function are: getTypo3Sources, getTypo3TempDir, setTypo3FilePermissions!</span>";
                break;
            }
        }
    }
}
