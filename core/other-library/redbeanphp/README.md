# RedBeanPHP ORM 集成说明

## 简介

RedBeanPHP是一个易于使用的PHP ORM库，具有零配置、自动适应数据库结构的特点。它支持多种数据库，包括MySQL、SQLite、PostgreSQL等。

## 特点

- 零配置：无需预先定义模型或表结构
- 自动适应：根据代码自动创建和修改表结构
- 简单易用：API简洁直观
- 无依赖：单文件实现，易于集成
- 支持多种数据库：MySQL、SQLite、PostgreSQL等

## 集成方式

本项目已集成RedBeanPHP ORM，并提供了一个门面类`RedBeanFacade`来简化使用。

### 使用示例

```php
use Core\OtherLibrary\RedBean\RedBeanFacade as R;

// 初始化（通常在框架引导时自动完成）
R::initialize();

// 创建Bean
$user = R::dispense('user');
$user->username = 'testuser';
$user->email = 'test@example.com';
$user->created_at = date('Y-m-d H:i:s');

// 保存Bean
$id = R::store($user);

// 查找Bean
$users = R::find('user');
$user = R::findOne('user', 'username = ?', ['testuser']);

// 通过ID加载Bean
$user = R::load('user', $id);

// 更新Bean
$user->username = 'updateduser';
R::store($user);

// 删除Bean
R::trash($user);

// 统计记录数
$count = R::count('user');
```

## 与Medoo的对比

| 特性 | Medoo | RedBeanPHP |
|------|-------|------------|
| 配置复杂度 | 需要预定义表结构 | 零配置 |
| 学习成本 | 中等 | 低 |
| 自动适应 | 不支持 | 支持 |
| 性能 | 高 | 中等 |
| 灵活性 | 高 | 中等 |

## 注意事项

1. RedBeanPHP会自动创建表和列，请在生产环境中谨慎使用
2. 在生产环境中，建议关闭自动适应功能
3. RedBeanPHP的Bean对象与数组不同，需要适应面向对象的操作方式

## 相关文件

- `RedBeanFacade.php` - 门面类，简化RedBeanPHP的使用
- `rb.php` - RedBeanPHP核心库文件
- `UPGRADE_GUIDE.md` - 从Medoo迁移到RedBeanPHP的升级指南
- `tests/redbean_test.php` - RedBeanPHP测试文件

## 参考资料

- [RedBeanPHP官方文档](https://redbeanphp.com/)
- [RedBeanPHP GitHub仓库](https://github.com/gabordemooij/redbean)