<?php

// 1. 定义目录常量
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CORE_PATH', ROOT_PATH . '/core');
define('HANDLER_PATH', ROOT_PATH . '/handler');
define('PAGES_PATH', HANDLER_PATH . '/pages');
define('LAYOUTS_PATH', HANDLER_PATH . '/layouts');
define('OWN_LIBRARY_PATH', CORE_PATH . '/own-library');
define('OTHER_LIBRARY_PATH', CORE_PATH . '/other-library');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STATIC_PATH', PUBLIC_PATH . '/static');

// 2. 加载引导文件
require_once CONFIG_PATH . '/bootstrap.php';
