<?php
// {module_title}表单页面（创建和编辑）

// 引入必要的类
use Core\OtherLibrary\RedBean\RedBeanFacade as R;
use Core\OwnLibrary\Security\CsrfTokenManager;
use Core\OwnLibrary\Validation\ValidationHelper;

// 布局变量
$layout = "green_layout";

// 页面元数据
$pageTitle = '{module_title}表单 - AiPHP应用';
$pageDescription = '创建或编辑{module_title}信息';
$pageKeywords = '{module_title}表单, 创建{module_title}, 编辑{module_title}';

// 引入CSS和JS文件
$additionalCSS = ['/static/own/css/{module_dir}/form.css'];
$additionalJS = ['/static/own/js/{module_dir}/form.js'];

// 初始化RedBean
R::initialize();

// 实例化CSRF令牌管理器
$csrfManager = new CsrfTokenManager();

// 生成CSRF令牌
$csrfToken = $csrfManager->getToken();

// 获取ID（用于编辑模式）
${module_dir_singular}Id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : (isset($params['id']) ? intval($params['id']) : 0));
$isEdit = ${module_dir_singular}Id > 0;

// 初始化数据
${module_dir_singular} = null;
if ($isEdit) {
    // 加载现有记录（编辑模式）
    ${module_dir_singular} = R::load('{table_name}', ${module_dir_singular}Id);
    if (!$module_dir_singular || !$module_dir_singular->id) {
        header('Location: /{module_dir}?error={module_dir_singular}_not_found');
        exit;
    }
    
    // 设置页面标题
    $pageTitle = '编辑{module_title} - AiPHP应用';
} else {
    // 设置页面标题
    $pageTitle = '创建{module_title} - AiPHP应用';
    
    // 初始化空对象
    ${module_dir_singular} = R::dispense('{table_name}');
}

// 初始化表单数据和错误
$formData = [
    {form_fields}
];

$errors = [];
$error = '';
$success = '';

// 获取当前页码
$returnPage = intval($_GET['page'] ?? $_POST['page'] ?? 1);
$backUrl = $returnPage > 1 ? "/{module_dir}?page={$returnPage}" : "/{module_dir}";

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 验证CSRF令牌
    if (!$csrfManager->validateRequestToken()) {
        $error = 'CSRF验证失败，请刷新页面后重试';
    } else {
        // 获取表单数据
        $formData = [
            {form_fields_post}
        ];
        
        // 收集参数进行验证
        $params = [
            {form_fields_params}
        ];
        
        // 验证字段
        {validation_rules}
        
        // 合并所有验证结果
        $errors = array_merge({validation_results});
        
        // 自定义验证
        {unique_checks}
        
        // 检查是否有验证错误
        if (!empty($errors)) {
            $error = reset($errors); // 获取第一个错误信息
        } else {
            // 如果没有错误，保存
            try {
                if ($isEdit) {
                    // 更新现有记录
                    {update_fields}
                    ${module_dir_singular}->updated_at = date('Y-m-d H:i:s');
                } else {
                    // 创建新记录
                    ${module_dir_singular} = R::dispense('{table_name}');
                    {create_fields}
                    ${module_dir_singular}->created_at = date('Y-m-d H:i:s');
                    ${module_dir_singular}->updated_at = date('Y-m-d H:i:s');
                }
                
                $id = R::store(${module_dir_singular});
                
                // 重定向到成功页面，包含当前页码参数
                $action = $isEdit ? 'updated' : 'created';
                $redirectUrl = $returnPage > 1 ? "/{module_dir}?page={$returnPage}&success={$action}" : "/{module_dir}?success={$action}";
                header("Location: {$redirectUrl}");
                exit;
            } catch (Exception $e) {
                $error = '保存失败：' . $e->getMessage();
            }
        }
    }
}
?>

<div class="{module_dir}-form">
    <!-- 在页面中添加CSRF令牌的meta标签，便于JavaScript获取 -->
    <meta name="csrf-token" content="<?php echo htmlspecialchars($csrfToken); ?>">
    
    <div class="page-header">
        <h1><?php echo $isEdit ? '编辑{module_title}' : '创建{module_title}'; ?></h1>
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
            <h3>{module_title}信息</h3>
        </div>
        <div class="card-body">
            <!-- 添加novalidate属性禁用浏览器验证，避免中文字段名问题 -->
            <form id="{module_dir}-form" method="post" action="/{module_dir}/save" novalidate>
                <!-- CSRF令牌隐藏字段 -->
                <?php echo $csrfManager->getTokenField(); ?>
                
                <!-- 添加隐藏的返回页面字段 -->
                <?php if ($returnPage > 1): ?>
                <input type="hidden" name="page" value="<?php echo htmlspecialchars($returnPage); ?>">
                <?php endif; ?>
                
                <!-- 在编辑模式下添加隐藏的ID字段 -->
                <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars(${module_dir_singular}Id); ?>">
                <?php endif; ?>
                
                {form_html}
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><?php echo $isEdit ? '更新{module_title}' : '创建{module_title}'; ?></button>
                    <a href="<?php echo $backUrl; ?>" class="btn btn-secondary">取消</a>
                </div>
            </form>
        </div>
    </div>
</div>