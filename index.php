<?php
define('DEVELOPMENT_ENVIRONMENT', true);
define('ROOT', realpath(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);
define('NG', '');
define('APPS', 'apps');
define('ASSETS', 'assets');
define('LIBS', 'libs');
define('CACHE', 'cache');
define('TMP', 'tmp');
define('COOKIES', 'cookies');
define('CURLCOOKIE', 'curlcookie');
define('MODEL', 'Model');
define('VIEW', 'View');
define('CONTROLLER', 'Controller');
define('CONFIG', 'Config');
define('LAYOUT', 'Layout');
include (ROOT . DS . APPS . DS . "Bootstrap.php");
new Bootstrap();