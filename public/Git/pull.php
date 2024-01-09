<?php
# /Git/pull.php?jqernaiiwirj2384jl=nvjsdf38zkajjkdf93
$key = "jqernaiiwirj2384jl";
$val = "nvjsdf38zkajjkdf93";

if (!($_GET[$key] === $val)) die('Access Denied');

# Execute the pull request after a Git push event
$commands = 'cd ../..;';
$commands .= 'export COMPOSER_HOME=/usr/local/bin/composer 2>&1;';
$commands .= 'git fetch 2>&1;';
$commands .= 'git pull 2>&1;';
$commands .= 'composer install 2>&1;';
$commands .= 'composer update 2>&1;';
$commands .= 'composer dump-autoload 2>&1;';
$commands .= '/usr/bin/git log -1 --format=format:%h > ./public/version.txt;';

# Running commands
echo '<pre>';
passthru($commands);
echo '</pre>';