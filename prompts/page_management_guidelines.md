# AiPHP 页面管理提示词
## 概述
本提示词定义了在AiPHP框架中进行页面管理的操作规范，包括创建页面、删除页面等功能。每个功能都有对应的编号，便于参考和使用。

### CRUD模板使用说明
在创建CRUD（增删改查）功能模块时，应优先使用项目提供的通用模板。通用CRUD模板位于`templates/crud/`目录下，包含完整的页面结构、样式和交互功能。

使用模板的步骤：
1. 确定模块信息（模块名、表名、字段等）
2. 复制模板文件到目标位置
3. 替换模板中的占位符
4. 根据具体需求调整模板内容

## 创建页面完整步骤
1. **创建页面文件**：在 `handler/pages/` 目录下创建 `.php` 文件，命名符合小驼峰规则。
2. **创建配套静态资源**：在 `public/static/own/` 目录下创建对应的 CSS 和 JS 文件。
3. **[CRITICAL] 配置路由**：在 `config/routes.php` 中添加页面路由。
4. **[CRITICAL] 更新导航菜单**：如果使用布局文件，需在布局中更新导航链接。

## 强制性执行清单（创建页面时必须逐项完成）

在创建任何页面时，必须严格按照以下顺序执行，并在完成后在每个项目前打勾：

### 创建阶段强制清单：
- [ ] 第1步：创建页面文件
  - [ ] 确认文件路径正确
  - [ ] 确认文件命名符合规范
  - [ ] 确认页面结构符合要求（使用布局时仅包含HTML片段）
  
- [ ] 第2步：创建配套静态资源
  - [ ] 创建CSS文件
  - [ ] 创建JS文件
  - [ ] 确认文件路径与页面文件匹配
  
- [ ] 第3步：配置路由
  - [ ] 在config/routes.php中添加GET路由
  - [ ] 如需要，添加POST/PUT/DELETE路由
  
- [ ] 第4步：更新导航菜单（如使用布局）
  - [ ] 在相应布局文件中添加导航链接
  - [ ] 确认链接路径正确
  - [ ] 确认链接文字合适

### 验证阶段强制清单：
- [ ] 验证页面文件结构
- [ ] 验证静态资源文件
- [ ] 验证路由配置
- [ ] 验证导航菜单更新
- [ ] 验证页面可以正常访问

## 错误预防机制

### 创建前自检问题：
在开始创建页面前，必须回答以下问题：

1. 我是否确定页面类型（独立页面还是模块下页面）？
2. 我是否确定使用哪种布局（如果使用布局）？
3. 我是否知道页面需要包含哪些功能元素？
4. 我是否了解完整的创建步骤？

### 创建后强制验证：
完成页面创建后，必须执行以下验证：

1. 检查点验证：
   - [ ] 页面文件是否存在且结构正确？
   - [ ] 静态资源文件是否已创建？
   - [ ] 路由是否已配置？
   - [ ] 导航菜单是否已更新（如适用）？

2. 功能验证：
   - [ ] 页面是否可以正常访问？
   - [ ] 页面样式是否正常显示？
   - [ ] 页面脚本是否正常运行？
   - [ ] 导航链接是否可以正常访问？

## 执行规范（违反将导致任务失败）

### 顺序执行要求：
1. 必须严格按照创建页面完整步骤的顺序执行
2. 不允许跳过任何步骤
3. 每完成一个步骤必须进行验证

### 完成标准：
只有当以下所有条件都满足时，页面创建任务才算完成：
1. 所有强制性执行清单项目都已完成并打勾
2. 所有验证检查都通过
3. 页面可以正常访问且功能完整
4. 没有违反任何强制规范

### 失败处理：
如果在执行过程中发现以下情况，必须立即停止并重新开始：
1. 跳过了任何一个步骤
2. 页面结构不符合规范
3. 遗漏了任何必需的配置项
4. 验证检查未通过

## 任务完成确认机制

在宣布任务完成前，必须完成以下确认：

### 自我审查：
1. [ ] 我是否严格按照步骤顺序执行？
2. [ ] 我是否遗漏了任何步骤？
3. [ ] 我是否完成了所有必需的验证？
4. [ ] 页面是否完全符合要求？

