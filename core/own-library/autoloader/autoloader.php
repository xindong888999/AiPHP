<?php

declare(strict_types=1);

namespace Core\OwnLibrary\Autoloader;

/**
 * 自动加载器类
 * 模拟Composer的自动加载机制
 * 
 * 该类实现了PHP的自动加载功能，支持两种加载方式：
 * 1. 类映射（Class Map）：直接将类名映射到具体的文件路径
 * 2. 命名空间映射（Namespace Map）：将命名空间前缀映射到目录，根据PSR-4规范自动查找类文件
 * 
 * 使用示例：
 * $autoloader = new Autoloader();
 * $autoloader->addClassMap('Core\OwnLibrary\Autoloader\Autoloader', '/core/own-library/autoloader/autoloader.php');
 * $autoloader->addNamespace('Core\\', '/core/');
 * $autoloader->register();
 */
class Autoloader
{
    /**
     * 类文件映射
     * 用于存储类名到文件路径的直接映射关系
     * 格式: ['ClassName' => '/path/to/class-file.php']
     * @var array
     */
    private array $classMap = [];

    /**
     * 命名空间前缀与目录的映射
     * 用于根据命名空间自动查找类文件，遵循PSR-4自动加载规范
     * 格式: ['Namespace\\Prefix\\' => '/path/to/directory/']
     * @var array
     */
    private array $namespaceMap = [];

    /**
     * 注册自动加载器
     * 将loadClass方法注册到SPL自动加载器栈中
     * 当PHP遇到尚未加载的类时，会自动调用已注册的加载方法
     * @return void
     */
    public function register(): void
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * 添加类文件映射
     * 用于添加类名到文件路径的直接映射关系
     * 这种方式具有最高的优先级，会在命名空间映射之前检查
     * 
     * @param string $class 类名（完整限定名，包括命名空间）
     * @param string $file 文件的绝对路径或相对于包含点的路径
     * @return void
     */
    public function addClassMap(string $class, string $file): void
    {
        $this->classMap[$class] = $file;
    }

    /**
     * 添加命名空间映射
     * 用于添加命名空间前缀到目录路径的映射关系
     * 当类名以指定的命名空间前缀开头时，将在这个目录中查找类文件
     * 
     * @param string $namespace 命名空间前缀（例如："Core\OwnLibrary\\"）
     * @param string $directory 对应的目录路径（例如："/core/own-library/"）
     * @return void
     */
    public function addNamespace(string $namespace, string $directory): void
    {
        $this->namespaceMap[$namespace] = rtrim($directory, '/') . '/';
    }

    /**
     * 加载类文件
     * 核心的类加载方法，根据类名查找并加载对应的PHP文件
     * 首先检查classMap中是否有直接映射，如果没有则通过命名空间映射查找
     * 
     * @param string $class 完整限定的类名（例如："Core\OwnLibrary\Autoloader\Autoloader"）
     * @return bool 是否成功加载类文件
     */
    public function loadClass(string $class): bool
    {
        // 检查类是否在类映射中
        if (isset($this->classMap[$class])) {
            if (file_exists($this->classMap[$class])) {
                require $this->classMap[$class];
                return true;
            }
        }

        // 遍历命名空间映射
        foreach ($this->namespaceMap as $namespace => $directory) {
            // 检查类是否属于当前命名空间
            if (strpos($class, $namespace) === 0) {
                // 将命名空间转换为文件路径
                // 例如: "Core\OwnLibrary\Autoloader\Autoloader" -> "OwnLibrary/Autoloader/Autoloader"
                $relativeClass = substr($class, strlen($namespace));
                $file = $directory . str_replace('\\', '/', $relativeClass) . '.php';

                // 检查文件是否存在并加载
                if (file_exists($file)) {
                    require $file;
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * 获取类文件映射
     * 返回当前已注册的所有类名到文件路径的映射关系
     * @return array 类文件映射数组
     */
    public function getClassMap(): array
    {
        return $this->classMap;
    }

    /**
     * 获取命名空间映射
     * 返回当前已注册的所有命名空间前缀到目录路径的映射关系
     * @return array 命名空间映射数组
     */
    public function getNamespaceMap(): array
    {
        return $this->namespaceMap;
    }
}