## Overview

Typo3 PHP simple deployment tool to automatically download Typo3 and create symlinks / robots.txt / .htaccess / robots.txt.
Creates typo3_config dir which includes a typo3_db.php with example database data.
Creates a AdditionalConfiguration.php in typo3conf/ to include of the typo3_db.php.
Creates 'FIRST_INSTALL' file in typo3/ for the first installation of Typo3.

Your database access data and your installtool password are stored in the file typo3_config/typo3_db.php.

Your existing files won't be overwritten during the deployment process.

## Installation

1. Download the [deploy.php](https://raw.githubusercontent.com/Teisi/typo3-deploy/master/deploy.php) and upload it to your document root (e.g. httpdocs).
2. Start the deployment in your browser www.example.com/deploy.php.
3. Delete the deploy.php from your server after successful Installation.
4. To start the installation of Typo3 change your domain documentroot (e.g. to "httpdocs/typo3/").
5. Open your domain in your browser and install Typo3.
6. Have fun.
