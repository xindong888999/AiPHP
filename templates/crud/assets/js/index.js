// {module_title}列表页面JavaScript

// 定义全局函数，确保HTML可以直接调用
function showDeleteModal({module_dir_singular}Id, {module_dir_singular}Name) {
    // 当前要删除的{module_title}信息
    window.currentDelete{module_title}Id = {module_dir_singular}Id;
    window.currentDelete{module_title}Name = {module_dir_singular}Name;
    
    const modal = document.getElementById('deleteModal');
    const {module_dir_singular}NameElement = document.getElementById('delete{module_title}Name');
    
    if (modal && {module_dir_singular}NameElement) {
        {module_dir_singular}NameElement.textContent = {module_dir_singular}Name;
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
    if (window.currentDelete{module_title}Id) {
        delete{module_title}(window.currentDelete{module_title}Id);
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
        
        // 隐藏背景遮罩并移除
        if (backdrop) {
            backdrop.classList.remove('show');
            setTimeout(() => {
                if (backdrop.parentNode) {
                    backdrop.parentNode.removeChild(backdrop);
                }
            }, 150); // 等待过渡动画完成
        }
        
        // 延迟隐藏模态框，确保过渡动画完成
        setTimeout(() => {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        }, 150);
    }
}

// 更新{module_title}状态函数
function update{module_title}Status({module_dir_singular}Id, newStatus, buttonElement) {
    // 防止重复点击
    if (buttonElement) {
        buttonElement.disabled = true;
        const originalText = buttonElement.textContent;
        buttonElement.textContent = '更新中...';
    }
    
    // 从meta标签中获取CSRF令牌
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = metaTag ? metaTag.getAttribute('content') : '';
    
    // 准备请求数据
    const requestData = {
        id: {module_dir_singular}Id,
        status: newStatus,
        _csrf_token: csrfToken
    };
    
    // 发送请求更新{module_title}状态
    fetch('/{module_dir}/update-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        // 检查响应是否为JSON格式
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.indexOf('application/json') !== -1) {
            return response.json();
        } else {
            // 尝试获取文本响应
            return response.text().then(text => {
                throw new Error('服务器响应不是有效的JSON格式: ' + text);
            });
        }
    })
    .then(data => {
        // 直接刷新页面，无论成功还是失败
        location.reload();
    })
    .catch(error => {
        // 直接刷新页面，不显示错误
        location.reload();
    });
}

// 删除{module_title}函数
function delete{module_title}({module_dir_singular}Id) {
    // 隐藏模态框
    hideDeleteModal();
    
    // 从meta标签中获取CSRF令牌
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = metaTag ? metaTag.getAttribute('content') : '';
    
    // 准备请求数据
    const requestData = {
        id: {module_dir_singular}Id,
        _csrf_token: csrfToken
    };
    
    fetch('/{module_dir}/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        // 检查响应是否为JSON格式
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.indexOf('application/json') !== -1) {
            return response.json();
        } else {
            // 尝试获取文本响应
            return response.text().then(text => {
                throw new Error('服务器响应不是有效的JSON格式: ' + text);
            });
        }
    })
    .then(data => {
        // 显示删除结果提示
        showDeleteToast(data.message || (data.success ? '删除成功' : '删除失败'), data.success);
    })
    .catch(error => {
        showDeleteToast('网络错误，请重试: ' + error.message, false);
    });
}

// 显示删除结果提示
function showDeleteToast(message, isSuccess) {
    const toast = document.getElementById('deleteToast');
    const toastTitle = document.getElementById('toastTitle');
    const toastMessage = document.getElementById('toastMessage');
    
    if (toast && toastTitle && toastMessage) {
        // 清理可能的旧内容
        toastTitle.textContent = '';
        toastMessage.textContent = '';
        
        // 设置标题和消息
        toastTitle.textContent = isSuccess ? '删除成功' : '删除失败';
        // 确保消息是纯文本，不包含HTML标签
        toastMessage.textContent = message.replace(/<[^>]*>/g, '');
        
        // 设置样式
        toast.className = 'toast';
        if (isSuccess) {
            toast.classList.add('toast-success');
        } else {
            toast.classList.add('toast-error');
        }
        
        // 显示提示框
        toast.style.display = 'block';
        toast.classList.add('show');
        
        // 1秒后自动关闭
        setTimeout(function() {
            toast.classList.remove('show');
            toast.style.display = 'none';
            // 刷新页面
            location.reload();
        }, 1000); // 1秒后关闭
    } else {
        // 如果提示框元素不存在，直接刷新页面
        location.reload();
    }
}

// 页面加载完成后初始化
document.addEventListener('DOMContentLoaded', function() {
    // 关闭模态框按钮事件
    document.addEventListener('click', function(e) {
        if (e.target.matches('[data-dismiss="modal"]') || e.target.matches('[data-dismiss="toast"]')) {
            // 检查父元素来确定要隐藏哪个模态框
            let parent = e.target.closest('.modal');
            if (!parent) {
                parent = e.target.closest('.toast');
            }
            
            if (parent) {
                if (parent.id === 'deleteModal') {
                    hideDeleteModal();
                } else {
                    parent.style.display = 'none';
                }
            }
        }
    });

    // 点击模态框背景关闭模态框
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            hideDeleteModal();
        }
    });
});