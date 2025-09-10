# 参数验证类使用说明

本文档详细说明了AiPHP框架中两个核心验证类的作用、使用方法以及扩展方式。

## 1. EnhancedParameterValidator 类

### 1.1 作用
EnhancedParameterValidator 是一个增强版的参数验证器类，提供了丰富的验证方法，支持自定义错误信息。它是框架中参数验证的核心组件。

### 1.2 主要功能
- 验证必需参数的存在性（validateRequired）
- 验证参数类型（validateType）
- 验证参数格式（validateFormat）
- 验证参数长度（validateLength）
- 验证数值范围（validateRange）
- 验证正则表达式（validateRegex）
- 验证枚举值（validateEnum）
- 综合验证参数（validate）

### 1.3 使用方法

```php
use Core\OwnLibrary\Validation\EnhancedParameterValidator;

$validator = new EnhancedParameterValidator();

// 验证必填参数
$params = ['id' => '123'];
$result = $validator->validateRequired($params, ['id' => 'ID是必需的']);
if (!$result['valid']) {
    echo $result['error']; // 输出错误信息
}

// 验证参数类型
$value = '123';
$result = $validator->validateType($value, 'int', 'ID必须是整数');
if (!$result['valid']) {
    echo $result['error'];
}

// 验证参数格式（邮箱）
$email = 'test@example.com';
$result = $validator->validateFormat($email, 'email', '邮箱格式不正确');
if (!$result['valid']) {
    echo $result['error'];
}
```

## 2. ValidationHelper 类

### 2.1 作用
ValidationHelper 是一个验证助手类，提供静态方法来简化参数验证操作。它主要作用是调用 EnhancedParameterValidator 中的不同方法进行验证，对外只提供一个统一的入口方法。ValidationHelper现已支持EnhancedParameterValidator的所有验证方法。

### 2.2 主要功能
- 提供简化的静态验证方法
- 支持链式规则验证
- 支持自定义错误信息
- 返回统一格式的验证结果
- 支持所有EnhancedParameterValidator的验证规则

### 2.3 使用方法

```php
use Core\OwnLibrary\Validation\ValidationHelper;

// 验证单个字段
$params = ['id' => '123'];
$result = ValidationHelper::validate($params, 'id', [
    '必填' => 'ID必须填写',  // 规则名 => 自定义错误信息
    '数字',                // 简化写法，使用默认错误信息
    '长度5' => 'ID长度必须为5个字符'  // 长度验证示例
]);

// 显示验证结果
echo $result['id'] ?? "验证通过";
```

### 2.4 返回值格式
- 验证通过：返回空数组 `[]`
- 验证失败：返回包含字段名和错误信息的数组 `['字段名' => '错误信息']`

## 3. 支持的验证规则

### 3.1 必填验证
```php
// 自定义错误信息
['必填' => '字段必须填写']
// 使用默认错误信息
['必填']
```

### 3.2 类型验证
``php
// 支持的类型：
['数字'] 或 ['number']      // int 类型验证
['整数'] 或 ['integer']     // int 类型验证
['字符串'] 或 ['string']    // string 类型验证
['布尔值'] 或 ['boolean']   // bool 类型验证
['浮点数'] 或 ['float']     // float 类型验证
['数组'] 或 ['array']       // array 类型验证
['空值'] 或 ['null']        // null 类型验证
```

### 3.3 格式验证
``php
// 支持的格式：
['邮箱'] 或 ['Email']        // 邮箱格式
['网址'] 或 ['Url']         // URL格式
['手机'] 或 ['Phone']       // 手机号格式
['IP地址'] 或 ['ip']        // IP地址格式
['IPv4']                   // IPv4地址格式
['IPv6']                   // IPv6地址格式
['字母'] 或 ['alpha']       // 纯字母格式
['字母数字'] 或 ['alnum']    // 字母数字格式
['数值'] 或 ['numeric']     // 数值格式
['数字串'] 或 ['digits']    // 纯数字串格式
['slug']                   // slug格式（字母、数字、连字符、下划线）
['日期'] 或 ['date']        // 日期格式 (YYYY-MM-DD)
['日期时间'] 或 ['datetime'] // 日期时间格式 (YYYY-MM-DD HH:MM:SS)
['UUID']                   // UUID格式
['信用卡'] 或 ['credit_card'] // 信用卡号格式
['邮政编码'] 或 ['postal_code'] // 邮政编码格式
```

### 3.4 长度验证
``php
// 固定长度
['长度6']        // 长度必须为6个字符

// 长度范围
['长度6-20']     // 长度必须在6-20个字符之间

// 最小长度
['最小长度6']     // 长度不能少于6个字符

// 最大长度
['最大长度20']    // 长度不能超过20个字符
```

### 3.5 范围验证
``php
// 数值范围
['范围18-65']    // 数值必须在18-65之间

// 最小值
['最小值0']       // 数值不能小于0

// 最大值
['最大值100']     // 数值不能大于100
```

