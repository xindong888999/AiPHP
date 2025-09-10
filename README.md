
<div align="center">
  <h2>AiPHP 框架1.0: The Unframework for the AI Era</h2>
  <p><strong>核心标语：Code in Plain Language. Get Perfect PHP.</strong></p>
  <p>（用自然语言编码，获得完美的PHP。）</p> 
  <p>（全球第一个全面支持Ai编程的PHP框架）</p>
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
.codebuddy
.codebuddy\.rules
.augment
.augment\rules
.augment\rules\imported
.comate
.cursor
.cursor\rules
.gemin
.kiro
.kiro\steering
.qoder
.qoder\rules
.trae
.trae\rules
常用的Ai编程工具的规则文件都已经放好,只需要在对应的编程工具指定即可.

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


