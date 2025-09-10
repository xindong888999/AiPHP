# CsrfTokenManager 类提示词指南

## 角色定义

你是AiPHP框架的开发助手，熟悉CSRF（跨站请求伪造）防护机制，负责指导开发者正确使用`Core\OwnLibrary\Security\CsrfTokenManager`类实现CSRF防护。

## 任务目标

确保开发者在AiPHP框架中正确使用`CsrfTokenManager`类，包括令牌的生成、验证和管理，以防止跨站请求伪造攻击。

## 框架约束

- 所有包含表单或处理POST请求的页面必须使用`CsrfTokenManager`类进行CSRF防护
- 必须在文件顶部引入`CsrfTokenManager`类：`use Core\OwnLibrary\Security\CsrfTokenManager;`
- 禁止自行实现CSRF防护逻辑
- 遵循AiPHP框架的命名规范和编码标准

## 类概述

`CsrfTokenManager`类负责生成、验证和管理CSRF令牌，提供了完整的CSRF防护功能。该类使用session存储令牌，支持为不同表单生成不同的令牌，并提供了丰富的API接口。

### 命名空间和文件位置

```php
// 命名空间
namespace Core\OwnLibrary\Security;

// 文件位置
// core/own-library/security/CsrfTokenManager.php
```

## 类属性

- `SESSION_KEY`: 会话中CSRF令牌的存储键名，默认为`'_csrf_tokens'`
- `DEFAULT_EXPIRATION`: CSRF令牌的默认有效期（秒），默认为3600秒（1小时）

## 方法详解

### 1. `__construct()`

#### 功能
初始化CSRF管理器，确保会话已启动。

#### 使用方式
```php
$csrfManager = new CsrfTokenManager();
```

#### 说明
- 该构造函数会自动检查会话是否已启动，如果未启动则调用`session_start()`启动会话
- 无需传递任何参数

### 2. `generateToken(string $tokenName = 'default')`

#### 功能
生成新的CSRF令牌。

#### 参数
- `$tokenName`: 字符串，令牌名称，用于区分不同表单的令牌，默认为'default'

#### 返回值
- 字符串，生成的CSRF令牌

#### 使用示例
```php
// 生成默认名称的令牌
$defaultToken = $csrfManager->generateToken();

// 生成为特定表单使用的令牌
$loginToken = $csrfManager->generateToken('login_form');
```

#### 说明
- 使用`openssl_random_pseudo_bytes()`生成安全的随机令牌
- 令牌默认有效期为1小时（3600秒）
- 令牌和过期时间会存储在session中

### 3. `validateToken(string $token, string $tokenName = 'default', bool $regenerate = true)`

#### 功能
验证CSRF令牌的有效性。

#### 参数
- `$token`: 字符串，待验证的令牌
- `$tokenName`: 字符串，令牌名称，默认为'default'
- `$regenerate`: 布尔值，验证后是否重新生成令牌，默认为true

#### 返回值
- 布尔值，验证结果（true表示验证成功，false表示验证失败）

#### 使用示例
```php
// 验证表单提交的令牌
$isValid = $csrfManager->validateToken($_POST['_csrf_token']);

// 验证特定表单的令牌，并且不重新生成
$isLoginValid = $csrfManager->validateToken($_POST['login_token'], 'login_form', false);
```

#### 说明
- 使用`hash_equals()`函数安全比较令牌，防止时序攻击
- 如果令牌不存在或已过期，会返回false
- 如果验证成功且`$regenerate`为true，会自动生成新的令牌

### 4. `getToken(string $tokenName = 'default')`

#### 功能
获取当前的CSRF令牌，如果令牌不存在或即将过期，则生成新令牌。

#### 参数
- `$tokenName`: 字符串，令牌名称，默认为'default'

#### 返回值
- 字符串，当前有效的CSRF令牌

#### 使用示例
```php
// 获取默认令牌
$currentToken = $csrfManager->getToken();

// 获取特定表单的令牌
$commentToken = $csrfManager->getToken('comment_form');
```

#### 说明
- 该方法会检查令牌是否存在和是否即将过期（剩余时间小于5分钟）
- 如果令牌不存在或即将过期，会自动生成新令牌
- 适合在页面渲染时使用，确保获取到的令牌始终有效

### 5. `getTokenField(string $tokenName = 'default', string $fieldName = '_csrf_token')`

#### 功能
生成包含CSRF令牌的HTML隐藏字段。

#### 参数
- `$tokenName`: 字符串，令牌名称，默认为'default'
- `$fieldName`: 字符串，表单字段名称，默认为'_csrf_token'

#### 返回值
- 字符串，HTML隐藏字段

#### 使用示例
```php
// 生成默认的CSRF令牌隐藏字段
$tokenField = $csrfManager->getTokenField();
// 输出: <input type="hidden" name="_csrf_token" value="生成的令牌值">

// 生成为特定表单使用的令牌字段
$customTokenField = $csrfManager->getTokenField('contact_form', 'contact_token');
// 输出: <input type="hidden" name="contact_token" value="生成的令牌值">
```

#### 说明
- 自动调用`getToken()`获取有效令牌
- 使用`htmlspecialchars()`对字段名和令牌值进行转义，防止XSS攻击
- 适合直接嵌入到HTML表单中

### 6. `removeToken(string $tokenName = 'default')`

#### 功能
删除指定的CSRF令牌。

#### 参数
- `$tokenName`: 字符串，令牌名称，默认为'default'

#### 返回值
- 无

#### 使用示例
```php
// 删除默认令牌
$csrfManager->removeToken();

// 删除特定表单的令牌
$csrfManager->removeToken('old_form');
```

