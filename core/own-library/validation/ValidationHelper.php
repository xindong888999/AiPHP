<?php

declare(strict_types=1);

namespace Core\OwnLibrary\Validation;

/**
 * 验证助手类
 * 
 * 该类提供简洁的静态方法接口，用于简化参数验证操作。
 * 封装了EnhancedParameterValidator的复杂性，提供了更简洁易用的API。
 * 支持多种验证规则，包括必填、类型、格式、长度、范围、枚举和正则表达式验证。
 * 
 * @package Core\OwnLibrary\Validation
 * @author AiPHP Team
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @example
 * ```php
 * // 基本使用方式
 * $params = ['id' => '12345', 'name' => 'John', 'email' => 'john@example.com'];
 * 
 * // 验证单个字段的多个规则
 * $result = ValidationHelper::validate($params, 'id', [
 *     '必填' => 'ID必须填写',
 *     '数字' => 'ID必须是数字',
 *     '长度5' => 'ID必须是5位数字'
 * ]);
 * 
 * // 在页面中显示验证结果
 * if (isset($result['id'])) {
 *     echo $result['id']; // 显示错误信息
 * } else {
 *     echo '验证通过'; 
 * }
 * ```
 */
class ValidationHelper
{
    /**
     * 验证指定字段
     * 
     * 对参数数组中的指定字段进行验证，支持多种验证规则。
     * 
     * @param array $params 要验证的参数数组
     * @param string $field 要验证的字段名
     * @param array $rules 验证规则数组，可以是简化形式（值为规则名）或详细形式（键为规则名，值为错误信息）
     * @return array 验证结果，如果验证失败返回[$field => '错误信息']，否则返回[]
     * 
     * @example
     * ```php
     * // 例1：基本验证，使用默认错误信息
     * $params = ['id' => '12345', 'name' => 'John'];
     * $result = ValidationHelper::validate($params, 'id', ['必填', '数字']);
     * // $result = [] (验证通过)
     * 
     * // 例2：使用自定义错误信息
     * $result = ValidationHelper::validate($params, 'email', ['必填' => '邮箱地址不能为空']);
     * // $result = ['email' => '邮箱地址不能为空']
     * 
     * // 例3：多规则组合
     * $params = ['id' => '123', 'name' => 'John'];
     * $result = ValidationHelper::validate($params, 'id', [
     *     '必填',
     *     '数字',
     *     '长度5' => 'ID必须是5位数字'
     * ]);
     * // $result = ['id' => 'ID必须是5位数字']
     * 
     * // 例4：验证邮箱
     * $params = ['email' => 'invalid-email'];
     * $result = ValidationHelper::validate($params, 'email', ['必填', '邮箱']);
     * // $result = ['email' => '必须是邮箱格式']
     * 
     * // 例5：验证数值范围
     * $params = ['age' => 15];
     * $result = ValidationHelper::validate($params, 'age', ['最小值18' => '年龄必须大于等于18岁']);
     * // $result = ['age' => '年龄必须大于等于18岁']
     * ```
     */
    public static function validate(array $params, string $field, array $rules): array
    {
        $validator = new EnhancedParameterValidator();
        
        // 遍历所有规则进行验证
        foreach ($rules as $key => $rule) {
            // 处理简化写法：如果$key是数字，则$rule就是规则名
            // 如果$key是字符串，则$key是规则名，$rule是错误信息
            if (is_int($key)) {
                // 简化写法：只提供了规则名
                $ruleName = $rule;
                $errorMessage = null; // 使用默认错误信息
            } else {
                // 完整写法：提供了规则名和错误信息
                $ruleName = $key;
                $errorMessage = $rule;
            }
            
            // 根据规则名调用相应的验证方法
            $result = self::applyRule($validator, $params, $field, $ruleName, $errorMessage);
            if (!$result['valid']) {
                return [$field => $result['error']];
            }
        }
        
        // 验证通过，返回空数组
        return [];
    }
    
