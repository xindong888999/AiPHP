# 通用CRUD模块模板使用说明

## 概述
本模板提供了一套完整的CRUD（创建、读取、更新、删除）操作模板，适用于AiPHP框架。模板遵循框架的"Route-Page-Class"架构模式，直接将路由映射到页面文件。

## 目录结构
```
templates/crud/
├── pages/               # 页面模板
│   ├── index.php        # 列表页面模板
│   ├── form.php         # 表单页面模板
│   ├── detail.php       # 详情页面模板
│   ├── delete.php       # 删除处理模板
│   └── update_status.php # 状态更新模板
├── assets/              # 静态资源模板
│   ├── css/
│   │   ├── index.css    # 列表页面样式模板
│   │   ├── form.css     # 表单页面样式模板
│   │   └── detail.css   # 详情页面样式模板
│   └── js/
│       ├── index.js     # 列表页面JavaScript模板
│       ├── form.js      # 表单页面JavaScript模板
│       └── detail.js    # 详情页面JavaScript模板
```

## 使用步骤

### 1. 确定模块信息
在使用模板前，需要确定以下信息：
- `{module_dir}`: 模块目录名（如：products、users）
- `{module_title}`: 模块标题（如：产品、用户）
- `{table_name}`: 数据库表名（如：products、users）
- `{primary_field}`: 主要字段名（用于显示记录名称，如：name、title）
- `{module_dir_singular}`: 模块单数形式（如：product、user）

### 2. 创建模块目录
```bash
# 创建页面目录
mkdir -p handler/pages/{module_dir}

# 创建静态资源目录
mkdir -p public/static/own/css/{module_dir}
mkdir -p public/static/own/js/{module_dir}
```

### 3. 复制并替换模板文件

#### 3.1 复制页面文件
```bash
# 复制列表页面
cp templates/crud/pages/index.php handler/pages/{module_dir}/index.php

# 复制表单页面
cp templates/crud/pages/form.php handler/pages/{module_dir}/form.php

# 复制详情页面
cp templates/crud/pages/detail.php handler/pages/{module_dir}/detail.php

# 复制删除处理页面
cp templates/crud/pages/delete.php handler/pages/{module_dir}/delete.php

# 复制状态更新页面
cp templates/crud/pages/update_status.php handler/pages/{module_dir}/update_status.php
```

#### 3.2 复制静态资源文件
```bash
# 复制CSS文件
cp templates/crud/assets/css/index.css public/static/own/css/{module_dir}/index.css
cp templates/crud/assets/css/form.css public/static/own/css/{module_dir}/form.css
cp templates/crud/assets/css/detail.css public/static/own/css/{module_dir}/detail.css

# 复制JS文件
cp templates/crud/assets/js/index.js public/static/own/js/{module_dir}/index.js
cp templates/crud/assets/js/form.js public/static/own/js/{module_dir}/form.js
cp templates/crud/assets/js/detail.js public/static/own/js/{module_dir}/detail.js
```

### 4. 替换模板占位符

在所有复制的文件中，将以下占位符替换为实际值：

#### 4.1 页面文件占位符替换
- `{module_dir}` → 模块目录名（如：products、users）
- `{module_title}` → 模块标题（如：产品、用户）
- `{table_name}` → 数据库表名（如：products、users）
- `{primary_field}` → 主要字段名（用于显示记录名称，如：name、title）
- `{module_dir_singular}` → 模块单数形式（如：product、user）
- `{search_fields}` → 搜索字段条件（如：name LIKE ? OR description LIKE ?）
- `{search_params}` → 搜索参数（如："%{$searchTerm}%", "%{$searchTerm}%"）
- `{search_placeholder}` → 搜索框占位符文本
- `{list_fields}` → 列表显示字段标题
- `{list_field_values}` → 列表显示字段值
- `{form_fields}` → 表单字段初始化
- `{form_fields_post}` → 表单字段POST数据获取
- `{form_fields_params}` → 表单字段验证参数
- `{form_html}` → 表单HTML结构
- `{detail_fields}` → 详情页面字段显示
- `{validation_rules}` → 验证规则
- `{validation_results}` → 验证结果合并
- `{unique_checks}` → 唯一性检查
- `{update_fields}` → 更新字段赋值
- `{create_fields}` → 创建字段赋值

