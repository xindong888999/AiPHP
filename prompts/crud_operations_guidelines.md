# AiPHP CRUD操作提示词

## 概述
本提示词定义了在AiPHP框架中使用RedBean ORM进行增删改查（CRUD）操作的标准流程、规范和最佳实践。

## 强制性规范

### 1. ORM使用规范
**必须使用RedBean ORM，禁止使用其他ORM或原生SQL**

```php
// 必须的引入和初始化
use Core\OtherLibrary\RedBean\RedBeanFacade as R;

// 在每个页面开头初始化
R::initialize();
```

### 2. 表名和字段规范
- **表名**：使用复数形式（users、products、orders）
- **字段命名**：使用下划线分隔（user_name、created_at）
- **必须字段**：id、created_at、updated_at、status

### 3. 数据验证规范
- 所有用户输入必须验证和过滤
- 使用`htmlspecialchars()`防止XSS
- 使用`trim()`去除空白字符
- 验证数据类型和长度

### 4. 安全规范
- 所有表单必须使用CSRF令牌保护
- 所有用户输入必须使用ValidationHelper验证
- 所有输出必须使用htmlspecialchars()转义
- 所有数据库操作必须在try-catch块中处理异常

## [重要] 常见错误和解决方案

### 1. PHP语法错误
**常见问题**：缺少闭合的大括号 `}` 导致PHP解析错误
**解决方案**：
```php
// 错误示例 - 缺少闭合大括号
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 处理POST请求
    // 缺少闭合大括号

// 正确示例 - 确保所有代码块都有正确的开始和结束标记
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 处理POST请求
    // 所有逻辑处理
} // 确保这里有一个闭合的大括号
```

**检查方法**：
1. 使用代码编辑器的语法高亮功能检查代码块是否正确闭合
2. 使用PHP命令行工具检查语法：`php -l 文件名.php`
3. 在开发环境中启用错误报告显示所有错误

### 2. CSRF令牌验证错误
**常见问题**：忘记验证CSRF令牌或验证方法不正确
**解决方案**：
```php
// 正确的CSRF验证方法
use Core\OwnLibrary\Security\CsrfTokenManager;

$csrfManager = new CsrfTokenManager();

// 生成CSRF令牌
$csrfToken = $csrfManager->getToken();

// 在页面中正确添加CSRF令牌meta标签
$additionalMeta = '<meta name="csrf-token" content="' . htmlspecialchars($csrfToken) . '">';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 必须验证CSRF令牌
    if (!$csrfManager->validateRequestToken()) {
        $error = 'CSRF验证失败，请刷新页面后重试';
        // 处理错误，不要继续执行
        return;
    }
    // 继续处理表单数据
}
```

