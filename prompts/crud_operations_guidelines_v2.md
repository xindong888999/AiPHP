# AiPHP CRUD操作提示词 V2.0

## 概述
本提示词定义了在AiPHP框架中使用RedBean ORM进行增删改查（CRUD）操作的标准流程、规范和最佳实践。V2.0版本针对用户反馈的问题进行了优化，提供了更美观的样式、更稳定的删除功能和更完善的创建/更新功能。

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
function updateStatusAndRefresh(recordId, newStatus, buttonElement) {
    // 防止重复点击
    buttonElement.disabled = true;
    
    // 发送请求更新状态
    fetch('/模块名/update-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': getCsrfToken() // 确保包含CSRF令牌
        },
        body: JSON.stringify({
            id: recordId,
            status: newStatus
        })
    })
    .then(response => response.json())
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

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 处理创建逻辑
}

include LAYOUTS_PATH . '/布局名.php';
?>
```

#### 1.2 创建操作标准代码
```php
// 1. 获取和验证表单数据
$formData = [
    'field1' => trim($_POST['field1'] ?? ''),
    'field2' => trim($_POST['field2'] ?? ''),
    // ... 其他字段
];

// 2. 数据验证
$params = [
    'field1' => $formData['field1'],
    'field2' => $formData['field2'],
    // ... 其他字段
];

// 使用ValidationHelper验证每个字段
$field1Result = ValidationHelper::validate($params, 'field1', [
    '必填' => '字段1不能为空',
    '长度3-50' => '字段1长度必须在3-50个字符之间'
]);

$field2Result = ValidationHelper::validate($params, 'field2', [
    '必填' => '字段2不能为空'
]);

// 合并所有验证结果
$errors = array_merge($field1Result, $field2Result);

// 3. 检查唯一性（如果需要）
if (empty($errors) && !empty($formData['unique_field'])) {
    $existing = R::findOne('表名', 'unique_field = ?', [$formData['unique_field']]);
    if ($existing) {
        $errors[] = '该字段已存在';
    }
}

// 4. 如果没有错误，创建记录
if (empty($errors)) {
    try {
        $record = R::dispense('表名');
        $record->field1 = $formData['field1'];
        $record->field2 = $formData['field2'];
        $record->status = 1; // 默认状态
        $record->created_at = date('Y-m-d H:i:s');
        $record->updated_at = date('Y-m-d H:i:s');
        
        $id = R::store($record);
        
        // 成功后重定向
        header('Location: /模块名?success=created');
        exit;
    } catch (Exception $e) {
        $errors[] = '创建失败：' . $e->getMessage();
    }
}
```

### 二、读取（Read）操作

#### 2.1 列表页面（handler/pages/模块名/index.php）
```php
// 1. 分页参数
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

// 2. 获取总数
$totalRecords = R::count('表名');
$totalPages = ceil($totalRecords / $perPage);

// 3. 获取当前页数据
$records = R::find('表名', 'ORDER BY id DESC LIMIT ? OFFSET ?', [$perPage, $offset]);

// 4. 处理搜索（可选）
$searchTerm = trim($_GET['search'] ?? '');
if (!empty($searchTerm)) {
    $records = R::find('表名', 'field1 LIKE ? OR field2 LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?', 
                      ["%{$searchTerm}%", "%{$searchTerm}%", $perPage, $offset]);
    $totalRecords = R::count('表名', 'field1 LIKE ? OR field2 LIKE ?', 
                            ["%{$searchTerm}%", "%{$searchTerm}%"]);
}
```

#### 2.2 详情页面（handler/pages/模块名/detail.php）
```php
// 1. 获取ID参数
$recordId = isset($params['id']) ? intval($params['id']) : 0;

// 2. 验证ID
if ($recordId <= 0) {
    header('Location: /模块名?error=invalid_id');
    exit;
}

// 3. 查找记录
$record = R::load('表名', $recordId);

// 4. 检查记录是否存在
if (!$record || !$record->id) {
    header('Location: /模块名?error=not_found');
    exit;
}
```

### 三、更新（Update）操作

#### 3.1 编辑页面处理
```php
// 1. 获取记录ID
$recordId = isset($params['id']) ? intval($params['id']) : 0;
$isEdit = $recordId > 0;

// 2. 加载现有记录（编辑模式）
if ($isEdit) {
    $record = R::load('表名', $recordId);
    if (!$record || !$record->id) {
        header('Location: /模块名?error=not_found');
        exit;
    }
}

