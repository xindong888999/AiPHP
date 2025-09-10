<?php
/**
 * 创建用户表并插入示例数据
 * 
 * 本脚本用于在SQLite数据库中创建users表
 * 并插入50条模拟用户数据
 */

// 确保ROOT_PATH常量已定义
define('ROOT_PATH', dirname(__FILE__));

// 设置错误报告
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 数据库文件路径
$databasePath = ROOT_PATH . '/database/database.sqlite';

try {
    // 连接到SQLite数据库
    // 如果数据库不存在，将会自动创建
    $pdo = new PDO('sqlite:' . $databasePath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "成功连接到SQLite数据库<br>";
    
    // 检查users表是否存在
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
    $tableExists = $stmt->fetchColumn() !== false;
    
    if ($tableExists) {
        echo "users表已存在，将删除并重新创建<br>";
        $pdo->exec("DROP TABLE users");
    }
    
    // 创建users表
    $createTableSQL = "
        CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            nickname VARCHAR(50) NOT NULL,
            avatar VARCHAR(255),
            gender TINYINT,
            age INTEGER,
            phone VARCHAR(20),
            address TEXT,
            bio TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            last_login TIMESTAMP,
            status TINYINT DEFAULT 1
        );
    ";
    
    $pdo->exec($createTableSQL);
    echo "成功创建users表<br>";
    
    // 准备插入用户数据的SQL语句
    $insertSQL = "
        INSERT INTO users (
            username, email, password, nickname, avatar, 
            gender, age, phone, address, bio, 
            created_at, updated_at, last_login, status
        ) VALUES (
            :username, :email, :password, :nickname, :avatar, 
            :gender, :age, :phone, :address, :bio, 
            :created_at, :updated_at, :last_login, :status
        );
    ";
    
    $stmt = $pdo->prepare($insertSQL);
    
    // 生成50个随机用户数据
    echo "开始插入50条用户数据...<br>";
    
    // 模拟数据
    $firstNames = ['张', '王', '李', '赵', '刘', '陈', '杨', '黄', '周', '吴'];
    $lastNames = ['伟', '芳', '娜', '秀英', '敏', '静', '强', '磊', '军', '洋', '勇', '杰', '丽', '涛', '磊'];
    $domains = ['gmail.com', 'yahoo.com', 'outlook.com', 'qq.com', '163.com', '126.com', 'sina.com'];
    $addresses = ['北京市朝阳区', '上海市浦东新区', '广州市天河区', '深圳市南山区', '杭州市西湖区', '南京市玄武区', '武汉市江汉区', '成都市锦江区'];
    $biographies = [
        '热爱编程和技术', 
        '喜欢阅读和旅行', 
        '摄影爱好者', 
        '美食探索者', 
        '健身达人', 
        '音乐发烧友', 
        '电影迷', 
        '游戏玩家'
    ];
    
    // 插入50条用户数据
    for ($i = 1; $i <= 50; $i++) {
        // 生成随机用户数据
        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        $nickname = $firstName . $lastName;
        $username = strtolower($firstName . $lastName . $i);
        $email = $username . '@' . $domains[array_rand($domains)];
        $password = password_hash('password' . $i, PASSWORD_DEFAULT); // 实际应用中应使用更安全的密码生成方式
        $avatar = 'https://randomuser.me/api/portraits/' . ($i % 2 == 0 ? 'women' : 'men') . '/' . ($i % 100) . '.jpg';
        $gender = $i % 2; // 0: 女, 1: 男
        $age = rand(18, 60);
        $phone = '13' . rand(100000000, 999999999);
        $address = $addresses[array_rand($addresses)] . ' ' . rand(100, 999) . '号';
        $bio = $biographies[array_rand($biographies)];
        $createdAt = date('Y-m-d H:i:s', strtotime('-'.rand(1, 365).' days'));
        $updatedAt = $createdAt;
        $lastLogin = rand(0, 1) ? date('Y-m-d H:i:s', strtotime('-'.rand(1, 30).' days')) : null;
        $status = rand(0, 1);
        
        // 绑定参数
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':nickname', $nickname);
        $stmt->bindParam(':avatar', $avatar);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_INT);
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':created_at', $createdAt);
        $stmt->bindParam(':updated_at', $updatedAt);
        $stmt->bindParam(':last_login', $lastLogin);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        
        // 执行插入
        $stmt->execute();
        
        // 每插入10条数据显示一次进度
        if ($i % 10 == 0) {
            echo "已插入 {$i} 条用户数据<br>";
        }
    }
    
    echo "成功插入50条用户数据！<br>";
    
    // 查询用户表记录数，验证插入结果
    $countStmt = $pdo->query("SELECT COUNT(*) FROM users");
    $userCount = $countStmt->fetchColumn();
    echo "当前users表中共有 {$userCount} 条用户记录<br>";
    
} catch (PDOException $e) {
    die("数据库错误: " . $e->getMessage());
} catch (Exception $e) {
    die("错误: " . $e->getMessage());
} finally {
    // 关闭数据库连接
    $pdo = null;
    echo "数据库连接已关闭<br>";
}

// 添加一个简单的按钮，方便用户在浏览器中返回
if (php_sapi_name() !== 'cli') {
    echo "<br><a href='javascript:history.back()'>返回</a>";
}

?>