### 最终检查：
1. [ ] 页面文件结构正确
2. [ ] 静态资源文件已创建
3. [ ] 路由配置正确
4. [ ] 导航菜单已更新（如适用）
5. [ ] 页面可以正常访问
6. [ ] 所有功能正常运行

只有当以上所有检查项都通过后，才能宣布任务完成。

## 1. 创建页面

### 1.1 核心规范
1. **[CRITICAL] 创建页面文件**：根据要求创建页面，如果是中文的自动翻译成英文名字，页面文件必须使用 `.php` 扩展名（如 `user.php`）。
2. **[CRITICAL] 文件路径**：
   - 单独页面路径：`handler/pages/页面名.php`。
   - 模块下页面路径：`handler/pages/模块名/页面名.php`（如 `home/about.php`）。
3. **[CRITICAL] 命名规则**：文件和文件夹使用小驼峰命名法。
4. **[CRITICAL] 静态资源**：创建配套的 CSS 和 JS 文件，目录结构与页面一致。
5. **布局使用**：如果指定布局文件，则使用布局；否则不使用。
6. **页面结构**：
   - 使用布局时：**仅构建 HTML 片段，严禁包含完整的 HTML 结构**。
   - 不使用布局时：构建完整的 HTML 结构。
7. **[CRITICAL] 路由配置**：在 `config/routes.php` 中配置页面路由。
8. **[CRITICAL] 导航更新**：如果使用布局文件，需在导航中添加超链接。
9. **CRUD模板使用**：对于增删改查功能模块，应优先使用`templates/crud/`目录下的通用模板，通过复制模板文件并替换占位符的方式快速创建功能模块。

### 1.2 详细规则

#### 命名转换规则
- 中文名称需要翻译成英文名称
- 文件名和文件夹名都使用小驼峰命名法 (camelCase)
- 示例：
  - "用户页面" → `user.php`
  - "产品列表" → `productList.php`
  - "订单详情" → `orderDetail.php`
  - "用户管理" → `userManagement`
  - "商品分类" → `productCategory`

#### 模块识别规则（重要改进点）
以下术语通常表示需要创建模块：
- **前端、客户端**：通常对应 `frontend` 模块
- **后端、管理端**：通常对应 `backend` 或 `admin` 模块
- **用户端**：通常对应 `user` 模块
- **API接口**：通常对应 `api` 模块

对于包含"管理"、"列表"、"增删改查"等关键词的功能模块，应优先考虑使用CRUD模板创建。

示例：
- "后端登录页面" → `handler/pages/backend/login.php`
- "前端首页" → `handler/pages/frontend/home.php`
- "管理端用户列表" → `handler/pages/admin/userList.php`
- "产品管理" → 使用CRUD模板创建`handler/pages/products/`模块

#### 文件路径规范
- 单独页面文件路径：`handler/pages/[页面名].php`（必须使用.php扩展名）
- 模块下页面文件路径：`handler/pages/[模块名]/[页面名].php`（必须使用.php扩展名）
- CSS文件路径：`public/static/own/css/[模块路径]/[页面名].css`
- JS文件路径：`public/static/own/js/[模块路径]/[页面名].js`

重要说明：
- 静态资源文件（CSS和JS）的文件名应与对应的页面文件名保持一致
- 如果是单独页面（如`about.php`），对应的静态资源文件路径为`public/static/own/css/about.css`和`public/static/own/js/about.js`
- 如果是模块下的页面（如`home/about.php`），对应的静态资源文件路径为`public/static/own/css/home/about.css`和`public/static/own/js/home/about.js`
- 静态资源目录结构应与页面目录结构完全一致

#### 页面结构规范

##### 使用布局的页面结构（强制规范）
```
<?php
// 页面描述注释

// 布局变量（必须设置，不能为空）
$layout="布局文件名";

// 设置页面特定变量（必须设置）
$pageTitle = '页面标题 - AiPHP应用';
$pageDescription = '页面描述';
$pageKeywords = '关键词1, 关键词2';

// 如果使用布局，需要通过变量引入对应的CSS和JS文件（必须设置）
$additionalCSS = ['/static/own/css/目录名/文件名.css'];
$additionalJS = ['/static/own/js/目录名/文件名.js'];

// 如果页面需要使用CSRF令牌，必须通过$additionalMeta变量添加meta标签
// $additionalMeta = '<meta name="csrf-token" content="' . htmlspecialchars($csrfToken) . '">';
?>
<div class="page-class">
    <!-- 页面HTML内容 -->
</div>
```

