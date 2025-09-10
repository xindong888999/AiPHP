# Logger 类 API 文档

## 概述

`Logger` 类是一个简单但功能完整的日志记录系统，支持多种日志级别、日志文件轮转、格式化日志消息等功能，不依赖任何第三方库。该类设计用于在 AiPHP 框架中提供统一的日志记录功能。

## 命名空间

```php
namespace Core\OwnLibrary\Logger;
```

## 文件位置

```
core/own-library/logger/Logger.php
```

## 主要特性

1. 支持多种日志级别（DEBUG, INFO, WARNING, ERROR, CRITICAL）
2. 支持日志文件轮转（按大小或日期）
3. 支持格式化日志消息
4. 支持自定义日志目录
5. 线程安全的日志写入
6. 不依赖任何第三方库

## 常量

### 日志级别常量

| 常量名 | 值 | 描述 |
|--------|-----|------|
| `DEBUG` | 100 | 调试级别，最低级别，记录详细的调试信息 |
| `INFO` | 200 | 信息级别，记录一般信息 |
| `WARNING` | 300 | 警告级别，记录可能的问题 |
| `ERROR` | 400 | 错误级别，记录错误信息 |
| `CRITICAL` | 500 | 严重错误级别，记录严重错误信息 |

### 日志文件轮转类型常量

| 常量名 | 值 | 描述 |
|--------|-----|------|
| `ROTATION_NONE` | 0 | 不进行日志文件轮转 |
| `ROTATION_DAILY` | 1 | 按日期进行日志文件轮转 |
| `ROTATION_SIZE` | 2 | 按文件大小进行日志文件轮转 |

## 构造函数

```php
public function __construct(
    string $logDir = '',
    string $logFile = 'application.log',
    int $logLevel = self::DEBUG,
    int $rotationType = self::ROTATION_DAILY,
    int $maxFileSize = 10485760, // 10MB
    int $maxFiles = 7
)
```

### 参数

| 参数名 | 类型 | 默认值 | 描述 |
|--------|------|--------|------|
| `$logDir` | string | '' | 日志目录路径，默认为项目根目录下的logs文件夹 |
| `$logFile` | string | 'application.log' | 日志文件名 |
| `$logLevel` | int | self::DEBUG | 日志级别，默认为DEBUG |
| `$rotationType` | int | self::ROTATION_DAILY | 日志文件轮转类型，默认为按日期轮转 |
| `$maxFileSize` | int | 10485760 (10MB) | 日志文件大小限制（字节） |
| `$maxFiles` | int | 7 | 日志文件保留数量 |

## 公共方法

### 日志记录方法

#### debug()

```php
public function debug(string $message, array $context = []): bool
```

记录调试级别日志。

##### 参数

| 参数名 | 类型 | 描述 |
|--------|------|------|
| `$message` | string | 日志消息 |
| `$context` | array | 上下文数据，可选 |

##### 返回值

`bool` - 是否成功写入日志

#### info()

```php
public function info(string $message, array $context = []): bool
```

记录信息级别日志。

##### 参数

| 参数名 | 类型 | 描述 |
|--------|------|------|
| `$message` | string | 日志消息 |
| `$context` | array | 上下文数据，可选 |

##### 返回值

`bool` - 是否成功写入日志

#### warning()

```php
public function warning(string $message, array $context = []): bool
```

记录警告级别日志。

##### 参数

| 参数名 | 类型 | 描述 |
|--------|------|------|
| `$message` | string | 日志消息 |
| `$context` | array | 上下文数据，可选 |

##### 返回值

`bool` - 是否成功写入日志

#### error()

```php
public function error(string $message, array $context = []): bool
```

记录错误级别日志。

##### 参数

| 参数名 | 类型 | 描述 |
|--------|------|------|
| `$message` | string | 日志消息 |
| `$context` | array | 上下文数据，可选 |

##### 返回值

`bool` - 是否成功写入日志

#### critical()

```php
public function critical(string $message, array $context = []): bool
```

记录严重错误级别日志。

##### 参数

| 参数名 | 类型 | 描述 |
|--------|------|------|
| `$message` | string | 日志消息 |
| `$context` | array | 上下文数据，可选 |

##### 返回值

`bool` - 是否成功写入日志

#### log()

```php
public function log(int $level, string $message, array $context = []): bool
```

记录指定级别的日志。

##### 参数

