<?php
// {module_title}状态更新页面

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
    
    // 获取ID和状态
    ${module_dir_singular}Id = isset($input['id']) ? intval($input['id']) : 0;
    $status = isset($input['status']) ? intval($input['status']) : -1;
    
    // 验证参数
    if (${$module_dir_singular}Id <= 0) {
        $response['message'] = '无效的{module_title}ID';
        echo json_encode($response);
        exit;
    }
    
    if ($status !== 0 && $status !== 1) {
        $response['message'] = '无效的状态值';
        echo json_encode($response);
        exit;
    }
    
    // 查找记录
    ${module_dir_singular} = R::load('{table_name}', ${module_dir_singular}Id);
    if (!${module_dir_singular} || !${module_dir_singular}->id) {
        $response['message'] = '{module_title}不存在';
        echo json_encode($response);
        exit;
    }
    
    // 保存信息用于响应
    ${module_dir_singular}Info = ${module_dir_singular}->{primary_field};
    $oldStatus = ${module_dir_singular}->status;
    
    // 更新状态
    ${module_dir_singular}->status = $status;
    ${module_dir_singular}->updated_at = date('Y-m-d H:i:s');
    R::store(${module_dir_singular});
    
    // 验证更新是否成功
    $updated{module_title} = R::load('{table_name}', ${module_dir_singular}Id);
    if ($updated{module_title}->status !== $status) {
        $response['message'] = '状态更新失败，请重试';
        echo json_encode($response);
        exit;
    }
    
    // 更新成功
    $statusText = $status == 1 ? '活跃' : '禁用';
    $response['success'] = true;
    $response['message'] = "{module_title} \"${$module_dir_singular}Info\" 状态已更新为 {$statusText}";
    $response['data'] = [
        'record_id' => ${module_dir_singular}Id,
        'record_name' => ${module_dir_singular}Info,
        'old_status' => $oldStatus,
        'new_status' => $status
    ];
    
} catch (Exception $e) {
    $response['message'] = '状态更新失败，请重试';
}

echo json_encode($response);
exit;