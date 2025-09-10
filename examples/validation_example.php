<?php
/**
 * ValidationHelper使用示例
 * 
 * 本文件展示如何使用ValidationHelper类进行表单验证
 */

// 引入ValidationHelper类
use Core\OwnLibrary\Validation\ValidationHelper;

// 模拟表单提交数据
$_POST = [
    'username' => 'test_user',
    'email' => 'test@example.com',
    'password' => 'Password123!',
    'confirm_password' => 'Password123!',
    'age' => 25,
    'gender' => 'male',
    'phone' => '13800138000',
    'website' => 'https://example.com',
    'bio' => '这是一段个人简介',
    'interests' => ['reading', 'coding', 'music']
];

// 设置响应头为JSON
header('Content-Type: application/json');

// 初始化响应数据
$response = [
    'success' => false,
    'message' => '',
    'data' => null
];

// 收集所有参数到一个数组中
$params = [
    'username' => $_POST['username'] ?? '',
    'email' => $_POST['email'] ?? '',
    'password' => $_POST['password'] ?? '',
    'confirm_password' => $_POST['confirm_password'] ?? '',
    'age' => isset($_POST['age']) ? (int)$_POST['age'] : null,
    'gender' => $_POST['gender'] ?? '',
    'phone' => $_POST['phone'] ?? '',
    'website' => $_POST['website'] ?? '',
    'bio' => $_POST['bio'] ?? '',
    'interests' => $_POST['interests'] ?? []
];

// 使用ValidationHelper进行验证

// 验证用户名
$usernameResult = ValidationHelper::validate($params, 'username', [
    '必填' => '用户名不能为空',
    '长度6-20' => '用户名长度必须在6-20个字符之间',
    '字母数字下划线' => '用户名只能包含字母、数字和下划线'
]);

// 验证邮箱
$emailResult = ValidationHelper::validate($params, 'email', [
    '必填' => '邮箱不能为空',
    '邮箱' => '邮箱格式不正确'
]);

// 验证密码
$passwordResult = ValidationHelper::validate($params, 'password', [
    '必填' => '密码不能为空',
    '长度8-20' => '密码长度必须在8-20个字符之间',
    '强密码' => '密码必须包含大小写字母、数字和特殊字符'
]);

// 验证确认密码
$confirmPasswordResult = ValidationHelper::validate($params, 'confirm_password', [
    '必填' => '确认密码不能为空'
]);

// 验证两次密码是否一致
if ($params['password'] !== $params['confirm_password']) {
    $confirmPasswordResult[] = '两次输入的密码不一致';
}

// 验证年龄
$ageResult = ValidationHelper::validate($params, 'age', [
    '数字' => '年龄必须是数字',
    '范围18-100' => '年龄必须在18-100之间'
]);

// 验证性别
$genderResult = ValidationHelper::validate($params, 'gender', [
    '枚举male,female,other' => '性别必须是male、female或other'
]);

// 验证手机号
$phoneResult = ValidationHelper::validate($params, 'phone', [
    '手机号' => '手机号格式不正确'
]);

// 验证网站
$websiteResult = ValidationHelper::validate($params, 'website', [
    '网址' => '网站地址格式不正确'
]);

// 验证个人简介
$bioResult = ValidationHelper::validate($params, 'bio', [
    '最大长度500' => '个人简介不能超过500个字符'
]);

// 验证兴趣爱好
$interestsResult = ValidationHelper::validate($params, 'interests', [
    '数组' => '兴趣爱好必须是数组'
]);

// 合并所有验证结果
$errors = array_merge(
    $usernameResult,
    $emailResult,
    $passwordResult,
    $confirmPasswordResult,
    $ageResult,
    $genderResult,
    $phoneResult,
    $websiteResult,
    $bioResult,
    $interestsResult
);

// 检查是否有错误
if (!empty($errors)) {
    $response['message'] = reset($errors); // 获取第一个错误信息
    $response['errors'] = $errors; // 所有错误信息
    echo json_encode($response);
    exit;
}

// 验证通过，继续处理业务逻辑
$response['success'] = true;
$response['message'] = '表单验证通过';
$response['data'] = $params;

// 返回JSON响应
echo json_encode($response);