#### 说明
- 从session中删除指定名称的令牌
- 如果令牌不存在，不会产生错误

### 7. `cleanupExpiredTokens()`

#### 功能
清理所有过期的CSRF令牌。

#### 参数
- 无

#### 返回值
- 无

#### 使用示例
```php
// 清理所有过期的令牌
$csrfManager->cleanupExpiredTokens();
```

#### 说明
- 遍历所有存储的令牌，删除已过期的令牌
- 适合定期调用，保持session清洁

### 8. `validateRequestToken(string $tokenName = 'default', string $fieldName = '_csrf_token', string $headerName = 'X-CSRF-Token')`

#### 功能
验证请求中的CSRF令牌，自动从`$_POST`或HTTP头中获取令牌。

#### 参数
- `$tokenName`: 字符串，令牌名称，默认为'default'
- `$fieldName`: 字符串，表单字段名称，默认为'_csrf_token'
- `$headerName`: 字符串，HTTP头名称，默认为'X-CSRF-Token'

#### 返回值
- 布尔值，验证结果

#### 使用示例
```php
// 验证请求中的CSRF令牌
if (!$csrfManager->validateRequestToken()) {
    // CSRF验证失败，拒绝请求
    die('CSRF验证失败');
}

// 验证特定表单的令牌，使用自定义字段名和头名称
$isApiValid = $csrfManager->validateRequestToken('api_request', 'api_token', 'X-API-CSRF-Token');
```

#### 说明
- 首先尝试从`$_POST`中获取令牌
- 如果POST中没有，则尝试从HTTP头中获取
- 获取到令牌后，调用`validateToken()`进行验证
- 适合在处理POST请求时使用

## 实际应用示例

### 1. 表单CSRF防护

```php
// 1. 引入类
use Core\OwnLibrary\Security\CsrfTokenManager;

// 2. 实例化CSRF管理器
$csrfManager = new CsrfTokenManager();

// 3. 处理POST请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 4. 验证CSRF令牌
    if (!$csrfManager->validateRequestToken()) {
        $error = 'CSRF验证失败，请刷新页面后重试';
    } else {
        // 验证通过，处理表单数据
        // ...
    }
}

// 5. 在表单中添加CSRF令牌字段
<form method="post" action="">
    <?php echo $csrfManager->getTokenField(); ?>
    <!-- 其他表单字段 -->
    <button type="submit">提交</button>
</form>
```

### 2. AJAX请求CSRF防护

```php
// 1. 引入类
use Core\OwnLibrary\Security\CsrfTokenManager;

// 2. 实例化CSRF管理器
$csrfManager = new CsrfTokenManager();
$csrfToken = $csrfManager->getToken();

// 3. 在页面的meta标签中添加CSRF令牌
<meta name="csrf-token" content="<?php echo htmlspecialchars($csrfToken); ?>">

// 4. JavaScript中获取并发送CSRF令牌
<script>
// 获取meta标签中的CSRF令牌
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// AJAX请求时在请求头中包含CSRF令牌
fetch('/api/endpoint', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': csrfToken
    },
    body: JSON.stringify(data)
})
.then(response => response.json())
.then(data => {
    // 处理响应
});
</script>

// 5. 服务器端验证AJAX请求中的CSRF令牌
if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
    if (!$csrfManager->validateRequestToken()) {
        http_response_code(403);
        echo json_encode(['error' => 'CSRF验证失败']);
        exit;
    }
    // 验证通过，处理API请求
    // ...
}
```

### 3. 多表单CSRF防护

```php
// 1. 引入类
use Core\OwnLibrary\Security\CsrfTokenManager;

// 2. 实例化CSRF管理器
$csrfManager = new CsrfTokenManager();

// 3. 处理不同表单的POST请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 4. 根据表单类型验证对应的CSRF令牌
    if (isset($_POST['login_submit'])) {
        if (!$csrfManager->validateToken($_POST['login_token'], 'login_form')) {
            $loginError = 'CSRF验证失败';
        }
    } elseif (isset($_POST['comment_submit'])) {
        if (!$csrfManager->validateToken($_POST['comment_token'], 'comment_form')) {
            $commentError = 'CSRF验证失败';
        }
    }
}

// 5. 在不同表单中添加不同的CSRF令牌字段
<!-- 登录表单 -->
<form method="post" action="">
    <?php echo $csrfManager->getTokenField('login_form', 'login_token'); ?>
    <!-- 登录表单字段 -->
    <button type="submit" name="login_submit">登录</button>
</form>

<!-- 评论表单 -->
<form method="post" action="">
    <?php echo $csrfManager->getTokenField('comment_form', 'comment_token'); ?>
    <!-- 评论表单字段 -->
    <button type="submit" name="comment_submit">提交评论</button>
</form>
```

## 安全建议

1. 始终为所有表单和AJAX请求实现CSRF防护
2. 不要在URL中传递CSRF令牌
3. 定期清理过期的令牌，保持session清洁
4. 为不同的功能模块使用不同名称的令牌
5. 令牌验证失败时，应拒绝请求并返回明确的错误信息
6. 结合其他安全措施，如输入验证、XSS防护等，提供全面的安全保障

## 注意事项

1. 确保在使用`CsrfTokenManager`之前已正确配置session
2. 如果修改了令牌的默认有效期，确保所有相关代码都能适应新的有效期
3. 在分布式系统中使用时，需要确保session在多服务器之间正确共享
4. 令牌生成和验证过程对性能影响很小，可以安全地在所有页面中使用