// 3. 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 验证CSRF令牌
    if (!$csrfManager->validateRequestToken()) {
        $error = 'CSRF验证失败，请刷新页面后重试';
    } else {
        // 获取表单数据
        $formData = [
            'field1' => trim($_POST['field1'] ?? ''),
            'field2' => trim($_POST['field2'] ?? ''),
        ];
        
        // 验证数据
        $params = [
            'field1' => $formData['field1'],
            'field2' => $formData['field2'],
        ];
        
        $field1Result = ValidationHelper::validate($params, 'field1', [
            '必填' => '字段1不能为空',
            '长度3-50' => '字段1长度必须在3-50个字符之间'
        ]);
        
        $field2Result = ValidationHelper::validate($params, 'field2', [
            '必填' => '字段2不能为空'
        ]);
        
        $errors = array_merge($field1Result, $field2Result);
        
        // 检查唯一性（排除当前记录）
        if (empty($errors) && !empty($formData['unique_field'])) {
            $existing = R::findOne('表名', 'unique_field = ?' . ($isEdit ? ' AND id != ?' : ''), 
                                  $isEdit ? [$formData['unique_field'], $recordId] : [$formData['unique_field']]);
            if ($existing) {
                $errors[] = '该字段已存在';
            }
        }
        
        // 更新记录
        if (empty($errors)) {
            try {
                if ($isEdit) {
                    // 更新现有记录
                    $record->field1 = $formData['field1'];
                    $record->field2 = $formData['field2'];
                    $record->updated_at = date('Y-m-d H:i:s');
                } else {
                    // 创建新记录
                    $record = R::dispense('表名');
                    $record->field1 = $formData['field1'];
                    $record->field2 = $formData['field2'];
                    $record->status = 1;
                    $record->created_at = date('Y-m-d H:i:s');
                    $record->updated_at = date('Y-m-d H:i:s');
                }
                
                R::store($record);
                
                $action = $isEdit ? 'updated' : 'created';
                header("Location: /模块名?success={$action}");
                exit;
            } catch (Exception $e) {
                $errors[] = '保存失败：' . $e->getMessage();
            }
        }
    }
}
```

### 四、删除（Delete）操作

#### 4.1 删除处理页面（handler/pages/模块名/delete.php）
```php
// 设置JSON响应头
header('Content-Type: application/json; charset=utf-8');

// 初始化响应
$response = [
    'success' => false,
    'message' => '',
    'data' => null
];

try {
    // 初始化RedBean
    R::initialize();
    
    // 实例化CSRF令牌管理器
    $csrfManager = new CsrfTokenManager();
    
    // 检查请求方法
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $response['message'] = '只允许POST请求';
        echo json_encode($response);
        exit;
    }
    
    // 验证CSRF令牌
    if (!$csrfManager->validateRequestToken()) {
        $response['message'] = 'CSRF验证失败，请刷新页面后重试';
        echo json_encode($response);
        exit;
    }
    
    // 获取记录ID
    $recordId = 0;
    if (isset($params['id'])) {
        $recordId = intval($params['id']);
    } elseif (isset($_POST['id'])) {
        $recordId = intval($_POST['id']);
    } else {
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['id'])) {
            $recordId = intval($input['id']);
        }
    }
    
    if ($recordId <= 0) {
        $response['message'] = '无效的记录ID';
        echo json_encode($response);
        exit;
    }
    
    // 查找记录
    $record = R::load('表名', $recordId);
    if (!$record || !$record->id) {
        $response['message'] = '记录不存在';
        echo json_encode($response);
        exit;
    }
    
    // 保存记录信息用于响应
    $recordInfo = $record->field1; // 或其他标识字段
    
    // 删除记录
    R::trash($record);
    
    // 验证删除是否成功
    $deletedRecord = R::load('表名', $recordId);
    if ($deletedRecord && $deletedRecord->id) {
        $response['message'] = '删除失败，请重试';
        echo json_encode($response);
        exit;
    }
    
    // 删除成功
    $response['success'] = true;
    $response['message'] = "记录 \"{$recordInfo}\" 删除成功";
    $response['data'] = [
        'deleted_id' => $recordId,
        'deleted_info' => $recordInfo
    ];
    
} catch (Exception $e) {
    $response['message'] = '删除失败: ' . $e->getMessage();
}

