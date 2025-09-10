<?php
/**
 * 数据库操作示例页面
 * 
 * 展示如何使用Database类进行基本的数据库操作
 */

// 布局变量
$layout="green_layout";

// 设置页面特定变量
$pageTitle = '数据库操作示例 - AiPHP框架';
$pageDescription = '展示如何使用Database类进行基本的数据库操作';
$pageKeywords = 'AiPHP,数据库,示例';

// 如果使用布局，需要引入对应的CSS和JS文件
$additionalCSS = ['/static/own/css/database_example.css'];
$additionalJS = ['/static/own/js/database_example.js'];

// 使用数据库类
use Core\OwnLibrary\Database\Database;

// 初始化变量
$users = [];
$errorMessage = '';

try {
    // 创建数据库实例
    $db = new Database();
    
    // 查询所有用户
    $users = $db->table('users')->orderBy('id', 'ASC')->get();
    
} catch (Exception $e) {
    $errorMessage = '数据库连接失败: ' . $e->getMessage();
}
?>
<div class="database-example-page">
    <h1>数据库操作示例</h1>
    
    <div class="example-section">
        <h2>如何使用Database类</h2>
        <p>Database类提供了简单易用的接口来操作MySQL和SQLite3数据库。</p>
        
        <h3>1. 创建数据库实例</h3>
        <pre><code>// 引入自动加载器
require_once CORE_PATH . '/own-library/autoloader/autoloader.php';

// 注册自动加载器
use Core\OwnLibrary\Autoloader\Autoloader;

$autoloader = new Autoloader();
$autoloader->addNamespace('Core\OwnLibrary', CORE_PATH . '/own-library');
$autoloader->register();

// 使用数据库类
use Core\OwnLibrary\Database\Database;

// 创建数据库实例
$db = new Database();
</code></pre>
        
        <h3>2. 查询数据</h3>
        <pre><code>// 查询所有用户
$users = $db->table('users')->get();

// 查询特定条件的用户
$user = $db->table('users')->where('id', '=', 1)->first();

// 带排序和限制的查询
$users = $db->table('users')
    ->where('age', '>', 18)
    ->orderBy('name', 'ASC')
    ->limit(10)
    ->get();
</code></pre>
        
        <h3>3. 插入数据</h3>
        <pre><code>// 插入单条记录
$userId = $db->table('users')->insert([
    'name' => '张三',
    'email' => 'zhangsan@example.com',
    'age' => 25
]);
</code></pre>
        
        <h3>4. 更新数据</h3>
        <pre><code>// 更新特定条件的记录
$affectedRows = $db->table('users')
    ->where('id', '=', 1)
    ->update([
        'name' => '李四',
        'age' => 26
    ]);
</code></pre>
        
        <h3>5. 删除数据</h3>
        <pre><code>// 删除特定条件的记录
$deletedRows = $db->table('users')
    ->where('id', '=', 1)
    ->delete();
</code></pre>
        
        <h3>6. 执行原生SQL</h3>
        <pre><code>// 执行查询SQL
$results = $db->query('SELECT * FROM users WHERE age > ?', [18]);

// 执行非查询SQL
$affectedRows = $db->execute('UPDATE users SET age = age + 1 WHERE id = ?', [1]);
</code></pre>
    </div>
    
    <div class="example-section">
        <h2>配置说明</h2>
        <p>数据库配置文件位于 <code>config/database.php</code>，支持MySQL和SQLite3两种数据库类型。</p>
        
        <h3>SQLite3配置示例</h3>
        <pre><code>'sqlite' => [
    'driver' => 'sqlite',
    'database' => ROOT_PATH . '/database/database.sqlite',
    'prefix' => '',
],</code></pre>
        
        <h3>MySQL配置示例</h3>
        <pre><code>'mysql' => [
    'driver' => 'mysql',
    'host' => 'localhost',
    'port' => 3306,
    'database' => 'aiphp_app',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
],</code></pre>
    </div>
    
    <div class="example-section">
        <h2>注意事项</h2>
        <ul>
            <li>Database类会自动处理数据库连接和断开</li>
            <li>所有查询都使用预处理语句，防止SQL注入攻击</li>
            <li>支持链式调用，使代码更简洁易读</li>
            <li>错误处理采用异常机制，便于调试和维护</li>
        </ul>
    </div>
</div>
