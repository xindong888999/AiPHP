<?php
// Contract anchors: 必须先读 prompts/page_management_guidelines.md 与 docs/callable_contracts.md
/**
 * AiPHP框架新闻页面
 * 中国9.3阅兵新闻
 */
//布局变量
$layout="green_layout";

// 设置页面特定变量
$pageTitle = '中国9.3阅兵新闻 - AiPHP框架';
$pageDescription = '中国9.3阅兵新闻，展示中国人民解放军的强大实力和精神风貌。';
$pageKeywords = '中国,9.3阅兵,抗战胜利纪念日,军事,新闻,AiPHP';

// 如果使用布局，需要引入对应的CSS和JS文件
$additionalCSS = ['/static/own/css/news.css'];
$additionalJS = ['/static/own/js/news.js'];
?>
<div class="news-page">
    <!-- 新闻标题区域 -->
    <section class="news-header">
        <h1>中国举行盛大阅兵式 纪念抗战胜利70周年</h1>
        <div class="news-meta">
            <span class="news-date">2015年9月3日</span>
            <span class="news-category">军事新闻</span>
        </div>
    </section>

    <!-- 新闻内容区域 -->
    <section class="news-content">
        <div class="news-image">
            <img src="/static/own/images/military_parade.svg" alt="中国9.3阅兵式" class="main-image">
            <p class="image-caption">2015年9月3日，中国在北京天安门广场举行盛大阅兵式</p>
        </div>
        
        <article class="news-article">
            <p>2015年9月3日，中国在北京天安门广场举行了盛大的阅兵式，纪念中国人民抗日战争暨世界反法西斯战争胜利70周年。这是中国历史上规模最大、参与国家最多的一次阅兵式，充分展示了中国人民解放军的强大实力和精神风貌。</p>
            
            <p>本次阅兵式共有11个徒步方队、27个装备方队和10个空中梯队参加，约12000名官兵、500多件主战装备和近200架各型飞机接受检阅。来自50多个国家的领导人和代表出席了阅兵式，共同见证这一历史性时刻。</p>
            
            <h2>阅兵亮点</h2>
            
            <p>此次阅兵式首次以纪念卫国战争胜利为主题，彰显了中国人民为世界反法西斯战争胜利作出的重要贡献。阅兵式上，中国展示了多款新型武器装备，包括东风-21D反舰弹道导弹、东风-26中远程弹道导弹等，这些装备代表了中国国防科技的最新成就。</p>
            
            <p>习近平主席在阅兵式上发表重要讲话，强调中国将坚定不移走和平发展道路，永远不称霸，永远不搞扩张。同时，他宣布中国将裁军30万，展示了中国维护世界和平的决心。</p>
            
            <h2>国际影响</h2>
            
            <p>此次阅兵式得到了国际社会的广泛关注。多国领导人出席阅兵式，体现了对中国在二战中作出贡献的认可，也表明了共同铭记历史、维护和平的决心。</p>
            
            <p>分析人士指出，此次阅兵式不仅是对历史的纪念，也是中国向世界展示和平发展决心的重要平台。通过这一国际性活动，中国进一步提升了国际影响力和话语权。</p>
            
            <h2>历史意义</h2>
            
            <p>9.3阅兵式具有重要的历史意义。它不仅是对中国人民抗日战争胜利的纪念，也是对世界反法西斯战争胜利的庆祝。通过这一活动，中国向世界传递了铭记历史、珍爱和平、开创未来的重要信息。</p>
            
            <p>专家认为，此次阅兵式的成功举行，有助于增强中华民族的凝聚力和自信心，激励中国人民为实现中华民族伟大复兴的中国梦而努力奋斗。</p>
        </article>
        
        <div class="news-related">
            <h3>相关新闻</h3>
            <ul>
                <li><a href="#">习近平会见出席阅兵式的外国元首和政府代表</a></li>
                <li><a href="#">阅兵式上亮相的新型武器装备解析</a></li>
                <li><a href="#">外国媒体热议中国9.3阅兵式</a></li>
            </ul>
        </div>
    </section>
</div>