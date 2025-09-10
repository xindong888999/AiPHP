# AiPHP 数据库设计规范

## 概述
本提示词定义了在AiPHP框架中进行数据库设计的标准规范，包括表结构设计、字段命名、索引策略等。

## 强制性规范

### 1. 表命名规范
- **使用复数形式**：users、products、orders、categories
- **全部小写**：避免大小写混合
- **下划线分隔**：user_profiles、order_items、product_categories
- **语义明确**：表名应该清楚表达存储的数据类型
- **避免保留字**：不使用SQL保留字作为表名

### 2. 字段命名规范
- **主键**：统一使用`id`，自增整型
- **外键**：`表名_id`格式（如：user_id、category_id）
- **时间字段**：
  - `created_at`：创建时间
  - `updated_at`：更新时间
  - `deleted_at`：删除时间（软删除）
- **状态字段**：`status`（整型，1=活跃，0=禁用，-1=删除）
- **布尔字段**：`is_`前缀（如：is_active、is_verified）
- **计数字段**：`count_`前缀（如：view_count、like_count）

### 3. 必须包含的字段
```sql
-- 所有表必须包含的基础字段
id INT PRIMARY KEY AUTO_INCREMENT,
created_at DATETIME NOT NULL,
updated_at DATETIME NOT NULL,
status TINYINT DEFAULT 1 COMMENT '状态：1=活跃，0=禁用，-1=删除'
```

## 标准表结构模板

### 1. 用户表（users）
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE COMMENT '用户名',
    email VARCHAR(100) NOT NULL UNIQUE COMMENT '邮箱',
    password VARCHAR(255) NOT NULL COMMENT '密码哈希',
    nickname VARCHAR(100) DEFAULT NULL COMMENT '昵称',
    phone VARCHAR(20) DEFAULT NULL COMMENT '手机号',
    avatar VARCHAR(255) DEFAULT NULL COMMENT '头像URL',
    gender TINYINT DEFAULT 0 COMMENT '性别：0=未知，1=男，2=女',
    age TINYINT DEFAULT NULL COMMENT '年龄',
    address TEXT DEFAULT NULL COMMENT '地址',
    bio TEXT DEFAULT NULL COMMENT '个人简介',
    last_login DATETIME DEFAULT NULL COMMENT '最后登录时间',
    email_verified_at DATETIME DEFAULT NULL COMMENT '邮箱验证时间',
    status TINYINT DEFAULT 1 COMMENT '状态：1=活跃，0=禁用，-1=删除',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户表';
```

### 2. 分类表（categories）
```sql
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL COMMENT '分类名称',
    slug VARCHAR(100) NOT NULL UNIQUE COMMENT 'URL别名',
    description TEXT DEFAULT NULL COMMENT '分类描述',
    parent_id INT DEFAULT NULL COMMENT '父分类ID',
    sort_order INT DEFAULT 0 COMMENT '排序',
    image VARCHAR(255) DEFAULT NULL COMMENT '分类图片',
    is_featured TINYINT DEFAULT 0 COMMENT '是否推荐',
    status TINYINT DEFAULT 1 COMMENT '状态：1=活跃，0=禁用，-1=删除',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    
    INDEX idx_parent_id (parent_id),
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_sort_order (sort_order),
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='分类表';
```

### 3. 文章表（articles）
```sql
CREATE TABLE articles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL COMMENT '文章标题',
    slug VARCHAR(200) NOT NULL UNIQUE COMMENT 'URL别名',
    content LONGTEXT NOT NULL COMMENT '文章内容',
    excerpt TEXT DEFAULT NULL COMMENT '文章摘要',
    featured_image VARCHAR(255) DEFAULT NULL COMMENT '特色图片',
    author_id INT NOT NULL COMMENT '作者ID',
    category_id INT DEFAULT NULL COMMENT '分类ID',
    view_count INT DEFAULT 0 COMMENT '浏览次数',
    like_count INT DEFAULT 0 COMMENT '点赞次数',
    comment_count INT DEFAULT 0 COMMENT '评论次数',
    is_featured TINYINT DEFAULT 0 COMMENT '是否推荐',
    is_published TINYINT DEFAULT 0 COMMENT '是否发布',
    published_at DATETIME DEFAULT NULL COMMENT '发布时间',
    status TINYINT DEFAULT 1 COMMENT '状态：1=活跃，0=禁用，-1=删除',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    
    INDEX idx_author_id (author_id),
    INDEX idx_category_id (category_id),
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_is_published (is_published),
    INDEX idx_published_at (published_at),
    INDEX idx_created_at (created_at),
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文章表';
```

### 4. 多对多关系表（user_roles）
```sql
CREATE TABLE user_roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL COMMENT '用户ID',
    role_id INT NOT NULL COMMENT '角色ID',
    assigned_by INT DEFAULT NULL COMMENT '分配者ID',
    assigned_at DATETIME NOT NULL COMMENT '分配时间',
    expires_at DATETIME DEFAULT NULL COMMENT '过期时间',
    status TINYINT DEFAULT 1 COMMENT '状态：1=活跃，0=禁用，-1=删除',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    
    UNIQUE KEY uk_user_role (user_id, role_id),
    INDEX idx_user_id (user_id),
    INDEX idx_role_id (role_id),
    INDEX idx_status (status),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户角色关系表';