| 参数名 | 类型 | 描述 |
|--------|------|------|
| `$level` | int | 日志级别 |
| `$message` | string | 日志消息 |
| `$context` | array | 上下文数据，可选 |

##### 返回值

`bool` - 是否成功写入日志

### 配置方法

#### setLogLevel()

```php
public function setLogLevel(int $level): self
```

设置日志级别。

##### 参数

| 参数名 | 类型 | 描述 |
|--------|------|------|
| `$level` | int | 日志级别 |

##### 返回值

`self` - 返回当前对象，支持链式调用

#### setRotationType()

```php
public function setRotationType(int $rotationType): self
```

设置日志文件轮转类型。

##### 参数

| 参数名 | 类型 | 描述 |
|--------|------|------|
| `$rotationType` | int | 日志文件轮转类型 |

##### 返回值

`self` - 返回当前对象，支持链式调用

#### setMaxFileSize()

```php
public function setMaxFileSize(int $maxFileSize): self
```

设置日志文件大小限制。

##### 参数

| 参数名 | 类型 | 描述 |
|--------|------|------|
| `$maxFileSize` | int | 日志文件大小限制（字节） |

##### 返回值

`self` - 返回当前对象，支持链式调用

#### setMaxFiles()

```php
public function setMaxFiles(int $maxFiles): self
```

设置日志文件保留数量。

##### 参数

| 参数名 | 类型 | 描述 |
|--------|------|------|
| `$maxFiles` | int | 日志文件保留数量 |

##### 返回值

`self` - 返回当前对象，支持链式调用

## 使用示例

### 基本使用

```php
// 创建日志记录器实例（使用默认配置）
$logger = new Logger();

// 记录不同级别的日志
$logger->debug('这是一条调试日志');
$logger->info('这是一条信息日志');
$logger->warning('这是一条警告日志');
$logger->error('这是一条错误日志', ['错误代码' => 500]);
$logger->critical('这是一条严重错误日志', ['异常' => '数据库连接失败']);
```

### 自定义配置

```php
// 使用自定义配置创建日志记录器
$customLogger = new Logger(
    logDir: __DIR__ . '/../logs',           // 日志目录
    logFile: 'custom.log',                  // 日志文件名
    logLevel: Logger::WARNING,              // 只记录警告及以上级别的日志
    rotationType: Logger::ROTATION_SIZE,    // 按大小轮转
    maxFileSize: 1024 * 1024,               // 最大文件大小为1MB
    maxFiles: 5                             // 最多保留5个日志文件
);
```

### 链式调用

```php
$logger->setLogLevel(Logger::ERROR)
       ->setRotationType(Logger::ROTATION_DAILY)
       ->setMaxFiles(10)
       ->error('这条错误日志会被记录');
```

### 在异常处理中使用

```php
try {
    // 业务逻辑
    throw new \Exception('发生了一个错误');
} catch (\Exception $e) {
    $logger->error('操作失败', [
        'exception' => get_class($e),
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
```

## 日志格式

默认的日志格式如下：

```
[日期时间] [日志级别] [文件名:行号]: 日志消息 上下文数据
```

例如：

```
[2023-09-09 11:45:30] [ERROR] [index.php:123]: 数据库连接失败 {"host":"localhost","port":3306}
```

## 日志文件轮转

### 按日期轮转

当启用按日期轮转（`ROTATION_DAILY`）时，每天会创建一个新的日志文件，旧的日志文件会被重命名为 `application.log.2023-09-08` 格式。

### 按大小轮转

当启用按大小轮转（`ROTATION_SIZE`）时，当日志文件大小超过设定的最大值时，会创建一个新的日志文件，旧的日志文件会被重命名为 `application.log.2023-09-09-114530` 格式。

## 线程安全

Logger 类使用文件锁（`flock`）确保在多线程/多进程环境下的日志写入是线程安全的。

## 性能考虑

- 日志级别过滤在写入前进行，可以避免不必要的I/O操作
- 日志文件轮转可以防止单个日志文件过大
- 自动清理旧的日志文件，防止磁盘空间占用过多

## 最佳实践

1. 在生产环境中，建议将日志级别设置为 `WARNING` 或 `ERROR`，以减少磁盘I/O
2. 在开发环境中，可以将日志级别设置为 `DEBUG`，以获取更详细的信息
3. 使用上下文数组传递结构化数据，而不是将所有信息拼接到消息字符串中
4. 定期检查和分析日志文件，及时发现和解决问题