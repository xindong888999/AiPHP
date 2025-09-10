<?php
// 文章保存页面

// 引入必要的类
use Core\OtherLibrary\RedBean\RedBeanFacade as R;
use Core\OwnLibrary\Security\CsrfTokenManager;
use Core\OwnLibrary\Validation\ValidationHelper;

// 初始化RedBean
R::initialize();

// 实例化CSRF令牌管理器
$csrfManager = new CsrfTokenManager();

// 获取ID（用于编辑模式）
$articleId = isset($_POST['id']) ? intval($_POST['id']) : 0;
$isEdit = $articleId > 0;

// 获取当前页码
$returnPage = intval($_POST['page'] ?? 1);
$backUrl = $returnPage > 1 ? "/articles?page={$returnPage}" : "/articles";

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 验证CSRF令牌
    if (!$csrfManager->validateRequestToken()) {
        // 正确处理URL参数连接，避免格式错误
        $csrfRedirectUrl = strpos($backUrl, '?') !== false ? 
            "{$backUrl}&error=csrf_validation_failed" : 
            "{$backUrl}?error=csrf_validation_failed";
        header("Location: {$csrfRedirectUrl}");
        exit;
    }
    
    // 获取表单数据
    $formData = [
        'title' => trim($_POST['title'] ?? ''),
        'content' => trim($_POST['content'] ?? ''),
        'status' => intval($_POST['status'] ?? 0)
    ];
    
    // 收集参数进行验证
    $params = [
        'title' => $formData['title'],
        'content' => $formData['content']
    ];
    
    // 验证字段
    $titleValidation = ValidationHelper::validate($params, 'title', [
        'required' => '请输入文章标题',
        'min_length' => '文章标题至少需要2个字符',
        'max_length' => '文章标题不能超过200个字符'
    ]);
    
    $contentValidation = ValidationHelper::validate($params, 'content', [
        'required' => '请输入文章内容',
        'min_length' => '文章内容至少需要10个字符'
    ]);
    
    // 合并所有验证结果
    $errors = array_merge($titleValidation, $contentValidation);
    
    // 自定义验证
    // 检查标题唯一性
    if (empty($errors)) {
        $existingArticle = R::findOne('articles', 'title = ? AND id != ?', [$formData['title'], $articleId]);
        if ($existingArticle) {
            $errors['title'] = '文章标题已存在';
        }
    }
    
    // 检查是否有验证错误
    if (!empty($errors)) {
        // 将错误信息和表单数据存储在会话中
        // 只有在会话未启动时才启动会话，避免与CsrfTokenManager中的会话启动冲突
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data'] = $formData;
        
        // 重定向回表单页面
        $redirectUrl = $isEdit ? "/articles/form/{$articleId}?page={$returnPage}" : "/articles/form?page={$returnPage}";
        header("Location: {$redirectUrl}");
        exit;
    } else {
        // 如果没有错误，保存
        try {
            if ($isEdit) {
                // 更新现有记录
                $article = R::load('articles', $articleId);
                $article->title = $formData['title'];
                $article->content = $formData['content'];
                $article->status = $formData['status'];
                $article->updated_at = date('Y-m-d H:i:s');
                $id = R::store($article);
            } else {
                // 创建新记录
                $article = R::dispense('articles');
                $article->title = $formData['title'];
                $article->content = $formData['content'];
                $article->status = $formData['status'];
                $article->created_at = date('Y-m-d H:i:s');
                $article->updated_at = date('Y-m-d H:i:s');
                $id = R::store($article);
            }
            
            // 重定向到列表页并显示成功消息
            $successType = $isEdit ? 'updated' : 'created';
            // 正确处理URL参数连接，避免格式错误
            $redirectUrl = strpos($backUrl, '?') !== false ? 
                "{$backUrl}&success={$successType}" : 
                "{$backUrl}?success={$successType}";
            header("Location: {$redirectUrl}");
            exit;
        } catch (Exception $e) {
            // 正确处理URL参数连接，避免格式错误
            $errorRedirectUrl = strpos($backUrl, '?') !== false ? 
                "{$backUrl}&error=" . urlencode('保存失败：' . $e->getMessage()) : 
                "{$backUrl}?error=" . urlencode('保存失败：' . $e->getMessage());
            header("Location: {$errorRedirectUrl}");
            exit;
        }
    }
} else {
    // 如果不是POST请求，重定向到列表页
    header("Location: {$backUrl}");
    exit;
}
?>