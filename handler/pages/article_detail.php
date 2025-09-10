<?php
// 独立文章详情页面

// 引入必要的类
use Core\OtherLibrary\RedBean\RedBeanFacade as R;

// 布局变量
$layout = "green_layout";

// 页面元数据
$pageTitle = '文章详情 - AiPHP应用';
$pageDescription = '文章详情页面，展示文章完整内容';
$pageKeywords = '文章详情, 阅读, 文章内容';

// 引入CSS和JS文件
$additionalCSS = ['/static/own/css/article_detail.css'];
$additionalJS = ['/static/own/js/article_detail.js'];

// 初始化RedBean
R::initialize();

// 获取文章ID
// 在AiPHP框架中，路由参数直接作为变量可用
$articleId = intval($id ?? 0);
$currentPage = intval($_GET['page'] ?? 1);

// 获取文章详情
$article = R::findOne('articles', 'id = ?', [$articleId]);

// 获取上一篇和下一篇已发布的文章
$prevArticle = R::findOne('articles', 'id < ? AND status = ? ORDER BY id DESC LIMIT 1', [$articleId, 1]);
$nextArticle = R::findOne('articles', 'id > ? AND status = ? ORDER BY id ASC LIMIT 1', [$articleId, 1]);

// 如果文章不存在，显示错误信息
if (!$article) {
    $errorMessage = '文章不存在或已被删除';
}
?> 
<div class="article-detail">
    <div class="page-header">
        <a href="/article_list?page=<?php echo $currentPage; ?>" class="back-button">返回列表</a>
    </div>
    
    <?php if (isset($errorMessage)): ?>
        <div class="error-message">
            <p><?php echo $errorMessage; ?></p>
        </div>
    <?php else: ?>
        <!-- 文章内容 -->
        <div class="article-content">
            <h1 class="article-title"><?php echo htmlspecialchars($article->title); ?></h1>
            <div class="article-meta">
                <span class="article-date">创建时间：<?php echo $article->created_at; ?></span>
                <span class="article-status"><?php echo $article->status == 1 ? '已发布' : '草稿'; ?></span>
            </div>
            <div class="article-body">
                <?php echo htmlspecialchars($article->content); ?>
            </div>
        </div>
        
        <!-- 上下篇导航 -->
        <div class="article-navigation">
            <?php if ($prevArticle): ?>
                <div class="nav-item prev-article">
                    <a href="/article_detail/<?php echo $prevArticle->id; ?>?page=<?php echo $currentPage; ?>">
                        <span class="nav-label">上一篇</span>
                        <span class="nav-title"><?php echo htmlspecialchars($prevArticle->title); ?></span>
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if ($nextArticle): ?>
                <div class="nav-item next-article">
                    <a href="/article_detail/<?php echo $nextArticle->id; ?>?page=<?php echo $currentPage; ?>">
                        <span class="nav-label">下一篇</span>
                        <span class="nav-title"><?php echo htmlspecialchars($nextArticle->title); ?></span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>