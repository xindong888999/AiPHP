// AiPHP框架首页JavaScript文件

// 页面加载完成后执行的代码
document.addEventListener('DOMContentLoaded', function() {
    // 为按钮添加交互效果
    const buttons = document.querySelectorAll('.btn-primary, .btn-secondary');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
        
        button.addEventListener('click', function(e) {
            // 阻止默认行为（如果需要）
            if (this.getAttribute('href') === '#') {
                e.preventDefault();
            }
            
            // 添加点击效果
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
    
    // 为特性卡片添加交互效果
    const featureItems = document.querySelectorAll('.feature-item');
    featureItems.forEach((item, index) => {
        // 初始动画效果
        setTimeout(() => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 100);
        }, index * 100);
        
        // 悬停效果已在CSS中定义
    });
    
    // 为流程步骤添加动画效果
    const stepItems = document.querySelectorAll('.step-item');
    stepItems.forEach((item, index) => {
        // 初始动画效果
        setTimeout(() => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 100);
        }, index * 200 + 300);
    });
    
    // 平滑滚动效果（扩展green_layout.js中的功能）
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
    
    // 滚动时的动画效果
    const animateOnScroll = () => {
        const sections = document.querySelectorAll('.features-section, .advantages-section, .process-section, .cta-section');
        
        sections.forEach(section => {
            const sectionTop = section.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (sectionTop < windowHeight * 0.8) {
                section.style.opacity = '1';
                section.style.transform = 'translateY(0)';
            }
        });
    };
    
    // 初始设置
    const sections = document.querySelectorAll('.features-section, .advantages-section, .process-section, .cta-section');
    sections.forEach(section => {
        section.style.opacity = '0.7';
        section.style.transform = 'translateY(30px)';
        section.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
    });
    
    // 初始执行一次
    animateOnScroll();
    
    // 监听滚动事件
    window.addEventListener('scroll', animateOnScroll);
    
    // 窗口大小变化时的响应
    const handleResize = () => {
        // 可以根据需要添加响应式调整
        // 这里主要依赖CSS媒体查询
    };
    
    // 监听窗口大小变化
    window.addEventListener('resize', handleResize);
});

// 添加到window对象，以便在控制台调试
window.AiPHP = window.AiPHP || {};
window.AiPHP.Home = {
    // 可以在这里添加全局可访问的方法
    init: function() {
        // 可以在需要时手动调用初始化
        console.log('AiPHP首页模块已加载');
    }
};

// 初始化首页模块
window.AiPHP.Home.init();