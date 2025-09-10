// 用户表单页面JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // 表单验证
    const userForm = document.getElementById('user-form');
    if (userForm) {
        userForm.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            
            // 验证用户名
            if (username.length < 3) {
                alert('用户名至少3个字符');
                e.preventDefault();
                return;
            }
            
            if (username.length > 50) {
                alert('用户名不能超过50个字符');
                e.preventDefault();
                return;
            }
            
            // 验证邮箱（支持中文邮箱）
            const emailRegex = /^[\w\-\.\x{4e00}-\x{9fa5}]+@[\w\-\.\x{4e00}-\x{9fa5}]+\.[\w\-\.\x{4e00}-\x{9fa5}]+$/u;
            if (!emailRegex.test(email)) {
                alert('请输入有效的邮箱地址');
                e.preventDefault();
                return;
            }
            
            if (email.length > 100) {
                alert('邮箱不能超过100个字符');
                e.preventDefault();
                return;
            }
        });
    }
    
    // 实时验证用户名长度
    const usernameInput = document.getElementById('username');
    if (usernameInput) {
        const usernameHelp = document.createElement('div');
        usernameHelp.className = 'form-text';
        usernameHelp.id = 'username-help';
        usernameInput.parentNode.appendChild(usernameHelp);
        
        usernameInput.addEventListener('input', function() {
            const length = this.value.length;
            usernameHelp.textContent = `${length}/50 字符`;
            if (length > 50) {
                usernameHelp.style.color = 'red';
            } else if (length > 40) {
                usernameHelp.style.color = 'orange';
            } else {
                usernameHelp.style.color = 'gray';
            }
        });
    }
    
    // 实时验证邮箱长度
    const emailInput = document.getElementById('email');
    if (emailInput) {
        const emailHelp = document.createElement('div');
        emailHelp.className = 'form-text';
        emailHelp.id = 'email-help';
        emailInput.parentNode.appendChild(emailHelp);
        
        emailInput.addEventListener('input', function() {
            const length = this.value.length;
            emailHelp.textContent = `${length}/100 字符`;
            if (length > 100) {
                emailHelp.style.color = 'red';
            } else if (length > 80) {
                emailHelp.style.color = 'orange';
            } else {
                emailHelp.style.color = 'gray';
            }
        });
    }
    
    // 为所有按钮添加悬停效果
    const allButtons = document.querySelectorAll('.btn');
    allButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // 表单字段焦点效果
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.style.borderColor = '#28a745';
            this.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
        });
        
        control.addEventListener('blur', function() {
            this.style.borderColor = '#ced4da';
            this.style.boxShadow = 'none';
        });
    });
    
    // 处理表单提交后的重定向
    const urlParams = new URLSearchParams(window.location.search);
    const successParam = urlParams.get('success');
    const pageParam = urlParams.get('page');
    
    if (successParam) {
        // 如果有成功参数，设置一个定时器在几秒后自动返回到用户列表页
        setTimeout(function() {
            let redirectUrl = '/users';
            if (pageParam) {
                redirectUrl += '?page=' + encodeURIComponent(pageParam);
            }
            window.location.href = redirectUrl;
        }, 2000); // 2秒后自动跳转
    }
});