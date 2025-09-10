<?php

declare(strict_types=1);

namespace Core\OwnLibrary\Validation;

/**
 * 增强版参数验证器类
 * 
 * 该类提供了一组方法，用于验证和过滤来自HTTP请求的参数。
 * 支持多种验证规则和自定义错误信息，可用于API接口参数验证、表单提交验证等场景。
 * 
 * @package Core\OwnLibrary\Validation
 * @author AiPHP Team
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * ```php
 * // 基本使用方式
 * $validator = new EnhancedParameterValidator();
 * 
 * // 验证必填参数
 * $params = ['name' => 'John', 'age' => 25];
 * $result = $validator->validateRequired($params, ['name', 'age', 'email' => '邮箱必须填写']);
 * if (!$result['valid']) {
 *     echo $result['error']; // 输出: 邮箱必须填写
 * }
 * 
 * // 验证类型
 * $result = $validator->validateType('25', 'int', '年龄必须是整数');
 * if ($result['valid']) {
 *     echo "验证通过";
 * }
 * 
 * // 综合验证
 * $rules = [
 *     'required' => ['name', 'email' => '邮箱必须填写'],
 *     'name' => ['type' => 'string', 'max' => 50],
 *     'email' => ['format' => 'email', 'format_error' => '邮箱格式不正确'],
 *     'age' => ['type' => 'int', 'min_range' => 18, 'max_range' => 120]
 * ];
 * $result = $validator->validate($params, $rules);
 * ```
 */
class EnhancedParameterValidator
{
    /**
     * 验证必需参数的存在性
     * 
     * 检查指定的参数是否都存在于参数数组中，支持为每个参数设置自定义错误信息。
     * 
     * @param array $params 要验证的参数数组
     * @param array $requiredKeys 必需的参数键名数组，可以是字符串或键值对（键为参数名，值为自定义错误信息）
     * @return array 验证结果，格式为：['valid' => bool, 'error' => string|null]
     * 
     * @example
     * ```php
     * // 例1：使用默认错误信息
     * $params = ['name' => 'John'];
     * $result = $validator->validateRequired($params, ['name', 'email']);
     * // $result = ['valid' => false, 'error' => '缺少必需参数: email']
     * 
     * // 例2：使用自定义错误信息
     * $params = ['name' => 'John'];
     * $result = $validator->validateRequired($params, ['name', 'email' => '邮箱地址不能为空']);
     * // $result = ['valid' => false, 'error' => '邮箱地址不能为空']
     * 
     * // 例3：所有参数都存在
     * $params = ['name' => 'John', 'email' => 'john@example.com'];
     * $result = $validator->validateRequired($params, ['name', 'email']);
     * // $result = ['valid' => true]
     * ```
     */
    public function validateRequired(array $params, array $requiredKeys): array
    {
        $missingKeys = [];
        $errorMessages = [];
        
        foreach ($requiredKeys as $key => $errorMessage) {
            // 如果是数字键，则值就是参数名
            if (is_int($key)) {
                $paramName = $errorMessage;
                if (!isset($params[$paramName])) {
                    $missingKeys[] = $paramName;
                    $errorMessages[] = "缺少必需参数: $paramName";
                }
            } else {
                // 如果是字符串键，则键是参数名，值是自定义错误信息
                $paramName = $key;
                if (!isset($params[$paramName])) {
                    $missingKeys[] = $paramName;
                    $errorMessages[] = is_string($errorMessage) ? $errorMessage : "缺少必需参数: $paramName";
                }
            }
        }
        
        if (!empty($missingKeys)) {
            return [
                'valid' => false,
                'error' => implode(', ', $errorMessages)
            ];
        }
        
        return ['valid' => true];
    }
    
