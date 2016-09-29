# PHPDeploy
Deploy your GitHub, GitLab or Bitbucket projects automatically on Git push events or webhooks using this small HTTP server written in PHP.

Only support bitbucket now.

### How dose it work?
When commits are pushed to your Git repository, the Git server will notify Git-Auto-Deploy by sending a HTTP POST request with a JSON body to a pre configured URL. The JSON body contains detailed information about the repository and what event that triggered the request. 

PHPDeploy parses and validates the request, and if all goes well and it is a new tag push, PHPDeploy will check the sites configured in config.php, if match, download tag package into the ./package/ dir, then unzip into the ./deploy/ dir, last generate soft links that you had configured in config.php. if necessarily you also can configure a composer install action for the site.

### How to use
## Download and install
```bash
git clone https://github.com/solody/phpdeploy.git
cd phpdeploy
php composer.phar install
```
## Set Permissions
```bash
chmod 777 ./*.sh
chmod 777 .
```
## Config your site info
```bash
cp config-sample.php config.php
vi config.php
```
```php
return [
    'secret'=>'sdfsdgfgjgukyuyukrrwwt45efgdgfdgf', // Use to verify the git webhook request, just fill some complex string
    'sites'=>[         // Config one or more site that hosted on your server
        'your-site-name'=>[          // your uniqu sitename
            'repository'=>[
                'home'=>'https://bitbucket.org/your-account-name/your-repository',     // Your git project home url
                'download_url'=>'https://bitbucket.org/your-account-name/your-repository/get/',   // Download url to get release packages 
                'auth'=>[            // If your project is private, set http auth account for download
                    'username'=>'your-username',
                    'password'=>'your-password'
                ]
            ],
            'siteRoot'=>'/var/www/sites/Your-Site-Root',       // Your site's root path, phpdeploy will delete it and create a soft link to replace it in every deployment.
            'siteData'=>[           // Your site's data/cache/config etc.
                // phpdeploy will delete the Key's path, and create a soft link that ref to the Value's path to instead it
                'config/Your-local.php'=>'/var/www/sites/Your-Site-Data/local_config.php'    // 'project relation path'=>'real data path in linux system'
            ],
            'runComposerInstall'=>true   // if need run composer install
        ]
    ]
];
```
## Set Webhook for your git project.
Since you setup the phpdeploy on you httpd server, then go to your git webhook setting page, fill your phpdeploy's url, the url similar 
http://you-domain-name/?secret=sdfsdgfgjgukyuyukrrwwt45efgdgfdgf
