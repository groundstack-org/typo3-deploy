## Overview

Typo3 PHP simple deployment tool to automatically download Typo3 and create symlinks / robots.txt / .htaccess / robots.txt.
Creates typo3_config dir which includes a typo3_db.php with example database data.
Creates a AdditionalConfiguration.php in typo3conf/ to include of the typo3_db.php.

Your existing files won't be overwritten during the deployment process.

## Installation

1. Download the [deploy.php](https://raw.githubusercontent.com/Teisi/typo3-deploy/master/deploy.php) and upload it to your document root (e.g. httpdocs).
2. Start the deployment in your browser www.example.com/deploy.php.
3. Delete the deploy.php from your server after successfull Installation.
4. Change your database login data in e.g. httpdocs/typo3_config/typo3_db.php.
5. To start the Installation of Typo3 change your domain documentroot (e.g. to "httpdocs/typo3/").
6. Open your domain in your browser and install Typo3.
7. Have fun.
