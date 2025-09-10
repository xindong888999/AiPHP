# AiPHP框架流程说明
## AiPHP框架哲学

1. 一切皆类：功能通过类来实现，不是抽象的中间件
2. 直接调用：AI需要什么功能，直接调用对应的类
3. 零学习成本：不需要理解复杂的框架概念
4. 提示词驱动：AI通过提示词就能知道有哪些类可用



## 框架总体流程

AiPHP是一个现代化的PHP开发框架，采用了简洁的MVC风格架构，本文档详细说明了框架的工作流程、文件结构和使用方法。

## 基本流程

1. **入口文件**：`public/index.php` 是整个框架的入口点
2. **引导加载**：入口文件加载 `config/bootstrap.php`
3. **核心初始化**：bootstrap.php 完成以下任务：
   - 载入自动加载机制
   - 加载核心类库
   - 加载路由配置文件
   - 实例化路由类
4. **路由解析**：路由类解析当前URL，确定对应的页面文件和路由参数
5. **页面加载**：实例化页面加载类，将页面路径和路由参数传入
6. **页面渲染**：加载对应的页面文件，使用布局文件渲染最终HTML

## 文件结构与职责

### 核心文件

- **`public/index.php`**: 框架入口文件，接收所有HTTP请求
- **`config/bootstrap.php`**: 框架引导文件，初始化核心组件
- **`config/routes.php`**: 路由配置文件，定义URL与页面的映射关系

### 核心类库

- **路由类**: 负责解析URL，匹配路由配置，获取页面路径和路由参数
- **页面加载类**: 负责加载页面文件，将路由参数传递给页面
- **验证辅助类**: 提供参数验证功能，确保输入数据符合要求

### 页面与布局

- **页面文件**: 位于 `handler/pages` 目录，包含页面特定的逻辑和内容
- **布局文件**: 位于 `handler/layouts` 目录，定义页面的整体结构和样式

## 页面开发流程

### 页面文件结构

页面文件通常包含三个部分：

1. **PHP逻辑部分**: 位于文件顶部，处理请求参数，执行业务逻辑
2. **HTML内容部分**: 定义页面的具体内容和结构
3. **布局加载部分**: 文件底部，将内容传递给布局文件进行渲染

```php
<?php
// 1. PHP逻辑部分 - 处理参数、验证和业务逻辑
$pageTitle = '页面标题';
$pageDescription = '页面描述';

// 可以直接使用路由参数变量
$userId = $id ?? '';

// 也可以使用$params数组访问所有路由参数
$allParams = $params;

// 使用验证辅助类验证参数
$validationResult = ValidationHelper::validate($params, 'id', [
    '必填' => 'ID不能为空',
    '数字' => 'ID必须是数字'
]);

// 处理业务逻辑...

// 2. HTML内容部分 - 开始输出缓冲
ob_start();
?>
<!-- HTML内容 -->
<div class="content">
    <h1>页面标题</h1>
    <p>这里是页面内容</p>
    
    <?php if (isset($validationResult['error'])): ?>
        <div class="error"><?php echo $validationResult['error']; ?></div>
    <?php endif; ?>
    
    <!-- 更多HTML内容... -->
</div>
<?php
// 获取输出缓冲内容
$content = ob_get_clean();

// 3. 布局加载部分 - 使用布局文件渲染最终HTML
require LAYOUTS_PATH . '/main.php';
?>
```

### 参数获取与验证

在页面文件中，有两种方式可以获取路由参数：

1. **直接使用变量**: 因为页面加载类使用了 `extract($params)`，所以路由参数会自动转换为同名变量
   ```php
   // 如果路由是 '/user/{id}'，且当前URL是 '/user/123'
   echo $id; // 输出 "123"
   ```

2. **使用参数数组**: 所有路由参数也可以通过 `$params` 数组访问
   ```php
   echo $params['id']; // 输出 "123"
   ```

3. **参数验证**: 使用 `ValidationHelper::validate` 方法验证参数
   ```php
   $result = ValidationHelper::validate($params, 'id', [
       '必填' => 'ID不能为空',
       '数字' => 'ID必须是数字',
       '长度5' => 'ID必须是5位数字'
   ]);
   
   if (isset($result['error'])) {
       echo $result['error']; // 显示错误信息
   }
   ```

## 静态资源管理

框架采用约定优于配置的原则来管理静态资源，确保页面和布局对应的CSS、JS文件能被自动加载。

### 静态资源命名约定

1. **页面静态资源**: 
   - 路径: `public/static/own/css/` 和 `public/static/own/js/`
   - 命名: 与页面文件名一致
   - 例如: 页面文件 `handler/pages/home.php` 对应的静态资源是:
     - `public/static/own/css/home.css`
     - `public/static/own/js/home.js`

2. **布局静态资源**:
   - 路径: 与页面静态资源相同
   - 命名: 与布局文件名一致
   - 例如: 布局文件 `handler/layouts/main.php` 对应的静态资源是:
     - `public/static/own/css/main.css`
     - `public/static/own/js/main.js`

3. **子目录页面**:
   - 如果页面位于子目录中，静态资源也应该在对应的子目录中
   - 例如: 页面文件 `handler/pages/contact/index.php` 对应的静态资源是:
     - `public/static/own/css/contact/index.css`
     - `public/static/own/js/contact/index.js`

## 布局系统

布局文件定义了页面的整体结构，负责将页面内容嵌入到一个完整的HTML文档中。

### 布局文件结构

```php
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle ?? 'AiPHP应用'; ?></title>
    <meta name="description" content="<?php echo $pageDescription ?? ''; ?>">
    <link rel="stylesheet" href="/static/own/css/main.css">
    <?php
    // 加载额外的CSS文件
    if (isset($additionalCSS) && is_array($additionalCSS)) {
        foreach ($additionalCSS as $css) {
            echo '<link rel="stylesheet" href="' . $css . '">' . PHP_EOL;
        }
    }
    ?>
</head>
<body>
    <header>
        <!-- 页面头部 -->
    </header>
    
    <main>
        <!-- 页面内容区域，由$content变量提供 -->
        <?php echo $content; ?>
    </main>
    
    <footer>
        <!-- 页面底部 -->
    </footer>
    
    <script src="/static/own/js/main.js"></script>
    <?php
    // 加载额外的JS文件
    if (isset($additionalJS) && is_array($additionalJS)) {
        foreach ($additionalJS as $js) {
            echo '<script src="' . $js . '"></script>' . PHP_EOL;
        }
    }
    ?>
</body>
</html>
```

## 总结

AiPHP框架采用了简洁而直观的架构，通过明确的文件组织和命名约定，简化了开发流程。开发者只需要关注页面的业务逻辑和内容，而框架会处理路由解析、参数传递和页面渲染等基础工作。

通过遵循本文档描述的开发流程和约定，开发者可以快速创建功能完善、结构清晰的Web应用。

进一步的“可调用契约约定”（类/函数/页面变量/错误结构/静态资源命名）请参见：`docs/callable_contracts.md`。