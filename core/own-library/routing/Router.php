<?php

declare(strict_types=1);

namespace Core\OwnLibrary\Routing;

/**
 * 路由器类
 * 负责加载路由配置文件、合并配置文件、解析URI、匹配路由等功能
 * 支持HTTP方法区分和参数化路由匹配
 * 
 * 该类是框架的核心组件之一，用于将传入的HTTP请求映射到相应的页面处理文件。
 * 它支持静态路由和参数化路由，能够处理不同HTTP方法的请求，并提取路由参数。
 * 路由参数支持任意名称，如 {id}、{slug}、{param1} 等。
 * 
 * 安全特性：
 * - 自动对路由参数进行安全处理，防止恶意输入
 * - 限制参数长度，防止过长输入导致的问题
 * - 移除潜在的危险字符，防止目录遍历攻击
 * - 对参数进行HTML实体编码，防止XSS攻击
 * 
 * 使用示例：
 * $router = new Router($routeConfig);
 * $routeInfo = $router->match();
 * // $routeInfo 包含匹配到的路径和参数
 * // 例如：['path' => 'user/view', 'params' => ['id' => '123']]
 */
class Router
{
    /**
     * 当前请求的URI
     * 存储解析后的请求URI，不包含查询参数部分
     * 例如：对于请求 "/user/profile?id=123"，$uri 为 "user/profile"
     * @var string
     */
    private string $uri;

    /**
     * 当前请求的HTTP方法
     * 存储当前请求使用的HTTP方法，如 GET、POST、PUT、DELETE 等
     * @var string
     */
    private string $method;

    /**
     * 路由配置
     * 存储所有路由配置信息，包括默认路由、404页面和各HTTP方法的路由映射
     * 结构示例：
     * [
     *   'default' => 'home',              // 默认页面
     *   'not_found' => '404',             // 404页面
     *   'routes' => [
     *     'GET' => [                      // GET方法路由
     *       '/user/{id}' => 'user/view',  // 参数化路由
     *       '/about' => 'about'           // 静态路由
     *     ],
     *     'POST' => [                     // POST方法路由
     *       '/user' => 'user/create'
     *     ]
     *   ]
     * ]
     * @var array
     */
    private array $routes;
    
    /**
     * 构造函数
     * 初始化路由器，解析当前请求并加载路由配置
     *
     * @param array ...$routeConfigs 路由配置数组，可以传入多个配置数组进行合并
     *                               如果不提供配置，则使用默认路由配置
     */
    public function __construct(array ...$routeConfigs)
    {
        $this->parseRequest();
        $this->routes = $this->mergeRouteConfigs(...$routeConfigs);
    }

    /**
     * 合并多个路由配置
     * 将传入的多个路由配置数组合并成一个完整的路由配置
     * 后面的配置会覆盖前面配置中相同键的值
     * 
     * @param array ...$routeConfigs 路由配置数组，可以传入多个
     * @return array 合并后的路由配置
     */
    private function mergeRouteConfigs(array ...$routeConfigs): array
    {
        // 如果没有提供路由配置，使用默认配置
        if (empty($routeConfigs)) {
            return $this->getDefaultRoutes();
        }
        
        // 获取默认路由配置
        $mergedRoutes = $this->getDefaultRoutes();
        
        // 合并所有传入的路由配置
        foreach ($routeConfigs as $routes) {
            $mergedRoutes = $this->mergeConfig($mergedRoutes, $routes);
        }
        
        return $mergedRoutes;
    }
    
    /**
     * 合并两个配置数组
     * 
     * @param array $baseConfig 基础配置
     * @param array $newConfig 新配置
     * @return array 合并后的配置
     */
    private function mergeConfig(array $baseConfig, array $newConfig): array
    {
        foreach ($newConfig as $key => $value) {
            if (is_array($value) && isset($baseConfig[$key]) && is_array($baseConfig[$key])) {
                // 递归合并数组
                $baseConfig[$key] = $this->mergeConfig($baseConfig[$key], $value);
            } else {
                // 直接替换或添加
                $baseConfig[$key] = $value;
            }
        }
        
        return $baseConfig;
    }

