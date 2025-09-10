<?php

// 引导文件 - 负责初始化应用程序

// 1. 设置错误报告级别
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 2. 定义核心常量
// 所有目录常量已在入口文件中定义

// 3. 注册自动加载函数
require_once CORE_PATH . '/own-library/autoloader/autoloader.php';

use Core\OwnLibrary\Autoloader\Autoloader;

$autoloader = new Autoloader();
$autoloader->addNamespace('Core\OwnLibrary', CORE_PATH . '/own-library');
$autoloader->addNamespace('Core\OtherLibrary', CORE_PATH . '/other-library');
// 更新命名空间映射以匹配实际目录结构
$autoloader->addNamespace('Core\OtherLibrary\RedBean', CORE_PATH . '/other-library/redbeanphp');
$autoloader->register();

// 4. 加载路由配置
$routes = require CONFIG_PATH . '/routes.php';

// 5. 初始化并运行路由
use Core\OwnLibrary\Routing\Router;
$router = new Router($routes);
$routeResult = $router->match();


// 6. 实例化调度中心并加载页面
use Core\OwnLibrary\Dispatcher\Dispatcher;
$dispatcher = new Dispatcher(dirname(__DIR__), 'handler/pages', 'handler/layouts');
$routePath = $routeResult['path'];
$routeParams = $routeResult['params'];
$dispatcher->dispatch($routePath, $routeParams);