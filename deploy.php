<!doctype html>
<html>
<head>
  <meta charset="utf-8">

  <title>Typo3 deploy script</title>
  <meta name="description" content="The HTML5 Herald">

  <style>
    *, *:before, *:after {padding:0;margin:0;-webkit-box-sizing:inherit;-moz-box-sizing:inherit;box-sizing:inherit;}html,body,div,span,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,abbr,address,cite,code,del,dfn,em,img,ins,kbd,q,samp,small,strong,sub,sup,var,b,i,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,figcaption,figure,footer,header,hgroup,menu,nav,section,main,summary,time,mark,audio,video{margin:0;padding:0;border:0;outline:0;vertical-align:baseline;background:transparent;position: relative;}article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section,main{display:block}nav ul{list-style:none}blockquote,q{quotes:none}blockquote:before,blockquote:after,q:before,q:after{content:none}a{margin:0;padding:0;font-size:100%;vertical-align:baseline;background:transparent;text-decoration:none;}ins{background-color:#ff9;color:#000;text-decoration:none}mark{background-color:#ff9;color:#000;font-style:italic;font-weight:bold}del{text-decoration:line-through}abbr[title],dfn[title]{border-bottom:1px dotted;cursor:help}table{border-collapse:collapse;border-spacing:0;}hr{display:block;float:left;height:1px;border:0;border-top:1px solid #ccc;margin:1em 0;padding:0;width:100%;}input,select{ vertical-align: middle; outline: 0; } input:focus {outline: 0 none;}html, body { font-size: 100.1%; min-height: 100%; min-width: 310px; position: relative; width: 100%; -webkit-overflow-scrolling: touch; }html {height: 100%;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}body {line-height:1;height: auto;-webkit-text-size-adjust: none; -ms-text-size-adjust: none; text-size-adjust: none; overflow-x:hidden; -webkit-backface-visibility: hidden; -moz-backface-visibility: hidden; -ms-backface-visibility: hidden; -o-backface-visibility: hidden; backface-visibility: hidden; height: 100%; background-color: #FFF; }img { display: block; height: auto; max-width: 100%; }a { text-decoration: none; -webkit-transition: color 300ms; -moz-transition: color 300ms; -ms-transition: color 300ms; transition: color 300ms; }a img { border: none; }template, .template { display: none; opacity: 0; visibility: hidden; }.br-to-old { background: red; padding: 1%; position: relative; width: 100%; }.ie img[src*=".svg"] {width: 100%; }
    @media screen and (-ms-high-contrast: active), (-ms-high-contrast: none) {
      img[src*=".svg"] {width: 100%; }
    }

    body { font-family: sans-serif; }
    span { clear: both; float: left; min-width: 300px; padding: 10px 0; }
    div, #header, #main, #footer, #main-wrapper, form { float: left; width: 100%; }
    #main-wrapper { padding: 15px 6%; }
    #header { border-bottom: 2px solid; margin-bottom: 10px; }
    #main > span { padding: 0 6%; }
    #main > div { margin: 10px 0; padding: 5px; border: 1px solid; }
    #form { padding-top: 15px; padding-bottom: 30px; }
    #form ul li { float: left; list-style: none; padding: 10px 0; width: 100%; }
    select { background-color: #8BC3A3; border: thin solid #000; border-radius: 4px; display: inline-block; padding-left: 4px; padding-top: 4px; padding-right: 45px; padding-bottom: 4px; margin: 0;
      -webkit-box-sizing: border-box;
      -moz-box-sizing: border-box;
      box-sizing: border-box;
      -webkit-appearance: none;
      -moz-appearance: none; }
    select.t3_version { background-image: linear-gradient(45deg, transparent 50%, blue 50%), linear-gradient(135deg, blue 50%, transparent 50%), linear-gradient(to right, #fff, #fff);
      background-position: calc(100% - 20px) calc(1em + 2px), calc(100% - 15px) calc(1em + 2px), 100% 0;
      background-size: 5px 5px, 5px 5px, 2.5em 2.5em; background-repeat: no-repeat; }
    select.t3_version:focus { background-image: linear-gradient(45deg, white 50%, transparent 50%), linear-gradient(135deg, transparent 50%, white 50%), linear-gradient(to right, #000, #000);
      background-position: calc(100% - 15px) 1em, calc(100% - 20px) 1em, 100% 0;
      background-size: 5px 5px, 5px 5px, 2.5em 2.5em; background-repeat: no-repeat; border-color: grey; outline: 0; }
    .form-btn { width: 115px; display: block; height: auto; padding: 6px; color: #fff; background: #8BC3A3; border: none; border-radius: 3px; outline: none;
      -webkit-transition: all 0.3s;
      -moz-transition: all 0.3s;
      transition: all 0.3s;
      box-shadow: 0 1px 4px rgba(0,0,0, 0.10);
      -moz-box-shadow: 0 1px 4px rgba(0,0,0, 0.10);
      -webkit-box-shadow: 0 1px 4px rgba(0,0,0, 0.10); }
    .form-btn:hover { background: #111; cursor: pointer; color: white; border: none;}
    .form-btn:active { opacity: 0.9; }
    .input { margin-bottom: 10px; }
    .btn-delete {  }
    #generate-install-pw { width: 100%; max-width: 200px; text-align: center; margin-bottom: 8px; }
    .hidden { display: none; }
    .warning { background-color: orange; color: #fff; }
    .error { background-color: darkred; color; #fff; }
    .success { background-color: green; }
    .exists { background-color: grey; }
    .readyToTakeOff { border-top: 2px solid; margin-top: 10px; text-align: center; }
  </style>
  <script>
		var js_var = "delete";
		var deleteScript = function() {
			window.location.href = "deploy.php?php_var=" + escape(js_var);
		}
	</script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>

<body>
  <div id="main-wrapper">
    <header id="header">
      <h1>Typo3 simple deployment</h1>
    </header>
    <main id="main">
      <div id="form">
        <p>
    			<strong>Nach Abschluss:</strong><br />
    			Diese Datei unbedingt manuel löschen! ODER <button class="btn-delete form-btn" onclick="deleteScript()">Lösche mich!</button><br />
    		</p>
        <form id="form-t3-install" method="post" action="<?php echo htmlentities(urlencode($_SERVER['PHP_SELF'])); ?>">
  				<ul class="">
  					<li class="choose-version">
  						<label class="t3_version_label" for="text_id">Hier Ihre gewünschte Version angeben:<br /><span class="info">(Bitte in dieser Form: 6.2.12)</label>
  						<input type="text" class="t3_version" name="t3_version" id="text_id" value="7.6.16" autofocus min="5" maxlength="8" required />
  					</li>
  					<li style="display: none;">
  						<label class="">Was möchten Sie machen?</label>
  						<fieldset>
  							<input type="radio" id="full" name="selection" value="full" checked="checked" required><label for="mc">Komplett Installation</label><br />
  							<input type="radio" id="only-symlink" name="selection" value="symlinks"><label for="vi">Nur Symlinks erstellen</label><br />
  							<input type="radio" id="only-download" name="selection" value="download"><label for="ae">Nur Typo3 downloaden und entpacken</label>
  						</fieldset>
  					</li>
            <li class="form-db-data">
              <label>Datenbank Zugangsdaten werden in 'typo3_config/typo3_db.php' gespeichert.</label><br /><br />
              <label class="label label-name" for="db-name">Datenbank Name</label><br />
              <input id="db-name" class="input" type="text" name="t3_db_name" value="databaseName"><br />
              <label class="label label-name" for="db-name">Datenbank Benutzername</label><br />
              <input id="db-user" class="input" type="text" name="t3_db_user" value="databaseUser"><br />
              <label class="label label-name" for="db-password">Datenbank Benutzerpassword (character '&' not allowed)</label><br />
              <input id="db-password" class="input" type="password" name="t3_db_password" value="databasePasswort"><br />
              <label class="label label-name" for="db-host">Datenbank Host</label><br />
              <input id="db-host" class="input" type="text" name="t3_db_host" value="localhost"><br />
              <label class="label label-name" for="db-socket">Datenbank Socket</label><br />
              <input id="db-socket" class="input" type="text" name="t3_db_socket" value=""><br />
            </li>
            <li class="form-install-tool">
              <label>Install Tool Password wird in 'typo3_config/typo3_db.php' gespeichert.</label><br /><br />
              <label class="label label-install-tool-pw" for="install-tool-pw">Install Tool Password (character '&' not allowed)</label><br />
              <input type="password" class="input left form-control" id="install-tool-pw" name="t3_install_tool" value="" /> <br /><br />
              <a href="#" class="btn btn-danger form-btn" id="generate-install-pw" >Generate a password</a>
              <div class="left" id="install-tool-pw-element"></div>
            </li>
  					<li class="from_submit">
  						<button id="submit" class="form-btn" type="submit" name="sent" value="Senden">Send</button>
  					</li>
  				</ul>
  			</form>
      </div>
<?php
$t3_version = "empty";

if (isset($_GET['php_var'])) {
    unlink("deploy.php");
}
if(isset($_POST['sent'])) {
  $t3_version = $t3_db_user = $t3_db_name = $t3_db_user = $t3_db_host = $t3_db_socket = $t3_install_tool = "";

  function escape_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $t3_version = escape_input($_POST['t3_version']);

    $t3_db_user = escape_input($_POST['t3_db_user']);
    $t3_db_name = escape_input($_POST['t3_db_name']);
    $t3_db_password = escape_input($_POST['t3_db_password']);
    $t3_db_host = escape_input($_POST['t3_db_host']);
    $t3_db_socket = escape_input($_POST['t3_db_socket']);

    $t3_install_tool = escape_input($_POST['t3_install_tool']);
    $t3_install_tool = md5($t3_install_tool);
  }
  echo "post data: " . $t3_version;

  $t3_src_dir_name = "typo3_sources";
  $t3_version_dir = "typo3_src-{$t3_version}";
  $t3_zip_file = "{$t3_version_dir}.tar.gz";
  $typo3_source = "https://netcologne.dl.sourceforge.net/project/typo3/TYPO3%20Source%20and%20Dummy/TYPO3%20{$t3_version}/typo3_src-{$t3_version}.tar.gz";

  function downloadFile($url, $path) {
      $newfname = $path;
      $file = fopen($url, 'rb');
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
  }

  function deleteFile($path, $filename) {
    $filepath = $path.$filename;
    echo "<div class='delete-file-dir'>";
    echo "<span class=''>Try to delete: '$filepath'...</span>";
    unlink($filepath);

    if(!file_exists($filepath)) {
      echo "<span class='success'>Successfully deleted: '$filepath' or dosen't exists.</span>";
    } else {
      echo "<span class='warning'>Can't delete: '$filepath'!</span>";
    }

    echo "</div>";
  }

  function createSymlink($target, $link) {
    echo "<div class='create-symlink'>";
    echo "<span class=''>Try to create symlink: '{$link}' to '{$target}'...</span>";
    symlink($target, $link);

    if(file_exists($link)) {
      echo "<span class='success'>Successfully created symlink '{$link}'.</span>";
    } else {
      echo "<span class='warning'>Can't create symlink '{$link}'.</span>";
    }

    echo "</div>";
  }

  function createDir($dirname, $path = "") {
    $filepath = $path.$dirname;
    echo "<div class='create-dir'>";
    echo "<span class=''>Try to create dir: '$filepath'...</span>";
    mkdir($filepath);

    if(file_exists($filepath)) {
      echo "<span class='success'>Successfully created dir '$filepath'.</span>";
    } else {
      echo "<span class='warning'>Can't create dir '$filepath'.</span>";
      exit();
    }

    echo "</div>";
  }
  function createFile($filename, $path = "") {
    echo "<div class='create-file'>";
    echo "<span class=''>Try to create file: '{$path}{$file}'...</span>";

    if(file_exists($file)) {
      echo "<span class='success'>Successfully created file '{$path}{$file}'.</span>";
    } else {
      echo "<span class='warning'>Can't create file '{$path}{$file}'!</span>";
    }

    echo "</div>";
  }
  function createExternalFile($url, $path_filename) {
    echo "<div class='download-external-file'>";
    echo "<span class=''>Try to download file: '{$url}'...</span>";
    downloadFile($url, $path_filename);

    if(file_exists($path_filename)) {
      echo "<span class='success'>Successfully downloaded file '{$path_filename}'.</span>";
    } else {
      echo "<span class='warning'>Can't download file '{$path_filename}'!</span>";
    }

    echo "</div>";
  }

  function check_file_dir($filetype = "dir", $file, $path = "") {
    $filepath = $path.$file;
    echo "<div class='check-file-dir'>";
    if(file_exists($filepath) && $filetype != "external_file") {
      echo "<span class='exists'>File / Dir '{$filepath}' already exists!</span>";
      echo "</div>";
      return true;
    } else {
      if($filetype === "dir") {
        createDir($file, $path);
      } else if($filetype === "file") {
        createFile($file, $path);
      } else if($filetype === "symlink") {
        createSymlink($path, $file);
      } else if($filetype === "external_file") {
        if(file_exists($file)) {
          echo "<span class='exists'>File / Dir '{$file}' already exists!</span>";
        } else {
          createExternalFile($path, $file);
        }
      } else {
        echo "<span class='error'>Wrong filetype: {$filetype}!</span>";
      }
      echo "</div>";
    }
  }

  check_file_dir("dir", "typo3_config");
  check_file_dir("dir", "typo3");
  check_file_dir("dir", "typo3conf", "typo3/");
  check_file_dir("dir", $t3_src_dir_name);

  check_file_dir("external_file", "typo3/humans.txt", "https://raw.githubusercontent.com/Teisi/typo3-deploy/master/humans.txt");
  check_file_dir("external_file", "typo3/robots.txt", "https://raw.githubusercontent.com/Teisi/typo3-deploy/master/robots.txt");
  check_file_dir("external_file", "typo3/.htaccess", "https://raw.githubusercontent.com/Teisi/typo3-deploy/master/.htaccess");

  check_file_dir("external_file", $t3_zip_file, $typo3_source);

  if (file_exists($t3_zip_file)) {
    echo "<span>File {$t3_zip_file} exists</span>";

    if(!file_exists($t3_src_dir_name . "/" . $t3_version_dir . "/typo3")) {
      exec("tar -xzvf {$t3_zip_file} -C {$t3_src_dir_name}");
    }

    if (file_exists($t3_src_dir_name . "/" . $t3_version_dir . "/typo3")) {
      echo "<span class='success'>Successfully extracted!</span>";
      deleteFile("", $t3_zip_file);

      if(!file_exists("typo3/typo3conf/AdditionalConfiguration.php")) {
        file_put_contents("typo3/typo3conf/AdditionalConfiguration.php", "
  <?php
  \$databaseCredentialsFile = PATH_site . './../typo3_config/typo3_db.php';
  if (file_exists(\$databaseCredentialsFile)) {
      require_once (\$databaseCredentialsFile);
  }
        ");
      }

      if (!file_exists("typo3/FIRST_INSTALL")) {
        file_put_contents("typo3/FIRST_INSTALL", "");
      }

      if (!file_exists("typo3_config/typo3_db.php")) {
        file_put_contents("typo3_config/typo3_db.php", "
  <?php
  \$GLOBALS['TYPO3_CONF_VARS']['DB']['database'] = '{$t3_db_name}';
  \$GLOBALS['TYPO3_CONF_VARS']['DB']['host'] = '{$t3_db_host}';
  \$GLOBALS['TYPO3_CONF_VARS']['DB']['password'] = '{$t3_db_password}';
  \$GLOBALS['TYPO3_CONF_VARS']['DB']['username'] = '{$t3_db_user}';
  \$GLOBALS['TYPO3_CONF_VARS']['DB']['socket'] = '{$t3_db_socket}';

  \$GLOBALS['TYPO3_CONF_VARS']['BE']['installToolPassword'] = '{$t3_install_tool}';

        ");
      }

      deleteFile("typo3/", "typo3_src");
      deleteFile("typo3/", "typo3");
      deleteFile("typo3/", "index.php");

      check_file_dir("symlink", "typo3/typo3_src", "../" . $t3_src_dir_name . "/" . $t3_version_dir . "/");
      check_file_dir("symlink", "typo3/typo3", "typo3_src/typo3/");
      check_file_dir("symlink", "typo3/index.php", "typo3_src/index.php");
      if(file_exists("typo3/index.php")) {
        echo "<div class='readyToTakeOff'><span class=''>Have fun :)</span></div>";
      }

    } else {
      echo "<span>Extraction faild!</span>";
    }

  } else {
    echo "<span>File {$t3_zip_file} dosent exists!</span>";
  }
}

?>
  </main>
  <footer id="footer">

  </footer>
</div>
<script>
  (function($) {
    var i = 0, label_info_version = $(".choose-version input"), label_info_version_init_text = label_info_version.text();
    $.fn.reverse = [].reverse;
    function validate(s) {
      var rgx = /^[0-9]*\.?[0-9]*\.?[0-9]*$/;
      if(s.match(rgx)) {
        return true;
      } else {
        return false;
      }
    }
    $("#form-t3-install").attr("action", "deploy.php");
    label_info_version.text("Please wait");
    $.getJSON("https://get.typo3.org/json", function(data) {
      var items = [];
      $.each(data, function(key, val) {
        i++;
        $.each(val.releases, function(k, v) {
          var s = v.version.toString();
          if(validate(s)) {
            items.push(v.version);
          }
        });
        if(i > 3) {
          return false;
        }
      });
      items.sort().reverse();
      label_info_version.replaceWith("<select class='t3_version' name='t3_version' required></select>");
      var selectT3Version = $("select.t3_version");
      $.each(items, function(i, el) {
        selectT3Version.prepend("<option value=" + el + ">Typo3 " + el + "</option>");
      });
      selectT3Version.find("option:first-child").attr("selected='selected'");
      $(".t3_version_label .info").fadeOut('fast');
    }).fail(function() {
      label_info_version.text(label_info_version_init_text);
      console.log("getJSON failed!");
    });
  })(jQuery);
  /*!
   * pGenerator jQuery Plugin v1.0.5
   * https://github.com/M1Sh0u/pGenerator
   *
   * Created by Mihai MATEI <mihai.matei@outlook.com>
   * Released under the MIT License (Feel free to copy, modify or redistribute this plugin.)
   */
   (function($){var numbers_array=[],upper_letters_array=[],lower_letters_array=[],special_chars_array=[],$pGeneratorElement=null;var methods={init:function(options,callbacks)
   {var settings=$.extend({'bind':'click','passwordElement':null,'displayElement':null,'passwordLength':16,'uppercase':!0,'lowercase':!0,'numbers':!0,'specialChars':!0,'additionalSpecialChars':[],'onPasswordGenerated':function(generatedPassword){}},options);for(var i=48;i<58;i++){numbers_array.push(i)}
   for(i=65;i<91;i++){upper_letters_array.push(i)}
   for(i=97;i<123;i++){lower_letters_array.push(i)}
   special_chars_array=[33,35,36,42,123,125,47,63,58,59,95].concat(settings.additionalSpecialChars);return this.each(function(){$pGeneratorElement=$(this);$pGeneratorElement.bind(settings.bind,function(e){e.preventDefault();methods.generatePassword(settings)})})},generatePassword:function(settings)
   {var password=new Array(),selOptions=settings.uppercase+settings.lowercase+settings.numbers+settings.specialChars,selected=0,no_lower_letters=new Array();var optionLength=Math.floor(settings.passwordLength/selOptions);if(settings.uppercase){for(var i=0;i<optionLength;i++){password.push(String.fromCharCode(upper_letters_array[randomFromInterval(0,upper_letters_array.length-1)]))}
   no_lower_letters=no_lower_letters.concat(upper_letters_array);selected++}
   if(settings.numbers){for(var i=0;i<optionLength;i++){password.push(String.fromCharCode(numbers_array[randomFromInterval(0,numbers_array.length-1)]))}
   no_lower_letters=no_lower_letters.concat(numbers_array);selected++}
   if(settings.specialChars){for(var i=0;i<optionLength;i++){password.push(String.fromCharCode(special_chars_array[randomFromInterval(0,special_chars_array.length-1)]))}
   no_lower_letters=no_lower_letters.concat(special_chars_array);selected++}
   var remained=settings.passwordLength-(selected*optionLength);if(settings.lowercase){for(var i=0;i<remained;i++){password.push(String.fromCharCode(lower_letters_array[randomFromInterval(0,lower_letters_array.length-1)]))}}else{for(var i=0;i<remained;i++){password.push(String.fromCharCode(no_lower_letters[randomFromInterval(0,no_lower_letters.length-1)]))}}
   password=shuffle(password).join('');if(settings.passwordElement!==null){$(settings.passwordElement).val(password)}
   if(settings.displayElement!==null){if($(settings.displayElement).is("input")){$(settings.displayElement).val(password)}else{$(settings.displayElement).text(password)}}
   settings.onPasswordGenerated(password)}};function shuffle(o)
   {for(var j,x,i=o.length;i;j=parseInt(Math.random()*i),x=o[--i],o[i]=o[j],o[j]=x);return o}
   function randomFromInterval(from,to)
   {return Math.floor(Math.random()*(to-from+1)+from)}
   $.fn.pGenerator=function(method)
   {if(methods[method]){return methods[method].apply(this,Array.prototype.slice.call(arguments,1))}
   else if(typeof method==='object'||!method){return methods.init.apply(this,arguments)}
   else{$.error('Method '+method+' does not exist on jQuery.pGenerator')}}})(jQuery);
   (function($) {
    $('#generate-install-pw').pGenerator({
        'bind': 'click',
        'passwordElement': '#install-tool-pw',
        'displayElement': '#install-tool-pw-element',
        'passwordLength': 16,
        'uppercase': true,
        'lowercase': true,
        'numbers':   true,
        'specialChars': true,
        'onPasswordGenerated': function(generatedPassword) {
        alert('My new generated password is ' + generatedPassword);
        }
    });
  })(jQuery);
</script>
</body>
</html>
