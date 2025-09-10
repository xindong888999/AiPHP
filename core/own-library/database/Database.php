<?php

declare(strict_types=1);

namespace Core\OwnLibrary\Database;

/**
 * 数据库操作类
 * 
 * 提供对MySQL和SQLite3数据库的统一操作接口
 * 支持基本的增删改查操作
 * 
 * @package Core\OwnLibrary\Database
 * @author AiPHP Framework Team
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * ```php
 * // 创建数据库实例
 * $db = new Database();
 * 
 * // 查询数据
 * $users = $db->table('users')->where('age', '>', 18)->get();
 * 
 * // 插入数据
 * $userId = $db->table('users')->insert([
 *     'name' => 'John',
 *     'email' => 'john@example.com',
 *     'age' => 25
 * ]);
 * 
 * // 更新数据
 * $db->table('users')->where('id', 1)->update([
 *     'name' => 'Jane',
 *     'age' => 26
 * ]);
 * 
 * // 删除数据
 * $db->table('users')->where('id', 1)->delete();
 * ```
 */
class Database
{
    /**
     * 数据库连接实例
     * 
     * @var \PDO|null
     */
    private ?\PDO $connection = null;
    
    /**
     * 数据库配置
     * 
     * @var array
     */
    private array $config;
    
    /**
     * 当前操作的表名
     * 
     * @var string|null
     */
    private ?string $table = null;
    
    /**
     * WHERE条件数组
     * 
     * @var array
     */
    private array $whereConditions = [];
    
    /**
     * ORDER BY子句
     * 
     * @var string|null
     */
    private ?string $orderBy = null;
    
    /**
     * LIMIT子句
     * 
     * @var string|null
     */
    private ?string $limit = null;
    
    /**
     * 构造函数
     * 
     * 初始化数据库配置并建立连接
     */
    public function __construct()
    {
        // 加载数据库配置 - 直接使用路径而不依赖常量
        $this->config = require dirname(__DIR__, 3) . '/config/database.php';
        
        // 建立数据库连接
        $this->connect();
    }
    
    /**
     * 建立数据库连接
     * 
     * 根据配置建立MySQL或SQLite3数据库连接
     * 
     * @throws \Exception 当数据库驱动不支持时抛出异常
     */
    private function connect(): void
    {
        $defaultConnection = $this->config['default'];
        $connectionConfig = $this->config['connections'][$defaultConnection];
        
        try {
            switch ($connectionConfig['driver']) {
                case 'sqlite':
                    $this->connection = new \PDO('sqlite:' . $connectionConfig['database']);
                    $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                    break;
                    
                case 'mysql':
                    $dsn = "mysql:host={$connectionConfig['host']};port={$connectionConfig['port']};dbname={$connectionConfig['database']};charset={$connectionConfig['charset']}";
                    $this->connection = new \PDO($dsn, $connectionConfig['username'], $connectionConfig['password']);
                    $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                    $this->connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
                    break;
                    
                default:
                    throw new \Exception("不支持的数据库驱动: {$connectionConfig['driver']}");
            }
        } catch (\PDOException $e) {
            throw new \Exception("数据库连接失败: " . $e->getMessage());
        }
    }
    
    /**
     * 设置要操作的表
     * 
     * @param string $table 表名
     * @return self 返回当前实例以支持链式调用
     */
    public function table(string $table): self
    {
        $this->table = $table;
        // 重置查询条件
        $this->resetQuery();
        return $this;
    }
    
    /**
     * 添加WHERE条件
     * 
     * @param string $column 列名
     * @param string $operator 操作符
     * @param mixed $value 值
     * @return self 返回当前实例以支持链式调用
     */
    public function where(string $column, string $operator, $value): self
    {
        $this->whereConditions[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ];
        return $this;
    }
    
    /**
     * 设置ORDER BY子句
     * 
     * @param string $column 排序列名
     * @param string $direction 排序方向(ASC/DESC)
     * @return self 返回当前实例以支持链式调用
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy = "{$column} {$direction}";
        return $this;
    }
    
    /**
     * 设置LIMIT子句
     * 
     * @param int $limit 限制数量
     * @param int|null $offset 偏移量
     * @return self 返回当前实例以支持链式调用
     */
    public function limit(int $limit, ?int $offset = null): self
    {
        if ($offset !== null) {
            $this->limit = "{$offset}, {$limit}";
        } else {
            $this->limit = (string)$limit;
        }
        return $this;
    }
    
