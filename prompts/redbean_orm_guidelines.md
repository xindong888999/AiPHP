# AiPHP RedBean ORM使用规范

## 概述
本提示词定义了在AiPHP框架中使用RedBean ORM的强制性规范、最佳实践和标准操作流程。

## 强制性声明
**在AiPHP框架中，必须且只能使用RedBean ORM进行数据库操作，严禁使用其他ORM或原生SQL语句。**

## 基础配置和引入

### 1. 标准引入方式
```php
<?php
// 必须在每个需要数据库操作的页面文件开头添加
use Core\OtherLibrary\RedBean\RedBeanFacade as R;

// 初始化RedBean（必须调用）
R::initialize();
?>
```

### 2. 错误处理包装
```php
try {
    R::initialize();
    // 数据库操作代码
} catch (Exception $e) {
    error_log('RedBean初始化失败: ' . $e->getMessage());
    // 错误处理逻辑
}
```

## 表名和字段命名规范

### 1. 表名规范（强制性）
- **使用复数形式**：users、products、orders、categories
- **小写字母**：全部使用小写字母
- **下划线分隔**：user_profiles、order_items、product_categories
- **避免保留字**：不使用SQL保留字作为表名

### 2. 字段命名规范（强制性）
- **主键**：id（RedBean自动管理）
- **外键**：表名_id（如：user_id、category_id）
- **时间字段**：created_at、updated_at、deleted_at
- **状态字段**：status（1=活跃，0=禁用，-1=删除）
- **布尔字段**：is_active、is_verified、is_published
- **文本字段**：使用下划线分隔（first_name、last_name、email_address）

### 3. 必须包含的字段
```php
// 所有表必须包含的基础字段
$record = R::dispense('表名');
$record->created_at = date('Y-m-d H:i:s');
$record->updated_at = date('Y-m-d H:i:s');
$record->status = 1; // 默认状态为活跃
```

## RedBean基础操作规范

### 1. 创建记录（Create）
```php
// 标准创建流程
$record = R::dispense('表名'); // 创建新的bean

// 设置字段值
$record->field_name = $value;
$record->another_field = $another_value;

// 必须设置的时间戳
$record->created_at = date('Y-m-d H:i:s');
$record->updated_at = date('Y-m-d H:i:s');
$record->status = 1; // 默认状态

// 保存记录
$id = R::store($record);

// 验证保存结果
if ($id > 0) {
    // 保存成功
    echo "记录创建成功，ID: {$id}";
} else {
    // 保存失败
    throw new Exception('记录创建失败');
}
```

### 2. 读取记录（Read）
```php
// 按ID读取单条记录
$record = R::load('表名', $id);

// 检查记录是否存在
if ($record && $record->id) {
    // 记录存在
    echo $record->field_name;
} else {
    // 记录不存在
    throw new Exception('记录不存在');
}

// 查找单条记录
$user = R::findOne('users', 'email = ?', [$email]);

// 查找多条记录
$users = R::find('users', 'status = ? ORDER BY created_at DESC', [1]);

// 查找所有记录
$allUsers = R::findAll('users');

// 计数查询
$userCount = R::count('users', 'status = ?', [1]);
```

### 3. 更新记录（Update）
```php
// 加载现有记录
$record = R::load('表名', $id);

// 检查记录是否存在
if (!$record || !$record->id) {
    throw new Exception('要更新的记录不存在');
}

// 更新字段值
$record->field_name = $new_value;
$record->another_field = $another_new_value;

// 必须更新时间戳
$record->updated_at = date('Y-m-d H:i:s');

// 保存更新
$result = R::store($record);

// 验证更新结果
if ($result) {
    echo "记录更新成功";
} else {
    throw new Exception('记录更新失败');
}
```

### 4. 删除记录（Delete）
```php
// 方法1：物理删除（推荐用于测试数据）
$record = R::load('表名', $id);
if ($record && $record->id) {
    R::trash($record);
    echo "记录已删除";
} else {
    throw new Exception('要删除的记录不存在');
}

// 方法2：逻辑删除（推荐用于生产数据）
$record = R::load('表名', $id);
if ($record && $record->id) {
    $record->status = -1; // 标记为已删除
    $record->deleted_at = date('Y-m-d H:i:s');
    $record->updated_at = date('Y-m-d H:i:s');
    R::store($record);
    echo "记录已标记删除";
} else {
    throw new Exception('要删除的记录不存在');
}
```

## 高级查询操作

### 1. 条件查询
```php
// 简单条件
$users = R::find('users', 'age > ? AND status = ?', [18, 1]);

// 模糊查询
$users = R::find('users', 'name LIKE ?', ["%{$searchTerm}%"]);

// 多条件查询
$users = R::find('users', 'status = ? AND created_at >= ? ORDER BY id DESC', [1, $startDate]);

// IN查询
$users = R::find('users', 'id IN (' . R::genSlots([1, 2, 3, 4, 5]) . ')', [1, 2, 3, 4, 5]);
```

