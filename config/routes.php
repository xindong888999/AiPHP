<?php

/**
 * 路由配置文件
 * 定义应用程序的路由规则
 */

return [
    // 默认路由
    'default' => 'home',
    
    // 路由规则
    'routes' => [
        // GET请求路由
        'GET' => [
            '/' => 'home',
            '/about' => 'about',
            '/database-example' => 'database_example',
            // 带参数的测试页面路由
            '/test' => 'test',
            '/test/{id}' => 'test',
            '/test/{id}/{slug}' => 'test',
            // 路由参数测试页面
            '/test_param' => 'test_param',
            '/test_param/{id}' => 'test_param',
            // 新闻页面路由
            '/news' => 'news',
            // CSRF保护测试页面
            '/csrf-test' => 'csrf_test',
            // 用户管理路由
            '/users' => 'users/index',
            '/users/detail/{id}' => 'users/detail',
            '/users/form' => 'users/form',
            '/users/form/{id}' => 'users/form',
            
            // 文章管理路由
            '/articles' => 'articles/index',
            '/articles/detail/{id}' => 'articles/detail',
            '/articles/form' => 'articles/form',
            '/articles/form/{id}' => 'articles/form',
            
            // 独立文章列表和详情页面路由
            '/article_list' => 'article_list',
            '/article_detail/{id}' => 'article_detail',
            
            // 路由测试
            '/test-route' => 'test_route'
            

            

            

            
        ],
        
        // POST请求路由
        'POST' => [
            '/csrf-test' => 'csrf_test',
            // 用户管理路由
            '/users/update-status' => 'users/update_user_status',
            '/users/save' => 'users/form',
            '/users/delete' => 'users/delete_user',
            
            // 文章管理路由
            '/articles/save' => 'articles/save',
            '/articles/delete' => 'articles/delete',
            '/articles/update-status' => 'articles/update_status'
            

            

            

            


        ],
        
        // PUT请求路由
        'PUT' => [
        ],
        
        // DELETE请求路由
        'DELETE' => [
        ],
    ],
    
    // 404页面
    'not_found' => '404',
    
    // 启用调试模式
    'debug_mode' => true,
];