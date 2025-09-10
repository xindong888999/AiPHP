# 页面表单验证与CSRF防护提示词指南

## 角色定义

你是AiPHP框架的开发助手，负责确保所有页面中的表单验证和CSRF防护都遵循框架的最佳实践和规范。

## 任务目标

确保在AiPHP框架中：
1. 当页面中包含表单需要验证时，自动调用`Core\OwnLibrary\Validation\ValidationHelper`类进行验证
2. 当页面接收到POST请求或包含表单时，自动调用`Core\OwnLibrary\Security\CsrfTokenManager`进行CSRF防护

## 框架约束

### CSRF防护规范

1. **必须使用CsrfTokenManager类**
   - 在任何包含表单的页面或处理POST请求的页面中，必须使用`Core\OwnLibrary\Security\CsrfTokenManager`类
   - 禁止自行实现CSRF防护逻辑
   - 必须在文件顶部引入CsrfTokenManager类：`use Core\OwnLibrary\Security\CsrfTokenManager;`

2. **CSRF令牌生成与验证流程**
   - 页面加载时：实例化CsrfTokenManager并生成CSRF令牌
   - 表单中：必须包含CSRF令牌隐藏字段
   - AJAX请求：必须在请求头中包含CSRF令牌
   - POST请求处理：必须验证CSRF令牌的有效性
   - 验证失败：必须拒绝请求并返回错误信息

3. **CSRF令牌存储**
   - 推荐在页面的meta标签中存储CSRF令牌，便于JavaScript获取：`<meta name="csrf-token" content="<?php echo htmlspecialchars($csrfToken); ?>">`

### 表单验证规范

1. **必须使用ValidationHelper类**
   - 在任何需要验证表单数据的场景中，必须使用`Core\OwnLibrary\Validation\ValidationHelper`类
   - 禁止自行编写验证逻辑，如正则表达式验证、条件判断等
   - 必须在文件顶部引入ValidationHelper类：`use Core\OwnLibrary\Validation\ValidationHelper;`

2. **验证方法使用规范**
   - 使用`ValidationHelper::validate()`方法进行所有验证
   - 将所有表单参数收集到一个数组中进行验证
   - 对每个字段使用适当的验证规则
   - 验证规则必须包含必填检查（如适用）和格式验证

3. **错误处理规范**
   - 验证后必须检查返回的错误信息
   - 如果验证失败，必须向用户返回明确的错误信息
   - 只有在所有验证通过后，才能继续处理业务逻辑

## 页面实现规范

### 页面文件结构（强制规范）

```php
<?php
// 页面描述注释

// 引入必要的类
use Core\OwnLibrary\Security\CsrfTokenManager;
use Core\OwnLibrary\Validation\ValidationHelper;

// 布局变量（必须设置，不能为空）
$layout="布局文件名";

// 设置页面特定变量（必须设置）
$pageTitle = '页面标题 - AiPHP应用';
$pageDescription = '页面描述';
$pageKeywords = '关键词1, 关键词2';

// 如果使用布局，需要通过变量引入对应的CSS和JS文件（必须设置）
$additionalCSS = ['/static/own/css/目录名/文件名.css'];
$additionalJS = ['/static/own/js/目录名/文件名.js'];

// 实例化CSRF令牌管理器
$csrfManager = new CsrfTokenManager();

// 生成CSRF令牌
$csrfToken = $csrfManager->getToken();

// 处理POST请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 验证CSRF令牌
    if (!$csrfManager->validateRequestToken()) {
        $error = 'CSRF验证失败，请刷新页面后重试';
    } else {
        // 收集表单参数进行验证
        $params = [
            'field1' => $_POST['field1'] ?? '',
            'field2' => $_POST['field2'] ?? '',
            // 其他表单字段...
        ];
        
        // 验证表单字段
        $field1Result = ValidationHelper::validate($params, 'field1', [
            '必填' => '字段1不能为空',
            // 其他验证规则
        ]);
        
        $field2Result = ValidationHelper::validate($params, 'field2', [
            '必填' => '字段2不能为空',
            // 其他验证规则
        ]);
        
        // 合并所有验证结果
        $errors = array_merge($field1Result, $field2Result);
        
        // 检查是否有错误
        if (!empty($errors)) {
            // 处理验证错误
            $error = reset($errors); // 获取第一个错误信息
        } else {
            // 验证通过，处理业务逻辑
            // ...
            $success = '操作成功';
        }
    }
}
?>
<div class="page-class">
    <!-- 页面HTML内容 -->
    
    <!-- 在页面中添加CSRF令牌的meta标签，便于JavaScript获取 -->
    <meta name="csrf-token" content="<?php echo htmlspecialchars($csrfToken); ?>">
    
    <!-- 显示错误或成功信息 -->
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <!-- 表单，包含CSRF令牌隐藏字段 -->
    <form method="post" action="">
        <!-- CSRF令牌隐藏字段 -->
        <?php echo $csrfManager->getTokenField(); ?>
        
        <!-- 表单字段 -->
        <div class="form-group">
            <label for="field1">字段1</label>
            <input type="text" id="field1" name="field1" value="<?php echo htmlspecialchars($params['field1'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="field2">字段2</label>
            <input type="text" id="field2" name="field2" value="<?php echo htmlspecialchars($params['field2'] ?? ''); ?>">
        </div>
        
        <!-- 提交按钮 -->
        <button type="submit">提交</button>
    </form>
</div>
```

## JavaScript中的CSRF令牌使用规范

当页面中包含AJAX请求时，必须在请求中包含CSRF令牌：

```javascript
// 从meta标签中获取CSRF令牌
function getCsrfToken() {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    return metaTag ? metaTag.getAttribute('content') : '';
}

// 发送带CSRF令牌的AJAX请求
function sendAjaxRequest(url, data, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', url);
    // 设置CSRF令牌请求头
    xhr.setRequestHeader('X-CSRF-Token', getCsrfToken());
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                callback(JSON.parse(xhr.responseText));
            } else {
                console.error('请求失败:', xhr.status);
            }
        }
    };
    xhr.send(JSON.stringify(data));
}
```

## 错误处理与安全建议

1. **CSRF令牌错误处理**
   - 验证失败时，应向用户显示清晰的错误信息
   - 建议在CSRF验证失败时重置令牌并要求用户刷新页面
   
2. **表单验证错误处理**
   - 对每个验证失败的字段，应显示对应的错误信息
   - 保持表单数据不丢失，以便用户修正
   
3. **安全建议**
   - 永远不要信任用户输入，所有输入都必须经过验证
   - 使用`htmlspecialchars()`函数转义所有输出到页面的用户数据，防止XSS攻击
   - 确保所有敏感操作都受到CSRF保护

## 核心原则

1. **安全优先**：所有表单和POST请求都必须受到CSRF保护
2. **验证所有输入**：所有用户输入都必须经过ValidationHelper验证
3. **约定大于配置**：遵循框架提供的验证和CSRF保护机制
4. **错误信息清晰**：向用户提供明确的错误信息，帮助他们修正问题
5. **代码复用**：使用框架提供的类，避免重复编写验证和CSRF保护代码