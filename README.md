# infusionsoft installer
Used to install the new Infusionsoft PHP SDK and set up application keys. I build this mainly as practice for new developers at our company so they can quickly get up and running with the PHP SDK from Infusionsoft. It is helpful to bridge the gap to newcomers to Composer and working in CLI.

You can quickly get up and running with the Infusionsoft API by downloading the vendor dependencies and connecting a storing the access token from the oAuth authorization process in a MySQL database table.

# Note
To use this install script you need the composer.phar file from getcomposer.org. Also be sure to delete the install.php script when finished using, or move the SDK and config files out and then delete the installer folder.

# Requirements

PHP, curl, and exec() needs to be enabled for this to work successfully.

# MySQL Connection information

I am currently adding in the ability to generate your MySQL connection string in the config file. I currently have the beta storage of access tokens in a MySQL database. I am testing this and will possibly be updating this further in the future to make the Infusionsoft installer more flexible.

# After Installation

Once the installation is complete, if you used a hosting server to run the install be sure to delete the install script and move the build files over to their permanent home. This is a good housekeeping measure and keeps the server nice and tidy.
