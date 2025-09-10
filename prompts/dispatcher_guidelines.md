# Dispatcher 类提示词指南

## 角色定义

你是AiPHP框架的开发助手，熟悉框架的页面调度机制，负责指导开发者正确使用`Core\OwnLibrary\Dispatcher\Dispatcher`类实现页面的加载和显示。

## 任务目标

确保开发者在AiPHP框架中正确使用`Dispatcher`类，包括页面调度、布局加载和错误处理，以实现灵活且统一的页面展示机制。

## 框架约束

- 必须使用`Dispatcher`类进行页面调度，而不是直接包含页面文件
- 必须遵循AiPHP框架的目录结构和命名规范
- 页面文件应放置在`handler/pages/`目录下
- 布局文件应放置在`handler/layouts/`目录下

## 类概述

`Dispatcher`类是AiPHP框架的核心调度组件，负责根据页面路径和参数加载并显示页面。它连接了路由解析和页面加载两个环节，提供了统一的页面调度接口。

### 命名空间和文件位置

```php
// 命名空间
namespace Core\OwnLibrary\Dispatcher;

// 文件位置
// core/own-library/dispatcher/Dispatcher.php
```

## 类属性

- `$rootPath`: 网站根目录路径，用于构建页面文件的完整路径
- `$baseDirectory`: 页面文件总目录（相对于网站根目录）
- `$layoutsDirectory`: 布局文件总目录（相对于网站根目录）

## 方法详解

### 1. `__construct(string $rootPath, string $baseDirectory, string $layoutsDirectory = '')`

#### 功能
初始化Dispatcher类，设置根目录、页面目录和布局目录路径。

#### 参数
- `$rootPath`: 字符串，项目根目录路径
- `$baseDirectory`: 字符串，页面文件总目录（相对于网站根目录）
- `$layoutsDirectory`: 字符串，布局文件总目录（相对于网站根目录），可为空

#### 返回值
- 无

#### 使用示例
```php
// 实例化Dispatcher类
$dispatcher = new Dispatcher(
    __DIR__ . '/../..',  // 项目根目录
    'handler/pages',     // 页面文件目录
    'handler/layouts'    // 布局文件目录
);
```

#### 说明
- `$rootPath`通常设置为项目的根目录路径
- `$baseDirectory`通常设置为'handler/pages'
- `$layoutsDirectory`通常设置为'handler/layouts'
- 这些路径设置将用于构建页面和布局文件的完整路径

### 2. `dispatch(string $pagePath, array $params = [])`

#### 功能
根据页面路径和参数加载并显示页面。

#### 参数
- `$pagePath`: 字符串，页面路径（相对于handler/pages目录）
- `$params`: 数组，路由参数，将传递给页面文件

#### 返回值
- 无

#### 使用示例
```php
// 调度首页
$dispatcher->dispatch('home');

// 调度用户列表页面，并传递参数
$dispatcher->dispatch('users', ['page' => 1, 'sort' => 'name']);

// 调度嵌套目录中的页面
$dispatcher->dispatch('admin/dashboard');
```

#### 说明
- 该方法是Dispatcher类的核心方法，负责实际的页面调度逻辑
- 它会构建页面文件的完整路径，并检查文件是否存在
- 如果页面文件存在，会使用输出缓冲获取页面内容
- 如果页面中定义了`$layout`变量，会调用`loadLayout()`方法加载布局文件
- 如果页面不存在，会尝试加载404页面
- 如果404页面也不存在，会显示基础的404错误信息

### 3. `loadLayout(string $layoutName, string $content, array $params = [], array $pageVars = [])`

#### 功能
加载布局文件并注入页面内容。

#### 参数
- `$layoutName`: 字符串，布局文件名
- `$content`: 字符串，页面内容
- `$params`: 数组，路由参数
- `$pageVars`: 数组，页面中定义的变量

#### 返回值
- 无

#### 使用示例
```php
// 此方法通常由dispatch()方法内部调用，不直接使用
// $dispatcher->loadLayout('main', $pageContent, $params, $pageVars);
```

#### 说明
- 该方法是私有的，通常不直接调用
- 它会构建布局文件的完整路径，并检查文件是否存在
- 使用`extract()`函数将路由参数和页面变量提取到当前作用域
- 如果布局文件存在，会加载布局文件并显示
- 如果布局文件不存在，会直接输出页面内容

### 4. `showNotFoundError(string $requestedPath)`

#### 功能
显示404错误信息。

#### 参数
- `$requestedPath`: 字符串，请求的路径

