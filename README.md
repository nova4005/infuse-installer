# infusionsoft-sdk-installer
Used to install the new Infusionsoft PHP SDK and set up application keys. I build this mainly as practice for new developers at our company so they can quickly get up and running with the PHP SDK from Infusionsoft. It is helpful to bridge the gap to newcomers to Composer and working in CLI.

# Note
To use this install script you need the composer.phar file from getcomposer.org. Also be sure to delete the install.php script when finished using, or move the SDK and config files out and then delete the installer folder.

# Requirements

PHP, curl, and exec() needs to be enabled for this to work successfully.

# Planned Additions

I am currently thinking of adding in some basic MySQL database connection setup to the app-config.php to help get started with storing the Infusionsoft Token object after oAuth authentication is complete. This will help to get up and running with a permanent storage engine quickly.

# After Installation

Once the installation is complete, if you used a hosting server to run the install be sure to delete the install script and move the build files over to their permanent home. This is a good housekeeping measure and keeps the server nice and tidy.
