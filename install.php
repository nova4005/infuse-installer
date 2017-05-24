<?php
/**
 * The script will pull and install the Infusionsoft SDK into this directory.
 *
 */
if(array_key_exists('clientKey', $_POST)) {
    echo 'Processing Installation';
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";


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
            echo 'Folder Name: ' . $dir . '<br>';
            echo 'woot!';
        } else {
            echo 'doh!';
        }
    }

    //Write the Infusionsoft Application settings to the config file
    $configWrite = fopen('app-config.php', 'w');
    $data = '<?php
$infusionsoft = new \Infusionsoft\Infusionsoft(array(
    "clientId" => "' . filter_var($_POST['clientKey'], FILTER_SANITIZE_STRING) . '",
    "clientSecret" => "' . filter_var($_POST['clientSecret'], FILTER_SANITIZE_STRING) . '",
    "redirectUri" => "' . filter_var($_POST['redirectURI'], FILTER_SANITIZE_URL) . '",
)); ?>';
    fwrite($configWrite, $data);
    fclose($configWrite);


    exit;
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
            background: #283c86;  /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #45a247, #283c86);  /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #45a247, #283c86); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            background-repeat: no-repeat;
        }

        .container {
            background: #cecece;
        }

        .row {
            flex: 1;
            padding: 20px;
        }
    </style>
  </head>
  <body>

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
                    <input type="text" class="form-control" id="redirectURI" name="redirectURI" placeholder="Redirect URI" required>
                  </div>
                  <input type="submit" name="infuseSubmit" value="Install API SDK">
              </form>
          </div>
      </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>
</html>
