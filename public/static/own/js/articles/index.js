// 文章列表页面JavaScript

// 定义全局函数，确保HTML可以直接调用
function showDeleteModal(articleId, articleName) {
    // 当前要删除的文章信息
    window.currentDeleteArticleId = articleId;
    window.currentDeleteArticleName = articleName;
    
    const modal = document.getElementById('deleteModal');
    const articleNameElement = document.getElementById('deleteArticleName');
    
    if (modal && articleNameElement) {
        articleNameElement.textContent = articleName;
        // 使用自定义的modal显示方式
        modal.classList.add('show');
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
        // 添加modal-open类到body以防止背景滚动
        document.body.classList.add('modal-open');
        // 添加modal-backdrop
        let backdrop = document.querySelector('.modal-backdrop');
        if (!backdrop) {
            backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop';
            document.body.appendChild(backdrop);
        }
        // 显示背景遮罩
        setTimeout(() => {
            backdrop.classList.add('show');
        }, 10);
    }
}

// 确认删除
function confirmDelete() {
    if (window.currentDeleteArticleId) {
        deleteArticle(window.currentDeleteArticleId);
    }
}

// 隐藏删除确认模态框
function hideDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const backdrop = document.querySelector('.modal-backdrop');
    
    if (modal) {
        // 使用自定义的modal隐藏方式
        modal.classList.remove('show');
        // 移除modal-open类
        document.body.classList.remove('modal-open');
        // 移除背景遮罩
        if (backdrop) {
            backdrop.classList.remove('show');
            setTimeout(() => {
                if (backdrop.parentNode) {
                    backdrop.parentNode.removeChild(backdrop);
                }
            }, 300);
        }
        // 隐藏modal
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
    }
    
    // 清除当前删除的文章信息
    window.currentDeleteArticleId = null;
    window.currentDeleteArticleName = '';
}

// 删除文章
function deleteArticle(articleId) {
    // 获取CSRF令牌
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        alert('CSRF令牌获取失败，请刷新页面后重试');
        hideDeleteModal();
        return;
    }
    
    // 发送AJAX请求删除文章
    fetch('/articles/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify({
            id: articleId,
            _csrf_token: csrfToken
        })
    })
    .then(response => response.json())
    .then(data => {
        hideDeleteModal();
        
        if (data.success) {
            // 显示成功提示
            showToast('文章删除成功');
            // 刷新页面或移除对应的行
            const table = document.querySelector('.articles-table');
            if (table) {
                // 查找并移除对应行
                const rows = table.querySelectorAll('tr');
                rows.forEach(row => {
                    const idCell = row.querySelector('td:first-child');
                    if (idCell && parseInt(idCell.textContent) === articleId) {
                        row.style.opacity = '0';
                        row.style.transition = 'opacity 0.3s ease';
                        setTimeout(() => {
                            row.remove();
                            // 检查是否还有数据行
                            const remainingRows = table.querySelectorAll('tbody tr');
                            if (remainingRows.length === 0) {
                                const tbody = table.querySelector('tbody');
                                if (tbody) {
                                    tbody.innerHTML = '<tr><td colspan="5" class="no-data">暂无数据</td></tr>';
                                }
                            }
                        }, 300);
                    }
                });
            }
        } else {
            // 显示错误提示
            alert(data.message || '删除失败，请稍后重试');
        }
    })
    .catch(error => {
        hideDeleteModal();
        console.error('Error deleting article:', error);
        alert('删除失败，请稍后重试');
    });
}

// 更新文章状态
function updateArticleStatus(articleId, status) {
    // 获取CSRF令牌
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        alert('CSRF令牌获取失败，请刷新页面后重试');
        return;
    }
    
    // 发送AJAX请求更新状态
    fetch('/articles/update-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify({
            id: articleId,
            status: status,
            _csrf_token: csrfToken
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // 显示成功提示
            showToast('状态更新成功');
            // 更新按钮状态
            const table = document.querySelector('.articles-table');
            if (table) {
                // 查找并更新对应行的状态按钮
                const rows = table.querySelectorAll('tr');
                rows.forEach(row => {
                    const idCell = row.querySelector('td:first-child');
                    if (idCell && parseInt(idCell.textContent) === articleId) {
                        const statusButton = row.querySelector('.btn-status');
                        if (statusButton) {
                            statusButton.textContent = data.data.statusText;
                            statusButton.className = `btn-status ${data.data.status === 1 ? 'status-active' : 'status-inactive'}`;
                            statusButton.onclick = () => updateArticleStatus(articleId, data.data.status === 1 ? 0 : 1);
                        }
                    }
                });
            }
        } else {
            // 显示错误提示
            alert(data.message || '状态更新失败，请稍后重试');
        }
    })
    .catch(error => {
        console.error('Error updating article status:', error);
        alert('状态更新失败，请稍后重试');
    });
}

// 显示提示消息
function showToast(message) {
    // 检查是否已存在toast元素
    let toast = document.getElementById('toastMessage');
    
    if (!toast) {
        // 创建新的toast元素
        toast = document.createElement('div');
        toast.id = 'toastMessage';
        toast.className = 'toast';
        toast.style.position = 'fixed';
        toast.style.top = '20px';
        toast.style.right = '20px';
        toast.style.padding = '10px 15px';
        toast.style.background = '#333';
        toast.style.color = 'white';
        toast.style.borderRadius = '4px';
        toast.style.zIndex = '9999';
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s ease';
        document.body.appendChild(toast);
    }
    
    // 设置消息内容
    toast.textContent = message;
    
    // 显示toast
    toast.style.opacity = '1';
    
    // 3秒后隐藏toast
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// 为模态框的关闭按钮添加事件监听
document.addEventListener('DOMContentLoaded', function() {
    // 为模态框的关闭按钮添加事件监听
    const closeButton = document.querySelector('#deleteModal .close');
    if (closeButton) {
        closeButton.addEventListener('click', hideDeleteModal);
    }
    
    // 点击模态框外部关闭模态框
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                hideDeleteModal();
            }
        });
    }
    
    // 按ESC键关闭模态框
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && document.getElementById('deleteModal').classList.contains('show')) {
            hideDeleteModal();
        }
    });
});