    /**
     * 验证参数类型
     * 
     * 验证参数是否符合指定的类型，支持多种类型检查和自定义错误信息。
     * 
     * @param mixed $value 要验证的参数值
     * @param string $type 期望的类型，支持: int, string, bool, float, array, null
     * @param string|null $errorMessage 自定义错误信息
     * @return array 验证结果，格式为：['valid' => bool, 'error' => string|null]
     * 
     * @example
     * ```php
     * // 例1：整数验证成功
     * $result = $validator->validateType(25, 'int');
     * // $result = ['valid' => true]
     * 
     * // 例2：字符串数字也可以通过整数验证
     * $result = $validator->validateType('25', 'int');
     * // $result = ['valid' => true]
     * 
     * // 例3：非数字字符串无法通过整数验证
     * $result = $validator->validateType('abc', 'int');
     * // $result = ['valid' => false, 'error' => '必须是数字']
     * 
     * // 例4：使用自定义错误信息
     * $result = $validator->validateType('abc', 'int', '年龄必须是数字');
     * // $result = ['valid' => false, 'error' => '年龄必须是数字']
     * 
     * // 例5：布尔值验证
     * $result = $validator->validateType(true, 'bool');
     * // $result = ['valid' => true]
     * $result = $validator->validateType('true', 'bool');
     * // $result = ['valid' => true]
     * ```
     */
    public function validateType($value, string $type, ?string $errorMessage = null): array
    {
        $valid = false;
        $actualType = gettype($value);
        
        switch ($type) {
            case 'int':
                $valid = is_int($value) || (is_string($value) && ctype_digit($value));
                break;
                
            case 'string':
                $valid = is_string($value);
                break;
                
            case 'bool':
                $valid = is_bool($value) || in_array($value, ['true', 'false', '1', '0'], true);
                break;
                
            case 'float':
                $valid = is_float($value) || is_numeric($value);
                break;
                
            case 'array':
                $valid = is_array($value);
                break;
                
            case 'null':
                $valid = is_null($value);
                break;
                
            default:
                return [
                    'valid' => false,
                    'error' => $errorMessage ?? "不支持的类型: $type"
                ];
        }
        
        if (!$valid) {
            // 如果提供了自定义错误信息，使用它；否则使用简短的默认错误信息
            if ($errorMessage !== null) {
                return [
                    'valid' => false,
                    'error' => $errorMessage
                ];
            } else {
                // 简短的默认错误信息
                switch ($type) {
                    case 'int':
                        return ['valid' => false, 'error' => '必须是数字'];
                    case 'string':
                        return ['valid' => false, 'error' => '必须是字符串'];
                    case 'bool':
                        return ['valid' => false, 'error' => '必须是布尔值'];
                    case 'float':
                        return ['valid' => false, 'error' => '必须是浮点数'];
                    case 'array':
                        return ['valid' => false, 'error' => '必须是数组'];
                    case 'null':
                        return ['valid' => false, 'error' => '必须为空值'];
                    default:
                        return ['valid' => false, 'error' => "必须是{$type}类型"];
                }
            }
        }
        
        return ['valid' => true];
    }
    
