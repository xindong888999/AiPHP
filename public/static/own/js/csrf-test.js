/**
 * CSRF保护测试页面的JavaScript功能
 * 实现AJAX请求示例和其他交互功能
 */

// 等待DOM加载完成
document.addEventListener('DOMContentLoaded', function() {
    // 处理AJAX请求示例
    setupAjaxDemo();
    
    // 为所有表单添加CSRF令牌刷新功能
    setupFormTokenRefresh();
});

/**
 * 设置AJAX请求演示
 */
function setupAjaxDemo() {
    const ajaxButton = document.getElementById('ajaxButton');
    const ajaxResult = document.getElementById('ajaxResult');
    
    if (ajaxButton && ajaxResult) {
        ajaxButton.addEventListener('click', function() {
            // 显示加载状态
            ajaxResult.textContent = '正在发送AJAX请求...';
            ajaxResult.className = 'loading';
            
            // 获取CSRF令牌
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // 模拟AJAX请求
            setTimeout(function() {
                // 在实际应用中，这里会发送真实的AJAX请求
                // 示例中使用setTimeout模拟网络延迟
                
                // 构建模拟数据
                const data = {
                    timestamp: new Date().toLocaleString(),
                    status: 'success',
                    message: 'AJAX请求成功！CSRF令牌已正确包含在请求头中。',
                    csrfTokenUsed: csrfToken.substring(0, 10) + '...'
                };
                
                // 显示结果
                ajaxResult.innerHTML = `
                    <div style="margin-bottom: 10px; font-weight: 500;">请求结果：</div>
                    <div><strong>时间戳：</strong>${data.timestamp}</div>
                    <div><strong>状态：</strong>${data.status}</div>
                    <div><strong>消息：</strong>${data.message}</div>
                    <div><strong>使用的CSRF令牌：</strong>${data.csrfTokenUsed}</div>
                `;
                ajaxResult.className = 'success';
                
                // 更新CSRF令牌（实际应用中可能需要从服务器获取新令牌）
                updateCsrfToken();
            }, 1000);
        });
    }
}

/**
 * 设置表单令牌刷新功能
 * 防止表单提交时令牌过期
 */
function setupFormTokenRefresh() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        // 为每个表单添加提交事件监听器
        form.addEventListener('submit', function() {
            // 在提交前检查令牌是否可能过期
            // 这里简单实现，实际应用中可以根据服务器返回的令牌有效期进行判断
            
            // 获取当前时间
            const now = new Date().getTime();
            
            // 检查表单中是否存在CSRF令牌字段
            const tokenField = form.querySelector('input[name="csrf_token"]');
            if (tokenField) {
                // 如果存在令牌字段，可以在这里添加检查逻辑
                // 例如：检查令牌是否过期，是否需要刷新
                
                // 在这个示例中，我们简单地记录日志
                console.log('表单提交，CSRF令牌已包含在请求中');
            }
        });
    });
}

/**
 * 更新CSRF令牌
 * 在AJAX请求后刷新页面上的令牌
 */
function updateCsrfToken() {
    // 注意：在实际应用中，应该从服务器获取新的CSRF令牌
    // 这里我们简单地生成一个随机字符串来模拟令牌更新
    const newToken = generateRandomToken();
    
    // 更新meta标签中的令牌
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    if (metaToken) {
        metaToken.setAttribute('content', newToken);
    }
    
    // 更新所有表单中的令牌字段
    const tokenFields = document.querySelectorAll('input[name="csrf_token"]');
    tokenFields.forEach(field => {
        field.value = newToken;
    });
    
    console.log('CSRF令牌已更新');
}

/**
 * 生成随机令牌（仅用于演示）
 * 在实际应用中，应该使用服务器生成的令牌
 */
function generateRandomToken() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let token = '';
    
    for (let i = 0; i < 32; i++) {
        token += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    
    return token;
}

/**
 * 示例：如何在fetch请求中使用CSRF令牌
 * 这个函数展示了在实际项目中如何发送带CSRF保护的AJAX请求
 */
function fetchWithCsrfToken(url, options = {}) {
    // 获取CSRF令牌
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // 合并默认选项和用户提供的选项
    const fetchOptions = {
        ...options,
        headers: {
            'X-CSRF-Token': csrfToken,
            ...(options.headers || {})
        },
        credentials: 'same-origin' // 确保包含cookies
    };
    
    // 发送fetch请求
    return fetch(url, fetchOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error(`服务器响应错误: ${response.status}`);
            }
            return response.json();
        })
        .catch(error => {
            console.error('AJAX请求错误:', error);
            throw error;
        });
}

// 导出函数，方便在其他脚本中使用（如果需要）
if (typeof module !== 'undefined') {
    module.exports = {
        fetchWithCsrfToken,
        updateCsrfToken
    };
}