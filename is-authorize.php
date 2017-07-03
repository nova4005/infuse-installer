<?php

require_once './infusionsoft-php-master/vendor/autoload.php';
require_once './mysql-db.php';
require_once './app-config.php';

if (isset($_GET['code']) and !$infusionsoft->getToken()) {
    $infusionsoft->requestAccessToken($_GET['code']);

    $myConnection = new MySQL_DB($dbName, $dbUserName, $dbPassword);
    $myConnection->store( serialize( $infusionsoft->getToken() ) );
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Authorize Application</title>

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
    </style>
  </head>
  <body>

      <?php if($infusionsoft->getToken()) { ?>
          <div class="container">
              <div class="row">
                  <h2>Authorization Successful</h2>
                  <p>Your Infusionsoft access token has been stored in the database and you can now use it to make API calls. Be sure to set up a cron job to refresh the token so you will not have to authorize the application again during development.</p>
              </div>
          </div>
      <?php } else { ?>
          <div class="container">
              <div class="row">
                  <h2>Infusionsoft Authorization Link</h2>
                  <p>Click the link below to authorize the developer application with your infusionsoft app. This will create and save the Infusionsoft Token object in the MySQL table you saved connection details for on the previous screen.</p>
                  <p><a href="<?= $infusionsoft->getAuthorizationUrl(); ?>">Authorize</a></p>

              </div>
          </div>
      <?php } ?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>
</html>
