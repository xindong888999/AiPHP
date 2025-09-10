<?php
// 文章删除处理页面

use Core\OtherLibrary\RedBean\RedBeanFacade as R;
use Core\OwnLibrary\Security\CsrfTokenManager;

// 设置JSON响应头
header('Content-Type: application/json; charset=utf-8');

// 初始化响应
$response = [
    'success' => false,
    'message' => '',
    'data' => null
];

try {
    // 初始化RedBean
    R::initialize();
    
    // 实例化CSRF令牌管理器
    $csrfManager = new CsrfTokenManager();
    
    // 检查请求方法
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $response['message'] = '只允许POST请求';
        echo json_encode($response);
        exit;
    }
    
    // 获取原始输入数据
    $input = json_decode(file_get_contents('php://input'), true);
    
    // 验证CSRF令牌 - 从JSON请求体中获取
    $csrfToken = isset($input['_csrf_token']) ? $input['_csrf_token'] : '';
    
    if (empty($csrfToken)) {
        // 如果请求体中没有，尝试使用CSRF管理器的验证方法
        if (!$csrfManager->validateRequestToken()) {
            $response['message'] = 'CSRF验证失败，请刷新页面后重试';
            echo json_encode($response);
            exit;
        }
    } else {
        // 手动验证CSRF令牌
        if (!$csrfManager->validateToken($csrfToken, 'default', false)) {
            $response['message'] = 'CSRF验证失败，请刷新页面后重试';
            echo json_encode($response);
            exit;
        }
    }
    
    // 获取ID - 从JSON请求体获取
    $articleId = 0;
    if (isset($input['id'])) {
        $articleId = intval($input['id']);
    }
    
    // 验证ID
    if ($articleId <= 0) {
        $response['message'] = '无效的文章ID';
        echo json_encode($response);
        exit;
    }
    
    // 查找记录
    $article = R::load('articles', $articleId);
    
    if (!$article || !$article->id) {
        $response['message'] = '文章不存在';
        echo json_encode($response);
        exit;
    }
    
    // 执行删除操作
    R::trash($article);
    
    // 设置成功响应
    $response['success'] = true;
    $response['message'] = '文章删除成功';
    $response['data'] = ['id' => $articleId];
    
} catch (Exception $e) {
    // 设置错误响应
    $response['message'] = '删除失败：' . $e->getMessage();
}

// 返回JSON响应
echo json_encode($response);
exit;