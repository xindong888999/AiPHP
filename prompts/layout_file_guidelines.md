# AiPHP 布局文件提示词指南

## 概述
本指南详细说明了在AiPHP框架中创建和使用布局文件的规范和最佳实践，确保布局文件的一致性、可维护性和可用性。请严格按照本指南创建布局文件，创建后无需进行测试。

## 布局文件命名规范
- 布局文件应放置在`handler/layouts/`目录下
- 文件名使用小写字母，单词间用下划线分隔
- 主布局文件通常命名为`main.php`
- 特殊用途布局文件应清晰反映其用途，并统一添加`_layout`后缀，如`admin_layout.php`、`login_layout.php`等
- 颜色主题布局文件命名为`颜色名称_layout.php`，例如：`green_layout.php`、`red_layout.php`

## 静态资源文件规范
- CSS文件应放置在`public/static/own/css/`目录下
- JavaScript文件应放置在`public/static/own/js/`目录下
- 图片和其他静态资源应放置在`public/static/own/images/`目录下
- 文件名使用小写字母，单词间用连字符分隔
- **每个布局文件必须有对应的CSS和JS文件**，文件名应与布局文件保持一致，例如：
  - 主布局文件`main.php`对应`main.css`和`main.js`
  - 绿色主题布局`green_layout.php`对应`green_layout.css`和`green_layout.js`
  - 红色主题布局`red_layout.php`对应`red_layout.css`和`red_layout.js`
  - 管理员布局`admin_layout.php`对应`admin_layout.css`和`admin_layout.js`

## 布局文件结构规范
布局文件应分为两个主要部分：
1. **PHP逻辑部分**：处理页面标题、元数据、CSS/JS资源等
2. **HTML模板部分**：完整的HTML结构，包含头部、主要内容区域和底部

## PHP逻辑部分规范
- 使用PHP变量来接收页面传递的数据
- 设置默认值以确保布局文件的独立性
- 变量命名应清晰明确，如`$pageTitle`、`$content`等
- 不应在布局文件中处理复杂的业务逻辑

### 核心变量
```php
<?php
// 默认页面标题
$pageTitle = $pageTitle ?? '默认标题';

// 默认页面描述
$pageDescription = $pageDescription ?? '默认描述';

// 默认关键字
$pageKeywords = $pageKeywords ?? '默认关键字';

// 页面内容
$content = $content ?? '';

// 额外CSS文件数组
$additionalCSS = $additionalCSS ?? [];

// 额外JS文件数组
$additionalJS = $additionalJS ?? [];
?>
```

## HTML模板部分规范
- 使用HTML5文档类型
- 包含完整的HTML结构：`<!DOCTYPE html>`、`<html>`、`<head>`、`<body>`
- 在`<head>`部分包含必要的meta标签和资源链接
- 在页面内容区域使用`<?php echo $content; ?>`输出页面内容
- 确保布局响应式设计

### 基本HTML结构
```html
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($pageKeywords); ?>">
    
    <!-- 布局文件对应的CSS -->
    <!-- 注意：这里应该引用与布局文件同名的CSS文件 -->
    <!-- 例如：main.php布局应引用main.css，green_layout.php布局应引用green_layout.css，red_layout.php布局应引用red_layout.css -->
    <link rel="stylesheet" href="/static/own/css/main.css">
    
    <!-- 额外的CSS文件 -->
    <?php foreach ($additionalCSS as $css): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($css); ?>">
    <?php endforeach; ?>
</head>
<body>
    <!-- 页面头部 -->
    <!-- 注意：创建新布局文件时，必须保留main.php布局文件中的页面头部结构，包括导航菜单 -->
    <header class="site-header">
        <div class="container">
            <h1 class="site-title"><a href="/"><?php echo htmlspecialchars($siteName); ?></a></h1>
            <nav class="site-nav">
                <ul>
                    <li><a href="/">首页</a></li>
                    <li><a href="/about">关于</a></li>
                    <li><a href="/contact">联系</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- 主要内容区域 -->
    <main>
        <?php echo $content; ?>
    </main>
    
    <!-- 页面底部 -->
    <footer>
        <!-- 底部内容 -->
    </footer>
    
    <!-- 布局文件对应的JS -->
    <!-- 注意：这里应该引用与布局文件同名的JS文件 -->
    <!-- 例如：main.php布局应引用main.js，green_layout.php布局应引用green_layout.js，red_layout.php布局应引用red_layout.js -->
    <script src="/static/own/js/main.js"></script>
    
    <!-- 额外的JS文件 -->
    <?php foreach ($additionalJS as $js): ?>
        <script src="<?php echo htmlspecialchars($js); ?>"></script>
    <?php endforeach; ?>
</body>
</html>
```

## 布局文件删除规范
当需要删除一个布局文件时，应遵循以下步骤以确保系统的完整性和一致性：

### 1. 删除指定的布局文件
- 删除位于`handler/layouts/`目录下的目标布局文件
- 例如：删除`handler/layouts/custom_layout.php`或`handler/layouts/red_layout.php`

### 2. 删除布局文件对应的静态文件
- **必须删除与布局文件同名的CSS和JS文件**
- CSS文件位于`public/static/own/css/`目录
- JS文件位于`public/static/own/js/`目录
- 例如：删除`custom_layout.php`时，应同时删除`public/static/own/css/custom_layout.css`和`public/static/own/js/custom_layout.js`
- 例如：删除`red_layout.php`时，应同时删除`public/static/own/css/red_layout.css`和`public/static/own/js/red_layout.js`

### 3. 查找并更新应用该布局的页面
- 使用搜索工具查找所有引用该布局文件的页面文件
- 将所有引用从目标布局文件更改为`main.php`（或其他适当的布局文件）
- 例如：将以下代码：
  ```php
  $layout='red_layout';
  ```
  改成:
  ```php
  $layout='main';
  ```
- 确保更新后页面显示正常，没有样式或功能问题
