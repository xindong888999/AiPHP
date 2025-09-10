<?php
// 文章列表页面

// 引入必要的类
use Core\OtherLibrary\RedBean\RedBeanFacade as R;
use Core\OwnLibrary\Security\CsrfTokenManager;

// 布局变量
$layout = "green_layout";

// 页面元数据
$pageTitle = '文章管理 - AiPHP应用';
$pageDescription = '文章管理页面，可以查看、添加、编辑和删除文章';
$pageKeywords = '文章管理, 文章列表, CRUD';

// 引入CSS和JS文件
$additionalCSS = ['/static/own/css/articles/index.css'];
$additionalJS = ['/static/own/js/articles/index.js'];

// 初始化RedBean
R::initialize();

// 实例化CSRF令牌管理器
$csrfManager = new CsrfTokenManager();

// 生成CSRF令牌
$csrfToken = $csrfManager->getToken();

// 处理搜索和分页
$page = max(1, intval($_GET['page'] ?? 1));
$searchTerm = trim($_GET['search'] ?? '');
$perPage = 10;
$offset = ($page - 1) * $perPage;

// 构建查询条件
$whereClause = '';
$params = [];

if (!empty($searchTerm)) {
    $whereClause = 'WHERE title LIKE ? OR content LIKE ?';
    $params = ["%{$searchTerm}%", "%{$searchTerm}%"];
}

// 获取总数
if (!empty($searchTerm)) {
    $totalRecords = R::count('articles', 'title LIKE ? OR content LIKE ?', $params);
} else {
    $totalRecords = R::count('articles');
}

$totalPages = ceil($totalRecords / $perPage);

// 获取当前页数据
$sql = "SELECT * FROM articles";
if (!empty($whereClause)) {
    $sql .= " {$whereClause}";
}
$sql .= " ORDER BY id DESC LIMIT ? OFFSET ?";
$params[] = $perPage;
$params[] = $offset;

$articles = R::getAll($sql, $params);

// 处理消息提示
$successMessage = $_GET['success'] ?? '';
$errorMessage = $_GET['error'] ?? '';

// 将CSRF令牌添加到额外的meta标签中
$additionalMeta = '<meta name="csrf-token" content="' . htmlspecialchars($csrfToken) . '">';
?>
<div class="articles-index">
    <div class="page-header">
        <h1>文章管理</h1>
        <a href="/articles/form?page=<?php echo $page; ?>" class="btn btn-primary">添加文章</a>
    </div>
    
    <?php if ($successMessage): ?>
    <div class="alert alert-success">
        <?php 
        switch ($successMessage) {
            case 'created': echo '文章创建成功'; break;
            case 'updated': echo '文章更新成功'; break;
            case 'deleted': echo '文章删除成功'; break;
            default: echo htmlspecialchars($successMessage);
        }
        ?>
    </div>
    <?php endif; ?>
    
    <?php if ($errorMessage): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($errorMessage); ?>
    </div>
    <?php endif; ?>
    
    <!-- 搜索表单 -->
    <div class="search-form">
        <form method="GET" action="/articles">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="搜索文章标题或内容..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-secondary">搜索</button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- 数据表格 -->
    <div class="articles-table-container">
        <table class="articles-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>标题</th>
                    <th>创建时间</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($articles)): ?>
                    <?php foreach ($articles as $article): ?>
                        <tr>
                            <td><?php echo $article['id']; ?></td>
                            <td><?php echo htmlspecialchars($article['title']); ?></td>
                            <td><?php echo $article['created_at']; ?></td>
                            <td>
                                <button 
                                    class="btn-status <?php echo $article['status'] == 1 ? 'status-active' : 'status-inactive'; ?>" 
                                    onclick="updateArticleStatus(<?php echo $article['id']; ?>, <?php echo $article['status'] == 1 ? 0 : 1; ?>)"
                                >
                                    <?php echo $article['status'] == 1 ? '启用' : '禁用'; ?>
                                </button>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/articles/detail/<?php echo $article['id']; ?>?page=<?php echo $page; ?>" class="btn btn-info">详情</a>
                                    <a href="/articles/form/<?php echo $article['id']; ?>?page=<?php echo $page; ?>" class="btn btn-warning">编辑</a>
                                    <button class="btn btn-danger" onclick="showDeleteModal(<?php echo $article['id']; ?>, '<?php echo addslashes(htmlspecialchars($article['title'])); ?>')">删除</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-data">暂无数据</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- 分页 -->
    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <ul class="pagination-list">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li>
                    <a href="/articles?page=<?php echo $i; ?><?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>" class="pagination-link <?php echo $i == $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <!-- 删除确认模态框 -->
    <div id="deleteModal" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">确认删除</h5>
                    <button type="button" class="close" onclick="hideDeleteModal()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>确定要删除文章 "<span id="deleteArticleName"></span>" 吗？此操作不可撤销。</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="hideDeleteModal()">取消</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">确认删除</button>
                </div>
            </div>
        </div>
    </div>
</div>