### 3.6 枚举验证
``php
// 枚举值验证
['枚举(active,inactive,pending)']  // 值必须是active、inactive或pending之一
```

### 3.7 正则表达式验证
``php
// 正则表达式验证
['正则(/^[a-zA-Z0-9]+$/)' ]  // 值必须匹配指定的正则表达式
```

## 4. 完整使用示例

``php
use Core\OwnLibrary\Validation\ValidationHelper;

// 示例1：验证用户注册信息
$params = [
    'username' => 'testuser',
    'email' => 'test@example.com',
    'password' => 'password123',
    'age' => '25'
];

$usernameValidation = ValidationHelper::validate($params, 'username', [
    '必填' => '用户名不能为空',
    '长度6-20' => '用户名长度必须在6-20个字符之间',
    '正则(/^[a-zA-Z0-9_]+$/)' => '用户名只能包含字母、数字和下划线'
]);

$emailValidation = ValidationHelper::validate($params, 'email', [
    '必填' => '邮箱不能为空',
    '邮箱' => '邮箱格式不正确'
]);

$passwordValidation = ValidationHelper::validate($params, 'password', [
    '必填' => '密码不能为空',
    '最小长度8' => '密码长度不能少于8个字符'
]);

$ageValidation = ValidationHelper::validate($params, 'age', [
    '必填' => '年龄不能为空',
    '数字' => '年龄必须是数字',
    '范围18-100' => '年龄必须在18-100之间'
]);

// 检查所有验证结果
$allValidations = array_merge($usernameValidation, $emailValidation, $passwordValidation, $ageValidation);
if (empty($allValidations)) {
    echo "所有验证通过";
} else {
    foreach ($allValidations as $field => $error) {
        echo "字段 {$field}: {$error}\n";
    }
}
```

## 5. 扩展方式

### 5.1 扩展 EnhancedParameterValidator
要添加新的验证方法，可以直接在 EnhancedParameterValidator 类中添加新的公共方法：

``php
/**
 * 验证自定义格式
 * 
 * @param string $value 参数值
 * @param string $errorMessage 自定义错误信息
 * @return array 验证结果
 */
public function validateCustomFormat(string $value, ?string $errorMessage = null): array
{
    // 实现验证逻辑
    $valid = your_validation_logic($value);
    
    if (!$valid) {
        return [
            'valid' => false,
            'error' => $errorMessage ?? "参数格式不正确"
        ];
    }
    
    return ['valid' => true];
}
```

### 5.2 扩展 ValidationHelper
要支持新的验证规则，需要修改 ValidationHelper 类中的 applyRule 方法：

