// {module_title}表单页面JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // 表单验证
    const {module_dir}Form = document.getElementById('{module_dir}-form');
    if ({module_dir}Form) {
        {module_dir}Form.addEventListener('submit', function(e) {
            // 添加表单验证逻辑
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
});