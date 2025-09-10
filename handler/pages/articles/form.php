<?php
// 文章表单页面（创建和编辑）

// 引入必要的类
use Core\OtherLibrary\RedBean\RedBeanFacade as R;
use Core\OwnLibrary\Security\CsrfTokenManager;

// 布局变量
$layout = "green_layout";

// 页面元数据
$pageTitle = '文章表单 - AiPHP应用';
$pageDescription = '创建或编辑文章信息';
$pageKeywords = '文章表单, 创建文章, 编辑文章';

// 引入CSS和JS文件
$additionalCSS = ['/static/own/css/articles/form.css'];
$additionalJS = ['/static/own/js/articles/form.js'];

// 初始化RedBean
R::initialize();

// 实例化CSRF令牌管理器
$csrfManager = new CsrfTokenManager();

// 生成CSRF令牌
$csrfToken = $csrfManager->getToken();

// 获取ID（用于编辑模式）
$articleId = isset($_GET['id']) ? intval($_GET['id']) : (isset($params['id']) ? intval($params['id']) : 0);
$isEdit = $articleId > 0;

// 初始化数据
$article = null;
if ($isEdit) {
    // 加载现有记录（编辑模式）
    $article = R::load('articles', $articleId);
    if (!$article || !$article->id) {
        header('Location: /articles?error=article_not_found');
        exit;
    }
    
    // 设置页面标题
    $pageTitle = '编辑文章 - AiPHP应用';
} else {
    // 设置页面标题
    $pageTitle = '创建文章 - AiPHP应用';
    
    // 初始化空对象
    $article = R::dispense('articles');
}

// 初始化表单数据和错误
$formData = [
    'title' => $isEdit ? $article->title : '',
    'content' => $isEdit ? $article->content : '',
    'status' => $isEdit ? $article->status : 1
];

$errors = [];
$error = '';
$success = '';

// 从会话中获取错误信息和表单数据（如果有）
// 只有在会话未启动时才启动会话，避免与CsrfTokenManager中的会话启动冲突
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['form_errors'])) {
    $errors = $_SESSION['form_errors'];
    unset($_SESSION['form_errors']);
}
if (isset($_SESSION['form_data'])) {
    $formData = $_SESSION['form_data'];
    unset($_SESSION['form_data']);
}

// 获取当前页码
$returnPage = intval($_GET['page'] ?? 1);
$backUrl = $returnPage > 1 ? "/articles?page={$returnPage}" : "/articles";

// 将CSRF令牌添加到额外的meta标签中
$additionalMeta = '<meta name="csrf-token" content="' . htmlspecialchars($csrfToken) . '">';
?>
<div class="articles-form">
    <div class="page-header">
        <h1><?php echo $isEdit ? '编辑文章' : '创建文章'; ?></h1>
        <a href="<?php echo $backUrl; ?>" class="btn btn-secondary">返回列表</a>
    </div>
    
    <?php if ($error): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>
    
    <!-- 表单 -->
    <form method="POST" action="/articles/save">
        <!-- CSRF令牌 -->
        <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
        
        <!-- 返回页码 -->
        <input type="hidden" name="page" value="<?php echo $returnPage; ?>">
        
        <!-- 文章ID (编辑模式) -->
        <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?php echo $article->id; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="title">文章标题 <span class="required">*</span></label>
            <input 
                type="text" 
                id="title" 
                name="title" 
                class="form-control <?php echo isset($errors['title']) ? 'error' : ''; ?>" 
                value="<?php echo htmlspecialchars($formData['title']); ?>" 
                placeholder="请输入文章标题"
            >
            <?php if (isset($errors['title'])): ?>
            <div class="error-message"><?php echo htmlspecialchars($errors['title']); ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="content">文章内容 <span class="required">*</span></label>
            <textarea 
                id="content" 
                name="content" 
                class="form-control <?php echo isset($errors['content']) ? 'error' : ''; ?>" 
                rows="10" 
                placeholder="请输入文章内容"><?php echo htmlspecialchars($formData['content']); ?></textarea>
            <?php if (isset($errors['content'])): ?>
            <div class="error-message"><?php echo htmlspecialchars($errors['content']); ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="status">状态</label>
            <select id="status" name="status" class="form-control">
                <option value="1" <?php echo $formData['status'] == 1 ? 'selected' : ''; ?>>启用</option>
                <option value="0" <?php echo $formData['status'] == 0 ? 'selected' : ''; ?>>禁用</option>
            </select>
        </div>
        
        <div class="form-actions">
            <a href="<?php echo $backUrl; ?>" class="btn btn-secondary">取消</a>
            <button type="submit" class="btn btn-primary"><?php echo $isEdit ? '更新' : '创建'; ?>文章</button>
        </div>
    </form>
</div>