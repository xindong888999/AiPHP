## 可调用契约约定（AI 原生）

本约定用于确保类/函数/页面/静态资源具有稳定、可预测、可被 AI 可靠消费与生成的接口与命名规则。目标：低心智负担、零隐式魔法、强可解释性、易回放。

### 命名约定

- **类名**: 使用大驼峰，名词或名词短语，表达职责边界。例如：`Router`、`Dispatcher`、`EnhancedParameterValidator`。
- **命名空间**: 从根按物理目录映射，层级少且语义直观。例如：`Core\OwnLibrary\Routing`、`Core\OwnLibrary\Dispatcher`、`Core\OwnLibrary\Validation`。
- **文件名**: 与类名一致，单类单文件。例如：`Router.php`、`Dispatcher.php`。
- **函数/方法名**: 小驼峰，动宾短语，直述行为。例如：`match()`、`loadPage()`、`validateRange()`。
- **变量名**: 小驼峰，语义明确，不使用缩写。例如：`pageTitle`、`pageDescription`、`additionalCSS`、`additionalJS`、`params`。
- **常量/路径占位**: 使用全大写加下划线，语义稳定。例如：`LAYOUTS_PATH`（页面中引用）。

### 页面文件契约（handler/pages/*.php）

- 页面文件顶部用于请求处理与变量准备；中部为 HTML 输出缓冲；末尾统一 `require` 布局文件。
- 路由参数会以两种方式提供：
  - 作为同名变量（来源于 `Dispatcher::dispatch()` 的 `extract($params)`）。例如：`$id`、`$slug`。
  - 作为完整数组：`$params`。
- 页面可（非必须）设置的标准变量：
  - `$pageTitle`: string，页面标题。
  - `$pageDescription`: string，页面描述。
  - `$additionalCSS`: string[]，额外样式表 URL 列表（可含绝对或以 `/` 开头的相对路径）。
  - `$additionalJS`: string[]，额外脚本 URL 列表。
  - `$content`: string，页面主体 HTML（通过输出缓冲生成）。
- 页面与布局交互：
  - 页面末尾必须设置 `$content` 并 `require LAYOUTS_PATH . '/main.php'`（或其他布局）。
  - 布局文件可安全读取上述标准变量，缺省时需有合理默认值。

示例（精简片段）：

```php
<?php
$pageTitle = '示例页面';
$pageDescription = '演示页面契约';

// 路由参数既可用 $id，也可用 $params['id']
$userId = $id ?? ($params['id'] ?? null);

// 业务与校验...
ob_start();
?>
<div class="content">
  <h1><?php echo htmlspecialchars($pageTitle, ENT_NOQUOTES, 'UTF-8'); ?></h1>
</div>
<?php
$content = ob_get_clean();
require LAYOUTS_PATH . '/main.php';
```

### 错误结构契约

- 参数校验方法返回统一结构：
  - 成功：`['valid' => true]`
  - 失败：`['valid' => false, 'error' => string]`
- 页面级错误呈现：
  - 建议将错误信息渲染为清晰可见的块元素，类名统一为 `.error`，内容来自上述 `error` 字段。
- 路由/页面不存在：
  - 当请求文件不存在时，`Dispatcher` 会尝试加载 `handler/pages/404.php`；若仍不存在，输出内置 404 HTML，HTTP 状态码为 `404`。

### 静态资源命名与加载约定

- 基础路径：
  - CSS：`/static/own/css/`
  - JS：`/static/own/js/`
- 页面对应资源（同名规则）：
  - 页面 `handler/pages/home.php` 对应：
    - CSS：`/static/own/css/home.css`
    - JS：`/static/own/js/home.js`
  - 子目录页面 `handler/pages/contact/index.php` 对应：
    - CSS：`/static/own/css/contact/index.css`
    - JS：`/static/own/js/contact/index.js`
- 布局对应资源（同名规则）：
  - 布局 `handler/layouts/main.php` 对应：
    - CSS：`/static/own/css/main.css`
    - JS：`/static/own/js/main.js`
- 追加资源：
  - 页面可通过 `$additionalCSS`、`$additionalJS` 在布局中追加加载，布局需遍历并按顺序注入。

### 安全最小集合（约定）

- 路由参数：已在 `Router` 中进行长度限制、危险序列移除与 `htmlspecialchars` 处理。
- 页面输出：对来自用户/参数的直接输出，建议再次进行 `htmlspecialchars` 处理，字符集统一 `UTF-8`。
- 路径构造：`Dispatcher` 会清理 `../` 等相对路径，禁止目录遍历。

### 约定的演进原则

- 新约定必须满足：
  - 对 AI 友好（可预测/可发现/易生成/易回放）。
  - 对人类友好（直观命名、最少层级、缺省安全）。
  - 后向兼容（已有页面/布局在不改动下仍可运行）。

### 快速检查清单（给 AI / 人类）

- 是否按页面-布局-静态资源同名规则放置？
- 页面是否设置了 `$content` 并 `require` 了布局？
- 是否使用了统一的 `$pageTitle`、`$pageDescription`、`$additionalCSS`、`$additionalJS`？
- 参数校验是否返回 `['valid' => bool, 'error' => string|null]`？
- 错误展示是否使用了 `.error` 类块并来源于 `error` 字段？
- 是否避免了未转义的用户输入直出？


