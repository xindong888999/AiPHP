<?php

declare(strict_types=1);

namespace Core\OwnLibrary\Logger;

/**
 * 日志记录器类
 * 
 * 该类提供了一个简单但功能完整的日志记录系统，支持多种日志级别、
 * 日志文件轮转、格式化日志消息等功能，不依赖任何第三方库。
 * 
 * 主要功能：
 * 1. 支持多种日志级别（DEBUG, INFO, WARNING, ERROR, CRITICAL）
 * 2. 支持日志文件轮转（按大小或日期）
 * 3. 支持格式化日志消息
 * 4. 支持自定义日志目录
 * 5. 线程安全的日志写入
 * 6. 支持单例模式，方便全局访问
 * 
 * 使用示例：
 * ```php
 * // 单例模式使用
 * Logger::getInstance()->info('这是一条信息日志');
 * Logger::getInstance()->error('发生错误', ['错误代码' => 500]);
 * 
 * // 或者创建自定义实例
 * $logger = new Logger('custom/logs', 'app.log');
 * $logger->info('这是一条信息日志');
 * ```
 */
class Logger
{
    /**
     * 单例实例
     * 
     * @var Logger|null
     */
    private static ?Logger $instance = null;
    
    /**
     * 获取单例实例
     * 
     * @param string $logDir 日志目录路径，默认为项目根目录下的logs文件夹
     * @param string $logFile 日志文件名，默认为application.log
     * @param int $logLevel 日志级别，默认为DEBUG
     * @param int $rotationType 日志文件轮转类型，默认为按日期轮转
     * @param int $maxFileSize 日志文件大小限制（字节），默认为10MB
     * @param int $maxFiles 日志文件保留数量，默认为7个
     * @return Logger 单例实例
     */
    public static function getInstance(
        string $logDir = '',
        string $logFile = 'application.log',
        int $logLevel = self::DEBUG,
        int $rotationType = self::ROTATION_DAILY,
        int $maxFileSize = 10485760, // 10MB
        int $maxFiles = 7
    ): Logger {
        if (self::$instance === null) {
            self::$instance = new self($logDir, $logFile, $logLevel, $rotationType, $maxFileSize, $maxFiles);
        }
        return self::$instance;
    }
    
    /**
     * 重置单例实例
     * 主要用于测试或需要重新配置日志记录器的场景
     * 
     * @return void
     */
    public static function resetInstance(): void
    {
        self::$instance = null;
    }
    
    /**
     * 日志级别常量
     */
    public const DEBUG = 100;
    public const INFO = 200;
    public const WARNING = 300;
    public const ERROR = 400;
    public const CRITICAL = 500;

    /**
     * 日志级别名称映射
     */
    protected const LEVEL_NAMES = [
        self::DEBUG => 'DEBUG',
        self::INFO => 'INFO',
        self::WARNING => 'WARNING',
        self::ERROR => 'ERROR',
        self::CRITICAL => 'CRITICAL',
    ];

    /**
     * 日志文件轮转类型常量
     */
    public const ROTATION_NONE = 0;
    public const ROTATION_DAILY = 1;
    public const ROTATION_SIZE = 2;

    /**
     * 日志目录路径
     * 
     * @var string
     */
    protected string $logDir;

    /**
     * 日志文件名
     * 
     * @var string
     */
    protected string $logFile;

    /**
     * 当前日志级别
     * 
     * @var int
     */
    protected int $logLevel;

    /**
     * 日志文件轮转类型
     * 
     * @var int
     */
    protected int $rotationType;

    /**
     * 日志文件大小限制（字节）
     * 
     * @var int
     */
    protected int $maxFileSize;

    /**
     * 日志文件保留数量
     * 
     * @var int
     */
    protected int $maxFiles;

    /**
     * 构造函数
     * 
     * @param string $logDir 日志目录路径，默认为项目根目录下的logs文件夹
     * @param string $logFile 日志文件名，默认为application.log
     * @param int $logLevel 日志级别，默认为DEBUG
     * @param int $rotationType 日志文件轮转类型，默认为按日期轮转
     * @param int $maxFileSize 日志文件大小限制（字节），默认为10MB
     * @param int $maxFiles 日志文件保留数量，默认为7个
     */
    public function __construct(
        string $logDir = '',
        string $logFile = 'application.log',
        int $logLevel = self::DEBUG,
        int $rotationType = self::ROTATION_DAILY,
        int $maxFileSize = 10485760, // 10MB
        int $maxFiles = 7
    ) {
        // 如果未指定日志目录，使用项目根目录下的logs文件夹
        if (empty($logDir)) {
            $logDir = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'logs';
        }

        $this->logDir = rtrim($logDir, '/\\');
        $this->logFile = $logFile;
        $this->logLevel = $logLevel;
        $this->rotationType = $rotationType;
        $this->maxFileSize = $maxFileSize;
        $this->maxFiles = $maxFiles;

        // 确保日志目录存在
        $this->ensureLogDirectoryExists();
    }

