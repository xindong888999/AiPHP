<?php
// Contract anchors: 必须先读 prompts/page_management_guidelines.md 与 docs/callable_contracts.md
/**
 * AiPHP框架首页
 * 全球首款全面支持AI编程的PHP框架
 */
//布局变量
$layout="green_layout";

// 设置页面特定变量
$pageTitle = 'AiPHP框架 - 全球首款AI编程友好型PHP框架';
$pageDescription = 'AiPHP框架，全球首款全面支持AI编程的PHP框架，只需要使用自然语言，就可以轻松构建自己的Web程序。';
$pageKeywords = 'AiPHP,PHP框架,AI编程,自然语言编程,Web开发,简易编程';

// 如果使用布局，需要引入对应的CSS和JS文件
$additionalCSS = ['/static/own/css/home.css'];
$additionalJS = ['/static/own/js/home.js'];
?>
<div class="home-page">
    <!-- 英雄区域 -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>AiPHP框架</h1>
            <p class="hero-subtitle">全球首款全面支持AI编程的PHP框架</p>
            <p class="hero-desc">只需要使用自然语言，就可以轻松构建自己的Web程序</p>
            <div class="hero-cta">
                <a href="#features" class="btn-primary">了解特性</a>
                <a href="/about" class="btn-secondary">关于我们</a>
            </div>
        </div>
    </section>

    <!-- 特性区域 -->
    <section id="features" class="features-section">
        <div class="section-header">
            <h2>创新特性</h2>
            <p>打破传统编程壁垒，让AI工具更理解你的需求</p>
        </div>
        
        <div class="features-grid">
            <!-- 特性1 -->
            <div class="feature-item">
                <div class="feature-icon">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24 4L2 18L24 32L46 18L24 4Z" stroke="#40916c" stroke-width="2" fill="none"/>
                        <path d="M24 4V28L46 18" stroke="#40916c" stroke-width="2"/>
                        <path d="M24 4V44" stroke="#40916c" stroke-width="2"/>
                    </svg>
                </div>
                <h3>独创架构</h3>
                <p>路由到页面，没有臃肿的MVC、依赖注入、中间件等复杂概念，让AI工具更理解你的代码结构。</p>
            </div>
            
            <!-- 特性2 -->
            <div class="feature-item">
                <div class="feature-icon">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 12H32" stroke="#40916c" stroke-width="2" stroke-linecap="round"/>
                        <path d="M16 24H32" stroke="#40916c" stroke-width="2" stroke-linecap="round"/>
                        <path d="M16 36H32" stroke="#40916c" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <h3>自然语言编程</h3>
                <p>只需用中文或英文描述你的需求，AI即可生成符合规范的代码，无需深入了解复杂的编程细节。</p>
            </div>
            
            <!-- 特性3 -->
            <div class="feature-item">
                <div class="feature-icon">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 36H36C38.2091 36 40 34.2091 40 32V16C40 13.7909 38.2091 12 36 12H12C9.79086 12 8 13.7909 8 16V32C8 34.2091 9.79086 36 12 36Z" stroke="#40916c" stroke-width="2"/>
                        <path d="M24 20V28" stroke="#40916c" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 24L20 16L28 24L36 16" stroke="#40916c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3>丰富提示词系统</h3>
                <p>内置丰富的提示词库，确保生成的代码风格统一、质量高，可以无限拓展功能，满足各种开发需求。</p>
            </div>
            
            <!-- 特性4 -->
            <div class="feature-item">
                <div class="feature-icon">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="24" cy="12" r="4" stroke="#40916c" stroke-width="2"/>
                        <path d="M24 20V28" stroke="#40916c" stroke-width="2" stroke-linecap="round"/>
                        <path d="M30 36H18L16 38L24 44L32 38L30 36Z" stroke="#40916c" stroke-width="2"/>
                    </svg>
                </div>
                <h3>模块化设计</h3>
                <p>独立的功能模块，便于调试和集成，同时支持第三方库集成，让你的项目具有高度的灵活性和可扩展性。</p>
            </div>
            
            <!-- 特性5 -->
            <div class="feature-item">
                <div class="feature-icon">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 12L24 8L32 12" stroke="#40916c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 24H40" stroke="#40916c" stroke-width="2" stroke-linecap="round"/>
                        <path d="M16 36L24 40L32 36" stroke="#40916c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3>AI工具兼容性</h3>
                <p>不管使用哪种AI工具，都能完美支持，让你可以自由选择最适合自己的AI助手来加速开发过程。</p>
            </div>
            
            <!-- 特性6 -->
            <div class="feature-item">
                <div class="feature-icon">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 16L24 8L36 16" stroke="#40916c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 24H40" stroke="#40916c" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 32L24 40L36 32" stroke="#40916c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3>快速开发</h3>
                <p>极简的开发流程，让你从构思到实现只需几分钟，大幅提升开发效率，降低开发成本。</p>
            </div>
        </div>
    </section>

    <!-- 优势区域 -->
    <section class="advantages-section">
        <div class="section-header">
            <h2>核心优势</h2>
            <p>AiPHP框架如何改变你的开发体验</p>
        </div>
        
        <div class="advantages-content">
            <div class="advantage-text">
                <h3>让编程变得更简单</h3>
                <p>AiPHP框架打破了传统编程的复杂性，让你可以用自然语言描述需求，AI工具就能生成高质量的代码。不需要深入学习复杂的编程概念，也能快速构建功能完善的Web应用。</p>
                <ul>
                    <li>无需掌握复杂的编程语法</li>
                    <li>降低学习门槛，提高开发效率</li>
                    <li>统一的代码风格，易于维护</li>
                    <li>快速迭代，响应市场需求</li>
                </ul>
            </div>
            <div class="advantage-image">
                <svg width="500" height="300" viewBox="0 0 500 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="50" y="50" width="400" height="200" rx="10" fill="#e8f5e9" stroke="#40916c" stroke-width="2"/>
                    <path d="M100 100L150 150L100 200" stroke="#40916c" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M200 100L250 150L200 200" stroke="#40916c" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M300 100L350 150L300 200" stroke="#40916c" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M400 100L450 150L400 200" stroke="#40916c" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    <text x="250" y="80" text-anchor="middle" fill="#2d6a4f" font-size="16" font-weight="bold">自然语言描述</text>
                    <text x="250" y="250" text-anchor="middle" fill="#2d6a4f" font-size="16" font-weight="bold">高质量代码输出</text>
                    <text x="480" y="150" text-anchor="middle" fill="#2d6a4f" font-size="16" font-weight="bold">AI</text>
                </svg>
            </div>
        </div>
    </section>

    <!-- 使用流程区域 -->
    <section class="process-section">
        <div class="section-header">
            <h2>简单三步，轻松开发</h2>
            <p>使用AiPHP框架，让Web开发变得前所未有的简单</p>
        </div>
        
        <div class="process-steps">
            <!-- 步骤1 -->
            <div class="step-item">
                <div class="step-number">1</div>
                <h3>描述需求</h3>
                <p>用自然语言描述你想要实现的功能，越详细越好。</p>
            </div>
            
            <!-- 步骤2 -->
            <div class="step-item">
                <div class="step-number">2</div>
                <h3>AI生成代码</h3>
                <p>AI工具根据你的描述，生成符合AiPHP框架规范的代码。</p>
            </div>
            
            <!-- 步骤3 -->
            <div class="step-item">
                <div class="step-number">3</div>
                <h3>部署运行</h3>
                <p>将生成的代码部署到服务器上，即可运行你的Web应用。</p>
            </div>
        </div>
    </section>

    <!-- 号召性区域 -->
    <section class="cta-section">
        <div class="cta-content">
            <h2>准备好体验未来的编程方式了吗？</h2>
            <p>加入AiPHP开发者社区，开启AI编程新时代</p>
            <div class="cta-buttons">
                <a href="#" class="btn-primary">立即开始</a>
                <a href="/about" class="btn-secondary">了解更多</a>
                <a href="/csrf-test.php" class="btn-secondary">测试CSRF保护</a>
            </div>
        </div>
    </section>
</div>