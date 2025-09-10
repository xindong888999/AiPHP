<?php
// 关于页面

// 布局变量
$layout="green_layout";

// 设置页面特定变量
$pageTitle = '关于我们 - AiPHP应用';
$pageDescription = '关于AiPHP框架的信息';
$pageKeywords = 'AiPHP, 关于我们, PHP框架';

// 引入对应的CSS和JS文件
$additionalCSS = ['/static/own/css/about.css'];
$additionalJS = ['/static/own/js/about.js'];

?>
<div class="about-page">
    <h1>关于我们</h1>
    
    <div class="intro">
        <p>AiPHP是一个专为AI开发工具设计的现代化PHP框架。</p>
    </div>
    
    <div class="section">
        <h2>我们的使命</h2>
        <p>我们的使命是创建一个对AI友好的开发框架，解决现有框架在AI辅助开发中的各种问题。</p>
    </div>
    
    <div class="section">
        <h2>框架特点</h2>
        <ul>
            <li>简洁清晰的代码结构</li>
            <li>统一的命名规范</li>
            <li>对AI工具友好的设计</li>
            <li>易于理解和维护</li>
            <li>现代化的PHP特性支持</li>
        </ul>
    </div>
    
    <div class="section">
        <h2>为什么选择AiPHP</h2>
        <p>与其他框架相比，AiPHP专门针对AI辅助开发进行了优化，可以显著提高AI工具生成代码的准确性和效率。</p>
    </div>
</div>