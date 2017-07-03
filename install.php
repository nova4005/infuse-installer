<?php
/**
 * The script will pull and install the Infusionsoft SDK into this directory.
 *
 */
require_once 'mysql-db.php';

if(array_key_exists('clientKey', $_POST)) {
    //Get Zip
    $isSDK = 'https://github.com/infusionsoft/infusionsoft-php/archive/master.zip';
    $fh = fopen('master.zip', 'w');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $isSDK);
    curl_setopt($ch, CURLOPT_FILE, $fh);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // this will follow redirects
    curl_exec($ch);
    curl_close($ch);
    fclose($fh);


    if($isSDK) {
        //Unzip git files
        $zip = new ZipArchive;
        $res = $zip->open('./master.zip');

        if($res === TRUE) {
            $zip->extractTo('.');
            $dir = trim($zip->getNameIndex(0), '/'); //Get Folder Name
            $zip->close();
            // echo 'Folder Name: ' . $dir . '<br>';
            // echo 'woot!';
        } else {
            // echo 'doh!';
        }
    }



    /**
     * Generate the starter MySQL connection for use in storing and accessing access tokens
     */
    $dbName = filter_var($_POST['dbname'], FILTER_SANITIZE_STRING);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    // $myConnection = new MySQL_DB($dbName, $username, $password);
    // $connectData = $myConnection->generate_connection();




    //Write the Infusionsoft Application settings to the config file
    $configWrite = fopen('app-config.php', 'w');
    $data = '<?php
$infusionsoft = new \Infusionsoft\Infusionsoft(array(
    "clientId" => "' . filter_var($_POST['clientKey'], FILTER_SANITIZE_STRING) . '",
    "clientSecret" => "' . filter_var($_POST['clientSecret'], FILTER_SANITIZE_STRING) . '",
    "redirectUri" => "' . filter_var($_POST['redirectURI'], FILTER_SANITIZE_URL) . '",
));';

    // if($connectData) {
        $data .= "\r\n\r\n";
        $data .= '$dbName = "' . $dbName . "\";\r\n";
        $data .= '$dbUserName = "' . $username . "\";\r\n";
        $data .= '$dbPassword = "' . $password . "\";\r\n";
    // }
    fwrite($configWrite, $data);
    fclose($configWrite);

    //Install the Vendor files through composer.phar
    chdir("./" . $dir);
    $phpDir = PHP_BINDIR . "/php";
    exec("$phpDir ../composer.phar install", $out, $ret);

}


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Infusionsoft API Installer</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <style media="screen">
        body, html {
            height: 100%;
        }

        body {
            width: 80%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000046;  /* fallback for old browsers */
            background: -webkit-linear-gradient(to bottom, #1CB5E0, #000046);  /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to bottom, #1CB5E0, #000046); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            background-repeat: no-repeat;
        }

        .container {
            background: #cecece;
        }

        .row {
            flex: 1;
            padding: 20px;
        }

        .pad {
            padding-left: 20px;
        }
    </style>
  </head>
  <body>
      <?php
      if(isset($ret) && $ret == 0 && array_key_exists('clientKey', $_POST)) {
          echo '<div class="container"><div class="row">';
          echo '<h2>Installation complete</h2>';
          echo '<p>Please find your installed SDK in the folder named ' . $dir . '. There is also a filed name app-config.php that has your application settings installed in the file and assigned to the $infusionsoft variable. This file can be stored above the web directory and included in script files when needing to access the API.</p>';
          echo '<p>If this is going to be a backend or development installation and you connected with a database on the previous screen you can go ahead and authorize the API development account with your Infusionsoft Application using this <a href="/is-authorize.php">Link</a>.</p>';
          echo '</div></div>';

          exit;
      } else if(array_key_exists('clientKey', $_POST) && isset($ret) && $ret != 0) {
          echo '<div class="container"><div class="row">';
          echo '<h2>Installation Error</h2>';
          echo '<p>There was an error executing the composer installation of vendor packages. Please check the files that were created and rerun installation if corrupt or missing. If problem persists check to make sure PHP exec() is enabled.</p>';
          echo '</div></div>';
      }
      ?>

      <div class="container">
          <div class="row">
              <h2>Complete Infusionsoft Application details</h2>
              <form class="infusion-installer" action="#" method="post">
                  <div class="form-group">
                    <label for="clientKey">Client Key</label>
                    <input type="text" class="form-control" id="clientKey" name="clientKey" placeholder="Client Key" required>
                  </div>
                  <div class="form-group">
                    <label for="clientSecret">Client Secret</label>
                    <input type="text" class="form-control" id="clientSecret" name="clientSecret" placeholder="Client Secret" required>
                  </div>
                  <div class="form-group">
                    <label for="redirectURI">Redirect URI</label>
                    <p>If you would like to set this up and do some API testing and you want to authorize the application and save the access tokens to the database, you can simply but in the redirect URL to go to the is-authorize.php file in this directory.</p>
                    <input type="text" class="form-control" id="redirectURI" name="redirectURI" placeholder="Redirect URI" required>
                  </div>
                  <h2>MySQL Connection Information</h2>
                  <p>For the database structure if you want to set this up and authorize on the next screen. Please go ahead and create the database and table with the following structure.</p>
                  <dl>
                      <dt>Database Name</dt>
                      <dd>Any Name</dd>
                      <dt>Table Name</dt>
                      <dd>admin_tokens</dd>
                      <dt>Table Structure</dt>
                      <dd>
                          <dl class="pad">
                              <dt>id</dt>
                              <dd>primary key, auto-increment</dd>
                              <dt>is_access_token</dt>
                              <dd>TEXT</dd>
                          </dl>
                      </dd>
                  </dl>
                  <div class="form-group">
                    <label for="dbname">Database Name</label>
                    <input type="text" class="form-control" id="dbname" name="dbname" placeholder="Database Name">
                  </div>
                  <div class="form-group">
                    <label for="username">User Name</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="User Name">
                  </div>
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                  </div>
                  <input type="submit" name="infuseSubmit" class="btn btn-success" value="Install API SDK">
              </form>
          </div>
      </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>
</html>
