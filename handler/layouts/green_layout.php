<?php
// Contract anchors: 必须先读 prompts/page_management_guidelines.md 与 docs/callable_contracts.md
/**
 * 绿色主题布局文件
 * 遵循AiPHP框架布局文件规范
 * 对应CSS文件: public/static/own/css/green_layout.css
 * 对应JS文件: public/static/own/js/green_layout.js
 */

// 设置默认值，防止变量未定义导致的错误
$pageTitle = $pageTitle ?? '默认标题 - AiPHP应用';
$pageDescription = $pageDescription ?? '默认页面描述';
$pageKeywords = $pageKeywords ?? 'AiPHP, PHP框架';
$content = $content ?? '页面内容加载失败';
$additionalCSS = $additionalCSS ?? [];
$additionalJS = $additionalJS ?? [];
$additionalMeta = $additionalMeta ?? '';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($pageKeywords); ?>">
    <!-- 输出额外的meta标签（如CSRF令牌） -->
    <?php echo $additionalMeta; ?>
    <!-- 引入Bootstrap CSS (本地版本) -->
    <link href="/static/third-party/bootstrap/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- 引入主要的CSS文件，与布局文件同名 -->
    <link rel="stylesheet" href="/static/own/css/green_layout.css">
    <!-- 引入额外的CSS文件 -->
    <?php foreach ($additionalCSS as $css): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($css); ?>">
    <?php endforeach; ?>
</head>
<body>
    <!-- 页面头部 -->
    <header class="green-header">
        <div class="container">
            <div class="logo">
                <a href="/">AiPHP应用</a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="/">首页</a></li>
                    <li><a href="/news">新闻</a></li>
                    <li><a href="/about">关于</a></li>
                    <li><a href="/test">测试</a></li>
                    <li><a href="/database-example">数据库示例</a></li>
                    <li><a href="/csrf-test">CSRF测试</a></li>
                    <li><a href="/users">用户列表</a></li>
                    <li><a href="/articles">文章管理</a></li>
                    <li><a href="/article_list">独立文章列表</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- 主要内容区域 -->
    <main class="green-main">
        <div class="container">
            <?php echo $content; ?>
        </div>
    </main>

    <!-- 页面底部 -->
    <footer class="green-footer">
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

    <!-- 引入Bootstrap JS和依赖 (本地版本) -->
    <script src="/static/third-party/bootstrap/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <!-- 引入主要的JS文件，与布局文件同名 -->
    <script src="/static/own/js/green_layout.js"></script>
    <!-- 引入额外的JS文件 -->
    <?php foreach ($additionalJS as $js): ?>
        <script src="<?php echo htmlspecialchars($js); ?>"></script>
    <?php endforeach; ?>
</body>
</html>