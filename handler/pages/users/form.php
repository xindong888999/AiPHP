<?php
// 用户表单页面（创建和编辑）

// 引入必要的类
use Core\OtherLibrary\RedBean\RedBeanFacade as R;
use Core\OwnLibrary\Security\CsrfTokenManager;
use Core\OwnLibrary\Validation\ValidationHelper;

// 布局变量
$layout = "green_layout";

// 页面元数据
$pageTitle = '用户表单 - AiPHP应用';
$pageDescription = '创建或编辑用户信息';
$pageKeywords = '用户表单, 创建用户, 编辑用户';

// 引入CSS和JS文件
$additionalCSS = ['/static/own/css/users/form.css'];
$additionalJS = ['/static/own/js/users/form.js'];

// 初始化RedBean
R::initialize();

// 实例化CSRF令牌管理器
$csrfManager = new CsrfTokenManager();

// 生成CSRF令牌
$csrfToken = $csrfManager->getToken();

// 获取用户ID（用于编辑模式）
$userId = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : (isset($params['id']) ? intval($params['id']) : 0));
$isEdit = $userId > 0;

// 初始化用户数据
$user = null;
if ($isEdit) {
    // 加载现有用户（编辑模式）
    $user = R::load('users', $userId);
    if (!$user || !$user->id) {
        header('Location: /users?error=user_not_found');
        exit;
    }
    
    // 设置页面标题
    $pageTitle = '编辑用户 - AiPHP应用';
} else {
    // 设置页面标题
    $pageTitle = '创建用户 - AiPHP应用';
    
    // 初始化空用户对象
    $user = R::dispense('users');
}

// 初始化表单数据和错误
$formData = [
    'username' => $user->username ?? '',
    'email' => $user->email ?? '',
    'nickname' => $user->nickname ?? '',
    'phone' => $user->phone ?? '',
    'gender' => $user->gender ?? '',
    'age' => $user->age ?? '',
    'address' => $user->address ?? '',
    'bio' => $user->bio ?? '',
    'status' => $user->status ?? 1
];

$errors = [];
$error = '';
$success = '';

