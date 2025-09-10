<?php
// 布局文件的PHP逻辑部分
// 这里可以处理页面标题、元数据、CSS/JS资源等

// 默认页面标题
$pageTitle = $pageTitle ?? 'AiPHP应用';

// 默认 meta 描述
$pageDescription = $pageDescription ?? '使用AiPHP框架构建的应用';

// 默认关键字
$pageKeywords = $pageKeywords ?? 'AiPHP, PHP框架';

// 可选的额外CSS文件
$additionalCSS = $additionalCSS ?? [];

// 可选的额外JS文件
$additionalJS = $additionalJS ?? [];

// 页面内容变量
$content = $content ?? '';

// 网站名称
$siteName = 'AiPHP应用';

// 当前年份（用于页脚）
$currentYear = date('Y');
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($pageKeywords); ?>">
    
    <!-- 默认CSS -->
    <link rel="stylesheet" href="/static/own/css/main.css">
    
    <!-- 额外的CSS文件 -->
    <?php foreach ($additionalCSS as $css): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($css); ?>">
    <?php endforeach; ?>
    
    <!-- 网站图标 -->
    <link rel="icon" href="/static/own/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <!-- 页面头部 -->
    <header class="site-header">
        <div class="container">
            <h1 class="site-title"><a href="/"><?php echo htmlspecialchars($siteName); ?></a></h1>
            <nav class="site-nav">
                <ul>
                    <li><a href="/">首页</a></li>
                    <li><a href="/about">关于</a></li>
                    <li><a href="/csrf-test">CSRF测试</a></li>
                    <li><a href="/ni-huai">你坏</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- 主要内容区域 -->
    <main class="main-content">
        <div class="container">
            <?php echo $content; ?>
        </div>
    </main>
    
    <!-- 页面底部 -->
    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?php echo $currentYear; ?> <?php echo htmlspecialchars($siteName); ?>. 保留所有权利。</p>
        </div>
    </footer>
    
    <!-- 默认JS -->
    <script src="/static/own/js/main.js"></script>
    
    <!-- 额外的JS文件 -->
    <?php foreach ($additionalJS as $js): ?>
        <script src="<?php echo htmlspecialchars($js); ?>"></script>
    <?php endforeach; ?>
</body>
</html>