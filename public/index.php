<?php
/**
 * Webhook listener.
 */
ignore_user_abort(true);
set_time_limit(0);

require ('../vendor/autoload.php');
$config = new \Zend\Config\Config(include '../config.php');

chdir(dirname(__DIR__));

putenv("HOME=".getcwd());

if ($_SERVER['REQUEST_METHOD']=='POST') {

    $input_content = file_get_contents("php://input");

    $txt = 'Git Webhook Event Begain:'.PHP_EOL;
    $txt .= 'Input content:'.PHP_EOL
           .$input_content.PHP_EOL;

    $input_json = json_decode($input_content);

    if ($_GET['secret'] != $config->secret) $txt .= 'Secret Verify Fails.'.PHP_EOL;
    else {

        $txt .= 'Secret Verify Success.'.PHP_EOL;

        foreach ($config->sites as $site_name=>$site_values) {

            if ($site_values->repository->home == $input_json->repository->links->html->href) {

                $txt .= 'Site ['.$site_name.'] Match'.PHP_EOL;

                $change_count = count($input_json->push->changes);

                if ($change_count > 0) {

                    $txt .= 'Get ['.$change_count.'] Changes'.PHP_EOL;

                    foreach ($input_json->push->changes as $index=>$change) {

                        $txt .= 'Processing Change '.($index+1).', changeType='.$change->new->type.PHP_EOL;

                        if ($change->new->type == 'tag' && !file_exists('./package/'.$site_name.'/'.$change->new->name.'.tar.gz')) {

                            $cmd = './download.sh '.$site_name.' '.$site_values->repository->download_url.' '.$change->new->name.' '.
                                $site_values->repository->auth->username.' '.$site_values->repository->auth->password;
                            $txt .= 'Running Download Shell Script: '.$cmd.PHP_EOL;
                            $txt .= shell_exec($cmd).PHP_EOL;
                            $txt .= 'Download Shell Complete.'.PHP_EOL;

                            if ($site_values->runComposerInstall) {
                                $cmd = './composer_install.sh '.$site_name;
                                $txt .= 'Running Composer Install: '.$cmd.PHP_EOL;
                                $txt .= shell_exec($cmd).PHP_EOL;
                                $txt .= 'Composer Install Complete.'.PHP_EOL;
                            }

                            if (!empty($site_values->siteData)) {
                                foreach ($site_values->siteData as $link=>$path) {
                                    $cmd = './link_data.sh '.$site_name.' '.$link.' '.$path;
                                    $txt .= 'Running Link Data : '.$cmd.PHP_EOL;
                                    $txt .= shell_exec($cmd).PHP_EOL;
                                    $txt .= 'Link Data Complete.'.PHP_EOL;
                                }
                            }

                            $cmd = './link_site.sh '.$site_name.' '.$site_values->siteRoot;
                            $txt .= 'Running Link Site : '.$cmd.PHP_EOL;
                            $txt .= shell_exec($cmd).PHP_EOL;
                            $txt .= 'Link Site Complete.'.PHP_EOL;

                        } elseif ($change->new->type != 'tag') {

                            $txt .= 'changeType ignored.'.PHP_EOL;

                        } elseif (file_exists('./package/'.$site_name.'/'.$change->new->name.'.tar.gz')) {

                            $txt .= 'This tag ['.$change->new->name.'] had deployed in ['.$site_name.'] before, ignored.'.PHP_EOL;

                        }

                    }

                } else {
                    $txt .= 'Get none Changes'.PHP_EOL;
                }

            }
        }

    }


    if (!file_exists('./log')) mkdir('./log');
    $my_file = fopen("./log/".date('Y-m-d-H-i-s',time()).".txt", "w");
    fwrite($my_file, $txt);
    fclose($my_file);

} else {
    echo '非法访问！';
}
