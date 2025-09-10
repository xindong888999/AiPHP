<?php
/**
 * RedBeanPHP门面类
 * 
 * 提供简化的RedBeanPHP数据库操作接口，自动加载配置文件
 */

namespace Core\OtherLibrary\RedBean;

// 引入RedBeanPHP库
require_once __DIR__ . '/rb.php';

class RedBeanFacade
{
    /**
     * RedBeanPHP是否已初始化
     * 
     * @var bool
     */
    private static $initialized = false;
    
    /**
     * 初始化RedBeanPHP
     * 
     * @return void
     */
    public static function initialize()
    {
        if (self::$initialized) {
            return;
        }
        
        // 加载数据库配置
        $configPath = dirname(__DIR__, 3) . '/config/database.php';
        if (!file_exists($configPath)) {
            throw new \Exception("数据库配置文件不存在: " . $configPath);
        }
        
        $config = require $configPath;
        
        // 获取默认连接配置
        $defaultConnection = $config['default'];
        if (!isset($config['connections'][$defaultConnection])) {
            throw new \Exception("默认数据库连接配置不存在: " . $defaultConnection);
        }
        
        $connectionConfig = $config['connections'][$defaultConnection];
        
        // 根据数据库类型构建DSN
        if ($connectionConfig['driver'] === 'sqlite') {
            $dsn = 'sqlite:' . $connectionConfig['database'];
            \R::setup($dsn);
        } elseif ($connectionConfig['driver'] === 'mysql') {
            $dsn = 'mysql:host=' . $connectionConfig['host'] . ';dbname=' . $connectionConfig['database'];
            \R::setup($dsn, $connectionConfig['username'], $connectionConfig['password']);
        } else {
            throw new \Exception("不支持的数据库驱动: " . $connectionConfig['driver']);
        }
        
        self::$initialized = true;
    }
    
    /**
     * 魔术方法，将调用转发给RedBeanPHP R类
     * 
     * @param string $name 方法名
     * @param array $arguments 参数
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        self::initialize();
        return call_user_func_array(['\\R', $name], $arguments);
    }
    
    /**
     * 创建一个Bean
     * 
     * @param string $type Bean类型
     * @return object
     */
    public static function dispense($type)
    {
        self::initialize();
        return \R::dispense($type);
    }
    
    /**
     * 保存Bean
     * 
     * @param object $bean 要保存的Bean
     * @return int
     */
    public static function store($bean)
    {
        self::initialize();
        return \R::store($bean);
    }
    
    /**
     * 查找Bean
     * 
     * @param string $type Bean类型
     * @param string $sql SQL条件
     * @param array $bindings 参数绑定
     * @return array
     */
    public static function find($type, $sql = null, $bindings = [])
    {
        self::initialize();
        return \R::find($type, $sql, $bindings);
    }
    
    /**
     * 查找一个Bean
     * 
     * @param string $type Bean类型
     * @param string $sql SQL条件
     * @param array $bindings 参数绑定
     * @return object|null
     */
    public static function findOne($type, $sql = null, $bindings = [])
    {
        self::initialize();
        return \R::findOne($type, $sql, $bindings);
    }
    
    /**
     * 删除Bean
     * 
     * @param object $bean 要删除的Bean
     * @return void
     */
    public static function trash($bean)
    {
        self::initialize();
        \R::trash($bean);
    }
    
    /**
     * 删除所有匹配条件的Bean
     * 
     * @param string $type Bean类型
     * @param string $sql SQL条件
     * @param array $bindings 参数绑定
     * @return int
     */
    public static function trashAll($type, $sql = null, $bindings = [])
    {
        self::initialize();
        return \R::trashAll($type, $sql, $bindings);
    }
    
    /**
     * 获取所有Bean
     * 
     * @param string $type Bean类型
     * @return array
     */
    public static function findAll($type)
    {
        self::initialize();
        return \R::findAll($type);
    }
    
    /**
     * 获取一个Bean通过ID
     * 
     * @param string $type Bean类型
     * @param int $id Bean ID
     * @return object|null
     */
    public static function load($type, $id)
    {
        self::initialize();
        return \R::load($type, $id);
    }
    