    /**
     * 记录调试级别日志
     * 
     * @param string $message 日志消息
     * @param array $context 上下文数据
     * @return bool 是否成功写入日志
     */
    public function debug(string $message, array $context = []): bool
    {
        return $this->log(self::DEBUG, $message, $context);
    }

    /**
     * 记录信息级别日志
     * 
     * @param string $message 日志消息
     * @param array $context 上下文数据
     * @return bool 是否成功写入日志
     */
    public function info(string $message, array $context = []): bool
    {
        return $this->log(self::INFO, $message, $context);
    }

    /**
     * 记录警告级别日志
     * 
     * @param string $message 日志消息
     * @param array $context 上下文数据
     * @return bool 是否成功写入日志
     */
    public function warning(string $message, array $context = []): bool
    {
        return $this->log(self::WARNING, $message, $context);
    }

    /**
     * 记录错误级别日志
     * 
     * @param string $message 日志消息
     * @param array $context 上下文数据
     * @return bool 是否成功写入日志
     */
    public function error(string $message, array $context = []): bool
    {
        return $this->log(self::ERROR, $message, $context);
    }

    /**
     * 记录严重错误级别日志
     * 
     * @param string $message 日志消息
     * @param array $context 上下文数据
     * @return bool 是否成功写入日志
     */
    public function critical(string $message, array $context = []): bool
    {
        return $this->log(self::CRITICAL, $message, $context);
    }

    /**
     * 记录日志
     * 
     * @param int $level 日志级别
     * @param string $message 日志消息
     * @param array $context 上下文数据
     * @return bool 是否成功写入日志
     */
    public function log(int $level, string $message, array $context = []): bool
    {
        // 检查日志级别
        if ($level < $this->logLevel) {
            return false;
        }

        // 格式化日志消息
        $formattedMessage = $this->formatMessage($level, $message, $context);

        // 检查是否需要轮转日志文件
        $this->rotateLogFileIfNeeded();

        // 写入日志
        return $this->writeLog($formattedMessage);
    }

    /**
     * 设置日志级别
     * 
     * @param int $level 日志级别
     * @return self
     */
    public function setLogLevel(int $level): self
    {
        $this->logLevel = $level;
        return $this;
    }

    /**
     * 设置日志文件轮转类型
     * 
     * @param int $rotationType 日志文件轮转类型
     * @return self
     */
    public function setRotationType(int $rotationType): self
    {
        $this->rotationType = $rotationType;
        return $this;
    }

    /**
     * 设置日志文件大小限制
     * 
     * @param int $maxFileSize 日志文件大小限制（字节）
     * @return self
     */
    public function setMaxFileSize(int $maxFileSize): self
    {
        $this->maxFileSize = $maxFileSize;
        return $this;
    }

    /**
     * 设置日志文件保留数量
     * 
     * @param int $maxFiles 日志文件保留数量
     * @return self
     */
    public function setMaxFiles(int $maxFiles): self
    {
        $this->maxFiles = $maxFiles;
        return $this;
    }

    /**
     * 确保日志目录存在
     * 
     * @return void
     */
    protected function ensureLogDirectoryExists(): void
    {
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
    }

    /**
     * 格式化日志消息
     * 
     * @param int $level 日志级别
     * @param string $message 日志消息
     * @param array $context 上下文数据
     * @return string 格式化后的日志消息
     */
    protected function formatMessage(int $level, string $message, array $context = []): string
    {
        // 获取日志级别名称
        $levelName = self::LEVEL_NAMES[$level] ?? 'UNKNOWN';

        // 获取当前时间
        $datetime = date('Y-m-d H:i:s');

        // 格式化上下文数据
        $contextStr = empty($context) ? '' : ' ' . json_encode($context, JSON_UNESCAPED_UNICODE);

        // 获取调用者信息
        $caller = $this->getCallerInfo();
        $callerInfo = $caller ? " [{$caller['file']}:{$caller['line']}]" : '';

        // 组合日志消息
        return "[{$datetime}] [{$levelName}]{$callerInfo}: {$message}{$contextStr}" . PHP_EOL;
    }