#### 返回值
- 无

#### 使用示例
```php
// 此方法通常由dispatch()方法内部调用，不直接使用
// $dispatcher->showNotFoundError('non-existent-page');
```

#### 说明
- 该方法是私有的，通常不直接调用
- 当请求的页面和404页面都不存在时，显示基础的404错误信息
- 设置HTTP状态码为404

## 实际应用示例

### 1. 基础页面调度

```php
// 引入Dispatcher类
use Core\OwnLibrary\Dispatcher\Dispatcher;

// 实例化Dispatcher
$dispatcher = new Dispatcher(
    __DIR__ . '/../..',  // 项目根目录
    'handler/pages',     // 页面文件目录
    'handler/layouts'    // 布局文件目录
);

// 调度页面
$dispatcher->dispatch('home');  // 加载handler/pages/home.php
```

### 2. 带参数的页面调度

```php
// 引入Dispatcher类
use Core\OwnLibrary\Dispatcher\Dispatcher;

// 实例化Dispatcher
$dispatcher = new Dispatcher(
    __DIR__ . '/../..',  // 项目根目录
    'handler/pages',     // 页面文件目录
    'handler/layouts'    // 布局文件目录
);

// 准备路由参数
$routeParams = [
    'userId' => 123,
    'action' => 'edit'
];

// 调度带参数的页面
$dispatcher->dispatch('user_edit', $routeParams);

// 在user_edit.php中可以直接使用这些参数
// echo $userId; // 输出: 123
// echo $action; // 输出: edit
```

### 3. 嵌套目录页面调度

```php
// 引入Dispatcher类
use Core\OwnLibrary\Dispatcher\Dispatcher;

// 实例化Dispatcher
$dispatcher = new Dispatcher(
    __DIR__ . '/../..',  // 项目根目录
    'handler/pages',     // 页面文件目录
    'handler/layouts'    // 布局文件目录
);

// 调度嵌套目录中的页面
$dispatcher->dispatch('admin/users/list');  // 加载handler/pages/admin/users/list.php
```

### 4. 自定义目录结构

```php
// 引入Dispatcher类
use Core\OwnLibrary\Dispatcher\Dispatcher;

// 实例化Dispatcher，使用自定义目录结构
$dispatcher = new Dispatcher(
    '/path/to/project',  // 项目根目录
    'custom/pages',      // 自定义页面目录
    'custom/layouts'     // 自定义布局目录
);

// 调度页面
$dispatcher->dispatch('home');  // 加载custom/pages/home.php
```

## 页面与布局的交互

`Dispatcher`类支持页面与布局的交互，主要通过以下方式实现：

### 1. 在页面中定义布局

页面文件中可以通过`$layout`变量指定要使用的布局文件：

```php
// handler/pages/example.php
$layout = 'main';  // 使用handler/layouts/main.php布局

$pageTitle = '示例页面';
$pageDescription = '这是一个示例页面';

// 页面内容
$content = '<h1>示例内容</h1>';
```

### 2. 在布局中访问页面变量

布局文件可以访问页面中定义的所有变量：

```php
// handler/layouts/main.php
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $pageDescription; ?>">
</head>
<body>
    <header>网站头部</header>
    <main>
        <?php echo $content; ?>  <!-- 页面内容 -->
    </main>
    <footer>网站底部</footer>
</body>
</html>
```

## 错误处理

`Dispatcher`类提供了以下错误处理机制：

1. **页面不存在时**：
   - 尝试加载`404.php`页面
   - 如果`404.php`不存在，显示基础的404错误信息
   - 设置HTTP状态码为404

2. **布局不存在时**：
   - 如果指定的布局文件不存在，直接输出页面内容

## 性能优化建议

1. **使用缓存**：对于频繁访问的页面，可以考虑使用缓存机制，减少文件I/O操作
2. **优化路由解析**：确保路由解析逻辑高效，减少不必要的路径检查
3. **避免过度嵌套**：页面文件的目录结构不要过度嵌套，以减少路径解析的复杂度
4. **使用自动加载**：确保框架的自动加载机制正确配置，避免重复加载文件

## 注意事项

1. 确保页面文件和布局文件的路径正确，遵循框架的目录结构规范
2. 页面文件中定义的变量会被提取到当前作用域，注意避免变量名冲突
3. 布局文件中需要通过输出语句（如`echo`）显示页面内容
4. 在Windows系统上，路径分隔符会自动规范化，确保跨平台兼容性
5. 当修改页面或布局文件后，可能需要清除缓存才能看到最新的更改