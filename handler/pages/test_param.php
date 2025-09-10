<?php
/**
 * 路由参数测试页面
 */

// 更详细地输出所有已定义变量
echo '<pre>';
echo '所有已定义变量：\n';
print_r(get_defined_vars());
echo '</pre>';

// 检查不同的参数访问方式
if (isset($params) && isset($params['id'])) {
    echo '<h1>通过 $params 数组访问路由参数: $params["id"] = ' . $params['id'] . '</h1>';
} else {
    echo '<h1>$params 数组中未找到id参数</h1>';
}

if (isset($id)) {
    echo '<h2>直接访问 $id 变量: $id = ' . $id . '</h2>';
} else {
    echo '<h2>$id 变量不存在</h2>';
}

// 输出所有$_GET参数
if (!empty($_GET)) {
    echo '<h2>GET参数：</h2>';
    echo '<pre>';
    print_r($_GET);
    echo '</pre>';
}

// 检查全局变量
if (isset($GLOBALS['params'])) {
    echo '<h2>全局变量 $GLOBALS["params"]：</h2>';
    echo '<pre>';
    print_r($GLOBALS['params']);
    echo '</pre>';
}

// 检查路由结果相关变量
if (isset($routeResult)) {
    echo '<h2>$routeResult 变量：</h2>';
    echo '<pre>';
    print_r($routeResult);
    echo '</pre>';
}