<?php
// 用户详情页面

// 布局变量
$layout = "green_layout";

// 页面元数据
$pageTitle = '用户详情 - AiPHP应用';
$pageDescription = '查看用户详细信息';
$pageKeywords = '用户详情, 用户信息';

// 引入CSS和JS文件
$additionalCSS = ['/static/own/css/users/detail.css'];
$additionalJS = ['/static/own/js/users/detail.js'];

use Core\OtherLibrary\RedBean\RedBeanFacade as R;

// 初始化RedBean
R::initialize();

// 获取用户ID
$userId = isset($params['id']) ? intval($params['id']) : 0;

// 验证ID
if ($userId <= 0) {
    header('Location: /users?error=invalid_id');
    exit;
}

// 查找用户
$user = R::load('users', $userId);

// 检查用户是否存在
if (!$user || !$user->id) {
    header('Location: /users?error=user_not_found');
    exit;
}

// 处理消息提示
$successMessage = $_GET['success'] ?? '';
$errorMessage = $_GET['error'] ?? '';

// 获取当前页码
$returnPage = intval($_GET['page'] ?? 1);
$backUrl = $returnPage > 1 ? "/users?page={$returnPage}" : "/users";
?>
<div class="users-detail">
    <div class="page-header">
        <h1>用户详情</h1>
        <div class="button-group">
            <a href="<?php echo $backUrl; ?>" class="btn btn-secondary">返回列表</a>
            <a href="/users/form/<?php echo $user->id; ?>?page=<?php echo $returnPage; ?>" class="btn btn-warning">编辑用户</a>
        </div>
    </div>
    
    <?php if ($successMessage): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($successMessage); ?>
    </div>
    <?php endif; ?>
    
    <?php if ($errorMessage): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($errorMessage); ?>
    </div>
    <?php endif; ?>
    
    <div class="user-info-card">
        <div class="card">
            <div class="card-header">
                <h3>基本信息</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><strong>ID:</strong></div>
                    <div class="col-md-9"><?php echo $user->id; ?></div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>用户名:</strong></div>
                    <div class="col-md-9">
                        <?php echo htmlspecialchars($user->username); ?>
                        <button class="btn btn-sm btn-outline-secondary copy-btn" data-copy="<?php echo htmlspecialchars($user->username); ?>" style="margin-left: 10px;">复制</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>邮箱:</strong></div>
                    <div class="col-md-9">
                        <?php echo htmlspecialchars($user->email); ?>
                        <button class="btn btn-sm btn-outline-secondary copy-btn" data-copy="<?php echo htmlspecialchars($user->email); ?>" style="margin-left: 10px;">复制</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>昵称:</strong></div>
                    <div class="col-md-9"><?php echo htmlspecialchars($user->nickname); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>电话:</strong></div>
                    <div class="col-md-9">
                        <?php echo htmlspecialchars($user->phone ?? '未设置'); ?>
                        <?php if (!empty($user->phone)): ?>
                        <button class="btn btn-sm btn-outline-secondary copy-btn" data-copy="<?php echo htmlspecialchars($user->phone); ?>" style="margin-left: 10px;">复制</button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>性别:</strong></div>
                    <div class="col-md-9">
                        <?php 
                        switch ($user->gender) {
                            case 1: echo '男'; break;
                            case 2: echo '女'; break;
                            default: echo '未设置';
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>年龄:</strong></div>
                    <div class="col-md-9"><?php echo htmlspecialchars($user->age ?? '未设置'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>状态:</strong></div>
                    <div class="col-md-9">
                        <?php if ($user->status == 1): ?>
                            <span class="status-active">活跃</span>
                        <?php else: ?>
                            <span class="status-inactive">禁用</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h3>详细信息</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><strong>地址:</strong></div>
                    <div class="col-md-9"><?php echo htmlspecialchars($user->address ?? '未设置'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>简介:</strong></div>
                    <div class="col-md-9"><?php echo htmlspecialchars($user->bio ?? '未设置'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>创建时间:</strong></div>
                    <div class="col-md-9"><?php echo date('Y-m-d H:i:s', strtotime($user->created_at)); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>更新时间:</strong></div>
                    <div class="col-md-9"><?php echo date('Y-m-d H:i:s', strtotime($user->updated_at)); ?></div>
                </div>
                <?php if ($user->last_login): ?>
                <div class="row">
                    <div class="col-md-3"><strong>最后登录:</strong></div>
                    <div class="col-md-9"><?php echo date('Y-m-d H:i:s', strtotime($user->last_login)); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>