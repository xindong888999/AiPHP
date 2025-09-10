// 关于页面的JavaScript代码

document.addEventListener('DOMContentLoaded', function() {
    // 添加页面加载完成后的处理逻辑
    console.log('关于页面加载完成');
    
    // 为页面标题添加动画效果
    const aboutPage = document.querySelector('.about-page');
    if (aboutPage) {
        const pageTitle = aboutPage.querySelector('h1');
        if (pageTitle) {
            pageTitle.style.opacity = '0';
            pageTitle.style.transform = 'translateY(-20px)';
            pageTitle.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
            setTimeout(() => {
                pageTitle.style.opacity = '1';
                pageTitle.style.transform = 'translateY(0)';
            }, 100);
        }
        
        // 为各个章节添加渐入效果
        const sections = aboutPage.querySelectorAll('.intro, .section');
        sections.forEach((section, index) => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            section.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
            setTimeout(() => {
                section.style.opacity = '1';
                section.style.transform = 'translateY(0)';
            }, 300 + (index * 200));
        });
    }
});