// 文章表单页面JavaScript

// 初始化表单
function initForm() {
    // 监听表单提交事件
    const form = document.getElementById('articleForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // 表单验证
            if (!validateForm()) {
                e.preventDefault();
                return false;
            }
            
            // 禁用提交按钮，防止重复提交
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = '提交中...';
            }
        });
    }
    
    // 监听表单输入，实时验证
    const inputs = document.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            // 简单的实时验证，根据输入内容启用/禁用提交按钮
            validateForm();
        });
    });
    
    // 监听状态切换按钮
    const statusToggle = document.getElementById('statusToggle');
    if (statusToggle) {
        statusToggle.addEventListener('change', function() {
            // 可以在这里添加状态切换的额外逻辑
            console.log('状态切换为:', this.checked ? '启用' : '禁用');
        });
    }
}

// 表单验证
function validateForm() {
    let isValid = true;
    const errorMessages = [];
    
    // 验证标题
    const title = document.getElementById('title');
    if (title && title.value.trim() === '') {
        isValid = false;
        errorMessages.push('请输入文章标题');
        addErrorClass(title);
    } else if (title && title.value.length > 255) {
        isValid = false;
        errorMessages.push('文章标题不能超过255个字符');
        addErrorClass(title);
    } else if (title) {
        removeErrorClass(title);
    }
    
    // 验证内容
    const content = document.getElementById('content');
    if (content && content.value.trim() === '') {
        isValid = false;
        errorMessages.push('请输入文章内容');
        addErrorClass(content);
    } else if (content && content.value.length > 5000) {
        isValid = false;
        errorMessages.push('文章内容不能超过5000个字符');
        addErrorClass(content);
    } else if (content) {
        removeErrorClass(content);
    }
    
    // 验证分类
    const category = document.getElementById('category');
    if (category && category.value === '') {
        isValid = false;
        errorMessages.push('请选择文章分类');
        addErrorClass(category);
    } else if (category) {
        removeErrorClass(category);
    }
    
    // 显示错误信息
    const errorContainer = document.getElementById('errorMessages');
    if (errorContainer) {
        if (errorMessages.length > 0) {
            errorContainer.innerHTML = errorMessages.map(msg => `<div class="error">${msg}</div>`).join('');
            errorContainer.style.display = 'block';
        } else {
            errorContainer.innerHTML = '';
            errorContainer.style.display = 'none';
        }
    }
    
    // 启用/禁用提交按钮
    const submitButton = document.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.disabled = !isValid;
    }
    
    return isValid;
}

// 添加错误样式
function addErrorClass(element) {
    if (element && !element.classList.contains('error')) {
        element.classList.add('error');
        // 添加错误样式
        element.style.borderColor = '#dc3545';
        element.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
    }
}

// 移除错误样式
function removeErrorClass(element) {
    if (element && element.classList.contains('error')) {
        element.classList.remove('error');
        // 移除错误样式
        element.style.borderColor = '';
        element.style.boxShadow = '';
    }
}

// 显示成功提示
function showSuccessMessage(message) {
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
        toast.style.background = '#28a745';
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

// 显示错误提示
function showErrorMessage(message) {
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
        toast.style.background = '#dc3545';
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

// 返回列表页面
function goBackToList() {
    window.location.href = '/articles';
}

// 初始化函数
function initialize() {
    // 当页面加载完成后执行初始化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initForm);
    } else {
        initForm();
    }
    
    // 检查URL参数，显示操作结果消息
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === 'true') {
        showSuccessMessage(urlParams.get('message') || '操作成功');
    } else if (urlParams.get('success') === 'false') {
        showErrorMessage(urlParams.get('message') || '操作失败');
    }
}

// 执行初始化
initialize();