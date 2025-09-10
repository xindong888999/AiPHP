<?php
// {module_title}详情页面

// 布局变量
$layout = "green_layout";

// 页面元数据
$pageTitle = '{module_title}详情 - AiPHP应用';
$pageDescription = '查看{module_title}详细信息';
$pageKeywords = '{module_title}详情, {module_title}信息';

// 引入CSS和JS文件
$additionalCSS = ['/static/own/css/{module_dir}/detail.css'];
$additionalJS = ['/static/own/js/{module_dir}/detail.js'];

use Core\OtherLibrary\RedBean\RedBeanFacade as R;

// 初始化RedBean
R::initialize();

// 获取ID
${module_dir_singular}Id = isset($params['id']) ? intval($params['id']) : 0;

// 验证ID
if (${module_dir_singular}Id <= 0) {
    header('Location: /{module_dir}?error=invalid_id');
    exit;
}

// 查找记录
${module_dir_singular} = R::load('{table_name}', ${module_dir_singular}Id);

// 检查记录是否存在
if (!$module_dir_singular || !$module_dir_singular->id) {
    header('Location: /{module_dir}?error={module_dir_singular}_not_found');
    exit;
}

// 处理消息提示
$successMessage = $_GET['success'] ?? '';
$errorMessage = $_GET['error'] ?? '';

// 获取当前页码
$returnPage = intval($_GET['page'] ?? 1);
$backUrl = $returnPage > 1 ? "/{module_dir}?page={$returnPage}" : "/{module_dir}";
?>
<div class="{module_dir}-detail">
    <div class="page-header">
        <h1>{module_title}详情</h1>
        <div class="button-group">
            <a href="<?php echo $backUrl; ?>" class="btn btn-secondary">返回列表</a>
            <a href="/{module_dir}/form/<?php echo ${module_dir_singular}->id; ?>?page=<?php echo $returnPage; ?>" class="btn btn-warning">编辑{module_title}</a>
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
    
    <div class="{module_dir_singular}-info-card">
        <div class="card">
            <div class="card-header">
                <h3>基本信息</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><strong>ID:</strong></div>
                    <div class="col-md-9"><?php echo ${module_dir_singular}->id; ?></div>
                </div>
                {detail_fields}
                <div class="row">
                    <div class="col-md-3"><strong>状态:</strong></div>
                    <div class="col-md-9">
                        <?php if (${module_dir_singular}->status == 1): ?>
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
                    <div class="col-md-3"><strong>创建时间:</strong></div>
                    <div class="col-md-9"><?php echo date('Y-m-d H:i:s', strtotime(${module_dir_singular}->created_at)); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-3"><strong>更新时间:</strong></div>
                    <div class="col-md-9"><?php echo date('Y-m-d H:i:s', strtotime(${module_dir_singular}->updated_at)); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>