    /**
     * 获取默认路由配置
     * 提供框架的基本路由配置，包括默认页面、404页面和HTTP方法占位符
     * 
     * @return array 默认路由配置数组
     */
    private function getDefaultRoutes(): array
    {
        return [
            'default' => 'home',
            'routes' => [
                'GET' => [],
                'POST' => [],
                'PUT' => [],
                'DELETE' => [],
            ],
            'not_found' => '404'
        ];
    }

    /**
     * 解析当前请求
     * 从 $_SERVER 全局变量中提取请求URI和HTTP方法，并解析查询参数
     * 将解析结果存储到类的属性中供后续使用
     * 改进版本，更好地处理中文字符
     */
    private function parseRequest(): void
    {
        // 获取请求URI
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        // 优先使用IIS特定的变量（如果存在）
        if (isset($_SERVER['UNENCODED_URL']) && !empty($_SERVER['UNENCODED_URL'])) {
            $requestUri = $_SERVER['UNENCODED_URL'];
        } elseif (isset($_SERVER['ORIG_PATH_INFO']) && !empty($_SERVER['ORIG_PATH_INFO'])) {
            $requestUri = $_SERVER['ORIG_PATH_INFO'];
            
            // 如果有查询字符串，添加回去
            if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
                $requestUri .= '?' . $_SERVER['QUERY_STRING'];
            }
        }
        
        // 分离URI和查询参数
        $uriParts = explode('?', $requestUri, 2);
        $this->uri = $uriParts[0];
        
        // 解析查询参数
        $queryParameters = [];
        if (isset($uriParts[1])) {
            parse_str($uriParts[1], $queryParameters);
            
            // 如果查询字符串中有url参数，使用它作为URI（这是IIS URL重写的结果）
            if (isset($queryParameters['url'])) {
                $this->uri = $queryParameters['url'];
            }
        }
        
        // 对URI进行URL解码以正确处理中文字符
        $this->uri = urldecode($this->uri);
        
        // 标准化URI
        $this->uri = trim($this->uri, '/');
        
        // 获取HTTP方法
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * 匹配路由
     * 根据当前请求的URI和HTTP方法，查找匹配的路由配置
     * 如果找到匹配项，返回对应的页面路径和参数；否则返回404页面
     * 
     * @return array 包含路径和参数的数组
     *               格式: ['path' => '页面路径', 'params' => [参数数组]]
     */
    public function match(): array
    {
        // 匹配配置路由
        $matchedRoute = $this->matchConfigRoute();
        if ($matchedRoute !== false) {
            return $matchedRoute;
        }
        
        // 如果没有匹配的路由，返回404
        return [
            'path' => $this->routes['not_found'] ?? '404',
            'params' => []
        ];
    }

    /**
     * 匹配配置文件中的路由
     * 在已加载的路由配置中查找与当前请求匹配的路由
     * 支持精确匹配和参数化路由匹配
     * 
     * @return array|bool 包含路径和参数的数组或false（未找到匹配）
     */
    private function matchConfigRoute()
    {
        // 处理默认路由
        if (empty($this->uri)) {
            return [
                'path' => $this->routes['default'] ?? 'home',
                'params' => []
            ];
        }
        
        // 检查是否有针对当前HTTP方法的路由配置
        if (!isset($this->routes['routes'][$this->method])) {
            return false;
        }
        
        $methodRoutes = $this->routes['routes'][$this->method];
        
        // 精确匹配 - 尝试带斜杠和不带斜杠两种形式
        if (isset($methodRoutes[$this->uri])) {
            return [
                'path' => $methodRoutes[$this->uri],
                'params' => []
            ];
        }
        
        $uriWithSlash = '/' . $this->uri;
        if (isset($methodRoutes[$uriWithSlash])) {
            return [
                'path' => $methodRoutes[$uriWithSlash],
                'params' => []
            ];
        }
        
        // 参数化路由匹配
        return $this->matchParameterizedRoutes($methodRoutes);
    }
    