**强制要求（违反将导致整个任务失败）**：
1. 页面文件严禁包含以下内容：
   - `<!DOCTYPE html>`
   - `<html>` 标签
   - `<head>` 标签
   - `<body>` 标签
   - `<style>` 标签
   - `<script>` 标签（除了在PHP代码中）
   - 任何内联CSS样式
   - 任何内联JavaScript代码
2. 必须通过 [$additionalCSS](file:///c:/Users/Administrator/Desktop/AiPHP/handler/pages/news.php#L17-L17) 和 [$additionalJS](file:///c:/Users/Administrator/Desktop/AiPHP/handler/pages/news.php#L18-L18) 变量引入外部资源
3. 必须设置页面元数据变量：[$pageTitle](file:///c:/Users/Administrator/Desktop/AiPHP/handler/pages/news.php#L14-L14), [$pageDescription](file:///c:/Users/Administrator/Desktop/AiPHP/handler/pages/news.php#L15-L15), [$pageKeywords](file:///c:/Users/Administrator/Desktop/AiPHP/handler/pages/news.php#L16-L16)
4. 页面内容必须仅包含HTML片段
5. 如果页面需要使用CSRF令牌进行AJAX请求，必须通过[$additionalMeta](file:///c:/Users/Administrator/Desktop/AiPHP/handler/pages/news.php#L20-L20)变量添加meta标签，而不是直接在HTML中输出

##### 不使用布局的页面结构（重要改进点）
```
<?php
// 页面描述注释
// 不使用布局

// 页面元数据
$pageTitle = '页面标题 - AiPHP应用';
$pageDescription = '页面描述';
$pageKeywords = '关键词1, 关键词2';

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($pageKeywords); ?>">
    <!-- 引用CSS文件，禁止在页面中直接写CSS样式 -->
    <link rel="stylesheet" href="/static/own/css/目录名/文件名.css">
</head>
<body>
    <!-- 页面HTML内容 -->
    
    <!-- 引用JS文件，禁止在页面中直接写JavaScript代码 -->
    <script src="/static/own/js/目录名/文件名.js"></script>
</body>
</html>
```

**布局使用说明**：
- 使用布局：在页面顶部设置 `// 布局变量` 并赋值，如 [$layout="布局文件名";](file:///c:/Users/Administrator/Desktop/AiPHP/handler/pages/news.php#L12-L12) （不需要.php后缀）
- 禁用布局：将布局变量设置为空字符串，如 [$layout="";](file:///c:/Users/Administrator/Desktop/AiPHP/handler/pages/news.php#L12-L12)
- 修改布局：将布局变量的值更改为其他布局文件名，如 [$layout="其他布局文件名";](file:///c:/Users/Administrator/Desktop/AiPHP/handler/pages/news.php#L12-L12)

#### 创建后强制验证步骤（必须逐项完成）

在创建任何页面后，必须完成以下验证步骤：

##### 第一步：验证页面文件结构
1. [ ] 打开创建的页面文件
2. [ ] 检查是否以`<?php`开始
3. [ ] 检查是否设置了正确的`$layout`变量
4. [ ] 如果使用布局：
   - [ ] 确认文件不包含`<!DOCTYPE html>`
   - [ ] 确认文件不包含`<html>`标签
   - [ ] 确认文件不包含`<head>`标签
   - [ ] 确认文件不包含`<body>`标签
   - [ ] 确认文件不包含`<style>`标签
   - [ ] 确认文件不包含`<script>`标签（除了在PHP代码中）
5. [ ] 检查是否设置了页面元数据变量
6. [ ] 检查是否通过`$additionalCSS`和`$additionalJS`引入资源

##### 第二步：验证静态资源文件
1. [ ] 检查CSS文件是否已创建
2. [ ] 检查JS文件是否已创建
3. [ ] 检查文件路径是否与页面文件匹配

##### 第三步：验证路由配置
1. [ ] 打开`config/routes.php`
2. [ ] 检查GET路由是否已添加
3. [ ] 如需要，检查POST/PUT/DELETE路由是否已添加

##### 第四步：验证导航菜单（如适用）
1. [ ] 打开布局文件
2. [ ] 检查导航菜单是否已更新

#### 错误处理规范

如果发现以下任何情况，必须立即停止操作并报告：
1. 页面结构不符合要求
2. 文件路径不符合规范
3. 路由配置错误
4. 导航菜单更新错误

在任何情况下，都不应该尝试"修复"错误，而应该：
1. 删除已创建的所有文件
2. 报告问题
3. 重新严格按照规范执行

#### 强制检查清单（创建页面前必须阅读并遵守）
在创建任何页面之前，必须完成以下检查：

1. [ ] 确认页面类型：独立页面还是模块下页面
2. [ ] 确认是否使用布局
3. [ ] 根据是否使用布局选择正确的页面结构模板
4. [ ] 确认文件命名符合规范
5. [ ] 确认目录结构符合规范
6. [ ] 确认需要包含的功能元素（如输入框、按钮等）
7. [ ] 确认需要设置的页面元数据

### 1.3 示例

#### 创建"关于我们"页面（单独页面示例）
1. 中文名称：关于我们
2. 英文翻译：about
3. 页面路径：handler/pages/about.php
4. CSS路径：public/static/own/css/about.css
5. JS路径：public/static/own/js/about.js
6. 路由配置：在config/routes.php中添加
   ```php
   '/about' => 'about',
   ```
7. 导航菜单：在布局文件中添加
   ```html
   <li><a href="/about">关于我们</a></li>
   ```

#### 创建"联系我们"页面（单独页面示例）
1. 中文名称：联系我们
2. 英文翻译：contact
3. 页面路径：handler/pages/contact.php
4. CSS路径：public/static/own/css/contact.css
5. JS路径：public/static/own/js/contact.js
6. 路由配置：在config/routes.php中添加
   ```php
   '/contact' => 'contact',
   ```
7. 导航菜单：在布局文件中添加
   ```html
   <li><a href="/contact">联系我们</a></li>
   ```

#### 创建"后端登录"页面（模块下页面示例）
1. 中文名称：后端登录
2. 英文翻译：backend/login
3. 页面路径：`handler/pages/backend/login.php`
4. CSS路径：`public/static/own/css/backend/login.css`
5. JS路径：`public/static/own/js/backend/login.js`
6. 路由配置：在`config/routes.php`中添加
   ```php
   '/backend/login' => 'backend/login',
   ```
7. 导航菜单：在布局文件中添加
   ```html
   <li><a href="/backend/login">后台登录</a></li>
   ```

### 1.3 使用CRUD模板创建页面

#### 1.3.1 模板位置和结构
通用CRUD模板位于项目根目录的`templates/crud/`文件夹中，包含：
- `pages/`: 页面模板文件
  - `index.php`: 列表页面模板
  - `form.php`: 表单页面模板
  - `detail.php`: 详情页面模板
  - `delete.php`: 删除处理模板
  - `update_status.php`: 状态更新模板
- `assets/`: 静态资源模板
  - `css/`: CSS样式模板目录
  - `js/`: JavaScript交互模板目录
- `README.md`: 使用说明文档

#### 1.3.2 使用步骤
1. 确定模块信息：
   - 模块目录名（如：products、users）
   - 模块标题（如：产品、用户）
   - 数据库表名（如：products、users）
   - 主要字段名（用于显示记录名称，如：name、title）

2. 创建模块目录结构：
   ```bash
   # 创建页面目录
   mkdir -p handler/pages/{module_dir}
   
   # 创建静态资源目录
   mkdir -p public/static/own/css/{module_dir}
   mkdir -p public/static/own/js/{module_dir}
   ```

3. 复制模板文件：
   ```bash
   # 复制页面模板
   cp templates/crud/pages/* handler/pages/{module_dir}/
   
   # 复制CSS模板
   cp templates/crud/assets/css/* public/static/own/css/{module_dir}/
   
   # 复制JS模板
   cp templates/crud/assets/js/* public/static/own/js/{module_dir}/
   ```

4. 替换模板中的占位符：
   - `{module_dir}`: 模块目录名
   - `{module_title}`: 模块标题
   - `{table_name}`: 数据库表名
   - `{primary_field}`: 主要字段名
   - 其他特定占位符根据模板README.md说明替换

5. 根据具体需求调整模板内容

6. 配置路由

7. 更新导航菜单

#### 1.3.3 模板优势
- 快速创建功能完整的CRUD模块
- 统一的代码风格和界面设计
- 内置安全防护机制（CSRF、XSS等）
- 包含完整的用户交互功能（分页、搜索、状态切换等）
- 遵循AiPHP框架规范

### 1.4 最佳实践
1. 始终使用输出缓冲捕获页面内容
2. 使用适当的HTML实体转义函数防止XSS攻击
3. 为每个页面设置合适的meta信息
4. 保持CSS和JS文件与页面功能一致
5. 遵循框架的目录结构和命名规范
6. 在创建页面前确认是否需要使用布局文件
7. **[CRITICAL] 严格按照创建页面完整步骤执行，确保不跳过任何环节**
8. **[CRITICAL] 每完成一个步骤，都要进行验证，确保该步骤真的完成且符合要求**
9. 创建页面后及时更新路由配置以便测试
10. 若使用布局文件，记得更新导航菜单以方便访问
11. 带参数的路由应根据实际需求选择合适的参数类型（id或slug）
12. 合理使用核心类，避免重复造轮子
13. 在页面中使用核心类时，确保正确引入命名空间
14. **[CRITICAL] 模块识别：当前端、后端、管理端等词汇出现时，应将其作为模块处理**
15. **[CRITICAL] 静态资源隔离：严禁在HTML页面中直接编写CSS样式或JavaScript代码**
16. **[CRITICAL] 路径一致性：静态资源目录结构必须与页面目录结构完全一致**
17. **[CRITICAL] CRUD模板优先：对于增删改查功能模块，应优先使用`templates/crud/`目录下的通用模板创建**

## 2. 删除页面

### 2.1 删除页面执行流程

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│  第1步: 删除    │     │  第2步: 删除    │     │  第3步: 删除    │     │  第4步: 删除    │     │  第5步: 删除    │     │  第6步: 清理    │     │  第7步: 执行    │
│  路由配置       │ ──> │  页面文件       │ ──> │  静态资源文件   │ ──> │  导航菜单项     │ ──> │  空目录         │ ──> │  数据库内容     │ ──> │  完整性检查     │
└─────────────────┘     └─────────────────┘     └─────────────────┘     └─────────────────┘     └─────────────────┘     └─────────────────┘     └─────────────────┘
```

### 2.2 删除页面核心规范

**[CRITICAL] 删除页面必须严格按照以下顺序执行，不得跳过任何步骤：**

1. **第1步：删除路由配置**
   - 在 `config/routes.php` 文件中删除对应的路由条目
   - ✓ **检查点**：确认路由配置文件中不再包含该页面的路由

2. **第2步：删除页面文件**
   - 删除 `handler/pages` 目录下对应的页面文件
   - ✓ **检查点**：确认页面文件已被成功删除

3. **第3步：删除静态资源文件**
   - 删除 `public/static/own/css` 目录下对应的 CSS 文件
   - 删除 `public/static/own/js` 目录下对应的 JS 文件
   - ✓ **检查点**：确认所有静态资源文件已被成功删除

4. **第4步：删除导航菜单项**
   - 确认页面使用的布局文件（如果有）
   - 在对应布局文件的导航菜单中删除该页面的链接
   - ✓ **检查点**：确认导航菜单中不再包含该页面的链接

5. **第5步：删除空目录**
   - 检查并删除`handler/pages/模块名`空目录
   - 检查并删除`public/static/own/css/模块名`空目录
   - 检查并删除`public/static/own/js/模块名`空目录
   - **执行命令**：
     ```powershell
     # 检查并删除handler目录下的空模块目录
     $count = Get-ChildItem -Path "handler/pages/模块名" | Measure-Object | Select-Object -ExpandProperty Count
     if ($count -eq 0) { Remove-Item -Path "handler/pages/模块名" -Force -Recurse }

     # 检查并删除css目录下的空模块目录
     $count = Get-ChildItem -Path "public/static/own/css/模块名" | Measure-Object | Select-Object -ExpandProperty Count
     if ($count -eq 0) { Remove-Item -Path "public/static/own/css/模块名" -Force -Recurse }

     # 检查并删除js目录下的空模块目录
     $count = Get-ChildItem -Path "public/static/own/js/模块名" | Measure-Object | Select-Object -ExpandProperty Count
     if ($count -eq 0) { Remove-Item -Path "public/static/own/js/模块名" -Force -Recurse }
     ```
   - **[强制验证]**：删除后必须验证目录是否确实不存在

6. **第6步：清理相关数据库内容（如适用）**
   - 检查页面是否涉及数据库操作
   - 确认是否需要删除相关的数据表或记录
   - 如果需要，执行相应的数据库清理操作
   - **安全步骤**：
     1. 备份相关数据库表
     2. 在测试环境中验证清理操作
     3. 执行清理操作

7. **第7步：执行完整性检查**
   - 使用下方的"删除确认检查清单"验证所有步骤是否完成
   - ✓ **最终检查点**：确认所有相关文件和引用都已被删除

**[强制执行条款]**
- [强制] 每个步骤执行后必须进行对应的检查点验证
- [强制] 必须严格按照提示词中提供的命令示例执行操作
- [强制] 删除任何目录前必须先验证目录是否为空
- [强制] 删除操作完成后必须逐项检查"删除确认检查清单"
- [强制] 如果因未按规范操作导致问题，必须承担相应责任并重新执行
- [强制] 空目录删除必须作为删除操作的第五步执行
- [强制] 必须使用提示词中提供的命令验证目录为空后才能删除
- [强制] 删除后必须再次验证目录是否确实被删除

### 2.3 删除前准备工作

**在开始删除页面前，请完成以下准备工作：**

1. 确认要删除的页面名称和路径
2. 检查该页面是否被其他页面引用或依赖
3. 备份重要数据（如有必要）
4. 确认删除操作的影响范围

### 2.4 详细删除步骤说明

#### 第1步：删除路由配置
- 打开 `config/routes.php` 文件
- 找到并删除与该页面相关的所有路由条目
- 确保删除所有与该页面相关的 GET、POST、PUT、DELETE 等请求方法的路由
- **执行命令**：使用编辑器或 `replace_in_file` 工具删除路由条目
- **[强制验证]**：删除后必须检查路由配置文件，确认相关路由已完全删除

#### 第2步：删除页面文件及空目录
1. **删除页面文件**：
   - 确认页面文件的完整路径（`handler/pages/页面名.php` 或 `handler/pages/模块名/页面名.php`）
   - 删除对应的页面文件
   - **执行命令**：
     ```shell
     rm handler/pages/路径/页面名.php
     ```
   - **[强制验证]**：删除后必须检查文件系统，确认页面文件已不存在

2. **清理空目录**：
   - 检查并删除空的父目录（如果删除后目录为空）
   - 递归检查直到`handler/pages`根目录
   - **[强制执行]**：必须使用提示词中提供的PowerShell命令来验证和删除空目录
   - **安全步骤**：
     1. 先检查目录是否为空：
        ```powershell
        Get-ChildItem -Path handler/pages/模块名 | Measure-Object | Select-Object -ExpandProperty Count
        ```
     2. 确认输出为0后执行删除：
        ```powershell
        Remove-Item -Path handler/pages/模块名 -Force -Recurse
        ```
   - **完整示例**：
     ```powershell
     # 删除frontend空目录
     $count = Get-ChildItem -Path handler/pages/frontend | Measure-Object | Select-Object -ExpandProperty Count
     if ($count -eq 0) {
         Remove-Item -Path handler/pages/frontend -Force -Recurse
     }
     ```
   - **[强制验证]**：删除后必须检查目录结构，确认所有空目录已被清理

3. **示例**：
   - 删除`frontend/hello.php`后：
     ```shell
     rm handler/pages/frontend/hello.php
     find handler/pages/frontend -type d -empty -delete
     ```

#### 第3步：删除静态资源文件
- 删除对应的 CSS 文件：`public/static/own/css/页面名.css` 或 `public/static/own/css/模块名/页面名.css`
- 删除对应的 JS 文件：`public/static/own/js/页面名.js` 或 `public/static/own/js/模块名/页面名.js`
- 检查并删除其他与该页面相关的静态资源（如图片、字体等）
- **执行命令**：
  ```
  rm public/static/own/css/页面名.css
  rm public/static/own/js/页面名.js
  ```
- **[强制验证]**：删除后必须检查静态资源目录，确认所有相关文件已删除

#### 第4步：删除导航菜单项
- 确认页面使用的布局文件（通常在页面文件顶部有 `$layout` 变量定义）
- 打开对应的布局文件（`handler/layouts/布局名.php`）
- 在导航菜单部分找到并删除该页面的链接（`<li><a href="/页面路径">页面名称</a></li>`）
- **执行命令**：使用编辑器或 `replace_in_file` 工具删除导航菜单项
- **[强制验证]**：删除后必须检查布局文件，确认导航菜单项已移除

#### 第5步：清理相关数据库内容（如适用）
- 检查页面是否涉及数据库操作
- 确认是否需要删除相关的数据表或记录
- 如果需要，执行相应的数据库清理操作
- **安全步骤**：
  1. 备份相关数据库表
  2. 在测试环境中验证清理操作
  3. 执行清理操作
- **[强制验证]**：清理后必须验证数据库，确认相关数据已正确处理

#### 第6步：版本控制跟踪
- 使用版本控制系统提交删除操作
- 编写清晰的提交信息，说明删除的页面和原因
- 推送更改到远程仓库
- **执行命令**：
  ```bash
  git add .
  git commit -m "删除后端登录页面及相关资源"
  git push origin main
  ```
- **[强制验证]**：提交后必须检查版本控制系统，确认更改已正确记录

### 2.5 删除确认检查清单

**删除页面后，必须逐项检查以下清单，确保完全清除所有相关资源：**

- [ ] **路由配置检查**
  - [ ] 打开 `config/routes.php` 文件
  - [ ] 搜索所有与被删除页面相关的路由条目
  - [ ] 确认所有 GET、POST、PUT、DELETE 等请求方法的相关路由都已删除

- [ ] **页面文件检查**
  - [ ] 确认 `handler/pages` 目录下不再存在该页面文件
  - [ ] 如果页面位于模块目录下，确认是否需要删除整个模块目录

- [ ] **静态资源检查**
  - [ ] 确认 `public/static/own/css` 目录下不再存在该页面的 CSS 文件
  - [ ] 确认 `public/static/own/js` 目录下不再存在该页面的 JS 文件
  - [ ] 确认没有其他与该页面相关的静态资源文件

- [ ] **导航菜单检查**
  - [ ] 确认页面使用的布局文件中不再包含该页面的导航链接
  - [ ] 检查所有可能包含该页面链接的布局文件

- [ ] **功能测试检查**
  - [ ] 尝试访问已删除页面的 URL，确认系统返回 404 错误
  - [ ] 检查应用程序的其他功能是否正常运行

- [ ] **数据库相关检查**
  - [ ] 确认是否需要清理与页面相关的数据库表或记录
  - [ ] 检查是否有其他页面依赖被删除页面的数据库内容

- [ ] **空目录清理验证**（强化）
  - [ ] 使用以下命令验证无空目录：
    ```powershell
    Get-ChildItem -Path "handler/pages","public/static/own/css","public/static/own/js" -Recurse -Directory | 
    Where-Object { (Get-ChildItem -Path $_.FullName).Count -eq 0 } |
    ForEach-Object { Write-Output "空目录: $($_.FullName)" }
    ```
  - [ ] 确认输出中不包含任何与删除页面相关的目录
  - [ ] 如发现空目录，必须重新执行删除操作

**[强制完成条款]**
- [强制] 所有检查项必须标记为完成（[x]）才能视为删除操作完成
- [强制] 如果任何检查项未通过，必须立即修复并重新检查
- [强制] 删除操作完成后必须由另一人复查确认

### 2.6 常见错误和解决方法

1. **忘记删除导航菜单项**
   - **问题**：删除页面后，导航菜单中仍然显示该页面的链接
   - **解决方法**：检查所有布局文件，找到并删除对应的导航菜单项

2. **忘记删除静态资源**
   - **问题**：删除页面后，静态资源文件仍然存在
   - **解决方法**：使用搜索功能找到所有与该页面相关的静态资源文件并删除

3. **删除后 404 错误**
   - **问题**：删除页面后，访问其他页面出现 404 错误
   - **解决方法**：检查路由配置是否正确更新，确保没有误删其他页面的路由

4. **删除后应用程序错误**
   - **问题**：删除页面后，应用程序出现错误
   - **解决方法**：检查是否有其他页面依赖被删除的页面，更新相关依赖

5. **未按规范删除空目录**
   - **问题**：删除页面后，仍有空的模块目录存在
   - **解决方法**：
     1. 严格按照提示词中提供的PowerShell命令检查和删除空目录
     2. 不仅检查`handler/pages`目录，还要检查`public/static/own/css`和`public/static/own/js`目录
     3. 使用以下命令检查所有空目录：
        ```powershell
        Get-ChildItem -Path "handler/pages" -Recurse -Directory | ForEach-Object { $count = (Get-ChildItem -Path $_.FullName | Measure-Object).Count; if ($count -eq 0) { Write-Output "Empty directory: $($_.FullName)" } }
        Get-ChildItem -Path "public/static/own/css" -Recurse -Directory | ForEach-Object { $count = (Get-ChildItem -Path $_.FullName | Measure-Object).Count; if ($count -eq 0) { Write-Output "Empty directory: $($_.FullName)" } }
        Get-ChildItem -Path "public/static/own/js" -Recurse -Directory | ForEach-Object { $count = (Get-ChildItem -Path $_.FullName | Measure-Object).Count; if ($count -eq 0) { Write-Output "Empty directory: $($_.FullName)" } }
        ```
     4. 使用以下命令删除所有空目录：
        ```powershell
        Get-ChildItem -Path "handler/pages" -Recurse -Directory | ForEach-Object { $count = (Get-ChildItem -Path $_.FullName | Measure-Object).Count; if ($count -eq 0) { Remove-Item -Path $_.FullName -Force -Recurse } }
        Get-ChildItem -Path "public/static/own/css" -Recurse -Directory | ForEach-Object { $count = (Get-ChildItem -Path $_.FullName | Measure-Object).Count; if ($count -eq 0) { Remove-Item -Path $_.FullName -Force -Recurse } }
        Get-ChildItem -Path "public/static/own/js" -Recurse -Directory | ForEach-Object { $count = (Get-ChildItem -Path $_.FullName | Measure-Object).Count; if ($count -eq 0) { Remove-Item -Path $_.FullName -Force -Recurse } }
        ```

### 2.7 删除页面示例

#### 示例：删除"关于我们"页面

**第1步：删除路由配置**
```
// 在 config/routes.php 中删除以下行
'/about' => 'about',
```

**第2步：删除页面文件**
```
rm handler/pages/about.php
```

**第3步：删除静态资源文件**
```
rm public/static/own/css/about.css
rm public/static/own/js/about.js
```

**第4步：删除导航菜单项**
在布局文件（如 `handler/layouts/main.php`）中删除：
```
<li><a href="/about">关于我们</a></li>
```

**第5步：执行完整性检查**
- 检查路由配置：确认 `/about` 路由已删除
- 检查页面文件：确认 `about.php` 已删除
- 检查静态资源：确认 `about.css` 和 `about.js` 已删除
- 检查导航菜单：确认"关于我们"链接已从导航菜单中删除
- 功能测试：访问 `/about` 确认返回 404 错误

### 2.8 最佳实践

1. **始终按顺序执行删除步骤**，不要跳过任何步骤
2. **每完成一个步骤后进行验证**，确保该步骤已正确完成
3. **在删除前备份重要数据**，特别是包含用户数据的页面
4. **删除后进行全面测试**，确保应用程序的其他功能正常运行
5. **记录删除操作**，包括删除的页面名称、路径和删除原因
6. **检查依赖关系**，确保删除操作不会影响其他页面或功能
7. **使用搜索功能**，确保删除所有与该页面相关的文件和引用
8. **清理空目录**，删除页面后检查并删除空的模块目录
9. **功能验证**，通过访问已删除页面的 URL 确认系统返回 404 错误
10. **数据库清理**，如果页面涉及数据库操作，需要清理相关的数据表或记录
11. **[强制遵守]**，严格按照提示词中的规范执行所有操作，不得跳过任何检查点
12. **[强制验证]**，每个步骤完成后必须进行对应的验证检查
13. **[强制复查]**，删除操作完成后必须由另一人复查确认