```
/**
 * 应用单个验证规则
 * 
 * @param EnhancedParameterValidator $validator 验证器实例
 * @param array $params 参数数组
 * @param string $field 字段名
 * @param string $rule 验证规则
 * @param string|null $errorMessage 自定义错误信息
 * @return array 验证结果
 */
private static function applyRule(EnhancedParameterValidator $validator, array $params, string $field, string $rule, ?string $errorMessage): array
{
    $value = $params[$field] ?? null;

    if ($rule === '必填' || $rule === 'required') {
        return $validator->validateRequired($params, [$field => $errorMessage ?? "字段{$field}是必需的"]);
    }

    if ($rule === '数字' || $rule === 'number' || $rule === '整数' || $rule === 'integer') {
        return $validator->validateType($value, 'int', $errorMessage ?? "字段{$field}必须是数字");
    }

    if ($rule === '字符串' || $rule === 'string') {
        return $validator->validateType($value, 'string', $errorMessage ?? "字段{$field}必须是字符串");
    }

    if ($rule === '布尔值' || $rule === 'boolean') {
        return $validator->validateType($value, 'bool', $errorMessage ?? "字段{$field}必须是布尔值");
    }

    if ($rule === '浮点数' || $rule === 'float') {
        return $validator->validateType($value, 'float', $errorMessage ?? "字段{$field}必须是浮点数");
    }

    if ($rule === '数组' || $rule === 'array') {
        return $validator->validateType($value, 'array', $errorMessage ?? "字段{$field}必须是数组");
    }

    if ($rule === '空值' || $rule === 'null') {
        return $validator->validateType($value, 'null', $errorMessage ?? "字段{$field}必须为空值");
    }

    if ($rule === '邮箱' || $rule === 'Email') {
        return $validator->validateFormat($value, 'email', $errorMessage ?? "字段{$field}格式不正确");
    }

    if ($rule === '网址' || $rule === 'Url') {
        return $validator->validateFormat($value, 'url', $errorMessage ?? "字段{$field}格式不正确");
    }

    if ($rule === '手机' || $rule === 'Phone') {
        return $validator->validateFormat($value, 'phone', $errorMessage ?? "字段{$field}格式不正确");
    }

    if ($rule === 'IP地址' || $rule === 'ip') {
        return $validator->validateFormat($value, 'ip', $errorMessage ?? "字段{$field}格式不正确");
    }

    if ($rule === 'IPv4') {
        return $validator->validateFormat($value, 'ipv4', $errorMessage ?? "字段{$field}格式不正确");
    }

    if ($rule === 'IPv6') {
        return $validator->validateFormat($value, 'ipv6', $errorMessage ?? "字段{$field}格式不正确");
    }

    if ($rule === '字母' || $rule === 'alpha') {
        return $validator->validateFormat($value, 'alpha', $errorMessage ?? "字段{$field}格式不正确");
    }

    if ($rule === '字母数字' || $rule === 'alnum') {
        return $validator->validateFormat($value, 'alnum', $errorMessage ?? "字段{$field}格式不正确");
    }

    if ($rule === '数值' || $rule === 'numeric') {
        return $validator->validateFormat($value, 'numeric', $errorMessage ?? "字段{$field}格式不正确");
    }

    if ($rule === '数字串' || $rule === 'digits') {
        return $validator->validateFormat($value, 'digits', $errorMessage ?? "字段{$field}格式不正确");
    }

    if ($rule === 'slug') {
        return $validator->validateFormat($value, 'slug', $errorMessage ?? "字段{$field}格式不正确");
    }

    if ($rule === '日期' || $rule === 'date') {
        return $validator->validateFormat($value, 'date', $errorMessage ?? "字段{$field}格式不正确");
    }

    if ($rule === '日期时间' || $rule === 'datetime') {
        return $validator->validateFormat($value, 'datetime', $errorMessage ?? "字段{$field}格式不正确");
    }

    if ($rule === 'UUID') {
        return $validator->validateFormat($value, 'uuid', $errorMessage ?? "字段{$field}格式不正确");
    }

    if ($rule === '信用卡' || $rule === 'credit_card') {
        return $validator->validateFormat($value, 'credit_card', $errorMessage ?? "字段{$field}格式不正确");
    }

    if ($rule === '邮政编码' || $rule === 'postal_code') {
        return $validator->validateFormat($value, 'postal_code', $errorMessage ?? "字段{$field}格式不正确");
    }

    if (preg_match('/^长度(\d+)$/', $rule, $matches)) {
        return $validator->validateLength($value, (int)$matches[1], (int)$matches[1], $errorMessage ?? "字段{$field}长度必须为{$matches[1]}个字符");
    }

    if (preg_match('/^长度(\d+)-(\d+)$/', $rule, $matches)) {
        return $validator->validateLength($value, (int)$matches[1], (int)$matches[2], $errorMessage ?? "字段{$field}长度必须在{$matches[1]}-{$matches[2]}个字符之间");
    }

    if (preg_match('/^最小长度(\d+)$/', $rule, $matches)) {
        return $validator->validateLength($value, (int)$matches[1], null, $errorMessage ?? "字段{$field}长度不能少于{$matches[1]}个字符");
    }

    if (preg_match('/^最大长度(\d+)$/', $rule, $matches)) {
        return $validator->validateLength($value, null, (int)$matches[1], $errorMessage ?? "字段{$field}长度不能超过{$matches[1]}个字符");
    }

    if (preg_match('/^范围(\d+)-(\d+)$/', $rule, $matches)) {
        return $validator->validateRange($value, (int)$matches[1], (int)$matches[2], $errorMessage ?? "字段{$field}必须在{$matches[1]}-{$matches[2]}之间");
    }

    if (preg_match('/^最小值(\d+)$/', $rule, $matches)) {
        return $validator->validateRange($value, (int)$matches[1], null, $errorMessage ?? "字段{$field}不能小于{$matches[1]}");
    }

    if (preg_match('/^最大值(\d+)$/', $rule, $matches)) {
        return $validator->validateRange($value, null, (int)$matches[1], $errorMessage ?? "字段{$field}不能大于{$matches[1]}");
    }

    if (preg_match('/^枚举\(([^)]+)\)$/', $rule, $matches)) {
        return $validator->validateEnum($value, explode(',', $matches[1]), $errorMessage ?? "字段{$field}必须是以下值之一：" . implode(', ', explode(',', $matches[1])));
    }

    if (preg_match('/^正则\(([^)]+)\)$/', $rule, $matches)) {
        return $validator->validateRegex($value, $matches[1], $errorMessage ?? "字段{$field}格式不正确");
    }

    // 添加新的规则处理
    if ($rule === '自定义规则' || $rule === 'custom') {
        return $validator->validateCustomFormat($value, $errorMessage ?? '自定义格式不正确');
    }

    return ['valid' => true];
}