# AiPHP 提示词使用示例

## 概述
本文档提供了如何使用AiPHP框架提示词系统的具体示例，帮助开发者快速上手。

## 提示词系统结构

### 核心提示词文件
1. **master_guidelines.md** - 总纲，定义框架基本规范
2. **crud_operations_guidelines.md** - CRUD操作标准流程
3. **redbean_orm_guidelines.md** - RedBean ORM使用规范
4. **database_design_guidelines.md** - 数据库设计规范
5. **page_management_guidelines.md** - 页面管理规范

## 使用场景示例

### 场景1：创建用户管理模块

**用户请求：**
```
根据CRUD提示词，创建一个完整的用户管理模块，包括用户列表、添加、编辑、删除功能。
```

**AI会自动执行的步骤：**
1. 读取 `prompts/master_guidelines.md` 了解框架规范
2. 读取 `prompts/crud_operations_guidelines.md` 了解CRUD标准流程
3. 读取 `prompts/redbean_orm_guidelines.md` 了解RedBean使用规范
4. 读取 `prompts/database_design_guidelines.md` 了解数据库设计规范
5. 按照规范创建完整的用户管理模块

**生成的文件结构：**
```
handler/pages/users/
├── index.php          # 用户列表页
├── detail.php         # 用户详情页
├── form.php           # 用户表单页（新建/编辑）
└── delete.php         # 用户删除处理

public/static/own/css/users/
├── index.css          # 列表页样式
├── detail.css         # 详情页样式
└── form.css           # 表单页样式

public/static/own/js/users/
├── index.js           # 列表页脚本
├── detail.js          # 详情页脚本
└── form.js            # 表单页脚本

config/routes.php      # 添加用户模块路由
```

### 场景2：创建产品分类管理

**用户请求：**
```
根据数据库设计提示词和CRUD提示词，创建产品分类管理功能，支持层级分类。
```

**AI会执行：**
1. 按照数据库设计规范创建categories表结构
2. 按照CRUD规范实现分类的增删改查
3. 使用RedBean ORM处理层级关系
4. 创建完整的前端界面

### 场景3：创建文章管理系统

**用户请求：**
```
根据提示词规范，创建文章管理系统，包括文章分类、标签、评论功能。
```

**AI会执行：**
1. 设计articles、categories、tags、comments等表结构
2. 实现文章的CRUD操作
3. 处理文章与分类、标签的多对多关系
4. 创建富文本编辑器界面

## 关键规范要点

### 1. RedBean ORM强制使用
```php
// 必须的引入方式
use Core\OtherLibrary\RedBean\RedBeanFacade as R;

// 必须的初始化
R::initialize();

// 标准操作方法
$user = R::dispense('users');        // 创建
$user = R::load('users', $id);       // 读取
$users = R::find('users', 'status = ?', [1]); // 查询
R::store($user);                     // 保存
R::trash($user);                     // 删除
```

### 2. 表名和字段规范
```php
// 表名：复数形式，小写，下划线分隔
users, products, order_items, user_profiles

// 必须字段
id, created_at, updated_at, status

// 外键命名
user_id, category_id, product_id
```

### 3. 标准CRUD流程
```php
// 创建操作
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. 获取和验证数据
    $formData = [
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
    ];
    
    // 2. 数据验证
    $errors = [];
    if (empty($formData['name'])) {
        $errors[] = '姓名不能为空';
    }
    
    // 3. 检查唯一性
    if (!empty($formData['email'])) {
        $existing = R::findOne('users', 'email = ?', [$formData['email']]);
        if ($existing) {
            $errors[] = '邮箱已存在';
        }
    }
    
    // 4. 保存数据
    if (empty($errors)) {
        $user = R::dispense('users');
        $user->name = $formData['name'];
        $user->email = $formData['email'];
        $user->status = 1;
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
        
        $id = R::store($user);
        header('Location: /users?success=created');
        exit;
    }
}
```

## 提示词触发关键词

当用户提到以下关键词时，AI会自动读取相应的提示词：

### 通用触发词
- **"提示词"** - 读取master_guidelines.md
- **"根据提示词"** - 读取相关提示词文件

### 具体功能触发词
- **"CRUD"、"增删改查"** - 读取crud_operations_guidelines.md
- **"RedBean"、"ORM"** - 读取redbean_orm_guidelines.md
- **"数据库设计"、"表结构"** - 读取database_design_guidelines.md
- **"页面管理"、"创建页面"** - 读取page_management_guidelines.md

### 模块触发词
- **"用户管理"** - 读取用户相关提示词
- **"产品管理"** - 读取产品相关提示词
- **"订单管理"** - 读取订单相关提示词

## 最佳实践建议

### 1. 明确指定提示词
```
根据CRUD操作提示词，创建产品管理功能
```

### 2. 组合使用提示词
```
根据数据库设计提示词和CRUD提示词，创建完整的订单管理系统
```

### 3. 指定具体需求
```
根据页面管理提示词，创建响应式的用户列表页面，包含搜索和分页功能
```

## 常见问题解答

### Q: 如何确保AI使用了正确的提示词？
A: 在请求中明确提到"根据XX提示词"，AI会自动读取相应文件。

### Q: 可以同时使用多个提示词吗？
A: 可以，例如："根据CRUD提示词和数据库设计提示词创建用户管理"。

### Q: 如何自定义提示词？
A: 可以在prompts目录下创建新的.md文件，并在master_guidelines.md中引用。

### Q: 提示词的优先级是什么？
A: master_guidelines.md是总纲，具体功能提示词会覆盖总纲中的通用规则。

## 提示词更新和维护

### 1. 添加新提示词
1. 在prompts目录下创建新的.md文件
2. 在master_guidelines.md中添加引用
3. 运行测试脚本验证完整性

### 2. 修改现有提示词
1. 直接编辑对应的.md文件
2. 确保不破坏现有的引用关系
3. 运行测试脚本验证修改结果

### 3. 测试提示词完整性
```bash
php tests/test_prompts_integrity.php
```

## 总结

AiPHP提示词系统提供了：
- **标准化的开发流程**
- **强制的RedBean ORM使用**
- **统一的代码规范**
- **完整的CRUD操作指南**
- **数据库设计最佳实践**

通过使用这些提示词，可以确保：
- 代码质量的一致性
- 开发效率的提升
- 维护成本的降低
- 团队协作的标准化
