<?php
// CSRF保护测试页面
// 演示如何在AiPHP框架中实现和使用CSRF保护功能，防止跨站请求伪造攻击

// 布局变量
$layout = "green_layout";

// 设置页面特定变量
$pageTitle = 'CSRF保护测试 - AiPHP应用';
$pageDescription = '演示如何在AiPHP框架中实现和使用CSRF保护功能，防止跨站请求伪造攻击';
$pageKeywords = 'CSRF,安全,跨站请求伪造,表单保护,AiPHP,PHP框架';

// 引入对应的CSS和JS文件
$additionalCSS = ['/static/own/css/csrf-test.css'];
$additionalJS = ['/static/own/js/csrf-test.js'];

// 实例化CSRF令牌管理器
use Core\OwnLibrary\Security\CsrfTokenManager;
$csrfManager = new CsrfTokenManager();

// 处理表单提交
$formMessage = '';
$formMessageType = '';
$receivedToken = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取接收到的CSRF令牌
    $receivedToken = $_POST['csrf_token'] ?? '';
    
    // 验证CSRF令牌
    if ($csrfManager->validateRequestToken('default', 'csrf_token')) {
        $formMessage = '表单提交成功，CSRF令牌验证通过！';
        $formMessageType = 'success';
        // 这里可以添加表单处理逻辑
    } else {
        $formMessage = 'CSRF令牌验证失败，表单提交被拒绝！';
        $formMessageType = 'error';
    }
}

// 生成CSRF令牌
$csrfToken = $csrfManager->generateToken('default');
?>

<!-- 添加CSRF令牌到meta标签，供JavaScript使用 -->
<meta name="csrf-token" content="<?php echo htmlspecialchars($csrfToken); ?>">

<div class="csrf-test-container">
    <header>
        <h1>CSRF保护测试</h1>
        <p class="page-description">演示如何在AiPHP框架中实现和使用CSRF保护功能，防止跨站请求伪造攻击</p>
    </header>
    
    <!-- 显示表单处理消息 -->
    <?php if (!empty($formMessage)): ?>
    <div class="message <?php echo $formMessageType; ?>">
        <?php echo $formMessage; ?>
    </div>
    <?php endif; ?>
    
    <!-- CSRF令牌调试信息 -->
    <section class="demo-section">
        <h2>CSRF令牌信息</h2>
        <div class="token-info">
            <div class="token-item">
                <strong>发送的令牌（Stored Token）:</strong>
                <div class="token-value"><?php echo htmlspecialchars($csrfToken); ?></div>
            </div>
            
            <?php if (!empty($receivedToken)): ?>
            <div class="token-item">
                <strong>接收的令牌（Received Token）:</strong>
                <div class="token-value"><?php echo htmlspecialchars($receivedToken); ?></div>
            </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- 基本表单示例 -->
    <section class="demo-section">
        <h2>基本表单保护示例</h2>
        <p>这个表单包含一个隐藏的CSRF令牌字段，可以防止跨站请求伪造攻击。</p>
        <form method="POST" action="">
            <!-- CSRF令牌字段 - 使用CSRF管理器生成 -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
            
            <div class="form-group">
                <label for="username">用户名:</label>
                <input type="text" id="username" name="username" placeholder="请输入用户名">
            </div>
            <div class="form-group">
                <label for="email">邮箱:</label>
                <input type="email" id="email" name="email" placeholder="请输入邮箱">
            </div>
            <button type="submit" class="btn-primary">提交表单</button>
        </form>
    </section>
    
    <!-- AJAX请求保护示例 -->
    <section class="demo-section">
        <h2>AJAX请求保护示例</h2>
        <p>对于AJAX请求，可以将CSRF令牌添加到请求头中。</p>
        
        <div class="ajax-demo">
            <button id="ajaxButton" class="btn-secondary">发送AJAX请求</button>
            <div id="ajaxResult"></div>
        </div>
    </section>
    
    <!-- 多表单保护示例 -->
    <section class="demo-section">
        <h2>多表单保护示例</h2>
        <p>可以为不同的表单使用不同的令牌名称，提高安全性。</p>
    </section>
</div>