```

## 数据类型规范

### 1. 字符串类型
- **VARCHAR(50)**：用户名、短标题
- **VARCHAR(100)**：邮箱、昵称、分类名
- **VARCHAR(200)**：长标题、URL
- **VARCHAR(255)**：文件路径、URL链接
- **TEXT**：短文本内容、描述
- **LONGTEXT**：长文本内容、文章正文

### 2. 数值类型
- **TINYINT**：状态、性别、年龄、布尔值
- **INT**：ID、计数、排序
- **BIGINT**：大数值、时间戳
- **DECIMAL(10,2)**：价格、金额

### 3. 时间类型
- **DATETIME**：精确时间（推荐）
- **TIMESTAMP**：自动更新时间
- **DATE**：仅日期

## 索引策略

### 1. 必须创建的索引
- **主键索引**：自动创建
- **唯一索引**：用户名、邮箱、slug等唯一字段
- **外键索引**：所有外键字段
- **状态索引**：status字段
- **时间索引**：created_at、updated_at

### 2. 复合索引
```sql
-- 查询优化的复合索引
INDEX idx_status_created (status, created_at),
INDEX idx_category_status (category_id, status),
INDEX idx_author_published (author_id, is_published, published_at)
```

### 3. 全文索引
```sql
-- 搜索功能的全文索引
FULLTEXT INDEX ft_title_content (title, content)
```

## 外键约束规范

### 1. 级联操作
- **ON DELETE CASCADE**：删除主记录时删除关联记录
- **ON DELETE SET NULL**：删除主记录时将外键设为NULL
- **ON UPDATE CASCADE**：更新主记录时同步更新外键

### 2. 外键命名
```sql
-- 外键约束命名规范
CONSTRAINT fk_表名_字段名 FOREIGN KEY (字段名) REFERENCES 目标表(目标字段)
```

## 数据库配置规范

### 1. 字符集和排序规则
```sql
-- 推荐配置
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

### 2. 存储引擎
```sql
-- 推荐使用InnoDB
ENGINE=InnoDB
```

### 3. 表注释
```sql
-- 每个表必须有注释
COMMENT='表的用途描述'
```

## RedBean ORM对应关系

### 1. 表名映射
```php
// RedBean会自动处理表名
$user = R::dispense('users');        // 对应users表
$article = R::dispense('articles');  // 对应articles表
$category = R::dispense('categories'); // 对应categories表
```

### 2. 关联关系
```php
// 一对多关系
$user = R::load('users', 1);
$articles = $user->ownArticlesList; // 用户的文章

// 多对一关系
$article = R::load('articles', 1);
$author = $article->users; // 文章的作者

// 多对多关系
$user = R::load('users', 1);
$roles = $user->sharedRolesList; // 用户的角色
```

## 数据迁移规范

### 1. 创建表的PHP代码
```php
// 使用RedBean创建表结构
function createUsersTable() {
    try {
        R::initialize();
        
        // 创建示例记录以生成表结构
        $user = R::dispense('users');
        $user->username = 'example';
        $user->email = 'example@test.com';
        $user->password = 'hashed_password';
        $user->status = 1;
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
        
        $id = R::store($user);
        
        // 删除示例记录
        R::trash($user);
        
        echo "用户表创建成功\n";
    } catch (Exception $e) {
        echo "创建用户表失败: " . $e->getMessage() . "\n";
    }
}
```

### 2. 数据填充
```php
// 创建测试数据
function seedUsersTable() {
    try {
        R::initialize();
        
        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $user = R::dispense('users');
            $user->username = "user{$i}";
            $user->email = "user{$i}@example.com";
            $user->password = password_hash('123456', PASSWORD_DEFAULT);
            $user->nickname = "用户{$i}";
            $user->status = 1;
            $user->created_at = date('Y-m-d H:i:s');
            $user->updated_at = date('Y-m-d H:i:s');
            $users[] = $user;
        }
        
        R::storeAll($users);
        echo "测试数据创建成功\n";
    } catch (Exception $e) {
        echo "创建测试数据失败: " . $e->getMessage() . "\n";
    }
}
```

## 强制性检查清单

### 设计数据库时必须确认：
- [ ] 表名使用复数形式和小写字母
- [ ] 包含必须的基础字段（id、created_at、updated_at、status）
- [ ] 字段命名符合规范
- [ ] 设置了适当的数据类型和长度
- [ ] 创建了必要的索引
- [ ] 设置了外键约束
- [ ] 添加了表和字段注释
- [ ] 使用了正确的字符集和排序规则
- [ ] 考虑了数据的完整性和一致性

### 性能优化检查：
- [ ] 为查询字段创建了索引
- [ ] 避免了过多的外键约束
- [ ] 考虑了分页查询的性能
- [ ] 设计了合理的表结构避免冗余
- [ ] 考虑了未来的扩展性
