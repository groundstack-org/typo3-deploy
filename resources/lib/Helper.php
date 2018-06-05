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
 */
class Helper {

    private $permissionIndex;

    function __construct() {
        $this->permissionIndex = 0;
    }

    /**
     * 
     * return the path to the document root
     * @return (string) document root path
     */
    public function getDocumentRoot() {
        return $_SERVER["DOCUMENT_ROOT"];
    }

    /**
     * 
     * return the document domain name
     * @return (string) document domain name
     */
    public function getDocumentDomain() {
        $domain = $_SERVER['HTTP_HOST'];
        if (!isset($domain)) {
            $domain = $_SERVER['SERVER_NAME'];
        }
        return $domain;
    }

    private function deleteDirs($src) {
        $dir = opendir($src);
        while ( false !== ( $file = readdir($dir)) ) {
            if (($file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    $this->deleteDirs($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }

    /**
     * deletes a folder recursive
     * @param (string) $src - path to dir incl. dirname
     * @return (bool)
     */
    public function deleteDir($src) {
        $src = $this->escape_input($src);
        $src = $src[0];

        $fileType = filetype($src);

        switch ($fileType) {
        case 'dir':
            $this->deleteDirs($src);
            if (!file_exists($src)) {
                echo "<span class='successful'>Dir {$src} successfully deleted.</span>";
                return true;
            } else {
                echo "<span class='error'>Dir {$src} not deleted!</span>";
                return false;
            }
            break;
        default:
            if (file_exists($src)) {
                echo "<span class='warning'>File: {$src} is filetype {$filetype} or something like zip / gz, try to delete it...</span>";
                $srcSub = strtolower(pathinfo($src, PATHINFO_EXTENSION));
                if ($srcSub === "zip") {
                    if ($this->deleteFile($src)) {
                        return true;
                    } else {
                        return false;
                    }
                } elseif ($srcSub === "gz") {
                    if ($this->deleteFile($src)) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    echo "<span class='error'>File still exists!</span>";
                }
            } else {
                echo "<span class='warning'>File not deleted, file not exists or not found!</span>";
            }
            return false;
            break;
        }
    }

    /**
     * deletes a file
     * @param (string) $filename - path to file incl. filename
     * @return (bool)
     */
    public function deleteFile($filename) {
        $filename = $this->escape_input($filename);
        $filename = $filename[0];

        $fileType = filetype($filename);

        switch($fileType) {
        case "file":
            unlink($filename);
            echo "<span class='successful'>File: {$filename} successfully deleted!</span>";
            return true;
            break;
        default:
            echo "<span class='error'>File: {$filename} is filetype {$filetype}</span>";
            if (file_exists($filename)) {
                return false;
            }
            return true;
        }
    }

    /**
     * deletes a symlink file
     * @param (string) $filename - path to symlink incl. symlinkname
     * @return (bool)
     */
    public function deleteLink($filename) {
        $filename = $this->escape_input($filename);
        $filename = $filename[0];

        $fileType = filetype($filename);

        switch($fileType) {
        case "link":
            unlink($filename);
            echo "<span class='successful'>Link: {$filename} successfully deleted!</span>";
            return true;
            break;
        default:
            if (file_exists($filename)) {
                echo "<span class='error'>Link: {$filename} is filetype {$filetype}! File is not deleted!</span>";
                return false;
            } else {
                echo "<span class='success'>Link: {$filename} doesn't exists!</span>";
                return true;
            }
        }
    }

    /**
     * create the empty file: ENABLE_INSTALL_TOOL
     * @return (bool)
     */
    public function createInstallToolFile() {
        return $this->createFile("ENABLE_INSTALL_TOOL", $this->getDocumentRoot()."/typo3conf/");
    }

    /**
     * creates a symlink
     * @param (string) $filename - name of the symlink
     * @param (string) $target - path to the file where the symlink links to
     * @return (bool)
     */
    public function createSymlink($filename, $target) {
        $filename = $this->escape_input($filename);
        $filename = $filename[0];
        $target = $this->escape_input($target);
        $target = $target[0];

        // if windows dont create symlinks - show info message
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            echo "<span class='warning'>Link: {$filename} not created! (windows?)</span>";
            echo "<span class='warning'>Please create that symlink manually: {$filename}!</span>";
            return false;
        } else {
            if ($this->deleteLink($filename) && symlink($target, $filename)) {
                echo "<span class='successful'>Link: {$filename} successfully created.</span>";
                return true;
            } else {
                echo "<span class='error'>Link: {$filename} not created!</span>";
                return false;
            }
        }
    }

    /**
     * creates a folder(dir)
     * @param (string) $dirName - name of the folder without slashes (default - DOCUMENT_ROOT)
     * @param (string) $pathToDir - path where the folder creates with ending slash
     * @return (bool)
     */
    public function createDir($dirName, $pathToDir = false) {
        $pathToDir = $pathToDir ? $pathToDir : $this->getDocumentRoot()."/";

        $pathToDir = $this->escape_input($pathToDir);
        $pathToDir = $pathToDir[0];
        $dirName = $this->escape_input($dirName);
        $dirName = $dirName[0];

        if (!dir($pathToDir.$dirName)) {
            if (mkdir($pathToDir.$dirName)) {
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

    /**
     * [createFile creates a file if it dosen't exists]
     * @param  (string) $filename filename
     * @param  (string) $pathToFile path to the file to write
     * @param  (string) $fileContent content of file to write
     * @return [bool]        [description]
     */
    public function createFile($filename, $pathToFile = false, $fileContent = false) {
        $pathToFile = $pathToFile ? $pathToFile : $this->getDocumentRoot()."/";
        $fileContent = $fileContent ? $fileContent : " ";

        $filename = $this->escape_input($filename);
        $filename = $filename[0];
        $pathToFile = $this->escape_input($pathToFile);
        $pathToFile = $pathToFile[0];

        $file = fopen($pathToFile.$filename,"x");
        if ($file != false) { // if true return resource
            fclose($file);
            file_put_contents($pathToFile.$filename, $fileContent);
            echo "<span class='successful'>File: {$filename} in '{$pathToFile}' successfully created.</span>";
            return true;
        } else {
            echo "<span class='error'>File: {$filename} in '{$pathToFile}' can't created!</span>";
            return false;
        }
    }

    /**
     * downloadExternalFile() - downloads a file from an external source
     * @param (string) $pathToExternalFile - path to external file (url) without filename
     * @param (string) $filename - name of the external file
     * @param (string) $pathToSafeFile - optional - path where to safe the file, with ending slash
     * @return (bool)
     */
    public function downloadExternalFile($pathToExternalFile, $filename, $outputFilename = false, $pathToSafeFile = false) {
        if (!$pathToSafeFile) {
            if ($this->createDir("typo3_sources", $this->getDocumentRoot()."/../")) {
                $pathToSafeFile = $this->getDocumentRoot()."/../typo3_sources/";
            }
        } else {
            $pathToSafeFile = $pathToSafeFile;
        }

        $outputFilename ? $outputFilename : $filename;

        $pathToExternalFile = $this->escape_input($pathToExternalFile);
        $pathToExternalFile = $pathToExternalFile[0];
        $filename = $this->escape_input($filename);
        $filename = $filename[0];
        $outputFilename = $this->escape_input($outputFilename);
        $outputFilename = $outputFilename[0];
        $pathToSafeFile = $this->escape_input($pathToSafeFile);
        $pathToSafeFile = $pathToSafeFile[0];

        $newfname = $pathToSafeFile.$outputFilename;

        if (file_exists($newfname)) {
            echo "<span class='warning'>File: {$filename} already exists in {$pathToSafeFile}.</span>";
            return true;
        } else {
            $file = fopen($pathToExternalFile, 'rb');
            if ($file) {
                $newf = fopen($newfname, 'wb');
                if ($newf) {
                    while (!feof($file)) {
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
            if (file_exists($newfname)) {
                echo "<span class='successful'>File: {$filename} successfully downloaded to '{$pathToSafeFile}{$filename}'!</span>";
                return true;
            } else {
                echo "<span class='error'>File: {$pathToSafeFile}{$filename} can't downloaded from '{$pathToExternalFile}'!</span>";
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
    public function extractZipFile($pathToZipFile, $pathToExtract = false) {
        $pathToZipFile = $this->escape_input($pathToZipFile);
        $pathToZipFile = $pathToZipFile[0];
        $pathToExtract = $this->escape_input($pathToExtract);
        $pathToExtract = $pathToExtract[0];

        $pathToExtract = $pathToExtract ? $pathToExtract : $this->getDocumentRoot()."../typo3_sources/";

        if (file_exists($pathToZipFile)) {
            $phar = new PharData($pathToZipFile);
            
            if ($phar->extractTo($pathToExtract)) {
                echo "<span class='successful'>ZipFile: {$pathToZipFile} successfully extracted!</span>";
                return true;
            } else {
                echo "<span class='error'>ZipFile: {$pathToZipFile} not extracted! ZipFile corrupt?</span>";
                return false;
            }
        } else {
            echo "<span class='error'>ZipFile: {$pathToZipFile} not extracted! File dosen't exist</span>";
            return false;
        }
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

    /**
     * [getDirList description]
     * @param  (string) $t3_sources_dir [description]
     * @return [type]        [description]
     */
    public function getDirList($t3_sources_dir = false) {
        $listdir = $t3_sources_dir ? dir($t3_sources_dir) : $this->getDocumentRoot()."/../typo3_sources";
        if (file_exists($listdir)) {
            $scanDir = scandir($listdir);

            echo "<form id='form-delete-typo3source' class='form-horizontal' method='post' action='#'>";
            echo "<input type='hidden' name='t3_version' value='' />";

            echo "<ul id='dirlist'>";

            $i = 0;
            foreach ($scanDir as $k => $v) {
                if ($v != "." && $v != "..") {
                    echo "<li><label for='typo3Source_{$i}' class='control control--checkbox'>{$v}
                    <input type='checkbox' id='typo3Source_{$i}' name='typo3Source_{$i}' form='form-delete-typo3source' value='{$v}'>
                    <span class='control__indicator'></span>
                    </label></li>";
                    $i = $i + 1;
                }
            }

            echo "</ul>";
            echo "<input type='hidden' name='t3sourcesanz' value='{$i}' id='t3sourcesanz' />";
            echo "<select id='' name='formtype'>
                    <option value='t3sourcezip' selected>Zip selected Typo3 source and delete this Typo3 source!</option>
                    <option value='t3sourcedelete'>Delete selected Typo3 sources!</option>
                </select>";
            echo "<p>(WARNING: this can take a while!)</p>";
            echo "<div class='form-actions'>";
            echo "<button id='submitsourcedelete' class='btn btn-success' type='submit' name='sendt3versiondelete' value='Senden' data-translate='_senddelete'>Delete or zips and delete source(s)</button>";
            echo "</div></form>";
        } else {
        echo "<span class='error'>No folder found '{$listdir}'</span>";
        }
    }

    /**
     * [addDbVersion7 description]
     * @param [string] $t3_db_name      [description]
     * @param [string] $t3_db_host      [description]
     * @param [string] $t3_db_password  [description]
     * @param [string] $t3_db_user      [description]
     * @param [string] $t3_db_socket    [description]
     * @param [string] $t3_install_tool [Password]
     * @param [datetime] $currentDateTime [description]
     */
    public function addDbVersion7($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool, $currentDateTime) {
        $str = $this->getDocumentRoot()."/../typo3_config/typo3_db.php";

        $t3_install_tool = md5($t3_install_tool);

        if (file_exists($str)) {
            echo "<span class='exists'>File: {$str} already exists.</span>";
            return false;
        } else {
            file_put_contents((string)$str, "<?php
if (!defined('TYPO3_MODE')) {
die('Access denied.');
}

\$GLOBALS['TYPO3_CONF_VARS']['DB']['database'] = '{$t3_db_name}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['host'] = '{$t3_db_host}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['password'] = '{$t3_db_password}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['username'] = '{$t3_db_user}';
\$GLOBALS['TYPO3_CONF_VARS']['DB']['socket'] = '{$t3_db_socket}';

\$GLOBALS['TYPO3_CONF_VARS']['BE']['installToolPassword'] = '{$t3_install_tool}';

// \$GLOBALS['TYPO3_CONF_VARS']['GFX']['im_path'] = ''; // e.g. OSX Mamp '/Applications/MAMP/Library/bin/' 
");
            echo "<span class='success'>File {$str} is created.</span>";
        }
    }

    /**
     * [addDbVersion8 description]
     * @param [string] $t3_db_name      [description]
     * @param [string] $t3_db_host      [description]
     * @param [string] $t3_db_password  [description]
     * @param [string] $t3_db_user      [description]
     * @param [string] $t3_db_socket    [description]
     * @param [string] $t3_install_tool [Password]
     * @param [datetime] $currentDateTime [description]
     */
    public function addDbVersion8($t3_db_name, $t3_db_host, $t3_db_password, $t3_db_user, $t3_db_socket, $t3_install_tool, $currentDateTime) {
        $str = $this->getDocumentRoot()."/../typo3_config/typo3_{$t3_db_name}.php";

        $t3_install_tool = md5($t3_install_tool);

        if (file_exists($str)) {
            echo "<span class='exists'>File: {$str} already exists.</span>";
            return false;
        } else {
            file_put_contents($str, "<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\$customChanges = [
    'DB' => [
        'Connections' => [
            'Default' => [
                'charset' => 'utf8',
                'driver' => 'mysqli',
                'dbname' => '{$t3_db_name}',
                'host' => '{$t3_db_host}',
                'password' => '{$t3_db_password}',
                'user' => '{$t3_db_user}',
                'unix_socket' => '{$t3_db_socket}',
                'port' => '3306'
            ]
        ]
    ],
    'BE' => [
        'installToolPassword' => '{$t3_install_tool}'
    ],
    // 'GFX' => [
    //   'im_path' = '', // e.g. OSX Mamp '/Applications/MAMP/Library/bin/'
    //   'processor_path' = '' // e.g. OSX Mamp '/Applications/MAMP/Library/bin/'
    // ]
];

\$GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive(\$GLOBALS['TYPO3_CONF_VARS'], (array)\$customChanges);

");
            echo "<span class='success'>File {$str} is created.</span>";
        }
    }

    /**
     * [strReplaceInFile replaces a string in a file;]
     * @param [string] $filepath    [path to file]
     * @param [string] $str_search  [string to be replaced]
     * @param [string] $str_replace [the new string]
     */
    public function strReplaceInFile($filepath, $str_search, $str_replace) {
        if (isset($str_search) && isset($str_replace) && isset($filepath) && file_exists($filepath)) {
            $content = file_get_contents($filepath);
            $content = str_replace($str_search, $str_replace, $content);
            file_put_contents($filepath, $content);
            echo "<span class='success'>String {$str_search} is replaced with {$str_replace} in file {$filepath}!</span>";
            return true;
        } elseif (!isset($filepath)) {
            echo "<span class='error'>\$filepath not set ({$filepath})!</span>";
            return false;
        } elseif (!isset($str_search)) {
            echo "<span class='error'>\$str_search not set ({$filepath})!</span>";
            return false;
        } elseif (!isset($str_replace)) {
            echo "<span class='error'>\$str_replace not set ({$filepath})!</span>";
            return false;
        } else {
            echo "<span class='error'>Something went wrong at 'strReplaceInFile()'!</span>";
            return false;
        }
    }

    /**
     * [setFilepermissions sets file permissions, uses php chmod();]
     * @param [string] $filepath   [path to file]
     * @param [int+] $permission [file permission e. g. 770]
     */
    public function setFilePermissions($filepath, $permission) {
        $permissionIndex = $this->permissionIndex;
        $filepath = $this->escape_input($filepath);
        $filepath = $filepath[0];
        $permission = (int)$permission;
        $permission = str_pad($permission, 4, '0', STR_PAD_LEFT);

        if (file_exists($filepath)) {
            if (chmod($filepath, octdec($permission))) {
                $fileperms = substr(sprintf("%o", fileperms($filepath)), -4);
                if ($fileperms == $permission) {
                    echo "<span class='success'>File {$filepath} have now file permission: {$permission}.</span>";
                    return true;
                } else {
                    $filePermission = substr(sprintf("%o",fileperms($filepath)),-4);
                    echo "<span class='error'>File permission for file: {$filepath} couldn't set. File have permission: {$filePermission}!</span>";
                    return false;
                }
            } else {
                $filePermission = substr(sprintf("%o",fileperms($filepath)),-4);
                echo "<span class='error'>File permission for file: {$filepath} couldn't set. File have permission: {$filePermission}!</span>";
                return false;
            }
        } else {
            echo "<span class='warning'>File dosen't exists: {$filepath}! Permission not set.</span>";
            return false;
        }
    }

    /**
     * [testFilePermissions tests file permissions;]
     * @param [string] $filepath   [path to file]
     * @param [int+] $permission [file permission e. g. 770]
     */
    public function testFilePermissions($filepath, $permission) {
        $permissionIndex = $this->permissionIndex;
        $filepath = $this->escape_input($filepath);
        $filepath = $filepath[0];
        $permission = (int)$permission;
        $permission = str_pad($permission, 4, '0', STR_PAD_LEFT);

        if (file_exists($filepath)) {
            clearstatcache();
            $currentPermission = substr(sprintf('%o', fileperms($filepath)), -4);
            if ($currentPermission === $permission) {
                echo "<span class='success'>File permission of {$filepath} looks good.</span>";
            } else {
                echo "<span class='warning'>File permission of {$filepath} looks not good! Please check it. Current permission: {$currentPermission} should be {$permission}.</span>";
            }
        }
    }

    /**
     * [Recursively Backup Files & Folders to ZIP-File]
     * @param [string] $source   [path to folder]
     * @param [string] $destination [path to folder where zip is stored]
     * MIT-License - Copyright (c) 2012-2017 Marvin Menzerath
     */
    public function zipDir($source = false, $destination = false) {
        // $source = $source ? dir($source) : $this->getDocumentRoot()."/../typo3_sources/";
        // $source = (string)$source;
        $source = $this->escape_input($source);
        $source = $source[0];
        $destination = $this->escape_input($destination);
        $destination = $destination[0];

        // Make sure the script can handle large folders/files
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '1024M');

        if (extension_loaded('zip')) {
            if (file_exists($source)) {
                $zip = new ZipArchive();
                if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
                    $source = realpath($source);
                    if (is_dir($source)) {
                        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
                        foreach ($files as $file) {
                            $file = realpath($file);
                            if (is_dir($file)) {
                                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                            } else if (is_file($file)) {
                                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                            }
                        }
                    } else if (is_file($source)) {
                        $zip->addFromString(basename($source), file_get_contents($source));
                    }
                }
                $zip->close();
                echo "<span class='success'>File {$source} is zipped to path {$destination}.</span>";
                return true;
            } else {
                echo "<span class='error'>File {$source} doesen't exist!</span>";
                return false;
            }
        } else {
            echo "<span class='error'>PHP zip extension not loaded!</span>";
            return false;
        }
    }

    /**
     * [Gets the TYPO3 original json info file]
     */
    public function getTypo3Json() {
        if($_SERVER['SERVER_ADDR'] === $_SERVER['REMOTE_ADDR']) {
            $url = 'http://get.typo3.org/json';
            $SSLverify = 0;
        } else {
            $url = 'https://get.typo3.org/json';
            $SLLverify = 1;
        }

        $data = "";

        if(function_exists('curl_version')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $SSLverify);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            echo curl_exec($ch);
            return true;
        } else {
            $header = "Content-type: application/json\r\n";
            $method = "POST";

            // use key 'http' even if you send the request to https://...
            $options = array(
                'http' => array(
                    'header'  => $header,
                    'method'  => $method,
                    'content' => $content
                )
            );
            $context  = stream_context_create($options);
            $result = @file_get_contents($url, false, $context);
            if ($result === FALSE) {
                echo "<span class='error'>Can't get TYPO3 versions json from 'https://get.typo3.org/json'!</span>";
                return false;
            } else {
                echo $result;
                return true;
            }
        }
    }
}
