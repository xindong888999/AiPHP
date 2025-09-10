<?php

declare(strict_types=1);

namespace Core\OwnLibrary\Dispatcher;

/**
 * 调度中心类
 * 
 * 该类负责根据页面路径和参数调度并加载页面。
 * 它是应用程序的核心调度组件，连接路由解析和页面加载两个环节。
 * 
 * 主要功能：
 * 1. 接收页面路径和参数
 * 2. 根据页面路径加载相应的页面文件
 * 3. 处理页面与布局的交互
 * 4. 提供统一的页面调度接口
 */
class Dispatcher
{
    /**
     * 网站根目录路径
     * 
     * 存储项目根目录的绝对路径，用于构建页面文件的完整路径。
     * 
     * @var string
     */
    private string $rootPath;
    
    /**
     * 页面文件总目录（相对于网站根目录）
     * 
     * 存储所有页面文件的基础路径，所有页面文件都应位于此目录下或其子目录中。
     * 
     * @var string
     */
    private string $baseDirectory;

    /**
     * 布局文件总目录（相对于网站根目录）
     * 
     * 存储所有布局文件的基础路径，所有布局文件都应位于此目录下或其子目录中。
     * 
     * @var string
     */
    private string $layoutsDirectory;

    /**
     * 构造函数
     * 
     * @param string $rootPath 项目根目录路径
     * @param string $baseDirectory 页面文件总目录（相对于网站根目录）
     * @param string $layoutsDirectory 布局文件总目录（相对于网站根目录），可为空
     */
    public function __construct(string $rootPath, string $baseDirectory, string $layoutsDirectory = '') 
    {
        $this->rootPath = $rootPath;
        $this->baseDirectory = $baseDirectory;
        $this->layoutsDirectory = $layoutsDirectory;
    }

    /**
     * 调度页面
     * 
     * 根据页面路径和参数加载并显示页面。
     * 
     * @param string $pagePath 页面路径（相对于handler/pages目录）
     * @param array $params 路由参数
     * @return void
     */
    public function dispatch(string $pagePath, array $params = []): void
    {
        // 构建完整路径：项目路径 + 页面文件目录 + 页面路径 + .php
        $fullPath = $this->rootPath . DIRECTORY_SEPARATOR . trim($this->baseDirectory, '/\\') . DIRECTORY_SEPARATOR . trim($pagePath, '/\\') . '.php';
        
        // 规范化路径分隔符
        $fullPath = str_replace('/', DIRECTORY_SEPARATOR, $fullPath);
        
        if (file_exists($fullPath)) {
            // 将参数提取到当前作用域，使它们在页面文件中可用
            extract($params);
            
            // 文件存在，使用输出缓冲获取页面内容和变量
            ob_start();
            require $fullPath;
            $pageContent = ob_get_clean();
            // 检查页面中是否有$layout变量，如果有并且不为空就加载布局文件
            if (isset($layout) && !empty($layout)) {
                $this->loadLayout($layout, $pageContent, $params, get_defined_vars());
            } else {
                // 没有布局，直接输出页面内容
                echo $pageContent;
            }
            return;
        }
        
        // 尝试加载404页面
        $notFoundPath = $this->rootPath . DIRECTORY_SEPARATOR . trim($this->baseDirectory, '/\\') . DIRECTORY_SEPARATOR . '404.php';
        if (file_exists($notFoundPath)) {
            require $notFoundPath;
            return;
        }
        // 输出404错误信息
        $this->showNotFoundError($pagePath);
    }

    /**
     * 加载布局文件
     * 
     * @param string $layoutName 布局文件名
     * @param string $content 页面内容
     * @param array $params 路由参数
     * @param array $pageVars 页面中定义的变量
     * @return void
     */
    private function loadLayout(string $layoutName, string $content, array $params = [], array $pageVars = []): void
    {
        // 去掉布局文件名的后缀（如果有的话）
        $layoutName = preg_replace('/\.[^.]*$/', '', $layoutName);
        
        // 构建布局文件完整路径
        $layoutPath = $this->rootPath . DIRECTORY_SEPARATOR . trim($this->layoutsDirectory, '/\\') . DIRECTORY_SEPARATOR . trim($layoutName, '/\\') . '.php';
        
        // 规范化路径分隔符
        $layoutPath = str_replace('/', DIRECTORY_SEPARATOR, $layoutPath);
        
        // 将参数和页面变量提取到当前作用域，使它们在布局文件中可用
        extract($params);
        extract($pageVars);
        
        // 检查布局文件是否存在
        if (file_exists($layoutPath)) {
            // 加载布局文件
            require $layoutPath;
        } else {
            // 布局文件不存在，直接输出页面内容
            echo $content;
        }
    }

    /**
     * 显示404错误信息
     * 
     * 当请求的页面和404页面都不存在时，显示基础的404错误信息。
     * 
     * @param string $requestedPath 请求的路径
     */
    private function showNotFoundError(string $requestedPath): void
    {
        // 设置HTTP状态码为404
        http_response_code(404);
        
        // 输出基础的错误信息
        echo "<!DOCTYPE html>\n";
        echo "<html lang='zh-CN'>\n";
        echo "<head>\n";
        echo "    <meta charset='UTF-8'>\n";
        echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
        echo "    <title>页面未找到 - 404</title>\n";
        echo "</head>\n";
        echo "<body>\n";
        echo "    <h1>404 - 页面未找到</h1>\n";
        echo "    <p>请求的页面不存在: '{$requestedPath}'</p>\n";
        echo "</body>\n";
        echo "</html>\n";
    }
}