    /**
     * 验证参数格式
     * 
     * 验证参数是否符合指定的格式，支持多种常见格式验证和自定义错误信息。
     * 
     * @param string $value 要验证的参数值
     * @param string $format 期望的格式，支持:
     *        email, url, ip, ipv4, ipv6, alpha, alnum, numeric, digits,
     *        slug, date, datetime, uuid, credit_card, phone, postal_code
     * @param string|null $errorMessage 自定义错误信息
     * @return array 验证结果，格式为：['valid' => bool, 'error' => string|null]
     * 
     * @example
     * ```php
     * // 例1：验证邮箱格式
     * $result = $validator->validateFormat('user@example.com', 'email');
     * // $result = ['valid' => true]
     * 
     * // 例2：无效邮箱
     * $result = $validator->validateFormat('invalid-email', 'email');
     * // $result = ['valid' => false, 'error' => '必须是邮箱格式']
     * 
     * // 例3：使用自定义错误信息
     * $result = $validator->validateFormat('invalid-email', 'email', '请输入有效的电子邮箱地址');
     * // $result = ['valid' => false, 'error' => '请输入有效的电子邮箱地址']
     * 
     * // 例4：验证URL
     * $result = $validator->validateFormat('https://example.com', 'url');
     * // $result = ['valid' => true]
     * 
     * // 例5：验证手机号
     * $result = $validator->validateFormat('13800138000', 'phone');
     * // $result = ['valid' => true]
     * ```
     */
    public function validateFormat(string $value, string $format, ?string $errorMessage = null): array
    {
        $valid = false;
        
        switch ($format) {
            case 'email':
                $valid = filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
                break;
                
            case 'url':
                // 更严格的URL验证
                // 空字符串不应该通过URL验证
                if ($value === '') {
                    $valid = false;
                } else {
                    $valid = filter_var($value, FILTER_VALIDATE_URL) !== false;
                    // 额外检查确保URL包含协议和主机
                    if ($valid) {
                        $parsedUrl = parse_url($value);
                        $valid = isset($parsedUrl['scheme']) && isset($parsedUrl['host']) && 
                                 in_array(strtolower($parsedUrl['scheme']), ['http', 'https']);
                    }
                }
                break;
                
            case 'ip':
                $valid = filter_var($value, FILTER_VALIDATE_IP) !== false;
                break;
                
            case 'ipv4':
                $valid = filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
                break;
                
            case 'ipv6':
                $valid = filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
                break;
                
            case 'alpha':
                $valid = ctype_alpha($value);
                break;
                
            case 'alnum':
                $valid = ctype_alnum($value);
                break;
                
            case 'numeric':
                $valid = is_numeric($value);
                break;
                
            case 'digits':
                $valid = ctype_digit($value);
                break;
                
            case 'slug':
                // 只包含字母、数字、连字符和下划线
                $valid = preg_match('/^[a-zA-Z0-9\-_]+$/', $value) === 1;
                break;
                
            case 'date':
                // 验证日期格式 YYYY-MM-DD
                $valid = preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1 && strtotime($value) !== false;
                break;
                
            case 'datetime':
                // 验证日期时间格式 YYYY-MM-DD HH:MM:SS
                $valid = preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value) === 1 && strtotime($value) !== false;
                break;
                
            case 'uuid':
                // 验证UUID格式
                $valid = preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value) === 1;
                break;
                
            case 'credit_card':
                // 验证信用卡号（使用Luhn算法）
                $valid = $this->validateCreditCard($value);
                break;
                
            case 'phone':
                // 验证电话号码格式
                $valid = preg_match('/^[\+]?[0-9\- \(\)]{7,20}$/', $value) === 1;
                break;
                
            case 'postal_code':
                // 验证邮政编码格式（简单验证）
                $valid = preg_match('/^[A-Za-z0-9\s\-]{3,10}$/', $value) === 1;
                break;
                