// 检查是否为AJAX请求
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if ($isAjax) {
    echo json_encode($response);
} else {
    if ($response['success']) {
        header('Location: /模块名?success=deleted');
    } else {
        header('Location: /模块名?error=' . urlencode($response['message']));
    }
}
exit;
```

## 状态切换操作

### 5.1 状态切换处理页面（handler/pages/模块名/update_status.php）
```php
// 设置JSON响应头
header('Content-Type: application/json; charset=utf-8');

// 初始化响应
$response = [
    'success' => false,
    'message' => '',
    'data' => null
];

try {
    // 初始化RedBean
    R::initialize();
    
    // 实例化CSRF令牌管理器
    $csrfManager = new CsrfTokenManager();
    
    // 检查请求方法
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $response['message'] = '只允许POST请求';
        echo json_encode($response);
        exit;
    }
    
    // 验证CSRF令牌
    if (!$csrfManager->validateRequestToken()) {
        $response['message'] = 'CSRF验证失败，请刷新页面后重试';
        echo json_encode($response);
        exit;
    }
    
    // 获取记录ID和状态
    $input = json_decode(file_get_contents('php://input'), true);
    $recordId = isset($input['id']) ? intval($input['id']) : 0;
    $status = isset($input['status']) ? intval($input['status']) : -1;
    
    // 验证参数
    if ($recordId <= 0) {
        $response['message'] = '无效的记录ID';
        echo json_encode($response);
        exit;
    }
    
    if ($status !== 0 && $status !== 1) {
        $response['message'] = '无效的状态值';
        echo json_encode($response);
        exit;
    }
    
    // 查找记录
    $record = R::load('表名', $recordId);
    if (!$record || !$record->id) {
        $response['message'] = '记录不存在';
        echo json_encode($response);
        exit;
    }
    
    // 保存记录信息用于响应
    $recordInfo = $record->field1;
    $oldStatus = $record->status;
    
    // 更新状态
    $record->status = $status;
    $record->updated_at = date('Y-m-d H:i:s');
    R::store($record);
    
    // 验证更新是否成功
    $updatedRecord = R::load('表名', $recordId);
    if ($updatedRecord->status !== $status) {
        $response['message'] = '状态更新失败，请重试';
        echo json_encode($response);
        exit;
    }
    
    // 更新成功
    $statusText = $status == 1 ? '活跃' : '禁用';
    $response['success'] = true;
    $response['message'] = "记录 \"{$recordInfo}\" 状态已更新为 {$statusText}";
    $response['data'] = [
        'record_id' => $recordId,
        'record_info' => $recordInfo,
        'old_status' => $oldStatus,
        'new_status' => $status
    ];
    
} catch (Exception $e) {
    $response['message'] = '状态更新失败: ' . $e->getMessage();
}

echo json_encode($response);
exit;
```

### 5.2 前端状态切换实现
```javascript
// 状态切换功能 - 直接切换并刷新页面
function updateStatusAndRefresh(recordId, newStatus, buttonElement) {
    // 防止重复点击
    buttonElement.disabled = true;
    
    // 发送请求更新状态
    fetch('/模块名/update-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': getCsrfToken() // 添加CSRF令牌
        },
        body: JSON.stringify({
            id: recordId,
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        // 直接刷新页面，无论成功还是失败
        location.reload();
    })
    .catch(error => {
        // 直接刷新页面，不显示错误
        location.reload();
    });
}

// 在页面加载完成后绑定事件
document.addEventListener('DOMContentLoaded', function() {
    // 使用事件委托处理动态添加的元素
    document.querySelector('.records-table').addEventListener('click', function(e) {
        // 处理状态按钮点击
        if (e.target && e.target.classList.contains('btn-status')) {
            const recordId = e.target.getAttribute('data-id');
            const currentStatus = parseInt(e.target.getAttribute('data-status'));
            // 切换状态：1->0, 0->1
            const newStatus = currentStatus === 1 ? 0 : 1;
            
            updateStatusAndRefresh(recordId, newStatus, e.target);
        }
    });
});
```

## 错误处理和安全规范

### 1. 输入验证
```php
// 必须的验证步骤
$field = trim($_POST['field'] ?? '');
$field = htmlspecialchars($field, ENT_QUOTES, 'UTF-8');

// 长度验证
if (strlen($field) > 100) {
    $errors[] = '字段长度不能超过100个字符';
}

// 格式验证
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = '邮箱格式不正确';
}

