<?php
// 用户删除处理页面

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
        // 如果请求体中没有，尝试从请求头中获取
        $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    }
    
    if (empty($csrfToken)) {
        $response['message'] = '缺少CSRF令牌';
        echo json_encode($response);
        exit;
    }
    
    // 手动验证CSRF令牌
    if (!$csrfManager->validateToken($csrfToken, 'default', false)) {
        $response['message'] = 'CSRF验证失败，请刷新页面后重试';
        echo json_encode($response);
        exit;
    }
    
    // 获取用户ID - 从JSON请求体获取
    $userId = 0;
    if (isset($input['id'])) {
        $userId = intval($input['id']);
    }
    
    // 验证ID
    if ($userId <= 0) {
        $response['message'] = '无效的用户ID';
        echo json_encode($response);
        exit;
    }
    
    // 查找用户
    $user = R::load('users', $userId);
    
    if (!$user || !$user->id) {
        $response['message'] = '用户不存在';
        echo json_encode($response);
        exit;
    }
    
    // 保存用户信息用于响应
    $userInfo = $user->username;
    
    // 删除用户
    R::trash($user);
    
    // 验证删除是否成功 - 使用count方法更准确地检查
    $userCount = R::count('users', 'id = ?', [$userId]);
    if ($userCount > 0) {
        $response['message'] = '删除失败，请重试';
        echo json_encode($response);
        exit;
    }
    
    // 删除成功
    $response['success'] = true;
    $response['message'] = "用户 \"{$userInfo}\" 删除成功";
    $response['data'] = [
        'deleted_id' => $userId,
        'deleted_info' => $userInfo
    ];
    
} catch (Exception $e) {
    $response['message'] = '删除失败: ' . $e->getMessage();
}

echo json_encode($response);
exit;