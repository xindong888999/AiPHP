<?php
// 文章详情页面

// 布局变量
$layout = "green_layout";

// 页面元数据
$pageTitle = '文章详情 - AiPHP应用';
$pageDescription = '查看文章详细信息';
$pageKeywords = '文章详情, 文章信息';

// 引入CSS和JS文件
$additionalCSS = ['/static/own/css/articles/detail.css'];
$additionalJS = ['/static/own/js/articles/detail.js'];

use Core\OtherLibrary\RedBean\RedBeanFacade as R;

// 初始化RedBean
R::initialize();

// 获取ID
$articleId = isset($params['id']) ? intval($params['id']) : 0;

// 验证ID
if ($articleId <= 0) {
    header('Location: /articles?error=invalid_id');
    exit;
}

// 查找记录
$article = R::load('articles', $articleId);

// 检查记录是否存在
if (!$article || !$article->id) {
    header('Location: /articles?error=article_not_found');
    exit;
}

// 处理消息提示
$successMessage = $_GET['success'] ?? '';
$errorMessage = $_GET['error'] ?? '';

// 获取当前页码
$returnPage = intval($_GET['page'] ?? 1);
$backUrl = $returnPage > 1 ? "/articles?page={$returnPage}" : "/articles";
?>
<div class="articles-detail">
    <div class="page-header">
        <h1>文章详情</h1>
        <div class="button-group">
            <a href="<?php echo $backUrl; ?>" class="btn btn-secondary">返回列表</a>
            <a href="/articles/form/<?php echo $article->id; ?>?page=<?php echo $returnPage; ?>" class="btn btn-warning">编辑文章</a>
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
    
    <div class="article-info-card">
        <div class="card">
            <div class="card-header">
                <h2>基本信息</h2>
            </div>
            <div class="card-body">
                <div class="info-item">
                    <label>ID:</label>
                    <span><?php echo $article->id; ?></span>
                </div>
                <div class="info-item">
                    <label>标题:</label>
                    <span><?php echo htmlspecialchars($article->title); ?></span>
                </div>
                <div class="info-item">
                    <label>内容:</label>
                    <div class="content-display">
                        <?php echo nl2br(htmlspecialchars($article->content)); ?>
                    </div>
                </div>
                <div class="info-item">
                    <label>状态:</label>
                    <span class="status-badge <?php echo $article->status == 1 ? 'status-active' : 'status-inactive'; ?>">
                        <?php echo $article->status == 1 ? '启用' : '禁用'; ?>
                    </span>
                </div>
                <div class="info-item">
                    <label>创建时间:</label>
                    <span><?php echo $article->created_at; ?></span>
                </div>
                <div class="info-item">
                    <label>更新时间:</label>
                    <span><?php echo $article->updated_at; ?></span>
                </div>
            </div>
        </div>
    </div>
</div>