**重要说明**：
- CSRF令牌的meta标签必须通过[$additionalMeta](file:///c:/Users/Administrator/Desktop/AiPHP/handler/pages/news.php#L20-L20)变量添加到页面头部，而不是直接在HTML中输出
- JavaScript通过`document.querySelector('meta[name="csrf-token"]')`获取CSRF令牌

### 3. 数据验证错误
**常见问题**：跳过数据验证或验证不完整
**解决方案**：
```php
// 使用ValidationHelper进行完整验证
use Core\OwnLibrary\Validation\ValidationHelper;

$params = [
    'username' => trim($_POST['username'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
];

// 验证每个字段
$usernameResult = ValidationHelper::validate($params, 'username', [
    '必填' => '用户名不能为空',
    '长度3-50' => '用户名长度必须在3-50个字符之间'
]);

$emailResult = ValidationHelper::validate($params, 'email', [
    '必填' => '邮箱不能为空'
]);

// 合并所有验证结果
$errors = array_merge($usernameResult, $emailResult);

// 检查是否有错误
if (!empty($errors)) {
    $error = reset($errors); // 获取第一个错误信息
    // 处理错误，不要继续执行
    return;
}
```

### 4. 唯一性检查错误
**常见问题**：更新记录时未排除当前记录导致唯一性检查失败
**解决方案**：
```php
// 更新用户时检查唯一性（排除当前用户）
if (!empty($formData['username'])) {
    $existingUser = R::findOne('users', 'username = ?' . ($isEdit ? ' AND id != ?' : ''), 
                              $isEdit ? [$formData['username'], $userId] : [$formData['username']]);
    if ($existingUser) {
        $errors[] = '该用户名已存在';
    }
}
```

### 5. 状态切换和删除操作错误
**常见问题**：缺少确认机制或状态切换后未正确刷新页面
**解决方案**：
```javascript
// 状态切换功能 - 直接切换并刷新页面
function updateUserStatus(recordId, newStatus, buttonElement) {
    // 防止重复点击
    if (buttonElement) {
        buttonElement.disabled = true;
        const originalText = buttonElement.textContent;
        buttonElement.textContent = '更新中...';
    }
    
    // 从meta标签中获取CSRF令牌
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = metaTag ? metaTag.getAttribute('content') : '';
    
    // 准备请求数据
    const requestData = {
        id: recordId,
        status: newStatus,
        _csrf_token: csrfToken
    };
    
    // 发送请求更新状态
    fetch('/模块名/update-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        // 检查响应是否为JSON格式
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.indexOf('application/json') !== -1) {
            return response.json();
        } else {
            // 尝试获取文本响应
            return response.text().then(text => {
                throw new Error('服务器响应不是有效的JSON格式: ' + text);
            });
        }
    })
    .then(data => {
        // 直接刷新页面，无论成功还是失败
        location.reload();
    })
    .catch(error => {
        // 直接刷新页面，不显示错误
        location.reload();
    });
}
```

## CRUD操作标准流程

### 一、创建（Create）操作

#### 1.1 页面结构
```php
<?php
// 页面文件：handler/pages/模块名/form.php
use Core\OtherLibrary\RedBean\RedBeanFacade as R;
use Core\OwnLibrary\Security\CsrfTokenManager;
use Core\OwnLibrary\Validation\ValidationHelper;

// 布局变量
$layout = "green_layout";

// 页面元数据
$pageTitle = '添加记录';
$pageDescription = '添加新记录';
$pageKeywords = '添加, 创建';

// 引入CSS和JS文件
$additionalCSS = ['/static/own/css/模块名/form.css'];
$additionalJS = ['/static/own/js/模块名/form.js'];

// 初始化RedBean
R::initialize();

// 实例化CSRF令牌管理器
$csrfManager = new CsrfTokenManager();

// 生成CSRF令牌
$csrfToken = $csrfManager->getToken();

// 将CSRF令牌添加到额外的meta标签中
$additionalMeta = '<meta name="csrf-token" content="' . htmlspecialchars($csrfToken) . '">';
```

### 二、读取（Read）操作

#### 2.1 列表页面
列表页面应包含以下功能：
- 分页功能
- 搜索功能
- 状态切换按钮
- 删除确认模态框
- 操作结果提示框

#### 2.2 详情页面
详情页面应包含以下功能：
- 记录详细信息展示
- 返回列表按钮
- 编辑按钮

### 三、更新（Update）操作

#### 3.1 状态更新
状态更新应通过AJAX请求完成，包含以下步骤：
1. 验证CSRF令牌
2. 验证记录ID和状态值
3. 更新记录状态
4. 返回JSON格式的响应

#### 3.2 记录编辑
记录编辑应包含以下步骤：
1. 验证CSRF令牌
2. 数据验证
3. 唯一性检查
4. 更新记录
5. 重定向到列表页面并显示成功消息

### 四、删除（Delete）操作

#### 4.1 删除确认
删除操作必须使用模态框进行确认，包含以下步骤：
1. 显示删除确认模态框
2. 用户确认后发送删除请求
3. 验证CSRF令牌
4. 验证记录ID
5. 执行删除操作
6. 返回JSON格式的响应

#### 4.2 删除结果提示
删除操作完成后应显示结果提示框，包含以下步骤：
1. 根据响应结果显示成功或失败提示
2. 1秒后自动关闭提示框并刷新页面

## 模态框和提示框样式规范

### 1. 删除确认模态框
所有删除操作必须使用模态框进行确认，模态框应包含以下元素：
- 标题："确认删除"
- 内容：提示用户确认删除操作，显示要删除的记录名称
- 按钮：取消按钮和确定按钮

### 2. 删除结果提示框
删除操作完成后应显示结果提示框，提示框应包含以下元素：
- 标题：成功或失败状态
- 内容：操作结果信息
- 自动关闭：1秒后自动关闭并刷新页面

### 3. 模态框和提示框样式
模态框和提示框应使用以下CSS类：
- `.modal`：模态框容器
- `.modal-dialog`：模态框对话框
- `.modal-content`：模态框内容
- `.modal-header`：模态框头部
- `.modal-body`：模态框主体
- `.modal-footer`：模态框底部
- `.toast`：提示框容器
- `.toast-header`：提示框头部
- `.toast-body`：提示框主体

### 4. JavaScript函数规范
所有模态框和提示框操作应使用以下JavaScript函数：
- `showDeleteModal(recordId, recordName)`：显示删除确认模态框
- `confirmDelete()`：确认删除操作
- `hideDeleteModal()`：隐藏删除确认模态框
- `showDeleteToast(message, isSuccess)`：显示删除结果提示框

## 安全最佳实践

### 1. CSRF保护
所有POST请求必须包含CSRF令牌验证：
```php
// 实例化CSRF令牌管理器
$csrfManager = new CsrfTokenManager();

// 验证CSRF令牌
if (!$csrfManager->validateRequestToken()) {
    $error = 'CSRF验证失败，请刷新页面后重试';
    return;
}
```

### 2. 输入验证
所有用户输入必须验证和过滤：
```php
// 使用ValidationHelper进行验证
use Core\OwnLibrary\Validation\ValidationHelper;

$params = [
    'username' => trim($_POST['username'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
];

$usernameResult = ValidationHelper::validate($params, 'username', [
    '必填' => '用户名不能为空',
    '长度3-50' => '用户名长度必须在3-50个字符之间'
]);
```

### 3. 输出转义
所有输出必须使用htmlspecialchars()转义：
```php
// 正确的输出转义
echo htmlspecialchars($username);
```

### 4. 异常处理
所有数据库操作必须在try-catch块中处理：
```php
try {
    // 数据库操作
    $id = R::store($record);
} catch (Exception $e) {
    $error = '操作失败：' . $e->getMessage();
}
```