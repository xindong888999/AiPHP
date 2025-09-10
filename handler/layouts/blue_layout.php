<?php
// Contract anchors: 必须先读 prompts/page_management_guidelines.md 与 docs/callable_contracts.md
/**
 * 蓝色主题布局文件
 * 遵循AiPHP框架布局文件规范
 * 对应CSS文件: public/static/own/css/blue_layout.css
 * 对应JS文件: public/static/own/js/blue_layout.js
 */

// 设置默认值，防止变量未定义导致的错误
$pageTitle = $pageTitle ?? '默认标题 - AiPHP应用';
$pageDescription = $pageDescription ?? '默认页面描述';
$pageKeywords = $pageKeywords ?? 'AiPHP, PHP框架';
$content = $content ?? '页面内容加载失败';
$additionalCSS = $additionalCSS ?? [];
$additionalJS = $additionalJS ?? [];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($pageKeywords); ?>">
    <!-- 引入主要的CSS文件，与布局文件同名 -->
    <link rel="stylesheet" href="/static/own/css/blue_layout.css">
    <!-- 引入额外的CSS文件 -->
    <?php foreach ($additionalCSS as $css): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($css); ?>">
    <?php endforeach; ?>
</head>
<body>
    <!-- 页面头部 -->
    <header class="blue-header">
        <div class="container">
            <div class="logo">
                <a href="/">AiPHP应用</a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="/">首页</a></li>
                    <li><a href="/test">测试</a></li>
                    <li><a href="/rb_users">RB用户管理</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- 主要内容区域 -->
    <main class="blue-main">
        <div class="container">
            <?php echo $content; ?>
        </div>
    </main>

    <!-- 页面底部 -->
    <footer class="blue-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <h3>AiPHP应用</h3>
                    <p>简单、高效的PHP开发框架</p>
                </div>
                <div class="footer-links">
                    <ul>
                    </ul>
                </div>
            </div>
            <div class="footer-copyright">
                <p>&copy; <?php echo date('Y'); ?> AiPHP应用. 保留所有权利.</p>
            </div>
        </div>
    </footer>

    <!-- 引入主要的JS文件，与布局文件同名 -->
    <script src="/static/own/js/blue_layout.js"></script>
    <!-- 引入额外的JS文件 -->
    <?php foreach ($additionalJS as $js): ?>
        <script src="<?php echo htmlspecialchars($js); ?>"></script>
    <?php endforeach; ?>
</body>
</html>