// 绿色主题布局的JavaScript文件

// 页面加载完成后执行的代码
document.addEventListener('DOMContentLoaded', function() {
    // 为导航链接添加交互效果
    const navLinks = document.querySelectorAll('.main-nav a');
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(255, 255, 255, 0.2)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // 为页脚链接添加交互效果
    const footerLinks = document.querySelectorAll('.footer-links a');
    footerLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.color = 'white';
            this.style.textDecoration = 'underline';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.color = '';
            this.style.textDecoration = '';
        });
    });
    
    // 平滑滚动效果
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80, // 减去头部高度
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // 响应式菜单处理
    const handleResize = () => {
        const header = document.querySelector('.green-header');
        const container = document.querySelector('.green-header .container');
        
        if (window.innerWidth <= 768) {
            // 移动端样式调整
            header.style.padding = '1rem 0';
        } else {
            // 桌面端样式调整
            header.style.padding = '1rem 0';
        }
    };
    
    // 初始化时执行一次
    handleResize();
    
    // 监听窗口大小变化
    window.addEventListener('resize', handleResize);
    
    // 页面加载动画
    const fadeInElements = document.querySelectorAll('.green-main > *');
    fadeInElements.forEach((element, index) => {
        setTimeout(() => {
            element.style.opacity = '0';
            element.style.transition = 'opacity 0.5s ease';
            
            setTimeout(() => {
                element.style.opacity = '1';
            }, 100);
        }, index * 100);
    });
});