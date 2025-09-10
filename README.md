# AiPHP 框架1.0

<div align="center">
  <h2>AiPHP 框架1.0: The Unframework for the AI Era</h2>
  <p><strong>核心标语：Code in Plain Language. Get Perfect PHP.</strong></p>
  <p>（用自然语言编码，获得完美的PHP。）</p>
</div>

---

## 宣言：告别幻觉与不一致，迎接可靠的AI编程

> 你是否曾厌倦向AI重复相同的指令？是否担心生成的代码风格混乱、漏洞百出？是否希望只需关注“做什么”，而无需操心“怎么写”？

AiPHP 框架1.0 终结了这一切。我们不仅解构了框架，更重构了AI辅助开发的工作流。这是一场致力于彻底消除AI幻觉、实现代码绝对规范化的范式革命。

---

## 核心哲学

### 零学习成本
你不必学习任何框架特有的API或概念。你的需求，用自然语言描述，即是规范。

### 绝对一致性
无论代码由谁（或哪个AI）生成，无论项目多么庞大，代码风格、安全 practices、错误处理方式都将保持完美统一。

### AI标准化
我们通过一套精心设计的 AI_PROMPT 规范，将你的自然语言意图“编译”成AI所能理解的精确指令，从根本上保证输出质量。

---

## 独创架构：RPC — 为AI生成而优化

我们的 RPC (Route-Page-Class) 架构是达成“绝对一致性”的基石。它为AI提供了最清晰、最不容出错的代码生成模板。

---

## AiPHP 框架1.0 如何解决AI的核心痛点？

### 1. 消除幻觉 (Hallucination)
- **约束性环境**：AI只需在 Page 和 Class 的固定范式下生成代码，大幅减少了“自由发挥”而出错的空间。
- **精准的上下文**：AI_PROMPT 提供了极其严格的上下文（端点、输入、流程、响应、错误），像一份严谨的产品需求文档，牢牢锁定了AI的生成范围。

### 2. 杜绝不一致 (Inconsistency)
- **内置的生成规范**：AiPHP 框架1.0 提供了一套官方的、最强的 AI_PROMPT 模板库。无论是处理身份认证、数据库操作还是API响应，都有对应的最佳实践提示词。
- **跨团队与跨AI工具的统一**：团队共享同一套提示词规范，这意味着即使使用不同品牌的AI工具（如ChatGPT、Claude、Copilot），生成的代码结构和质量也高度一致。新成员 onboarding 的成本降至极低。

### 3. 真正的零学习成本
- **开发即是描述**：你的开发过程不再是编写代码，而是精确地描述功能需求。你只需说：“创建一个需要JWT认证的用户信息获取接口，成功返回用户数据，失败返回401错误。”
- **AiPHP 框架1.0 的 AI_PROMPT 规范**会负责将你的描述转化为AI能完美执行的指令，并输出完全符合 AiPHP 框架1.0 架构的、生产就绪的代码。

---

## AI赋能：你的描述，即是规范

```php
<?php
// AI_PROMPT: [USER_PROFILE_GET]
// - ENDPOINT: GET /user/profile
// - AUTH: MUST validate JWT from `Authorization: Bearer <token>` header.
// - PROCESS: Decode JWT to get user_id, fetch user info from DB, hide password field.
// - RESPONSE: 200 + JSON user object.
// - ERROR: 401 if unauthorized, 404 if user not found, 500 on server error.
// - STYLE: Use prepared statements. Response JSON must include `code`, `data`, `msg` fields.
?>
```

无论背后的AI模型如何变化，这段提示词所生成的 `/user/profile.php` 和 `UserProfileGetter` 类都将遵循完全一致的规范和风格。

---

## 开始你的革命

你的新工作流程：

1. **构思**：用中文或英文描述下一个功能。
2. **自动**：AI从 AiPHP 框架1.0 提示词库找到匹配的 AI_PROMPT 模板，填充你的业务细节。
3. **生成**：将其交给任何你喜欢的AI助手。
4. **审核与交付**：审核生成的可直接运行的代码，重点关注业务逻辑而非风格细节。

你从未如此强大，也从未如此轻松。

---

## 加入我们，定义未来

AiPHP 框架1.0 是为实用主义者准备的。为那些受够了AI的不确定性，却又渴望其强大能力的开发者准备的。如果你相信，AI编程的未来应该是可靠、一致、且极度简单的，那么这里就是你的归宿。

> **AiPHP 框架1.0：用自然语言指挥，产出规范代码。**

---

## 开发约定（必读）

### 编程工具规则
不管是trae\qoder\kiro\codebuddy还是别的编程工具，都要在规则里写入：每一次回答都要先读取这个规则文件。

**IF** 用户的指令包含以下任意关键词：['页面', '核心', '布局', '提示词', '测试', '路由', '文件', '数据库', '创建', '修改', '删除', '添加', '更新', '重构', '第三方', '类库', '集成', '增删改查', 'CRUD']
**THEN**
你 **必须 (MUST)** 严格执行以下工作流，**在任何情况下 (UNDER NO CIRCUMSTANCES)** 都禁止跳过任何步骤。

**第一步：读取总纲**
- 必须首先读取`prompts/master_guidelines.md`作为项目的总纲
- 深入理解其中的框架规范、目录结构和核心原则
- 将总纲的原则作为所有操作的基础指导

