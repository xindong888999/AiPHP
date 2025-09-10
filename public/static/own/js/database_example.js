/**
 * 数据库操作示例页面脚本
 * 
 * 用于增强数据库操作示例页面的交互效果
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('数据库操作示例页面已加载');
    
    // 为示例代码块添加复制按钮
    const codeBlocks = document.querySelectorAll('pre');
    codeBlocks.forEach(function(block) {
        // 创建复制按钮
        const copyButton = document.createElement('button');
        copyButton.textContent = '复制代码';
        copyButton.className = 'copy-button';
        copyButton.style.cssText = `
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #40916c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        `;
        
        // 添加复制功能
        copyButton.addEventListener('click', function() {
            const code = block.querySelector('code') || block;
            const text = code.textContent || code.innerText;
            
            // 使用现代Clipboard API
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(function() {
                    copyButton.textContent = '已复制';
                    setTimeout(function() {
                        copyButton.textContent = '复制代码';
                    }, 2000);
                }).catch(function(err) {
                    console.error('复制失败:', err);
                });
            } else {
                // 降级方案
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    copyButton.textContent = '已复制';
                    setTimeout(function() {
                        copyButton.textContent = '复制代码';
                    }, 2000);
                } catch (err) {
                    console.error('复制失败:', err);
                }
                document.body.removeChild(textArea);
            }
        });
        
        // 将按钮添加到代码块
        block.style.position = 'relative';
        block.appendChild(copyButton);
    });
    
    // 为示例章节添加动画效果
    const exampleSections = document.querySelectorAll('.example-section');
    exampleSections.forEach(function(section, index) {
        // 添加延迟显示效果
        setTimeout(function() {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            section.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
            // 触发重排
            section.offsetHeight;
            
            // 显示元素
            section.style.opacity = '1';
            section.style.transform = 'translateY(0)';
        }, index * 200);
    });
    
    // 添加页面加载完成提示
    console.log('数据库操作示例页面脚本执行完成');
});