    /**
     * 查询多条记录
     * 
     * @return array 查询结果数组
     * @throws \Exception 当未设置表名时抛出异常
     */
    public function get(): array
    {
        if ($this->table === null) {
            throw new \Exception('未设置表名');
        }
        
        // 构建SQL查询语句
        $sql = "SELECT * FROM {$this->table}";
        
        // 添加WHERE条件
        $params = [];
        if (!empty($this->whereConditions)) {
            $whereClause = [];
            foreach ($this->whereConditions as $condition) {
                $whereClause[] = "{$condition['column']} {$condition['operator']} ?";
                $params[] = $condition['value'];
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        // 添加ORDER BY子句
        if ($this->orderBy !== null) {
            $sql .= " ORDER BY {$this->orderBy}";
        }
        
        // 添加LIMIT子句
        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }
        
        // 执行查询
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * 查询单条记录
     * 
     * @return array|null 查询结果数组或null
     * @throws \Exception 当未设置表名时抛出异常
     */
    public function first(): ?array
    {
        $result = $this->limit(1)->get();
        return $result ? $result[0] : null;
    }
    
    /**
     * 插入数据
     * 
     * @param array $data 要插入的数据
     * @return int|string 插入记录的ID
     * @throws \Exception 当未设置表名时抛出异常
     */
    public function insert(array $data)
    {
        if ($this->table === null) {
            throw new \Exception('未设置表名');
        }
        
        // 构建INSERT语句
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        // 执行插入
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(array_values($data));
        
        // 返回插入的ID
        $connectionConfig = $this->config['connections'][$this->config['default']];
        if ($connectionConfig['driver'] === 'sqlite') {
            return $this->connection->lastInsertId();
        } else {
            return $this->connection->lastInsertId();
        }
    }
    
    /**
     * 更新数据
     * 
     * @param array $data 要更新的数据
     * @return int 受影响的行数
     * @throws \Exception 当未设置表名时抛出异常
     */
    public function update(array $data): int
    {
        if ($this->table === null) {
            throw new \Exception('未设置表名');
        }
        
        // 构建UPDATE语句
        $setClause = [];
        $params = [];
        foreach ($data as $column => $value) {
            $setClause[] = "{$column} = ?";
            $params[] = $value;
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setClause);
        
        // 添加WHERE条件
        if (!empty($this->whereConditions)) {
            $whereClause = [];
            foreach ($this->whereConditions as $condition) {
                $whereClause[] = "{$condition['column']} {$condition['operator']} ?";
                $params[] = $condition['value'];
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        // 执行更新
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
    
    /**
     * 删除数据
     * 
     * @return int 受影响的行数
     * @throws \Exception 当未设置表名时抛出异常
     */
    public function delete(): int
    {
        if ($this->table === null) {
            throw new \Exception('未设置表名');
        }
        
        // 构建DELETE语句
        $sql = "DELETE FROM {$this->table}";
        
        // 添加WHERE条件
        $params = [];
        if (!empty($this->whereConditions)) {
            $whereClause = [];
            foreach ($this->whereConditions as $condition) {
                $whereClause[] = "{$condition['column']} {$condition['operator']} ?";
                $params[] = $condition['value'];
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        // 执行删除
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
    
    /**
     * 执行原生SQL查询
     * 
     * @param string $sql SQL语句
     * @param array $params 参数数组
     * @return array 查询结果
     */
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * 执行原生SQL语句
     * 
     * @param string $sql SQL语句
     * @param array $params 参数数组
     * @return int 受影响的行数
     */
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
    
    /**
     * 获取数据库连接实例
     * 
     * @return \PDO 数据库连接实例
     */
    public function getConnection(): \PDO
    {
        return $this->connection;
    }
    
    /**
     * 统计记录数量
     * 
     * @return int 记录数量
     * @throws \Exception 当未设置表名时抛出异常
     */
    public function count(): int
    {
        if ($this->table === null) {
            throw new \Exception('未设置表名');
        }
        
        // 构建COUNT查询语句
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        
        // 添加WHERE条件
        $params = [];
        if (!empty($this->whereConditions)) {
            $whereClause = [];
            foreach ($this->whereConditions as $condition) {
                $whereClause[] = "{$condition['column']} {$condition['operator']} ?";
                $params[] = $condition['value'];
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        // 执行查询
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        
        return (int)$result['count'];
    }
    
    /**
     * 重置查询条件
     * 
     * 清空WHERE条件、ORDER BY和LIMIT子句
     */
    private function resetQuery(): void
    {
        $this->whereConditions = [];
        $this->orderBy = null;
        $this->limit = null;
    }
    
    /**
     * 析构函数
     * 
     * 关闭数据库连接
     */
    public function __destruct()
    {
        $this->connection = null;
    }
}