    /**
     * 应用单个验证规则
     * 
     * 根据规则名称应用相应的验证方法，支持多种规则格式。
     * 
     * 支持的规则格式：
     * - 必填/required - 验证字段必须存在且非空
     * - 数字/整数/number/integer - 验证字段必须是数字
     * - 字符串/string - 验证字段必须是字符串
     * - 布尔值/boolean - 验证字段必须是布尔值
     * - 浮点数/float - 验证字段必须是浮点数
     * - 邮箱/Email - 验证字段必须是有效的邮箱地址
     * - 网址/Url - 验证字段必须是有效的URL
     * - 手机/Phone - 验证字段必须是有效的手机号码
     * - 长度N - 验证字段长度必须等于N
     * - 长度N-M - 验证字段长度必须在N到M之间
     * - 最小长度N - 验证字段长度必须大于等于N
     * - 最大长度N - 验证字段长度必须小于等于N
     * - 范围N-M - 验证字段值必须在N到M之间
     * - 最小值N - 验证字段值必须大于等于N
     * - 最大值N - 验证字段值必须小于等于N
     * - 枚举(A,B,C) - 验证字段值必须是A,B,C之一
     * - 正则(模式) - 验证字段值必须匹配指定的正则表达式
     * 
     * @param EnhancedParameterValidator $validator 验证器实例
     * @param array $params 参数数组
     * @param string $field 字段名
     * @param string $rule 验证规则
     * @param string|null $errorMessage 自定义错误信息
     * @return array 验证结果，格式为['valid' => bool, 'error' => string|null]
     * 
     * @example
     * ```php
     * // 内部方法，通常不直接调用，而是通过validate方法间接调用
     * // 例如，当调用：
     * // ValidationHelper::validate($params, 'id', ['必填', '数字', '长度5']);
     * // 时，内部会分别调用applyRule方法验证每一条规则
     * ```
     */
    private static function applyRule(EnhancedParameterValidator $validator, array $params, string $field, string $rule, ?string $errorMessage): array
    {
        // 必填验证
        if ($rule === '必填' || $rule === 'required') {
            // 如果提供了自定义错误信息，使用它；否则使用默认错误信息
            if ($errorMessage !== null) {
                $requiredKeys = [$field => $errorMessage];
            } else {
                $requiredKeys = [$field];
            }
            $requiredResult = $validator->validateRequired($params, $requiredKeys);
            return $requiredResult;
        }
        
        // 检查字段是否存在
        if (!isset($params[$field])) {
            // 字段不存在且不是必填验证，认为验证通过
            return ['valid' => true];
        }
        
        $value = $params[$field];
        
        // 如果值为空且非必填，则跳过其他验证
        // 但URL验证是个例外，空值也应该被验证
        if (($value === null || $value === '') && $rule !== 'URL' && $rule !== '网址' && $rule !== 'Url') {
            return ['valid' => true];
        }
        
        // 类型验证
        $typeRules = [
            '数字' => 'int',
            '整数' => 'int',
            '字符串' => 'string',
            '布尔值' => 'bool',
            '浮点数' => 'float',
            '数组' => 'array',
            '空值' => 'null',
            'number' => 'int',
            'integer' => 'int',
            'string' => 'string',
            'boolean' => 'bool',
            'float' => 'float',
            'array' => 'array',
            'null' => 'null'
        ];
        
        if (isset($typeRules[$rule])) {
            return $validator->validateType($value, $typeRules[$rule], $errorMessage);
        }
        
        // 格式验证
        $formatRules = [
            '邮箱' => 'email',
            '网址' => 'url',
            'IP地址' => 'ip',
            'IPv4' => 'ipv4',
            'IPv6' => 'ipv6',
            '字母' => 'alpha',
            '字母数字' => 'alnum',
            '数值' => 'numeric',
            '数字串' => 'digits',
            'slug' => 'slug',
            '日期' => 'date',
            '日期时间' => 'datetime',
            'UUID' => 'uuid',
            '信用卡' => 'credit_card',
            '手机' => 'phone',
            '邮政编码' => 'postal_code',
            'Email' => 'email',
            'Url' => 'url',
            'URL' => 'url',
            'Phone' => 'phone'
        ];
        
        if (isset($formatRules[$rule])) {
            return $validator->validateFormat($value, $formatRules[$rule], $errorMessage);
        }
        
        // 长度验证
        if (preg_match('/^长度(\d+)(-(\d+))?$/', $rule, $matches)) {
            $min = (int)$matches[1];
            $max = isset($matches[3]) ? (int)$matches[3] : $min;
            if ($min === $max) {
                return $validator->validateLength($value, $min, $max, $errorMessage ?? "字段长度必须是{$min}个字符");
            } else {
                return $validator->validateLength($value, $min, $max, $errorMessage ?? "字段长度必须在{$min}-{$max}个字符之间");
            }
        }
        
        // 最小长度验证
        if (preg_match('/^最小长度(\d+)$/', $rule, $matches)) {
            $min = (int)$matches[1];
            return $validator->validateLength($value, $min, null, $errorMessage ?? "字段长度不能少于{$min}个字符");
        }
        
        // 最大长度验证
        if (preg_match('/^最大长度(\d+)$/', $rule, $matches)) {
            $max = (int)$matches[1];
            return $validator->validateLength($value, null, $max, $errorMessage ?? "字段长度不能超过{$max}个字符");
        }
        
        // 范围验证
        if (preg_match('/^范围(\d+)(\.?\d*)-(\d+)(\.?\d*)$/', $rule, $matches)) {
            $min = is_numeric($matches[1].$matches[2]) ? (float)($matches[1].$matches[2]) : (int)$matches[1];
            $max = is_numeric($matches[3].$matches[4]) ? (float)($matches[3].$matches[4]) : (int)$matches[3];
            return $validator->validateRange($value, $min, $max, $errorMessage ?? "字段值必须在{$min}-{$max}之间");
        }
        
        // 最小值验证
        if (preg_match('/^最小值(\d+)(\.?\d*)$/', $rule, $matches)) {
            $min = is_numeric($matches[1].$matches[2]) ? (float)($matches[1].$matches[2]) : (int)$matches[1];
            return $validator->validateRange($value, $min, null, $errorMessage ?? "字段值不能小于{$min}");
        }
        
        // 最大值验证
        if (preg_match('/^最大值(\d+)(\.?\d*)$/', $rule, $matches)) {
            $max = is_numeric($matches[1].$matches[2]) ? (float)($matches[1].$matches[2]) : (int)$matches[1];
            return $validator->validateRange($value, null, $max, $errorMessage ?? "字段值不能大于{$max}");
        }
        
        // 枚举验证
        if (preg_match('/^枚举\(([^)]+)\)$/', $rule, $matches)) {
            $allowedValues = explode(',', $matches[1]);
            $allowedValues = array_map('trim', $allowedValues);
            return $validator->validateEnum($value, $allowedValues, $errorMessage);
        }
        
        // 特殊规则处理
        if ($rule === '字母数字下划线') {
            // 用户名只能包含字母、数字和下划线
            return $validator->validateRegex($value, '/^[a-zA-Z0-9_]+$/', $errorMessage);
        }
        
        if ($rule === '强密码') {
            // 密码必须包含字母、数字和特殊字符
            // 这是一个相对宽松的强密码验证
            $hasLetter = preg_match('/[a-zA-Z]/', $value);
            $hasDigit = preg_match('/\d/', $value);
            $hasSpecial = preg_match('/[^a-zA-Z\d]/', $value);
            
            if ($hasLetter && $hasDigit && $hasSpecial) {
                return ['valid' => true];
            } else {
                return [
                    'valid' => false,
                    'error' => $errorMessage ?? '密码必须包含字母、数字和特殊字符'
                ];
            }
        }
        
        // 正则表达式验证
        if (preg_match('/^正则\((.+)\)$/', $rule, $matches)) {
            $pattern = $matches[1];
            return $validator->validateRegex($value, $pattern, $errorMessage);
        }
        
        // 如果没有匹配的规则，返回验证通过
        return ['valid' => true];
    }
}