<?php
  // Dear maintainer:
  //
  // Once you are done trying to 'optimize' this routine,
  // and have realized what a terrible mistake that was,
  // please increment the following counter as a warning
  // to the next guy:
  //
  // total_hours_wasted_here = 12
  session_start();
  error_reporting(E_ALL);
  require_once("resources/lib/Helper.php");
  require_once("resources/lib/Deployer.php");

  $deployer = new Deployer($_POST);

  // if(file_exists("getTypo3Deployer.php")) {
  //   unlink("getTypo3Deployer.php");
  // }
  // if(file_exists("_config.yml")) {
  //   unlink("_config.yml");
  // }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Typo3 simple deployer</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="resources/css/bootstrap.min.css" />
  <link rel="stylesheet" href="resources/css/bootstrap-responsive.min.css" />
  <link rel="stylesheet" href="resources/css/colorpicker.css" />
  <link rel="stylesheet" href="resources/css/datepicker.css" />
  <link rel="stylesheet" href="resources/css/uniform.css" />
  <link rel="stylesheet" href="resources/css/select2.css" />
  <link rel="stylesheet" href="resources/css/matrix-style.css" />
  <link rel="stylesheet" href="resources/css/matrix-media.css" />
  <link rel="stylesheet" href="resources/css/bootstrap-wysihtml5.css" />
  <link rel="stylesheet" href="resources/css/typo3-simple-deploy.css" />
  <link href="resources/css/font-awesome.css" rel="stylesheet" />
</head>
<body>

<noscript><h1>THIS DEPLOMENT NEEDS JAVASCRIPT ON!</h1></noscript>

