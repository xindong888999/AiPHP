<?php
// 用户列表页面

// 引入必要的类
use Core\OtherLibrary\RedBean\RedBeanFacade as R;
use Core\OwnLibrary\Security\CsrfTokenManager;

// 布局变量
$layout = "green_layout";

// 页面元数据
$pageTitle = '用户管理 - AiPHP应用';
$pageDescription = '用户管理页面，可以查看、添加、编辑和删除用户';
$pageKeywords = '用户管理, 用户列表, CRUD';

// 引入CSS和JS文件
$additionalCSS = ['/static/own/css/users/index.css'];
$additionalJS = ['/static/own/js/users/index.js'];

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
    $whereClause = 'WHERE username LIKE ? OR email LIKE ? OR nickname LIKE ?';
    $params = ["%{$searchTerm}%", "%{$searchTerm}%", "%{$searchTerm}%"];
}

// 获取总数
if (!empty($searchTerm)) {
    $totalRecords = R::count('users', 'username LIKE ? OR email LIKE ? OR nickname LIKE ?', $params);
} else {
    $totalRecords = R::count('users');
}

$totalPages = ceil($totalRecords / $perPage);

// 获取当前页数据
$sql = "SELECT * FROM users";
if (!empty($whereClause)) {
    $sql .= " {$whereClause}";
}
$sql .= " ORDER BY id DESC LIMIT ? OFFSET ?";
$params[] = $perPage;
$params[] = $offset;

$users = R::getAll($sql, $params);

// 处理消息提示
$successMessage = $_GET['success'] ?? '';
$errorMessage = $_GET['error'] ?? '';

// 将CSRF令牌添加到额外的meta标签中
$additionalMeta = '<meta name="csrf-token" content="' . htmlspecialchars($csrfToken) . '">';
?>
<div class="users-index">
    <div class="page-header">
        <h1>用户管理</h1>
        <div>
            <a href="/users/form?page=<?php echo $page; ?>" class="btn btn-primary">添加用户</a>
        </div>
    </div>
    
    <?php if ($successMessage): ?>
    <div class="alert alert-success">
        <?php 
        switch ($successMessage) {
            case 'created': echo '用户创建成功'; break;
            case 'updated': echo '用户更新成功'; break;
            case 'deleted': echo '用户删除成功'; break;
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
        <form method="GET" action="/users">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="搜索用户名、邮箱或昵称..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">搜索</button>
                    <?php if (!empty($searchTerm)): ?>
                    <a href="/users" class="btn btn-outline-secondary">清除</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
    
    <!-- 用户列表 -->
    <div class="users-table-container">
        <?php if (!empty($users)): ?>
        <table class="table table-striped users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>用户名</th>
                    <th>邮箱</th>
                    <th>昵称</th>
                    <th>状态</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['nickname']); ?></td>
                    <td>
                        <?php if ($user['status'] == 1): ?>
                            <button class="btn btn-sm btn-status status-active" 
                                    onclick="updateUserStatus(<?php echo $user['id']; ?>, 0, this)">活跃</button>
                        <?php else: ?>
                            <button class="btn btn-sm btn-status status-inactive" 
                                    onclick="updateUserStatus(<?php echo $user['id']; ?>, 1, this)">禁用</button>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('Y-m-d H:i', strtotime($user['created_at'])); ?></td>
                    <td>
                        <a href="/users/detail/<?php echo $user['id']; ?>?page=<?php echo $page; ?>" class="btn btn-sm btn-info">查看</a>
                        <a href="/users/form/<?php echo $user['id']; ?>?page=<?php echo $page; ?>" class="btn btn-sm btn-warning">编辑</a>
                        <!-- 直接在按钮上添加onclick事件 -->
                        <button class="btn btn-sm btn-danger delete-user" 
                                onclick="showDeleteModal(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username'], ENT_QUOTES); ?>')">
                            删除
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- 分页 -->
        <?php if ($totalPages > 1): ?>
        <nav aria-label="用户列表分页">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="/users?page=<?php echo $page - 1; ?><?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>">上一页</a>
                </li>
                <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">上一页</span>
                </li>
                <?php endif; ?>
                
                <?php
                // 计算分页范围
                $startPage = max(1, $page - 2);
                $endPage = min($totalPages, $page + 2);
                
                // 如果起始页大于1，显示第一页和省略号
                if ($startPage > 1) {
                    echo '<li class="page-item"><a class="page-link" href="/users?page=1' . (!empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '') . '">1</a></li>';
                    if ($startPage > 2) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                }
                
                // 显示页码
                for ($i = $startPage; $i <= $endPage; $i++) {
                    if ($i == $page) {
                        echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="/users?page=' . $i . (!empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '') . '">' . $i . '</a></li>';
                    }
                }
                
                // 如果结束页小于总页数，显示省略号和最后一页
                if ($endPage < $totalPages) {
                    if ($endPage < $totalPages - 1) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                    echo '<li class="page-item"><a class="page-link" href="/users?page=' . $totalPages . (!empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '') . '">' . $totalPages . '</a></li>';
                }
                ?>
                
                <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="/users?page=<?php echo $page + 1; ?><?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>">下一页</a>
                </li>
                <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">下一页</span>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
        <?php else: ?>
        <div class="no-users">
            <p>暂无用户数据<?php echo !empty($searchTerm) ? '匹配 "' . htmlspecialchars($searchTerm) . '"' : ''; ?>。</p>
            <a href="/users/form" class="btn btn-primary">添加第一个用户</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- 删除确认模态框 -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">确认删除</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>确定要删除用户 <strong id="deleteUserName"></strong> 吗？此操作不可撤销。</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-danger" id="confirmDelete" onclick="confirmDelete()">确定</button>
            </div>
        </div>
    </div>
</div>

<!-- 删除结果提示框 -->
<div class="toast" id="deleteToast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <strong class="mr-auto" id="toastTitle">提示</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body" id="toastMessage">
    </div>
</div>