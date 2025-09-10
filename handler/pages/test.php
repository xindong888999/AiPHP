<?php
/**
 * 参数验证测试页面
 * 遵循AiPHP框架页面管理规范
 * 使用绿色布局
 */

// 布局变量
$layout="green_layout";

// 设置页面特定变量
$pageTitle = '参数验证测试 - AiPHP应用';
$pageDescription = '这是一个专注于参数验证功能的测试页面，展示AiPHP框架的各种参数验证规则';
$pageKeywords = '参数验证,表单验证,AiPHP,测试,ValidationHelper';

// 如果使用布局，需要引入对应的CSS和JS文件
$additionalCSS = ['/static/own/css/test.css'];
$additionalJS = ['/static/own/js/test.js'];

// 引入ValidationHelper类
use Core\OwnLibrary\Validation\ValidationHelper;

// 合并所有参数（路由参数和查询参数）
$allParams = array_merge($_GET, $params ?? []);

// 初始化验证结果数组
$validationResults = [];

// 检查是否有需要验证的参数
if (!empty($allParams)) {
    // 遍历所有参数进行验证
    foreach ($allParams as $paramName => $paramValue) {
        // 根据参数名选择相应的验证规则
        switch ($paramName) {
            case 'phone':
                // 验证手机号
                $validationResults['phone'] = ValidationHelper::validate($allParams, 'phone', [
                    '必填' => '手机号不能为空',
                    '手机' => '请输入有效的手机号'
                ]);
                break;
                
            case 'email':
                // 验证邮箱
                $validationResults['email'] = ValidationHelper::validate($allParams, 'email', [
                    '必填' => '邮箱不能为空',
                    '邮箱' => '请输入有效的邮箱地址'
                ]);
                break;
                
            case 'id':
                // 验证ID（数字）
                $validationResults['id'] = ValidationHelper::validate($allParams, 'id', [
                    '必填' => 'ID不能为空',
                    '数字' => 'ID必须是数字'
                ]);
                break;
                
            case 'username':
                // 验证用户名
                $validationResults['username'] = ValidationHelper::validate($allParams, 'username', [
                    '必填' => '用户名不能为空',
                    '长度2-20' => '用户名长度必须在2-20个字符之间',
                    '字母数字下划线' => '用户名只能包含字母、数字和下划线'
                ]);
                break;
                
            case 'password':
                // 验证密码
                $validationResults['password'] = ValidationHelper::validate($allParams, 'password', [
                    '必填' => '密码不能为空',
                    '长度6-20' => '密码长度必须在6-20个字符之间',
                    '强密码' => '密码必须包含字母、数字和特殊字符'
                ]);
                break;
                
            case 'c':
                // 验证URL - 特殊处理空值情况
                if (!isset($allParams['c']) || $allParams['c'] === '') {
                    $validationResults['c'] = ['c' => 'URL不能为空'];
                } else {
                    $validationResults['c'] = ValidationHelper::validate($allParams, 'c', [
                        '必填' => 'URL不能为空',
                        'URL' => '请输入有效的URL地址'
                    ]);
                }
                break;
                
            case 'date':
                // 验证日期
                $validationResults['date'] = ValidationHelper::validate($allParams, 'date', [
                    '必填' => '日期不能为空',
                    '日期' => '请输入有效的日期格式（YYYY-MM-DD）'
                ]);
                break;
                
            case 'custom':
                // 自定义正则验证示例
                $validationResults['custom'] = ValidationHelper::validate($allParams, 'custom', [
                    '必填' => '自定义字段不能为空',
                    '正则:/^[A-Z][a-z]{2,}$/' => '自定义字段必须以大写字母开头，后跟至少2个小写字母'
                ]);
                break;
        }
    }
}
?>
<div class="test-page-content">
    <!-- 验证结果显示区域 - 放在最上面 -->
    <?php if (!empty($validationResults)): ?>
    <div class="validation-results">
        <h2>验证结果</h2>
        <?php foreach ($validationResults as $paramName => $result): ?>
            <div class="validation-result <?php echo empty($result) ? 'success' : 'error'; ?>">
                <strong><?php echo ucfirst($paramName); ?> 参数验证：</strong>
                <?php if (empty($result)): ?>
                    <span class="success-message">验证通过</span>
                <?php else: ?>
                    <span class="error-message"><?php echo reset($result); ?></span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <!-- 参数验证测试链接区域 -->
    <div class="validation-tests">
        <h2>常用验证类型测试</h2>
        
        <!-- 手机号验证 -->
        <div class="validation-category">
            <div class="category-title">1. 手机号验证</div>
            <ul>
                <li><a href="/test?phone=13812345678">测试有效手机号 (13812345678)</a></li>
                <li><a href="/test?phone=12345">测试无效手机号 (长度不足)</a></li>
                <li><a href="/test?phone=abc12345678">测试无效手机号 (包含非数字字符)</a></li>
                <li><a href="/test?phone=">测试空手机号</a></li>
            </ul>
        </div>
        
        <!-- 邮箱验证 -->
        <div class="validation-category">
            <div class="category-title">2. 邮箱验证</div>
            <ul>
                <li><a href="/test?email=test@example.com">测试有效邮箱 (test@example.com)</a></li>
                <li><a href="/test?email=invalid-email">测试无效邮箱</a></li>
                <li><a href="/test?email=test@">测试不完整邮箱</a></li>
                <li><a href="/test?email=">测试空邮箱</a></li>
            </ul>
        </div>
        
        <!-- 数字验证 -->
        <div class="validation-category">
            <div class="category-title">3. 数字验证</div>
            <ul>
                <li><a href="/test?id=123">测试有效数字ID (123)</a></li>
                <li><a href="/test?id=abc">测试无效数字ID (非数字)</a></li>
                <li><a href="/test?id=">测试空数字ID</a></li>
            </ul>
        </div>
        
        <!-- 用户名验证 -->
        <div class="validation-category">
            <div class="category-title">4. 用户名验证</div>
            <ul>
                <li><a href="/test?username=john">测试有效用户名 (john)</a></li>
                <li><a href="/test?username=a">测试无效用户名 (长度不足)</a></li>
                <li><a href="/test?username=user name">测试无效用户名 (包含空格)</a></li>
                <li><a href="/test?username=">测试空用户名</a></li>
            </ul>
        </div>
        
        <!-- 密码验证 -->
        <div class="validation-category">
            <div class="category-title">5. 密码验证</div>
            <ul>
                <li><a href="/test?password=Abc123!">测试强密码 (Abc123!)</a></li>
                <li><a href="/test?password=123456">测试弱密码 (只有数字)</a></li>
                <li><a href="/test?password=abc">测试短密码 (长度不足)</a></li>
                <li><a href="/test?password=">测试空密码</a></li>
            </ul>
        </div>
        
        <!-- URL验证 -->
        <div class="validation-category">
            <div class="category-title">6. URL验证</div>
            <ul>
                <li><a href="/test?c=https://www.example.com">测试有效URL (https://www.example.com)</a></li>
                <li><a href="/test?c=example">测试无效URL (example)</a></li>
                <li><a href="/test?c=">测试空URL</a></li>
            </ul>
        </div>
        
        <!-- 日期验证 -->
        <div class="validation-category">
            <div class="category-title">7. 日期验证</div>
            <ul>
                <li><a href="/test?date=2023-12-25">测试有效日期 (2023-12-25)</a></li>
                <li><a href="/test?date=2023/12/25">测试无效日期格式</a></li>
                <li><a href="/test?date=2023-13-25">测试无效月份</a></li>
                <li><a href="/test?date=">测试空日期</a></li>
            </ul>
        </div>
        
        <!-- 自定义正则验证 -->
        <div class="validation-category">
            <div class="category-title">8. 自定义正则验证</div>
            <p>自定义规则：以大写字母开头，后跟至少2个小写字母</p>
            <ul>
                <li><a href="/test?custom=Test">测试有效自定义格式 (Test)</a></li>
                <li><a href="/test?custom=abc">测试无效自定义格式 (小写开头)</a></li>
                <li><a href="/test?custom=T">测试无效自定义格式 (长度不足)</a></li>
                <li><a href="/test?custom=">测试空自定义字段</a></li>
            </ul>
        </div>
        
        <!-- 多参数组合验证 -->
        <div class="validation-category">
            <div class="category-title">9. 多参数组合验证</div>
            <ul>
                <li><a href="/test?phone=13812345678&email=test@example.com">手机号+邮箱组合验证</a></li>
                <li><a href="/test?username=john&id=123&email=invalid">多参数混合验证</a></li>
            </ul>
        </div>
    </div>
</div>