**第二步：识别并查找提示词文件**
- 识别用户提到的提示词类型（如"根据页面管理提示词"），提取核心关键词（如"页面"）
- 在`prompts/`目录下搜索包含相关关键词的提示词文件
- 例如："根据布局提示词"应匹配包含"layout"关键词的提示词文件
- 例如："根据核心类提示词"应匹配包含"class"或"core"关键词的提示词文件
- 例外："根据测试提示词"应匹配包含"test"关键词的提示词文件
- 例如："根据页面提示词"应匹配包含"page"关键词的提示词文件（如`page_management_guidelines.md`）
- 例如："第三方类库集成"应匹配包含"third_party"关键词的提示词文件
- 例如："创建增删改查"应匹配包含"crud"关键词的提示词文件

**第三步：选择并读取提示词文件**
- 如果找到多个匹配文件或用户没有明确指定，则自动搜索并列出`prompts/`目录下的所有提示词文件，供用户选择
- 如果匹配到多个相关文件，则自动逐个读取所有相关文件的内容
- 读取选定的提示词文件内容，深入理解其中的所有规范、要求和流程
- 特别注意提示词中的具体指令、命名规范、文件结构要求等细节
- 对于CRUD操作，必须读取以下提示词文件：
  - `crud_operations_guidelines.md` - CRUD操作规范
  - `page_validation_csrf_guidelines.md` - 页面验证与CSRF防护
  - `validation_guidelines.md` - 表单验证规范

**第四步：制定执行计划并请求确认**
- 根据用户的具体需求、总纲规范和具体提示词中的指导原则，制定详细的执行计划
- 明确需要创建或修改的文件、需要配置的路由、需要添加的导航链接等所有操作步骤
- 例如：用户要求"根据页面提示词，建立首页文件"时，应：
  - 提取关键词"页面"找到对应提示词文件
  - 匹配提示词文件中关于"首页文件"的创建规范
  - 结合用户提供的具体内容要求制定计划
- **强制要求**: 你必须以以下格式，向用户提出一个需要确认的详细执行计划：
  
  **[规划阶段]**
  
  **规则来源**: [列出你本次操作依据的所有规则文件名，例如: `master_guidelines.md`, `page_management_guidelines.md`]
  **触发关键词**: [用户指令中触发此规划的关键词，例如: '创建页面']
  
  **执行步骤**:
  1. [步骤一的详细描述]
  2. [步骤二的详细描述]
  3. ...
  
  **等待您的确认...**

**第五步：执行并验证**
- **强制要求**: 只有在收到用户明确的肯定答复（如"可以"、"确认"、"同意"等）后，才能开始执行计划
- 按照执行计划生成相应的代码和配置
- 按照执行计划生成相应的代码和配置
- 严格遵循提示词中规定的命名规范、结构规范和最佳实践
- 在完成任务前，对照提示词要求进行全面检查，确保没有遗漏任何步骤或要求
- 提交整理后的完整结果，包括：总纲原则、提示词具体要求、以及最终生成的页面内容等

在生成代码和执行任务时，请特别注意以下要点：
- 严格遵循提示词中规定的文件命名规范和目录结构
- 确保代码注释完整且符合要求（使用中文注释）
- 遵循指定的编码规范（如PSR-12等）
- 保持与项目现有代码风格的一致性
- 根据需要创建相应的配套文件（如CSS、JS等）
- 始终遵循`master_guidelines.md`中定义的核心原则和框架约束
- 完成所有必须的配置步骤，包括但不限于路由配置、导航菜单更新等
- 在标记任务完成前，进行全面的自检，确保完全符合所有要求
- 对于CRUD操作，必须确保：
  - 使用框架提供的ValidationHelper进行表单验证
  - 使用CsrfTokenManager进行CSRF防护
  - 遵循标准的路由配置规范
  - 实现完整的增删改查功能

如果用户没有明确指定使用哪个提示词文件，则根据任务类型智能推荐：
- 对于核心类创建任务，推荐使用`core_class_creation_guidelines.md`
- 对于布局文件创建任务，推荐使用`layout_file_guidelines.md`
- 对于测试相关任务，推荐使用`test_guidelines.md`
- 对于第三方类库集成任务，推荐使用`third_party_integration_guidelines.md`
- 对于CRUD操作任务，推荐使用`crud_operations_guidelines.md`
- 对于其他任务，推荐参考`master_guidelines.md`

系统会随着项目发展不断添加新的提示词文件，因此需要动态搜索和识别`prompts/`目录下的所有文件。

### 基础文档规范
- **必须先读**（每次任务均需遵循）：`docs/manifest.json` → `prompts/page_management_guidelines.md`（若涉及页面） → `docs/callable_contracts.md` → `docs/framework_flow.md`
- **可调用契约约定**：`docs/callable_contracts.md`
- **框架流程说明**：`docs/framework_flow.md`

---

## 快速开始

1. **入口**：`public/index.php`
2. **引导**：`config/bootstrap.php`
3. **路由**：`config/routes.php` → `Core\OwnLibrary\Routing\Router`
4. **页面**：`handler/pages`（页面末尾 `require` 布局）
5. **布局**：`handler/layouts`
6. **静态资源**：`public/static/own/{css,js}`，与页面/布局同名


