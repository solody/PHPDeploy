# PHPDeploy
Deploy your GitHub, GitLab or Bitbucket projects automatically on Git push events or webhooks using this small HTTP server written in PHP.

Only support bitbucket now.

### How dose it work?
When commits are pushed to your Git repository, the Git server will notify Git-Auto-Deploy by sending a HTTP POST request with a JSON body to a pre configured URL. The JSON body contains detailed information about the repository and what event that triggered the request. 

PHPDeploy parses and validates the request, and if all goes well and it is a new tag push, PHPDeploy will check the sites configured in config.php, if match, download tag package into the ./package/ dir, then unzip into the ./deploy/ dir, last generate soft links that you had configured in config.php. if necessarily you also can configure a composer install action for the site.

### How to use
git clone https://github.com/solody/phpdeploy.git
cd phpdeploy
composer.phar install

chmod 777 ./*.sh
chmod 777 .

cp config-sample.php config.php
