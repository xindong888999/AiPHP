<?php
// 用户状态更新页面

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
    
    // 获取用户ID和状态
    $userId = isset($input['id']) ? intval($input['id']) : 0;
    $status = isset($input['status']) ? intval($input['status']) : -1;
    
    // 验证参数
    if ($userId <= 0) {
        $response['message'] = '无效的用户ID';
        echo json_encode($response);
        exit;
    }
    
    if ($status !== 0 && $status !== 1) {
        $response['message'] = '无效的状态值';
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
    $oldStatus = $user->status;
    
    // 更新状态
    $user->status = $status;
    $user->updated_at = date('Y-m-d H:i:s');
    R::store($user);
    
    // 验证更新是否成功
    $updatedUser = R::load('users', $userId);
    if ($updatedUser->status !== $status) {
        $response['message'] = '状态更新失败，请重试';
        echo json_encode($response);
        exit;
    }
    
    // 更新成功
    $statusText = $status == 1 ? '活跃' : '禁用';
    $response['success'] = true;
    $response['message'] = "用户 \"{$userInfo}\" 状态已更新为 {$statusText}";
    $response['data'] = [
        'user_id' => $userId,
        'username' => $userInfo,
        'old_status' => $oldStatus,
        'new_status' => $status
    ];
    
} catch (Exception $e) {
    $response['message'] = '状态更新失败，请重试';
}

echo json_encode($response);
exit;