// 支持中文邮箱的验证
if (!empty($email) && !preg_match('/^[\w\-\.\x{4e00}-\x{9fa5}]+@[\w\-\.\x{4e00}-\x{9fa5}]+\.[\w\-\.\x{4e00}-\x{9fa5}]+$/u', $email)) {
    $errors[] = '邮箱格式不正确';
}
```

### 2. 异常处理
```php
try {
    // 数据库操作
    R::store($record);
} catch (Exception $e) {
    error_log('数据库操作失败: ' . $e->getMessage());
    $errors[] = '操作失败，请重试';
}
```

### 3. 权限检查（如果需要）
```php
// 在操作前检查权限
if (!hasPermission('模块名', '操作类型')) {
    header('Location: /error/403');
    exit;
}
```

## 路由配置规范

### 标准CRUD路由配置
```php
// 在config/routes.php中添加
$routes = [
    // 列表页
    'GET /模块名' => '模块名/index',
    
    // 详情页
    'GET /模块名/detail/{id}' => '模块名/detail',
    
    // 表单页（新建和编辑）
    'GET /模块名/form' => '模块名/form',
    'GET /模块名/form/{id}' => '模块名/form',
    
    // 保存操作
    'POST /模块名/save' => '模块名/form',
    
    // 删除操作
    'POST /模块名/delete' => '模块名/delete',
    'POST /模块名/delete/{id}' => '模块名/delete',
    
    // 状态更新操作
    'POST /模块名/update-status' => '模块名/update_status',
];
```

## 前端交互规范

### 1. 删除确认
```javascript
// 必须有删除确认对话框
function confirmDelete(id, name) {
    if (confirm(`确定要删除 "${name}" 吗？此操作不可撤销。`)) {
        deleteRecord(id);
    }
}
```

### 2. AJAX请求
```javascript
// 标准的AJAX删除请求
function deleteRecord(id) {
    fetch('/模块名/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': getCsrfToken() // 添加CSRF令牌
        },
        body: JSON.stringify({id: id})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // 成功处理
            location.reload();
        } else {
            alert('删除失败: ' + data.message);
        }
    })
    .catch(error => {
        alert('网络错误，请重试');
    });
}
```

### 3. 状态切换按钮样式
```css
/* 状态按钮样式 */
.btn-status {
    color: #fff;
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 20px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    pointer-events: auto;
}

