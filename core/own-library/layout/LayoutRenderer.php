<?php

declare(strict_types=1);

namespace Core\OwnLibrary\Layout;

/**
 * LayoutRenderer 类
 * 用于渲染页面布局的工具类，支持链式调用设置页面属性和资源
 */
class LayoutRenderer
{
    /**
     * 布局名称
     * @var string
     */
    private string $layoutName;

    /**
     * 页面标题
     * @var string
     */
    private string $title = '';

    /**
     * 页面描述
     * @var string
     */
    private string $description = '';

    /**
     * 页面关键字
     * @var string
     */
    private string $keywords = '';

    /**
     * CSS文件数组
     * @var array
     */
    private array $cssFiles = [];

    /**
     * JS文件数组
     * @var array
     */
    private array $jsFiles = [];

    /**
     * 自定义数据数组
     * @var array
     */
    private array $data = [];

    /**
     * 页面内容
     * @var string
     */
    private string $content = '';

    /**
     * 构造函数
     * 
     * @param string $layoutName 布局名称
     */
    public function __construct(string $layoutName)
    {
        $this->layoutName = $layoutName;
    }

    /**
     * 设置页面标题
     * 
     * @param string $title 页面标题
     * @return self 返回当前实例以支持链式调用
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * 设置页面描述
     * 
     * @param string $description 页面描述
     * @return self 返回当前实例以支持链式调用
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * 设置页面关键字
     * 
     * @param string $keywords 页面关键字
     * @return self 返回当前实例以支持链式调用
     */
    public function setKeywords(string $keywords): self
    {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * 添加CSS文件
     * 
     * @param string $cssFile CSS文件路径
     * @return self 返回当前实例以支持链式调用
     */
    public function addCSS(string $cssFile): self
    {
        $this->cssFiles[] = $cssFile;
        return $this;
    }

    /**
     * 添加JS文件
     * 
     * @param string $jsFile JS文件路径
     * @return self 返回当前实例以支持链式调用
     */
    public function addJS(string $jsFile): self
    {
        $this->jsFiles[] = $jsFile;
        return $this;
    }

    /**
     * 设置自定义数据
     * 
     * @param string $key 数据键名
     * @param mixed $value 数据值
     * @return self 返回当前实例以支持链式调用
     */
    public function setData(string $key, $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * 批量设置自定义数据
     * 
     * @param array $data 数据数组
     * @return self 返回当前实例以支持链式调用
     */
    public function setAllData(array $data): self
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    /**
     * 设置页面内容
     * 
     * @param string $content 页面内容
     * @return self 返回当前实例以支持链式调用
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * 渲染页面
     * 
     * @return void
     */
    public function render(): void
    {
        // 将数据提取到当前作用域，以便在布局文件中使用
        $pageTitle = $this->title;
        $pageDescription = $this->description;
        $pageKeywords = $this->keywords;
        $content = $this->content;
        $additionalCSS = $this->cssFiles;
        $additionalJS = $this->jsFiles;
        
        // 将自定义数据提取到变量中
        extract($this->data);
        
        // 构建布局文件路径
        $layoutFile = LAYOUTS_PATH . '/' . $this->layoutName . '.php';
        // 检查布局文件是否存在，如果存在就加载，不存在就直接输出内容
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }
    
    /**
     * 获取布局名称
     * 
     * @return string 布局名称
     */
    public function getLayoutName(): string
    {
        return $this->layoutName;
    }
    
    /**
     * 获取页面标题
     * 
     * @return string 页面标题
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    
    /**
     * 获取页面描述
     * 
     * @return string 页面描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    /**
     * 获取页面关键字
     * 
     * @return string 页面关键字
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }
    
    /**
     * 获取CSS文件数组
     * 
     * @return array CSS文件数组
     */
    public function getCSSFiles(): array
    {
        return $this->cssFiles;
    }
    
    /**
     * 获取JS文件数组
     * 
     * @return array JS文件数组
     */
    public function getJSFiles(): array
    {
        return $this->jsFiles;
    }
    
    /**
     * 获取自定义数据
     * 
     * @return array 自定义数据数组
     */
    public function getData(): array
    {
        return $this->data;
    }
    
    /**
     * 获取页面内容
     * 
     * @return string 页面内容
     */
    public function getContent(): string
    {
        return $this->content;
    }
}