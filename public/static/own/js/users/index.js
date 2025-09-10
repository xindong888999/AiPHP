// 用户列表页面JavaScript

// 定义全局函数，确保HTML可以直接调用
function showDeleteModal(userId, username) {
    console.log('showDeleteModal called with:', {userId, username});
    
    // 当前要删除的用户信息
    window.currentDeleteUserId = userId;
    window.currentDeleteUserName = username;
    
    const modal = document.getElementById('deleteModal');
    const userNameElement = document.getElementById('deleteUserName');
    
    if (modal && userNameElement) {
        userNameElement.textContent = username;
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
        console.log('Modal displayed successfully');
    } else {
        console.error('Modal or userNameElement not found');
        console.log('Modal element:', modal);
        console.log('userNameElement:', userNameElement);
    }
}

// 确认删除
function confirmDelete() {
    console.log('confirmDelete called with currentDeleteUserId:', window.currentDeleteUserId);
    if (window.currentDeleteUserId) {
        deleteUser(window.currentDeleteUserId);
    } else {
        console.error('No user ID to delete');
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

// 更新用户状态函数
function updateUserStatus(userId, newStatus, buttonElement) {
    console.log('Updating user status:', {userId, newStatus});
    
    // 防止重复点击
    if (buttonElement) {
        buttonElement.disabled = true;
        const originalText = buttonElement.textContent;
        buttonElement.textContent = '更新中...';
    }
    
    // 从meta标签中获取CSRF令牌
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = metaTag ? metaTag.getAttribute('content') : '';
    console.log('CSRF Token from meta tag:', csrfToken);
    
    // 准备请求数据
    const requestData = {
        id: userId,
        status: newStatus,
        _csrf_token: csrfToken
    };
    
    // 发送请求更新用户状态
    fetch('/users/update-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        console.log('Status update response status:', response.status);
        
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
        console.log('Status update response data:', data);
        // 直接刷新页面，无论成功还是失败
        location.reload();
    })
    .catch(error => {
        console.error('Status update error:', error);
        // 直接刷新页面，不显示错误
        location.reload();
    });
}

// 删除用户函数
function deleteUser(userId) {
    console.log('Deleting user with ID:', userId);
    
    // 隐藏模态框
    hideDeleteModal();
    
    // 从meta标签中获取CSRF令牌
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = metaTag ? metaTag.getAttribute('content') : '';
    console.log('CSRF Token from meta tag:', csrfToken);
    
    // 准备请求数据
    const requestData = {
        id: userId,
        _csrf_token: csrfToken
    };
    
    fetch('/users/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', [...response.headers.entries()]);
        
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
        console.log('Response data:', data);
        // 显示删除结果提示
        showDeleteToast(data.message || (data.success ? '删除成功' : '删除失败'), data.success);
    })
    .catch(error => {
        console.error('Error:', error);
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
        console.error('Toast elements not found');
        // 如果提示框元素不存在，直接刷新页面
        location.reload();
    }
}

// 页面加载完成后初始化
document.addEventListener('DOMContentLoaded', function() {
    console.log('Users index.js loaded and DOM is ready');
    
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
    
    console.log('Event listeners attached');
});

// 页面完全加载后再次检查
window.addEventListener('load', function() {
    console.log('Window load event fired');
});