### 2. 分页查询
```php
// 分页参数
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

// 获取总数
$totalRecords = R::count('users', 'status = ?', [1]);
$totalPages = ceil($totalRecords / $perPage);

// 获取当前页数据
$users = R::find('users', 'status = ? ORDER BY id DESC LIMIT ? OFFSET ?', [1, $perPage, $offset]);
```

### 3. 关联查询
```php
// 一对多关联
$user = R::load('users', $userId);
$orders = $user->ownOrdersList; // 获取用户的所有订单

// 多对一关联
$order = R::load('orders', $orderId);
$user = $order->users; // 获取订单的用户

// 多对多关联
$user = R::load('users', $userId);
$roles = $user->sharedRolesList; // 获取用户的所有角色
```

## 数据验证和安全规范

### 1. 输入验证
```php
// 必须的验证步骤
function validateInput($data) {
    $errors = [];
    
    // 去除空白字符
    $data = array_map('trim', $data);
    
    // HTML实体编码防止XSS
    $data = array_map(function($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }, $data);
    
    // 长度验证
    if (strlen($data['name']) > 100) {
        $errors[] = '姓名长度不能超过100个字符';
    }
    
    // 邮箱验证
    if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = '邮箱格式不正确';
    }
    
    return ['data' => $data, 'errors' => $errors];
}
```

### 2. 唯一性检查
```php
// 创建时检查唯一性
function checkUniqueness($table, $field, $value, $excludeId = null) {
    $condition = "{$field} = ?";
    $params = [$value];
    
    if ($excludeId) {
        $condition .= " AND id != ?";
        $params[] = $excludeId;
    }
    
    $existing = R::findOne($table, $condition, $params);
    return $existing === null;
}

// 使用示例
if (!checkUniqueness('users', 'email', $email, $userId)) {
    $errors[] = '该邮箱已被使用';
}
```

### 3. 事务处理
```php
// 复杂操作使用事务
try {
    R::begin(); // 开始事务
    
    // 创建用户
    $user = R::dispense('users');
    $user->name = $name;
    $user->email = $email;
    $user->created_at = date('Y-m-d H:i:s');
    $user->updated_at = date('Y-m-d H:i:s');
    $userId = R::store($user);
    
    // 创建用户配置
    $profile = R::dispense('user_profiles');
    $profile->user_id = $userId;
    $profile->bio = $bio;
    $profile->created_at = date('Y-m-d H:i:s');
    $profile->updated_at = date('Y-m-d H:i:s');
    R::store($profile);
    
    R::commit(); // 提交事务
    echo "用户和配置创建成功";
    
} catch (Exception $e) {
    R::rollback(); // 回滚事务
    throw new Exception('创建失败: ' . $e->getMessage());
}
```

## 性能优化规范

### 1. 查询优化
```php
// 避免N+1查询问题
$users = R::find('users', 'status = ?', [1]);
$userIds = array_column($users, 'id');
$profiles = R::find('user_profiles', 'user_id IN (' . R::genSlots($userIds) . ')', $userIds);

// 使用索引字段查询
$user = R::findOne('users', 'email = ?', [$email]); // 确保email字段有索引
```

### 2. 批量操作
```php
// 批量插入
$users = [];
for ($i = 0; $i < 100; $i++) {
    $user = R::dispense('users');
    $user->name = "User {$i}";
    $user->email = "user{$i}@example.com";
    $user->created_at = date('Y-m-d H:i:s');
    $user->updated_at = date('Y-m-d H:i:s');
    $users[] = $user;
}
R::storeAll($users);
```

## 错误处理和调试

### 1. 标准错误处理
```php
try {
    // RedBean操作
    $result = R::store($record);
} catch (Exception $e) {
    // 记录错误日志
    error_log('RedBean操作失败: ' . $e->getMessage());
    error_log('错误跟踪: ' . $e->getTraceAsString());
    
    // 用户友好的错误消息
    $userMessage = '操作失败，请稍后重试';
    
    // 开发环境显示详细错误
    if (defined('DEBUG') && DEBUG) {
        $userMessage .= ': ' . $e->getMessage();
    }
    
    throw new Exception($userMessage);
}
```

### 2. 调试模式
```php
// 开发环境启用SQL日志
if (defined('DEBUG') && DEBUG) {
    R::debug(true); // 显示执行的SQL语句
}
```

## 强制性检查清单

### 使用RedBean ORM时必须确认：
- [ ] 已正确引入RedBean门面类
- [ ] 已调用R::initialize()初始化
- [ ] 表名使用复数形式和小写字母
- [ ] 字段命名符合规范
- [ ] 包含必须的时间戳字段
- [ ] 实现了适当的错误处理
- [ ] 进行了输入验证和安全检查
- [ ] 使用了事务处理（如果需要）
- [ ] 考虑了性能优化
- [ ] 添加了适当的注释

### 禁止的操作：
- [ ] 禁止使用原生SQL语句
- [ ] 禁止使用其他ORM框架
- [ ] 禁止直接操作数据库连接
- [ ] 禁止在前端直接暴露数据库结构
- [ ] 禁止跳过数据验证步骤