<?php if (true) { ?>

<!-- Header-part -->
<div id="header">
  <h1><a href="http://groundstack.de">Typo3 simple deployer</a></h1>
</div>
<!-- close-Header-part -->

<!-- top-Header-menu -->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">Welcome User</span><b class="caret"></b></a>
      <ul class="dropdown-menu">
        <!-- <li><a href="login.html"><i class="icon-key"></i> Log Out</a></li> -->
        <li id="form-login-out">
          <?php $deployer->userLoginCheck(); ?>
        </li>
      </ul>
    </li>

    <li class=""><a title="" href="login.html"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
  </ul>
</div>

<!-- sidebar-menu -->

<div id="sidebar"> <a href="#" class="visible-phone"><i class="icon icon-list"></i>Forms</a>
  <ul>
    <li class="active"><a href="index.php" class="typo3"><i class="icon icon-home"></i> <span>Typo3 install & change</span></a> </li>
    <li class="" style="display: none;"><a href="#" class="theme"><i class="icon icon-list"></i> <span>Themes</span></a> </li>
    <li class=""><a href="readme.php" class="readme"><i class="icon icon-file"></i> <span>Readme</span></a> </li>
  </ul>
</div>

<!-- close-left-menu-stats-sidebar -->

<div id="content" class="deploy-typo3">
  <div id="content-header">
    <div id="breadcrumb"> <a href="deploy.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>  <a href="#" class="current typo3">Typo3 install & change</a> </div>
    <h1>Typo3 deployer</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <h4>PHP config:</h4>
        <?php
          $maxExecutionTime = ini_get('max_execution_time');
          if ($maxExecutionTime < 240) {
            echo "<p class='warning'>max_execution_time = {$maxExecutionTime} <span>(should be 240!)</span></p>";
          } else {
            echo "<p class='success'>max_execution_time = {$maxExecutionTime}</p>";
          }
          $memory_limit = ini_get('memory_limit');
          if ($memory_limit < 128) {
            echo "<p class='warning'>memory_limit = {$memory_limit} <span>(should be 128!)</span></p>";
          } else {
            echo "<p class='success'>memory_limit = {$memory_limit}</p>";
          }
          $max_input_vars = ini_get('max_input_vars');
          if ($max_input_vars < 1500) {
            echo "<p class='warning'>max_input_vars = {$max_input_vars} <span>(should be 1500!)</span></p>";
          } else {
            echo "<p class='success'>max_input_vars = {$max_input_vars}</p>";
          }
        ?>
      </div>
    </div>
    <div class="row-fluid">
      <div class="span6">

        <!-- form to delete deployment script -->
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Delete deployment after success!</h5>
          </div>
          <div id="delete-deployment" class="widget-content nopadding">
            <form id="form-delete-deployment" class="form-horizontal" method="post" action="<?php echo htmlentities(urlencode($_SERVER['PHP_SELF'])); ?>">
              <input type="hidden" name="formtype" value="deletedeployment" />
              <?php echo "<input type='hidden' name='deploymentfolder' value='".__DIR__."' />"; ?>
              <div class="form-actions">
                <button type="submit" class="btn btn-danger" name="sent" value="Senden" data-translate="_send">Delete!</button>
              </div>
            </form>
          </div>
        </div>

        <!-- form to install or change Typo3 installation -->
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Typo3 Install or change</h5>
          </div>
          <div class="widget-content nopadding">
            <form id="form-install-typo3" action="#" method="post" class="form-horizontal ajax_form">
              <input type="hidden" name="formtype" value="t3install" />
              <?php echo "<input type='hidden' name='deploymentfolder' value='".__DIR__."' />"; ?>

              <div id="t3version" class="control-group">
                <label for="t3_version" class="control-label">
                  <span data-translate="_yourversion">Enter your desired version:</span>
                </label>
                <div class="controls">
                  <select id="t3_version" name="t3_version">
                    <option value="7.6.18">7.6.18</option>
                    <option value="8.7.1">8.7.1</option>
                  </select>
                </div>
              </div>

              <div id="t3function" class="control-group">
                <label for="text_function_id" class="control-label">
                  <span data-translate="_t3function">Please choose:</span>
                </label>
                <div class="controls">
                  <select id="text_function_id" name="t3_install_function">
                    <option value="completeinstall" selected>Full Install</option>
                    <option value="onlysymlink">Only change symlink</option>
                    <option value="downloadextract">Only download and extract</option>
                    <option value="downloadextractlink">Download, extract and change symlink</option>
                  </select>
                </div>
              </div>

              <div class="control-group display">
                <label class="control-label" for="db-name" data-translate="_databasename">Database name</label>
                <div class="controls">
                  <input id="db-name" class="input-big span8" type="text" name="t3_db_name" value="databaseName">
                  <span class="help-block" data-translate="_databaseisstored">Database Access data are stored in 'typo3_config/typo3_db.php'.</span>
                </div>
              </div>
              <div class="control-group display">
                <label class="control-label" for="db-user" data-translate="_databaseuser">Database username</label>
                <div class="controls">
                  <input id="db-user" class="input-big span8" type="text" name="t3_db_user" value="databaseUser">
                </div>
              </div>
              <div class="control-group display">
                <label class="control-label" for="db-password" data-translate="_databaseuserpassword">Database userpassword</label>
                <div class="controls">
                  <input id="db-password" class="input-big span8" type="password" name="t3_db_password" value="databasePasswort">
                </div>
              </div>
              <div class="control-group display">
                <label class="control-label" for="db-host" data-translate="_databasehost">Database host</label>
                <div class="controls">
                  <input id="db-host" class="input-big span8" type="text" name="t3_db_host" value="localhost">
                </div>
              </div>
              <div class="control-group display">
                <label class="control-label" for="db-socket" data-translate="_databasesocket">Database socket</label>
                <div class="controls">
                  <input id="db-socket" class="input-big span8" type="text" name="t3_db_socket" value="">
                </div>
              </div>
              <div class="control-group display">
                <label class="label control-label label-install-tool-pw" for="install-tool-pw" data-translate="_installpassword">Install Tool password</label>
                <div class="controls">
                  <input type="password" class="input-big span8" id="install-tool-pw" name="t3_install_tool" value="" />
                  <a href="#" class="btn btn-danger form-btn" id="generate-install-pw" data-translate="_generatepassword">Generate a password</a>
                  <div class="left" id="install-tool-pw-element"></div>
                  <span class="help-block" data-translate="_installtoolstoredin">Install Tool password is stored in 'typo3_config/typo3_db.php'.</span>
                </div>
              </div>
              <div class="form-actions">
                <button id="submitinstall" class="btn btn-success" type="submit" name="sendt3install" value="Senden" data-translate="_send">Send</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="span6">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>List all local Typo3 sources (in folder 'typo3_sources')</h5>
          </div>
          <div class="widget-content">
            <div id="list-typo3-sources" class="controls ajaxpost" data-ajax='{ "formtype":"ajaxpost", "ajax_function":"getTypo3Sources" }'>
                <?php
                  $deployer->helper->getDirList();
                ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row-fluid">
      <div class="span12">
        <div id="deploy-output">

        </div>
      </div>
    </div>

    <div class="row-fluid">

      <div class="span6">
        <div class="widget-box" id="typo3-delete-temp">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Delets all files and folders in '/typo3temp/'! Then trys to recreate nessasary tmp files and folders.</h5>
          </div>
          <div class="widget-content">
            <div id="delete-typo3temp" class="controls">
              <form id='form-delete-typo3temp' class='form-horizontal' method='post'>
                <input type='hidden' name='t3_version' value='' />
                <input type='hidden' name='formtype' value='deletetypo3temp' />
                <div class="form-actions">
                  <button id="submit" class="btn btn-success" type="submit" name="deletetypo3temp" value="Senden" data-translate="_deletetempfiles">Delete Typo3 temp files</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="span6">
        <div class="widget-box" id="typo3-file-permission">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Trys to set default Typo3 file permissions. For change permissions to e.g. 2775 and it faild, the script trys to set 0755!</h5>
          </div>
          <div class="widget-content">
            <div id="set-typo3-permissions" class="controls">
              <form id='form-set-typo3filepermission' class='form-horizontal' method='post'>
                <input type='hidden' name='formtype' value='setTypo3FilePermissions' />
                <div class="form-actions">
                  <button id="submit" class="btn btn-success" type="submit" name="setTypo3FilePermissions" value="Senden" data-translate="_setfilepermissionssubmit">Set permissions</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
</div>

<div class="socket" id="loader">
	<div class="gel center-gel">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c1 r1">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c2 r1">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c3 r1">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c4 r1">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c5 r1">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c6 r1">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>

	<div class="gel c7 r2">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>

	<div class="gel c8 r2">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c9 r2">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c10 r2">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c11 r2">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c12 r2">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c13 r2">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c14 r2">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c15 r2">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c16 r2">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c17 r2">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c18 r2">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c19 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c20 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c21 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c22 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c23 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c24 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c25 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c26 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c28 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c29 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c30 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c31 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c32 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c33 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c34 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c35 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c36 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>
	<div class="gel c37 r3">
		<div class="hex-brick h1"></div>
		<div class="hex-brick h2"></div>
		<div class="hex-brick h3"></div>
	</div>

</div>

<?php } ?>

<!--end-Footer-part-->
<script src="resources/javascript/jquery.min.js"></script>
<script src="resources/javascript/jquery.ui.custom.js"></script>
<script src="resources/javascript/bootstrap.min.js"></script>
<script src="resources/javascript/bootstrap-colorpicker.js"></script>
<script src="resources/javascript/bootstrap-datepicker.js"></script>
<script src="resources/javascript/masked.js"></script>
<script src="resources/javascript/jquery.uniform.js"></script>
<script src="resources/javascript/select2.min.js"></script>
<script src="resources/javascript/matrix.js"></script>
<script src="resources/javascript/matrix.form_common.js"></script>
<script src="resources/javascript/wysihtml5-0.3.0.js"></script>
<script src="resources/javascript/jquery.peity.min.js"></script>
<script src="resources/javascript/bootstrap-wysihtml5.js"></script>
<script src="resources/javascript/pGenerator.min.js"></script>
<script src="resources/javascript/typo3-simple-deploy.js"></script>
<script>
	$('.textarea_editor').wysihtml5();
</script>
</body>
</html>