.status-active {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.status-inactive {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.btn-status:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.status-active:hover {
    background-color: rgba(40, 167, 69, 0.2);
}

.status-inactive:hover {
    background-color: rgba(220, 53, 69, 0.2);
}
```

## 用户体验优化要求

### 1. 页面导航
- 返回列表按钮应保持当前页码，使用URL参数传递页码信息
- 搜索功能应与分页兼容，保持搜索状态

### 2. 表单优化
- 所有输入框应提供placeholder提示
- 表单验证应在前端和后端同时进行
- 支持中文邮箱验证

### 3. 状态切换
- 状态切换应直接刷新页面，不显示确认对话框
- 状态按钮应有明显的视觉区分
- 防止重复点击机制

### 4. 删除操作
- 删除操作应有确认对话框
- 删除成功或失败应有提示信息
- 删除后应刷新当前页面

## 强制性检查清单

### 创建CRUD功能时必须完成：
- [ ] 使用RedBean ORM进行所有数据库操作
- [ ] 包含完整的数据验证
- [ ] 实现错误处理和异常捕获
- [ ] 添加安全防护（XSS、SQL注入等）
- [ ] 配置正确的路由
- [ ] 实现前端交互和确认机制
- [ ] 添加成功和错误消息提示
- [ ] 测试所有CRUD操作功能
- [ ] 实现状态切换功能（如适用）
- [ ] 优化用户体验（分页、搜索、提示等）

### 代码质量要求：
- [ ] 代码注释完整（中文）
- [ ] 变量命名规范
- [ ] 错误信息友好
- [ ] 响应式设计
- [ ] 性能优化（分页、索引等）
- [ ] 一致的视觉风格

### [重要] 容易出错的地方检查清单：
- [ ] 确保所有PHP代码块都有正确的开始和结束标记（大括号匹配）
- [ ] 确保所有页面文件都有正确的PHP结束标记 `?>`
- [ ] 确保所有表单都包含CSRF令牌验证
- [ ] 确保所有用户输入都经过ValidationHelper验证
- [ ] 确保更新操作时唯一性检查排除当前记录
- [ ] 确保状态切换和删除操作有适当的用户确认机制
- [ ] 确保所有路由都正确配置在config/routes.php中
- [ ] 确保所有静态资源文件（CSS、JS）都已创建
- [ ] 确保页面使用正确的布局和变量设置
- [ ] 确保所有数据库操作都在try-catch块中处理异常
- [ ] 确保所有输出都使用htmlspecialchars()防止XSS攻击
- [ ] 确保所有输入都使用trim()去除空白字符

## V2.0版本改进点

### 1. 样式优化
- 提供了更美观的CSS样式模板
- 改进了响应式设计
- 增加了悬停效果和过渡动画
- 统一了按钮和表单样式

### 2. 删除功能改进
- 使用模态框进行删除确认，替代浏览器默认confirm
- 添加了删除结果的Toast提示
- 优化了删除后的页面刷新逻辑

### 3. 创建和更新功能改进
- 完善了表单验证逻辑
- 改进了错误提示信息
- 优化了表单提交后的重定向逻辑
- 增加了表单数据保持功能（验证失败时）

### 4. 通用模板支持
- 提供了完整的CRUD模板系统
- 支持快速生成新的CRUD模块
- 包含详细的使用说明文档

### 5. 用户体验优化
- 改进了分页功能
- 增加了搜索功能
- 优化了状态切换交互
- 提供了更好的错误处理机制

## 使用通用模板创建CRUD模块

### 1. 模板位置
通用CRUD模板位于项目根目录的`templates/crud/`文件夹中，包含：
- `pages/`: 页面模板文件
  - `index.php`: 列表页面模板
  - `form.php`: 表单页面模板
  - `detail.php`: 详情页面模板
  - `delete.php`: 删除处理模板
  - `update_status.php`: 状态更新模板
- `assets/`: 静态资源模板
  - `css/`: CSS样式模板目录
    - `index.css`: 列表页面样式模板
    - `form.css`: 表单页面样式模板
    - `detail.css`: 详情页面样式模板
  - `js/`: JavaScript交互模板目录
    - `index.js`: 列表页面JavaScript模板
    - `form.js`: 表单页面JavaScript模板
    - `detail.js`: 详情页面JavaScript模板
- `README.md`: 使用说明文档

### 2. 创建步骤
1. 确定模块信息（模块名、表名、字段等）
2. 创建模块目录结构：
   ```bash
   # 创建页面目录
   mkdir -p handler/pages/{module_dir}
   
   # 创建静态资源目录
   mkdir -p public/static/own/css/{module_dir}
   mkdir -p public/static/own/js/{module_dir}
   ```
3. 复制模板文件到相应位置：
   ```bash
   # 复制页面模板
   cp templates/crud/pages/* handler/pages/{module_dir}/
   
   # 复制CSS模板
   cp templates/crud/assets/css/* public/static/own/css/{module_dir}/
   
   # 复制JS模板
   cp templates/crud/assets/js/* public/static/own/js/{module_dir}/
   ```
4. 替换模板中的占位符
5. 配置路由
6. 根据需要自定义模板内容

### 3. 模板占位符详解
- `{module_dir}`: 模块目录名（如：products、users）
- `{module_title}`: 模块标题（如：产品、用户）
- `{table_name}`: 数据库表名（如：products、users）
- `{primary_field}`: 主要字段名（用于显示记录名称，如：name、title）
- `{search_fields}`: 搜索字段条件（如：name LIKE ? OR description LIKE ?）
- `{search_params}`: 搜索参数（如："%{$searchTerm}%", "%{$searchTerm}%"）
- `{search_placeholder}`: 搜索框占位符文本
- `{list_fields}`: 列表显示字段标题
- `{list_field_values}`: 列表显示字段值
- `{form_fields}`: 表单字段初始化
- `{form_fields_post}`: 表单字段POST数据获取
- `{form_fields_params}`: 表单字段验证参数
- `{form_html}`: 表单HTML结构
- `{detail_fields}`: 详情页面字段显示
- `{validation_rules}`: 验证规则
- `{validation_results}`: 验证结果合并
- `{unique_checks}`: 唯一性检查
- `{update_fields}`: 更新字段赋值
- `{create_fields}`: 创建字段赋值

### 4. 模板使用示例
以创建一个产品管理模块为例：
1. 确定模块信息：
   - 模块目录名：products
   - 模块标题：产品
   - 数据库表名：products
   - 主要字段名：name
2. 创建目录结构：
   ```bash
   mkdir -p handler/pages/products
   mkdir -p public/static/own/css/products
   mkdir -p public/static/own/js/products
   ```
3. 复制模板文件并替换占位符
4. 配置路由
5. 根据需要自定义模板内容

通过使用通用模板和遵循本提示词规范，可以快速创建功能完整、样式美观、安全可靠的CRUD模块。