    /**
     * 获取调用者信息
     * 
     * @return array|null 调用者信息，包含文件名和行号
     */
    protected function getCallerInfo(): ?array
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);
        
        // 查找第一个非Logger类的调用者
        foreach ($trace as $frame) {
            if (isset($frame['file']) && !str_contains($frame['file'], 'Logger.php')) {
                return [
                    'file' => basename($frame['file']),
                    'line' => $frame['line']
                ];
            }
        }
        
        return null;
    }

    /**
     * 检查是否需要轮转日志文件
     * 
     * @return void
     */
    protected function rotateLogFileIfNeeded(): void
    {
        $logFilePath = $this->getLogFilePath();

        // 如果日志文件不存在，不需要轮转
        if (!file_exists($logFilePath)) {
            return;
        }

        // 根据轮转类型检查是否需要轮转
        switch ($this->rotationType) {
            case self::ROTATION_DAILY:
                $this->rotateDailyIfNeeded($logFilePath);
                break;
            case self::ROTATION_SIZE:
                $this->rotateBySizeIfNeeded($logFilePath);
                break;
        }
    }

    /**
     * 按日期轮转日志文件
     * 
     * @param string $logFilePath 日志文件路径
     * @return void
     */
    protected function rotateDailyIfNeeded(string $logFilePath): void
    {
        // 获取日志文件的最后修改时间
        $lastModified = filemtime($logFilePath);
        
        // 如果最后修改时间不是今天，则轮转日志文件
        if (date('Y-m-d', $lastModified) !== date('Y-m-d')) {
            $this->rotateLogFile($logFilePath, date('Y-m-d', $lastModified));
        }
    }

    /**
     * 按大小轮转日志文件
     * 
     * @param string $logFilePath 日志文件路径
     * @return void
     */
    protected function rotateBySizeIfNeeded(string $logFilePath): void
    {
        // 获取日志文件大小
        $fileSize = filesize($logFilePath);
        
        // 如果文件大小超过限制，则轮转日志文件
        if ($fileSize >= $this->maxFileSize) {
            $this->rotateLogFile($logFilePath, date('Y-m-d-His'));
        }
    }

    /**
     * 轮转日志文件
     * 
     * @param string $logFilePath 日志文件路径
     * @param string $suffix 文件后缀
     * @return void
     */
    protected function rotateLogFile(string $logFilePath, string $suffix): void
    {
        // 构造新的日志文件名
        $newLogFilePath = $logFilePath . '.' . $suffix;
        
        // 重命名日志文件
        rename($logFilePath, $newLogFilePath);
        
        // 清理旧的日志文件
        $this->cleanOldLogFiles();
    }

    /**
     * 清理旧的日志文件
     * 
     * @return void
     */
    protected function cleanOldLogFiles(): void
    {
        // 获取所有日志文件
        $pattern = $this->getLogFilePath() . '.*';
        $logFiles = glob($pattern);
        
        // 如果日志文件数量超过限制，则删除最旧的文件
        if (count($logFiles) > $this->maxFiles) {
            // 按修改时间排序
            usort($logFiles, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            
            // 删除最旧的文件，直到文件数量不超过限制
            $filesToDelete = array_slice($logFiles, 0, count($logFiles) - $this->maxFiles);
            foreach ($filesToDelete as $file) {
                unlink($file);
            }
        }
    }

    /**
     * 获取日志文件路径
     * 
     * @return string 日志文件路径
     */
    protected function getLogFilePath(): string
    {
        return $this->logDir . DIRECTORY_SEPARATOR . $this->logFile;
    }

    /**
     * 写入日志
     * 
     * @param string $message 日志消息
     * @return bool 是否成功写入日志
     */
    protected function writeLog(string $message): bool
    {
        $logFilePath = $this->getLogFilePath();
        
        // 使用文件锁确保线程安全
        $fp = fopen($logFilePath, 'a');
        if ($fp === false) {
            return false;
        }
        
        // 获取文件锁
        if (flock($fp, LOCK_EX)) {
            // 写入日志
            $result = fwrite($fp, $message);
            
            // 释放文件锁
            flock($fp, LOCK_UN);
            fclose($fp);
            
            return $result !== false;
        }
        
        // 无法获取文件锁
        fclose($fp);
        return false;
    }
}