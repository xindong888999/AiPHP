/**
 * 文章详情页面JavaScript
 */

// 页面加载完成后执行
document.addEventListener('DOMContentLoaded', function() {
    // 返回按钮交互
    const backButton = document.querySelector('.back-button');
    
    if (backButton) {
        backButton.addEventListener('click', function(e) {
            // 可以在这里添加返回前的逻辑
            // 默认行为是导航回列表页，所以不需要阻止默认行为
        });
    }
    
    // 上下篇导航交互
    const navItems = document.querySelectorAll('.nav-item');
    
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // 可以在这里添加页面切换前的逻辑
            // 默认行为是导航到新页面，所以不需要阻止默认行为
        });
    });
    
    // 可以添加分享功能
    // 可以添加打印功能
    // 可以添加字体大小调整功能
    // 可以添加暗色模式切换功能
});