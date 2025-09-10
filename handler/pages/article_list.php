<?php
// 独立文章列表页面

// 引入必要的类
use Core\OtherLibrary\RedBean\RedBeanFacade as R;

// 布局变量
$layout = "green_layout";

// 页面元数据
$pageTitle = '文章列表 - AiPHP应用';
$pageDescription = '独立的文章列表页面，展示所有文章记录';
$pageKeywords = '文章列表, 文章, 阅读';

// 引入CSS和JS文件
$additionalCSS = ['/static/own/css/article_list.css'];
$additionalJS = ['/static/own/js/article_list.js'];

// 初始化RedBean
R::initialize();

// 处理分页
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

// 获取激活状态文章总数（status=1表示已发布）
$totalRecords = R::count('articles', 'status = ?', [1]);
$totalPages = ceil($totalRecords / $perPage);

// 获取当前页激活状态的文章数据
$articles = R::getAll('SELECT * FROM articles WHERE status = ? ORDER BY id DESC LIMIT ? OFFSET ?', [1, $perPage, $offset]);
?> 
<div class="article-list">
    <div class="page-header">
        <h1>文章列表</h1>
    </div>
    
    <!-- 文章列表 -->
    <div class="articles-container">
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
                <div class="article-item">
                    <a href="/article_detail/<?php echo $article['id']; ?>?page=<?php echo $page; ?>">
                        <h3 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                        <div class="article-meta">
                            <span class="article-date">创建时间：<?php echo $article['created_at']; ?></span>
                            <span class="article-status"><?php echo $article['status'] == 1 ? '已发布' : '草稿'; ?></span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-articles">
                <p>暂无文章</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- 分页 -->
    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <ul class="pagination-list">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li>
                    <a href="/article_list?page=<?php echo $i; ?>" class="pagination-link <?php echo $i == $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>