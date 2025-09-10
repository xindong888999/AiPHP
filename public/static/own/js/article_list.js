/**
 * 文章列表页面JavaScript
 */

// 页面加载完成后执行
document.addEventListener('DOMContentLoaded', function() {
    // 添加文章列表项的悬停动画效果
    const articleItems = document.querySelectorAll('.article-item');
    
    articleItems.forEach(item => {
        // 已在CSS中实现悬停效果
        // 这里可以添加更多交互逻辑
    });
    
    // 分页链接交互
    const paginationLinks = document.querySelectorAll('.pagination-link');
    
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // 可以在这里添加加载状态或其他逻辑
            // 默认行为是导航到新页面，所以不需要阻止默认行为
        });
    });
    
    // 可以添加搜索功能
    // 可以添加过滤功能
    // 可以添加排序功能
});