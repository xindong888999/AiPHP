/**
 * 新闻页面JavaScript文件
 * 提供新闻页面的交互功能
 */

document.addEventListener('DOMContentLoaded', function() {
    // 初始化新闻页面功能
    initNewsPage();
});

/**
 * 初始化新闻页面功能
 */
function initNewsPage() {
    // 添加图片点击放大功能
    initImageZoom();
    
    // 添加相关新闻链接点击事件
    initRelatedNewsLinks();
    
    console.log('新闻页面初始化完成');
}

/**
 * 初始化图片点击放大功能
 */
function initImageZoom() {
    const mainImage = document.querySelector('.main-image');
    
    if (mainImage) {
        mainImage.addEventListener('click', function() {
            // 创建遮罩层
            const overlay = document.createElement('div');
            overlay.className = 'image-overlay';
            overlay.style.position = 'fixed';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100%';
            overlay.style.height = '100%';
            overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.9)';
            overlay.style.display = 'flex';
            overlay.style.justifyContent = 'center';
            overlay.style.alignItems = 'center';
            overlay.style.zIndex = '1000';
            overlay.style.cursor = 'pointer';
            
            // 创建放大的图片
            const zoomedImage = document.createElement('img');
            zoomedImage.src = this.src;
            zoomedImage.style.maxWidth = '90%';
            zoomedImage.style.maxHeight = '90%';
            zoomedImage.style.objectFit = 'contain';
            zoomedImage.style.border = '2px solid white';
            
            // 添加关闭按钮
            const closeButton = document.createElement('span');
            closeButton.innerHTML = '&times;';
            closeButton.style.position = 'absolute';
            closeButton.style.top = '20px';
            closeButton.style.right = '30px';
            closeButton.style.color = 'white';
            closeButton.style.fontSize = '40px';
            closeButton.style.cursor = 'pointer';
            
            // 添加到页面
            overlay.appendChild(zoomedImage);
            overlay.appendChild(closeButton);
            document.body.appendChild(overlay);
            
            // 点击关闭
            overlay.addEventListener('click', function() {
                document.body.removeChild(overlay);
            });
        });
        
        // 添加鼠标悬停效果
        mainImage.style.cursor = 'zoom-in';
    }
}

/**
 * 初始化相关新闻链接点击事件
 */
function initRelatedNewsLinks() {
    const relatedLinks = document.querySelectorAll('.news-related a');
    
    relatedLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // 显示一个简单的提示，表明这是一个示例链接
            alert('这是一个示例链接，实际功能尚未实现。');
        });
    });
}