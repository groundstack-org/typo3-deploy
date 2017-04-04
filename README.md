## Overview

Typo3 PHP simple deployment tool to automatically download Typo3 and create symlinks / robots.txt / .htaccess / robots.txt.<br />
Creates typo3_config dir which includes a typo3_db.php with example database data.<br />
Creates a AdditionalConfiguration.php in typo3conf/ to include of the typo3_db.php.<br />
Creates 'FIRST_INSTALL' file in typo3/ for the first installation of Typo3.<br />

Your database access data and your installtool password are stored in the file typo3_config/typo3_db.php.<br />

Your existing files won't be overwritten during the deployment process.<br />

### Installation

1. Download the [**deploy.php**](https://raw.githubusercontent.com/Teisi/typo3-deploy/master/deploy.php) and upload it to your document root (e.g. httpdocs).<br />
2. Start the deployment in your browser www.example.com/deploy.php.<br />
3. **Delete** the deploy.php from your server after successful Installation.<br />
4. To start the installation of Typo3 change your domain documentroot (e.g. to "httpdocs/typo3/").<br />
5. Open your domain in your browser and install Typo3.<br />
6. Have fun.<br />

![example picture of the deploy tool](resources/images/typo3-simple-deploy.jpg?raw=true "Title")