            default:
                return [
                    'valid' => false,
                    'error' => $errorMessage ?? "不支持的格式: $format"
                ];
        }
        
        if (!$valid) {
            // 如果提供了自定义错误信息，使用它；否则使用简短的默认错误信息
            if ($errorMessage !== null) {
                return [
                    'valid' => false,
                    'error' => $errorMessage
                ];
            } else {
                // 简短的默认错误信息
                switch ($format) {
                    case 'email':
                        return ['valid' => false, 'error' => '必须是邮箱格式'];
                    case 'url':
                        return ['valid' => false, 'error' => '必须是网址格式'];
                    case 'ip':
                        return ['valid' => false, 'error' => '必须是IP地址格式'];
                    case 'ipv4':
                        return ['valid' => false, 'error' => '必须是IPv4地址格式'];
                    case 'ipv6':
                        return ['valid' => false, 'error' => '必须是IPv6地址格式'];
                    case 'alpha':
                        return ['valid' => false, 'error' => '必须是字母格式'];
                    case 'alnum':
                        return ['valid' => false, 'error' => '必须是字母数字格式'];
                    case 'numeric':
                        return ['valid' => false, 'error' => '必须是数值格式'];
                    case 'digits':
                        return ['valid' => false, 'error' => '必须是数字串格式'];
                    case 'slug':
                        return ['valid' => false, 'error' => '必须是slug格式'];
                    case 'date':
                        return ['valid' => false, 'error' => '必须是日期格式'];
                    case 'datetime':
                        return ['valid' => false, 'error' => '必须是日期时间格式'];
                    case 'uuid':
                        return ['valid' => false, 'error' => '必须是UUID格式'];
                    case 'credit_card':
                        return ['valid' => false, 'error' => '必须是信用卡号格式'];
                    case 'phone':
                        return ['valid' => false, 'error' => '必须是手机号格式'];
                    case 'postal_code':
                        return ['valid' => false, 'error' => '必须是邮政编码格式'];
                    default:
                        return ['valid' => false, 'error' => "必须是{$format}格式"];
                }
            }
        }
        
        return ['valid' => true];
    }
    
    /**
     * 验证参数长度
     * 
     * 验证字符串参数的长度是否在指定范围内，支持设置最小长度和/或最大长度。
     * 
     * @param string $value 要验证的参数值
     * @param int|null $min 最小长度，null表示不限制最小长度
     * @param int|null $max 最大长度，null表示不限制最大长度
     * @param string|null $errorMessage 自定义错误信息
     * @return array 验证结果，格式为：['valid' => bool, 'error' => string|null]
     * 
     * @example
     * ```php
     * // 例1：验证字符串长度在指定范围内
     * $result = $validator->validateLength('password123', 8, 20);
     * // $result = ['valid' => true]
     * 
     * // 例2：字符串长度小于最小值
     * $result = $validator->validateLength('123', 8, 20);
     * // $result = ['valid' => false, 'error' => '长度不能少于8个字符']
     * 
     * // 例3：字符串长度大于最大值
     * $result = $validator->validateLength('this_is_a_very_long_password', 8, 20);
     * // $result = ['valid' => false, 'error' => '长度不能超过20个字符']
     * 
     * // 例4：只验证最小长度
     * $result = $validator->validateLength('password123', 8);
     * // $result = ['valid' => true]
     * 
     * // 例5：只验证最大长度
     * $result = $validator->validateLength('123', null, 5);
     * // $result = ['valid' => true]
     * 
     * // 例6：使用自定义错误信息
     * $result = $validator->validateLength('123', 8, null, '密码长度不能少于8位');
     * // $result = ['valid' => false, 'error' => '密码长度不能少于8位']
     * ```
     */
    public function validateLength(string $value, ?int $min = null, ?int $max = null, ?string $errorMessage = null): array
    {
        $length = strlen($value);
        
        if ($min !== null && $length < $min) {
            return [
                'valid' => false,
                'error' => $errorMessage ?? "长度不能少于{$min}个字符"
            ];
        }
        
        if ($max !== null && $length > $max) {
            return [
                'valid' => false,
                'error' => $errorMessage ?? "长度不能超过{$max}个字符"
            ];
        }
        
        return ['valid' => true];
    }
    
    /**
     * 验证数值范围
     * 
     * 验证数值参数是否在指定范围内，支持设置最小值和/或最大值。
     * 
     * @param mixed $value 要验证的参数值
     * @param int|float|null $min 最小值，null表示不限制最小值
     * @param int|float|null $max 最大值，null表示不限制最大值
     * @param string|null $errorMessage 自定义错误信息
     * @return array 验证结果，格式为：['valid' => bool, 'error' => string|null]
     * 
     * @example
     * ```php
     * // 例1：验证数值在指定范围内
     * $result = $validator->validateRange(25, 18, 60);
     * // $result = ['valid' => true]
     * 
     * // 例2：数值小于最小值
     * $result = $validator->validateRange(15, 18, 60);
     * // $result = ['valid' => false, 'error' => '不能小于18']
     * 
     * // 例3：数值大于最大值
     * $result = $validator->validateRange(65, 18, 60);
     * // $result = ['valid' => false, 'error' => '不能大于60']
     * 
     * // 例4：只验证最小值
     * $result = $validator->validateRange(25, 18);
     * // $result = ['valid' => true]
     * 
     * // 例5：只验证最大值
     * $result = $validator->validateRange(25, null, 60);
     * // $result = ['valid' => true]
     * 
     * // 例6：使用自定义错误信息
     * $result = $validator->validateRange(15, 18, 60, '年龄必须在18到60岁之间');
     * // $result = ['valid' => false, 'error' => '年龄必须在18到60岁之间']
     * 
     * // 例7：非数值无法通过验证
     * $result = $validator->validateRange('abc', 18, 60);
     * // $result = ['valid' => false, 'error' => '必须是数值']
     * ```
     */
    public function validateRange($value, $min = null, $max = null, ?string $errorMessage = null): array
    {
        // 首先检查是否为数值
        if (!is_numeric($value)) {
            return [
                'valid' => false,
                'error' => $errorMessage ?? "必须是数值"
            ];
        }
        
        $numValue = is_float($value) ? $value : (is_int($value) ? $value : (float)$value);
        
        if ($min !== null && $numValue < $min) {
            return [
                'valid' => false,
                'error' => $errorMessage ?? "不能小于{$min}"
            ];
        }
        
        if ($max !== null && $numValue > $max) {
            return [
                'valid' => false,
                'error' => $errorMessage ?? "不能大于{$max}"
            ];
        }
        
        return ['valid' => true];
    }
    
    /**
     * 验证正则表达式
     * 
     * 验证参数是否匹配指定的正则表达式模式，适用于自定义格式验证。
     * 
     * @param string $value 要验证的参数值
     * @param string $pattern 正则表达式模式，需要包含分隔符，例如: /^[a-z]+$/
     * @param string|null $errorMessage 自定义错误信息
     * @return array 验证结果，格式为：['valid' => bool, 'error' => string|null]
     * 
     * @example
     * ```php
     * // 例1：验证字符串只包含小写字母
     * $result = $validator->validateRegex('abc', '/^[a-z]+$/');
     * // $result = ['valid' => true]
     * 
     * // 例2：验证字符串包含无效字符
     * $result = $validator->validateRegex('abc123', '/^[a-z]+$/');
     * // $result = ['valid' => false, 'error' => '格式不正确']
     * 
     * // 例3：使用自定义错误信息
     * $result = $validator->validateRegex('abc123', '/^[a-z]+$/', '只能包含小写字母');
     * // $result = ['valid' => false, 'error' => '只能包含小写字母']
     * 
     * // 例4：验证手机号格式
     * $result = $validator->validateRegex('13800138000', '/^1[3-9]\d{9}$/');
     * // $result = ['valid' => true]
     * 
     * // 例5：验证中国身份证号码
     * $result = $validator->validateRegex('310101199001011234', '/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/');
     * // $result = ['valid' => true]
     * ```
     */
    public function validateRegex(string $value, string $pattern, ?string $errorMessage = null): array
    {
        if (preg_match($pattern, $value) === 1) {
            return ['valid' => true];
        }
        
        return [
            'valid' => false,
            'error' => $errorMessage ?? "格式不正确"
        ];
    }
    
    /**
     * 验证枚举值
     * 
     * 验证参数是否在指定的枚举值列表中，使用严格比较（===）。
     * 
     * @param mixed $value 要验证的参数值
     * @param array $allowedValues 允许的值列表
     * @param string|null $errorMessage 自定义错误信息
     * @return array 验证结果，格式为：['valid' => bool, 'error' => string|null]
     * 
     * @example
     * ```php
     * // 例1：验证状态值是否有效
     * $result = $validator->validateEnum('active', ['active', 'inactive', 'pending']);
     * // $result = ['valid' => true]
     * 
     * // 例2：验证无效的状态值
     * $result = $validator->validateEnum('deleted', ['active', 'inactive', 'pending']);
     * // $result = ['valid' => false, 'error' => '值不在允许范围内']
     * 
     * // 例3：使用自定义错误信息
     * $result = $validator->validateEnum('deleted', ['active', 'inactive', 'pending'], '状态值无效');
     * // $result = ['valid' => false, 'error' => '状态值无效']
     * 
     * // 例4：验证数值选项
     * $result = $validator->validateEnum(2, [1, 2, 3]);
     * // $result = ['valid' => true]
     * 
     * // 例5：字符串数字与整数不是严格相等的
     * $result = $validator->validateEnum('2', [1, 2, 3]);
     * // $result = ['valid' => false, 'error' => '值不在允许范围内']
     * ```
     */
    public function validateEnum($value, array $allowedValues, ?string $errorMessage = null): array
    {
        if (in_array($value, $allowedValues, true)) {
            return ['valid' => true];
        }
        
        return [
            'valid' => false,
            'error' => $errorMessage ?? "值不在允许范围内"
        ];
    }
    
    /**
     * 综合验证参数
     * 
     * 对参数进行完整的验证，支持多种验证规则组合和自定义错误信息。
     * 
     * @param array $params 要验证的参数数组
     * @param array $rules 验证规则数组，格式为：
     *        [
     *            'required' => [参数名1, 参数名2 => 错误信息, ...],
     *            '参数名1' => [
     *                'type' => 类型名称,
     *                'type_error' => 类型错误信息,
     *                'format' => 格式名称,
     *                'format_error' => 格式错误信息,
     *                'min' => 最小长度,
     *                'max' => 最大长度,
     *                'length_error' => 长度错误信息,
     *                'min_range' => 最小值,
     *                'max_range' => 最大值,
     *                'range_error' => 范围错误信息,
     *                'regex' => 正则表达式,
     *                'regex_error' => 正则错误信息,
     *                'enum' => [允许值1, 允许值2, ...],
     *                'enum_error' => 枚举错误信息
     *            ],
     *            ...
     *        ]
     * @return array 验证结果，格式为：['valid' => bool, 'error' => string|null]
     * 
     * @example
     * ```php
     * // 例1：验证用户注册数据
     * $params = [
     *     'username' => 'john_doe',
     *     'email' => 'john@example.com',
     *     'password' => 'password123',
     *     'age' => 25,
     *     'role' => 'user'
     * ];
     * 
     * $rules = [
     *     'required' => ['username', 'email', 'password', 'age'],
     *     'username' => [
     *         'type' => 'string',
     *         'min' => 3,
     *         'max' => 20,
     *         'regex' => '/^[a-zA-Z0-9_]+$/',
     *         'regex_error' => '用户名只能包含字母、数字和下划线'
     *     ],
     *     'email' => [
     *         'format' => 'email',
     *         'format_error' => '邮箱格式不正确'
     *     ],
     *     'password' => [
     *         'min' => 8,
     *         'length_error' => '密码长度不能少于8个字符'
     *     ],
     *     'age' => [
     *         'type' => 'int',
     *         'min_range' => 18,
     *         'max_range' => 120,
     *         'range_error' => '年龄必须在18到120岁之间'
     *     ],
     *     'role' => [
     *         'enum' => ['user', 'admin', 'editor'],
     *         'enum_error' => '角色无效'
     *     ]
     * ];
     * 
     * $result = $validator->validate($params, $rules);
     * // $result = ['valid' => true]
     * 
     * // 例2：验证失败的情况
     * $params['age'] = 15; // 未成年
     * $result = $validator->validate($params, $rules);
     * // $result = ['valid' => false, 'error' => '年龄必须在18到120岁之间']
     * ```
     */
    public function validate(array $params, array $rules): array
    {
        // 首先验证必需参数
        if (isset($rules['required']) && is_array($rules['required'])) {
            $requiredResult = $this->validateRequired($params, $rules['required']);
            if (!$requiredResult['valid']) {
                return $requiredResult;
            }
        }
        
        // 验证各个参数
        foreach ($rules as $paramName => $paramRules) {
            // 跳过required规则，因为已经处理过了
            if ($paramName === 'required') {
                continue;
            }
            
            // 如果参数不存在且不是必需的，跳过验证
            if (!isset($params[$paramName])) {
                continue;
            }
            
            $value = $params[$paramName];
            
            // 类型验证
            if (isset($paramRules['type'])) {
                $errorMessage = $paramRules['type_error'] ?? null;
                $typeResult = $this->validateType($value, $paramRules['type'], $errorMessage);
                if (!$typeResult['valid']) {
                    return $typeResult;
                }
            }
            
            // 格式验证
            if (isset($paramRules['format'])) {
                // 只有字符串类型才能进行格式验证
                if (is_string($value)) {
                    $errorMessage = $paramRules['format_error'] ?? null;
                    $formatResult = $this->validateFormat($value, $paramRules['format'], $errorMessage);
                    if (!$formatResult['valid']) {
                        return $formatResult;
                    }
                }
            }
            
            // 长度验证
            if (isset($paramRules['min']) || isset($paramRules['max'])) {
                // 只有字符串类型才能进行长度验证
                if (is_string($value)) {
                    $errorMessage = $paramRules['length_error'] ?? null;
                    $lengthResult = $this->validateLength(
                        $value, 
                        $paramRules['min'] ?? null, 
                        $paramRules['max'] ?? null,
                        $errorMessage
                    );
                    if (!$lengthResult['valid']) {
                        return $lengthResult;
                    }
                }
            }
            
            // 范围验证
            if (isset($paramRules['min_range']) || isset($paramRules['max_range'])) {
                $errorMessage = $paramRules['range_error'] ?? null;
                $rangeResult = $this->validateRange(
                    $value,
                    $paramRules['min_range'] ?? null,
                    $paramRules['max_range'] ?? null,
                    $errorMessage
                );
                if (!$rangeResult['valid']) {
                    return $rangeResult;
                }
            }
            
            // 正则表达式验证
            if (isset($paramRules['regex'])) {
                if (is_string($value)) {
                    $errorMessage = $paramRules['regex_error'] ?? null;
                    $regexResult = $this->validateRegex($value, $paramRules['regex'], $errorMessage);
                    if (!$regexResult['valid']) {
                        return $regexResult;
                    }
                }
            }
            
            // 枚举验证
            if (isset($paramRules['enum'])) {
                $errorMessage = $paramRules['enum_error'] ?? null;
                $enumResult = $this->validateEnum($value, $paramRules['enum'], $errorMessage);
                if (!$enumResult['valid']) {
                    return $enumResult;
                }
            }
        }
        
        return ['valid' => true];
    }
    
    /**
     * 验证信用卡号（使用Luhn算法）
     * 
     * @param string $number 信用卡号
     * @return bool 是否有效
     * 
     * @example
     * ```php
     * // 有效的Visa卡号
     * $result = $validator->validateCreditCard('4111111111111111');
     * // $result = true
     * 
     * // 有效的MasterCard卡号（带空格和连字符）
     * $result = $validator->validateCreditCard('5555-5555-5555-4444');
     * // $result = true
     * 
     * // 无效的信用卡号
     * $result = $validator->validateCreditCard('1234567890123456');
     * // $result = false
     * ```
     */
    private function validateCreditCard(string $number): bool
    {
        // 移除空格和连字符
        $number = preg_replace('/[\s\-]/', '', $number);
        
        // 检查是否全为数字
        if (!ctype_digit($number)) {
            return false;
        }
        
        // Luhn算法验证
        $sum = 0;
        $length = strlen($number);
        $parity = $length % 2;
        
        for ($i = 0; $i < $length; $i++) {
            $digit = (int)$number[$i];
            
            if ($i % 2 == $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            
            $sum += $digit;
        }
        
        return ($sum % 10) == 0;
    }
}