// 获取当前页码
$returnPage = intval($_GET['page'] ?? $_POST['page'] ?? 1);
$backUrl = $returnPage > 1 ? "/users?page={$returnPage}" : "/users";

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 验证CSRF令牌
    if (!$csrfManager->validateRequestToken()) {
        $error = 'CSRF验证失败，请刷新页面后重试';
    } else {
        // 获取表单数据
        $formData = [
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'nickname' => trim($_POST['nickname'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'gender' => intval($_POST['gender'] ?? 0),
            'age' => intval($_POST['age'] ?? 0),
            'address' => trim($_POST['address'] ?? ''),
            'bio' => trim($_POST['bio'] ?? ''),
            'status' => intval($_POST['status'] ?? 1)
        ];
        
        // 收集参数进行验证
        $params = [
            'username' => $formData['username'],
            'email' => $formData['email'],
            'nickname' => $formData['nickname'],
            'phone' => $formData['phone'],
            'gender' => $formData['gender'],
            'age' => $formData['age'],
            'address' => $formData['address'],
            'bio' => $formData['bio'],
            'status' => $formData['status']
        ];
        
        // 验证用户名
        $usernameResult = ValidationHelper::validate($params, 'username', [
            '必填' => '用户名不能为空',
            '长度3-50' => '用户名长度必须在3-50个字符之间'
        ]);
        
        // 验证邮箱
        $emailResult = ValidationHelper::validate($params, 'email', [
            '必填' => '邮箱不能为空'
        ]);
        
        // 验证昵称长度
        $nicknameResult = [];
        if (!empty($params['nickname'])) {
            $nicknameResult = ValidationHelper::validate($params, 'nickname', [
                '最大长度50' => '昵称不能超过50个字符'
            ]);
        }
        
        // 验证电话号码长度
        $phoneResult = [];
        if (!empty($params['phone'])) {
            $phoneResult = ValidationHelper::validate($params, 'phone', [
                '最大长度20' => '电话号码不能超过20个字符'
            ]);
        }
        
        // 验证地址长度
        $addressResult = [];
        if (!empty($params['address'])) {
            $addressResult = ValidationHelper::validate($params, 'address', [
                '最大长度500' => '地址不能超过500个字符'
            ]);
        }
        
        // 验证简介长度
        $bioResult = [];
        if (!empty($params['bio'])) {
            $bioResult = ValidationHelper::validate($params, 'bio', [
                '最大长度1000' => '简介不能超过1000个字符'
            ]);
        }
        
        // 验证性别
        $genderResult = ValidationHelper::validate($params, 'gender', [
            '枚举0,1,2' => '性别值无效'
        ]);
        
        // 验证年龄 - 重新修复验证逻辑，正确区分空值和0值
        $ageResult = [];
        // 只有当用户填写了年龄字段时才进行验证（包括填写0的情况）
        if (!empty($_POST['age']) || $_POST['age'] === '0') {
            $ageResult = ValidationHelper::validate($params, 'age', [
                '数字' => '年龄必须是数字',
                '范围0-150' => '年龄必须在0-150之间'  // 修改范围包含0
            ]);
        }
        
        // 合并所有验证结果
        $errors = array_merge($usernameResult, $emailResult, $nicknameResult, $phoneResult, 
                             $addressResult, $bioResult, $genderResult, $ageResult);
        
        // 自定义邮箱验证，支持中文和其他Unicode字符，ValidationHelper暂不支持
        if (empty($errors) && !empty($formData['email'])) {
            if (!preg_match('/^[\p{L}\p{N}\p{M}\.\-_]+@[\p{L}\p{N}\p{M}\.\-_]+\.[\p{L}\p{N}\p{M}\.\-_]+$/u', $formData['email'])) {
                $errors[] = '邮箱格式不正确';
            } elseif (strlen($formData['email']) > 100) {
                $errors[] = '邮箱不能超过100个字符';
            }
        }
        
        // 检查是否有验证错误
        if (!empty($errors)) {
            $error = reset($errors); // 获取第一个错误信息
        } else {
            // 检查用户名唯一性（排除当前用户）
            if (!empty($formData['username'])) {
                $existingUser = R::findOne('users', 'username = ?' . ($isEdit ? ' AND id != ?' : ''), 
                                              $isEdit ? [$formData['username'], $userId] : [$formData['username']]);
                if ($existingUser) {
                    $error = '该用户名已存在';
                } else {
                    // 检查邮箱唯一性（排除当前用户）
                    if (!empty($formData['email'])) {
                        $existingEmail = R::findOne('users', 'email = ?' . ($isEdit ? ' AND id != ?' : ''), 
                                                       $isEdit ? [$formData['email'], $userId] : [$formData['email']]);
                        if ($existingEmail) {
                            $error = '该邮箱已被使用';
                        } else {
                            // 如果没有错误，保存用户
                            try {
                                if ($isEdit) {
                                    // 更新现有用户
                                    $user->username = $formData['username'];
                                    $user->email = $formData['email'];
                                    $user->nickname = $formData['nickname'];
                                    $user->phone = $formData['phone'];
                                    $user->gender = $formData['gender'];
                                    $user->age = $formData['age'];
                                    $user->address = $formData['address'];
                                    $user->bio = $formData['bio'];
                                    $user->status = $formData['status'];
                                    $user->updated_at = date('Y-m-d H:i:s');
                                } else {
                                    // 创建新用户
                                    $user = R::dispense('users');
                                    $user->username = $formData['username'];
                                    $user->email = $formData['email'];
                                    $user->nickname = $formData['nickname'];
                                    $user->phone = $formData['phone'];
                                    $user->gender = $formData['gender'];
                                    $user->age = $formData['age'];
                                    $user->address = $formData['address'];
                                    $user->bio = $formData['bio'];
                                    $user->status = $formData['status'];
                                    $user->created_at = date('Y-m-d H:i:s');
                                    $user->updated_at = date('Y-m-d H:i:s');
                                    // 设置默认密码
                                    $user->password = password_hash('123456', PASSWORD_DEFAULT);
                                }
                                
                                $id = R::store($user);
                                
                                // 重定向到成功页面，包含当前页码参数
                                $action = $isEdit ? 'updated' : 'created';
                                $redirectUrl = $returnPage > 1 ? "/users?page={$returnPage}&success={$action}" : "/users?success={$action}";
                                header("Location: {$redirectUrl}");
                                exit;
                            } catch (Exception $e) {
                                $error = '保存失败：' . $e->getMessage();
                            }
                        }
                    } else if (!$isEdit) {
                        // 在创建模式下，如果邮箱为空，应该显示错误（因为邮箱是必填的）
                        // 这个错误已经在ValidationHelper中处理了，但为了确保，我们再检查一次
                        $error = '邮箱不能为空';
                    }
                }
            } else if (!$isEdit) {
                // 在创建模式下，如果用户名为空，应该显示错误（因为用户名是必填的）
                // 这个错误已经在ValidationHelper中处理了，但为了确保，我们再检查一次
                $error = '用户名不能为空';
            }
        }
    }
}
?>

<div class="users-form">
    <!-- 在页面中添加CSRF令牌的meta标签，便于JavaScript获取 -->
    <meta name="csrf-token" content="<?php echo htmlspecialchars($csrfToken); ?>">
    
    <div class="page-header">
        <h1><?php echo $isEdit ? '编辑用户' : '创建用户'; ?></h1>
        <a href="<?php echo $backUrl; ?>" class="btn btn-secondary">返回列表</a>
    </div>
    
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($success); ?>
    </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header">
            <h3>用户信息</h3>
        </div>
        <div class="card-body">
            <!-- 添加novalidate属性禁用浏览器验证，避免中文字段名问题 -->
            <form id="user-form" method="post" action="/users/save" novalidate>
                <!-- CSRF令牌隐藏字段 -->
                <?php echo $csrfManager->getTokenField(); ?>
                
                <!-- 添加隐藏的返回页面字段 -->
                <?php if ($returnPage > 1): ?>
                <input type="hidden" name="page" value="<?php echo htmlspecialchars($returnPage); ?>">
                <?php endif; ?>
                
                <!-- 在编辑模式下添加隐藏的用户ID字段 -->
                <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($userId); ?>">
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username">用户名 *</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?php echo htmlspecialchars($formData['username']); ?>" 
                                   placeholder="请输入用户名（3-50个字符）">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">邮箱 *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($formData['email']); ?>" 
                                   placeholder="请输入邮箱地址">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nickname">昵称</label>
                            <input type="text" class="form-control" id="nickname" name="nickname" 
                                   value="<?php echo htmlspecialchars($formData['nickname']); ?>" 
                                   placeholder="请输入昵称">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">电话</label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($formData['phone']); ?>" 
                                   placeholder="请输入电话号码">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gender">性别</label>
                            <select class="form-control" id="gender" name="gender">
                                <option value="0"<?php echo $formData['gender'] == 0 ? ' selected' : ''; ?>>保密</option>
                                <option value="1"<?php echo $formData['gender'] == 1 ? ' selected' : ''; ?>>男</option>
                                <option value="2"<?php echo $formData['gender'] == 2 ? ' selected' : ''; ?>>女</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="age">年龄</label>
                            <input type="number" class="form-control" id="age" name="age" 
                                   value="<?php echo htmlspecialchars($formData['age']); ?>" 
                                   placeholder="请输入年龄">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address">地址</label>
                    <input type="text" class="form-control" id="address" name="address" 
                           value="<?php echo htmlspecialchars($formData['address']); ?>" 
                           placeholder="请输入地址">
                </div>
                
                <div class="form-group">
                    <label for="bio">简介</label>
                    <textarea class="form-control" id="bio" name="bio" rows="3" 
                              placeholder="请输入用户简介"><?php echo htmlspecialchars($formData['bio']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="status">状态</label>
                    <select class="form-control" id="status" name="status">
                        <option value="1"<?php echo $formData['status'] == 1 ? ' selected' : ''; ?>>活跃</option>
                        <option value="0"<?php echo $formData['status'] == 0 ? ' selected' : ''; ?>>禁用</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><?php echo $isEdit ? '更新用户' : '创建用户'; ?></button>
                    <a href="<?php echo $backUrl; ?>" class="btn btn-secondary">取消</a>
                </div>
            </form>
        </div>
    </div>
</div>