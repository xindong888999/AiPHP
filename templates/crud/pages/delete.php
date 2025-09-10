<?php
// {module_title}删除处理页面

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
    ${module_dir_singular}Id = 0;
    if (isset($input['id'])) {
        ${module_dir_singular}Id = intval($input['id']);
    }
    
    // 验证ID
    if (${$module_dir_singular}Id <= 0) {
        $response['message'] = '无效的{module_title}ID';
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
    
    // 删除记录
    R::trash(${module_dir_singular});
    
    // 验证删除是否成功 - 使用count方法更准确地检查
    $recordCount = R::count('{table_name}', 'id = ?', [${module_dir_singular}Id]);
    if ($recordCount > 0) {
        $response['message'] = '删除失败，请重试';
        echo json_encode($response);
        exit;
    }
    
    // 删除成功
    $response['success'] = true;
    $response['message'] = "{module_title} \"${$module_dir_singular}Info\" 删除成功";
    $response['data'] = [
        'deleted_id' => ${module_dir_singular}Id,
        'deleted_info' => ${module_dir_singular}Info
    ];
    
} catch (Exception $e) {
    $response['message'] = '删除失败: ' . $e->getMessage();
}

echo json_encode($response);
exit;