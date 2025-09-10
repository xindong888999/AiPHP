<?php

declare(strict_types=1);

// 引入自动加载器（假设已经配置好）
require_once __DIR__ . '/../core/own-library/autoloader/Autoloader.php';

// 使用日志类
use Core\OwnLibrary\Logger\Logger;

/**
 * 日志类使用示例
 */

// 创建日志记录器实例（使用默认配置）
$logger = new Logger();

// 记录不同级别的日志
$logger->debug('这是一条调试日志');
$logger->info('这是一条信息日志');
$logger->warning('这是一条警告日志');
$logger->error('这是一条错误日志', ['错误代码' => 500]);
$logger->critical('这是一条严重错误日志', ['异常' => '数据库连接失败']);

// 使用自定义配置创建日志记录器
$customLogger = new Logger(
    logDir: __DIR__ . '/../logs',           // 日志目录
    logFile: 'custom.log',                  // 日志文件名
    logLevel: Logger::WARNING,              // 只记录警告及以上级别的日志
    rotationType: Logger::ROTATION_SIZE,    // 按大小轮转
    maxFileSize: 1024 * 1024,               // 最大文件大小为1MB
    maxFiles: 5                             // 最多保留5个日志文件
);

// 使用自定义日志记录器
$customLogger->debug('这条调试日志不会被记录，因为级别低于WARNING');
$customLogger->warning('这条警告日志会被记录到custom.log文件');
$customLogger->error('这条错误日志也会被记录', ['用户ID' => 123]);

// 链式调用示例
$logger->setLogLevel(Logger::ERROR)
       ->setRotationType(Logger::ROTATION_DAILY)
       ->setMaxFiles(10)
       ->info('这条信息日志不会被记录，因为级别已设置为ERROR')
       ->error('这条错误日志会被记录');

// 在实际应用中的使用示例
function processOrder($orderId) {
    global $logger;
    
    $logger->info('开始处理订单', ['orderId' => $orderId]);
    
    try {
        // 模拟订单处理
        if ($orderId <= 0) {
            throw new \InvalidArgumentException('无效的订单ID');
        }
        
        // 订单处理成功
        $logger->info('订单处理成功', ['orderId' => $orderId]);
        
    } catch (\Exception $e) {
        // 记录异常
        $logger->error('订单处理失败', [
            'orderId' => $orderId,
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        // 重新抛出异常或返回错误
        return false;
    }
    
    return true;
}

// 测试订单处理函数
processOrder(123); // 成功
processOrder(-1);  // 失败

echo "日志已写入到 logs/application.log 和 logs/custom.log 文件中。\n";