    /**
     * 匹配参数化路由
     * 
     * @param array $methodRoutes 当前HTTP方法的路由配置
     * @return array|bool 包含路径和参数的数组或false（未找到匹配）
     */
    private function matchParameterizedRoutes(array $methodRoutes)
    {
        // 构造带斜杠和不带斜杠的URI
        $uriWithSlash = '/' . $this->uri;
        
        // 遍历所有路由配置进行匹配
        foreach ($methodRoutes as $pattern => $handler) {
            // 尝试两种模式进行匹配：带斜杠和不带斜杠的URI
            $resultWithSlash = $this->matchRoutePattern($pattern, $uriWithSlash);
            if ($resultWithSlash !== false) {
                // 直接返回匹配结果，不进行参数白名单验证
                return [
                    'path' => $handler,
                    'params' => $resultWithSlash
                ];
            }
            
            // 也尝试直接用uri匹配（不带斜杠前缀）
            $resultWithoutSlash = $this->matchRoutePattern($pattern, $this->uri);
            if ($resultWithoutSlash !== false) {
                // 直接返回匹配结果，不进行参数白名单验证
                return [
                    'path' => $handler,
                    'params' => $resultWithoutSlash
                ];
            }
        }
        
        return false;
    }

    /**
     * 匹配路由模式
     * 使用正则表达式匹配参数化路由模式，并提取参数值
     * 支持任意参数名，如 {id}、{slug}、{param1} 等
     * 
     * @param string $pattern 路由模式，如 "/user/{id}" 或 "/post/{param1}"
     * @param string $uri 实际URI，如 "/user/123" 或 "/post/value1"
     * @return array|bool 参数数组（成功时）或false（未匹配）
     */
    private function matchRoutePattern(string $pattern, string $uri)
    {
        // 基本的安全检查：防止路径遍历和URI过长
        if (strpos($uri, '../') !== false || strpos($uri, '..\\') !== false || strlen($uri) > 2048) {
            return false;
        }
        
        // 将路由模式中的参数占位符提取出来
        $paramNames = [];
        if (preg_match_all('/\{([^\/]+)}/', $pattern, $matches)) {
            $paramNames = $matches[1];
        }
        
        // 对路由模式进行转义并替换参数占位符
        $patternRegex = preg_quote($pattern, '/');
        foreach ($paramNames as $paramName) {
            $escapedParam = preg_quote('{' . $paramName . '}', '/');
            // 允许任何字符（除了斜杠）作为参数值
            $patternRegex = str_replace($escapedParam, '([^\/]+)', $patternRegex);
        }
        
        // 执行正则表达式匹配
        if (preg_match('/^' . $patternRegex . '$/', $uri, $matches)) {
            // 提取参数值
            $params = [];
            for ($i = 0; $i < count($paramNames); $i++) {
                // 捕获组索从1开始（0是完整匹配）
                if (isset($matches[$i + 1])) {
                    // 对参数值进行安全处理
                    $paramValue = $this->sanitizeParam($matches[$i + 1]);
                    $params[$paramNames[$i]] = $paramValue;
                }
            }
            
            return $params;
        }
        
        return false;
    }
    
    /**
     * 对路由参数进行安全处理
     * 防止恶意输入和潜在的安全问题
     * 
     * @param string $paramValue 参数值
     * @return string 处理后的参数值
     */
    private function sanitizeParam(string $paramValue): string
    {
        // 注意：这里不再进行urldecode，因为在parseRequest中已经处理过了
        
        // 限制参数长度，防止过长输入
        if (strlen($paramValue) > 255) {
            $paramValue = substr($paramValue, 0, 255);
        }
        
        // 移除潜在的危险字符和序列
        $dangerousPatterns = [
            '../', '..\\', // 路径遍历
            '\0', // 空字符
            '<?php', '?>', // PHP代码注入
            '--', '/*', '*/' // SQL注释和代码注释
        ];
        
        // 使用str_ireplace进行不区分大小写的替换，增强安全性
        $paramValue = str_ireplace($dangerousPatterns, '', $paramValue);
        
        // 对参数进行HTML实体编码，防止XSS，但保留中文字符的可读性
        // 使用ENT_NOQUOTES来减少对中文字符的影响
        $paramValue = htmlspecialchars($paramValue, ENT_NOQUOTES, 'UTF-8');

        return $paramValue;
    }

    /**
     * 获取当前路由配置
     * 返回当前路由器使用的完整路由配置
     * 
     * @return array 当前路由配置数组
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}