#### 4.2 静态资源文件占位符替换
- `{module_dir}` → 模块目录名（如：products、users）
- `{module_title}` → 模块标题（如：产品、用户）
- `{module_dir_singular}` → 模块单数形式（如：product、user）

### 5. 配置路由

将路由配置添加到`config/routes.php`文件中：

```php
// GET请求路由
'GET' => [
    // ...
    '/{module_dir}' => '{module_dir}/index',
    '/{module_dir}/detail/{id}' => '{module_dir}/detail',
    '/{module_dir}/form' => '{module_dir}/form',
    '/{module_dir}/form/{id}' => '{module_dir}/form',
],

// POST请求路由
'POST' => [
    // ...
    '/{module_dir}/update-status' => '{module_dir}/update_status',
    '/{module_dir}/save' => '{module_dir}/form',
    '/{module_dir}/delete' => '{module_dir}/delete',
],
```

### 6. 自定义模板内容

根据具体需求，可能需要对以下内容进行自定义：

1. **字段验证规则**：在表单页面中修改验证规则
2. **唯一性检查**：根据业务需求添加唯一性检查
3. **样式调整**：修改CSS文件以适应具体设计需求
4. **交互功能**：根据需要修改JavaScript文件

## 模板特点

### 1. 安全性
- 集成CSRF令牌保护
- 使用ValidationHelper进行数据验证
- 使用htmlspecialchars防止XSS攻击
- 所有数据库操作在try-catch块中处理

### 2. 功能完整性
- 完整的CRUD操作
- 分页功能
- 搜索功能
- 状态切换
- 删除确认
- 消息提示

### 3. 用户体验
- 响应式设计
- 悬停效果
- 模态框确认
- Toast提示
- 状态按钮视觉区分

### 4. 代码规范
- 遵循AiPHP框架规范
- 使用RedBean ORM进行数据库操作
- 符合页面管理提示词要求
- 包含完整的页面元数据

## 注意事项

1. **数据库表结构**：确保数据库表包含必要的字段（id、created_at、updated_at、status）
2. **字段验证**：根据实际字段类型和业务需求调整验证规则
3. **唯一性检查**：根据业务需求添加适当的唯一性检查
4. **路由配置**：确保路由配置正确并添加到主路由文件中
5. **静态资源**：确保CSS和JS文件路径正确
6. **CSRF令牌使用**：列表页面中的CSRF令牌meta标签必须通过[$additionalMeta](file:///c:/Users/Administrator/Desktop/AiPHP/handler/pages/news.php#L20-L20)变量添加到页面头部，而不是直接在HTML中输出。JavaScript通过`document.querySelector('meta[name="csrf-token"]')`获取CSRF令牌。

## 扩展建议

1. **字段类型支持**：可以根据需要添加对不同字段类型的支持（如日期、文件上传等）
2. **权限控制**：可以集成权限控制机制
3. **日志记录**：可以添加操作日志记录功能
4. **批量操作**：可以扩展支持批量删除、批量更新等功能

## 常见问题

1. **路由无法访问**：检查路由配置是否正确添加到主路由文件
2. **样式不生效**：检查CSS文件路径是否正确
3. **JavaScript功能异常**：检查JS文件路径和类名是否正确
4. **数据库操作失败**：检查数据库表结构和字段名是否正确
5. **CSRF验证失败**：检查CSRF令牌meta标签是否正确添加到页面头部，JavaScript是否能正确获取令牌

通过使用本模板，可以快速创建符合AiPHP框架规范的CRUD功能模块，提高开发效率并确保代码质量。