    /**
     * 开始事务
     * 
     * @return void
     */
    public static function begin()
    {
        self::initialize();
        \R::begin();
    }
    
    /**
     * 提交事务
     * 
     * @return void
     */
    public static function commit()
    {
        self::initialize();
        \R::commit();
    }
    
    /**
     * 回滚事务
     * 
     * @return void
     */
    public static function rollback()
    {
        self::initialize();
        \R::rollback();
    }
    
    /**
     * 获取错误信息
     * 
     * @return string|null
     */
    public static function error()
    {
        self::initialize();
        return \R::error();
    }
    
    /**
     * 统计记录数量
     * 
     * @param string $type Bean类型
     * @param string $addSQL SQL条件
     * @param array $bindings 参数绑定
     * @return int
     */
    public static function count($type, $addSQL = '', $bindings = [])
    {
        self::initialize();
        return \R::count($type, $addSQL, $bindings);
    }
    
    /**
     * 启用或禁用查询缓存
     * 
     * @param bool $enable 是否启用缓存
     * @return void
     */
    public static function enableCache($enable = true)
    {
        self::initialize();
        \R::getWriter()->setUseCache($enable);
    }
    
    /**
     * 清空所有缓存
     * 
     * @param int|null $maxCacheSize 可选：设置新的最大缓存大小
     * @return void
     */
    public static function flushCache($maxCacheSize = null)
    {
        self::initialize();
        \R::getWriter()->flushCache($maxCacheSize);
    }
    
    /**
     * 启用调试模式（包含SQL日志）
     * 
     * @param bool $enable 是否启用调试
     * @param int $mode 调试模式 (0=基础, 2=美化)
     * @return object Logger实例
     */
    public static function debug($enable = true, $mode = 2)
    {
        self::initialize();
        return \R::debug($enable, $mode);
    }
    
    /**
     * 执行带缓存标记的查询
     * 使用 -- keep-cache 注释来保持缓存
     * 
     * @param string $sql SQL查询
     * @param array $bindings 参数绑定
     * @return array
     */
    public static function getWithCache($sql, $bindings = [])
    {
        self::initialize();
        // 在SQL末尾添加缓存保持标记
        if (strpos($sql, '-- keep-cache') === false) {
            $sql = rtrim($sql, '; ') . ' -- keep-cache';
        }
        return \R::getAll($sql, $bindings);
    }
    
    /**
     * 查找Bean并启用缓存
     * 
     * @param string $type Bean类型
     * @param string $sql SQL条件
     * @param array $bindings 参数绑定
     * @return array
     */
    public static function findWithCache($type, $sql = null, $bindings = [])
    {
        self::initialize();
        // 确保缓存已启用
        \R::getWriter()->setUseCache(true);
        return \R::find($type, $sql, $bindings);
    }
    
    /**
     * 获取缓存统计信息
     * 
     * @return array 缓存统计信息
     */
    public static function getCacheStats()
    {
        self::initialize();
        $writer = \R::getWriter();
        
        // 通过反射获取缓存信息（因为cache属性是protected）
        $reflection = new \ReflectionClass($writer);
        $cacheProperty = $reflection->getProperty('cache');
        $cacheProperty->setAccessible(true);
        $cache = $cacheProperty->getValue($writer);
        
        $flagProperty = $reflection->getProperty('flagUseCache');
        $flagProperty->setAccessible(true);
        $cacheEnabled = $flagProperty->getValue($writer);
        
        $maxSizeProperty = $reflection->getProperty('maxCacheSizePerType');
        $maxSizeProperty->setAccessible(true);
        $maxSize = $maxSizeProperty->getValue($writer);
        
        $stats = [
            'cache_enabled' => $cacheEnabled,
            'max_cache_size_per_type' => $maxSize,
            'cache_types' => array_keys($cache),
            'total_cached_queries' => 0
        ];
        
        foreach ($cache as $type => $queries) {
            $stats['cache_details'][$type] = count($queries);
            $stats['total_cached_queries'] += count($queries);
        }
        
        return $stats;
    }
}