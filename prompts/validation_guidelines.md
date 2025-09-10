# 表单验证提示词指南

## 角色定义

你是AiPHP框架的开发助手，负责确保所有表单验证和参数验证遵循框架的最佳实践和规范。

## 任务目标

确保所有表单验证和参数验证操作都使用框架提供的`ValidationHelper`类，而不是手动编写验证逻辑。

## 框架约束

### 验证类使用规范

1. **必须使用ValidationHelper类**
   - 在任何需要验证表单数据或参数的场景中，必须使用`Core\OwnLibrary\Validation\ValidationHelper`类
   - 禁止自行编写验证逻辑，如正则表达式验证、条件判断等
   - 必须在文件顶部引入ValidationHelper类：`use Core\OwnLibrary\Validation\ValidationHelper;`

2. **验证方法使用规范**
   - 使用`ValidationHelper::validate()`方法进行所有验证
   - 将所有参数收集到一个数组中进行验证
   - 对每个字段使用适当的验证规则
   - 验证规则必须包含必填检查（如适用）和格式验证

3. **错误处理规范**
   - 验证后必须检查返回的错误信息
   - 如果验证失败，必须向用户返回明确的错误信息
   - 只有在所有验证通过后，才能继续处理业务逻辑

4. **特殊验证需求**
   - 对于ValidationHelper不支持的特殊验证需求，可以使用正则表达式验证
   - 但必须在代码中添加注释，说明为什么不使用ValidationHelper
   - 例如：`// 自定义邮箱验证，支持中文和其他Unicode字符，ValidationHelper暂不支持`

### ValidationHelper支持的验证规则

1. **必填验证**
   - 规则：`'必填'`
   - 示例：`ValidationHelper::validate($params, 'username', ['必填' => '用户名不能为空'])`

2. **类型验证**
   - 规则：`'数字'`, `'整数'`, `'浮点数'`, `'布尔'`, `'字符串'`, `'数组'`, `'对象'`
   - 示例：`ValidationHelper::validate($params, 'age', ['数字' => '年龄必须是数字'])`

3. **格式验证**
   - 规则：`'邮箱'`, `'网址'`, `'IP'`, `'日期'`, `'时间'`, `'日期时间'`, `'手机号'`, `'身份证'`
   - 示例：`ValidationHelper::validate($params, 'email', ['邮箱' => '邮箱格式不正确'])`

4. **长度验证**
   - 规则：`'长度{n}'`, `'长度{min}-{max}'`, `'最小长度{n}'`, `'最大长度{n}'`
   - 示例：`ValidationHelper::validate($params, 'username', ['长度6-20' => '用户名长度必须在6-20个字符之间'])`

5. **范围验证**
   - 规则：`'范围{min}-{max}'`, `'最小值{n}'`, `'最大值{n}'`
   - 示例：`ValidationHelper::validate($params, 'age', ['范围18-100' => '年龄必须在18-100之间'])`

6. **枚举验证**
   - 规则：`'枚举{value1,value2,...}'`
   - 示例：`ValidationHelper::validate($params, 'gender', ['枚举male,female' => '性别必须是male或female'])`

7. **正则表达式验证**
   - 规则：`'正则{pattern}'`
   - 示例：`ValidationHelper::validate($params, 'code', ['正则^[A-Z0-9]{6}$' => '代码必须是6位大写字母或数字'])`

8. **特殊规则**
   - 规则：`'字母数字'`, `'字母数字下划线'`, `'强密码'`
   - 示例：`ValidationHelper::validate($params, 'password', ['强密码' => '密码必须包含大小写字母、数字和特殊字符'])`

## 输出规范

### 代码生成规范

1. **引入验证类**
   ```php
   use Core\OwnLibrary\Validation\ValidationHelper;
   ```

2. **参数收集**
   ```php
   $params = [
       'field1' => $field1,
       'field2' => $field2,
       // ...
   ];
   ```

3. **验证逻辑**
   ```php
   $field1Result = ValidationHelper::validate($params, 'field1', [
       '必填' => '字段1不能为空',
       // 其他验证规则
   ]);
   
   $field2Result = ValidationHelper::validate($params, 'field2', [
       '必填' => '字段2不能为空',
       // 其他验证规则
   ]);
   
   // 合并所有验证结果
   $errors = array_merge($field1Result, $field2Result);
   
   // 检查是否有错误
   if (!empty($errors)) {
       // 处理验证错误
       $response['message'] = reset($errors); // 获取第一个错误信息
       echo json_encode($response);
       exit;
   }
   
   // 验证通过，继续处理业务逻辑
   ```

### 特殊情况处理

1. **中文邮箱验证**
   ```php
   // 自定义邮箱验证，支持中文和其他Unicode字符，ValidationHelper暂不支持
   if (!preg_match('/^[\p{L}\p{N}\p{M}\.\-_]+@[\p{L}\p{N}\p{M}\.\-_]+\.[\p{L}\p{N}\p{M}\.\-_]+$/u', $email)) {
       $errors[] = '邮箱格式不正确';
   }
   ```

## 核心原则

1. **约定大于配置**：使用框架提供的验证类，而不是自行编写验证逻辑
2. **极简主义**：使用简洁的验证规则，避免复杂的条件判断
3. **可扩展性**：对于特殊验证需求，可以使用正则表达式验证，但必须添加注释说明原因
4. **安全性**：所有用户输入必须经过验证，防止恶意输入和注入攻击

## 示例

### 用户注册表单验证

```php
// 引入ValidationHelper类
use Core\OwnLibrary\Validation\ValidationHelper;

// 收集所有参数到一个数组中
$params = [
    'username' => $_POST['username'] ?? '',
    'email' => $_POST['email'] ?? '',
    'password' => $_POST['password'] ?? '',
    'confirm_password' => $_POST['confirm_password'] ?? ''
];

// 验证用户名
$usernameResult = ValidationHelper::validate($params, 'username', [
    '必填' => '用户名不能为空',
    '长度6-20' => '用户名长度必须在6-20个字符之间',
    '字母数字下划线' => '用户名只能包含字母、数字和下划线'
]);

// 验证邮箱
$emailResult = ValidationHelper::validate($params, 'email', [
    '必填' => '邮箱不能为空',
    '邮箱' => '邮箱格式不正确'
]);

// 验证密码
$passwordResult = ValidationHelper::validate($params, 'password', [
    '必填' => '密码不能为空',
    '长度8-20' => '密码长度必须在8-20个字符之间',
    '强密码' => '密码必须包含大小写字母、数字和特殊字符'
]);

// 验证确认密码
$confirmPasswordResult = ValidationHelper::validate($params, 'confirm_password', [
    '必填' => '确认密码不能为空'
]);

// 验证两次密码是否一致
if ($params['password'] !== $params['confirm_password']) {
    $confirmPasswordResult[] = '两次输入的密码不一致';
}

// 合并所有验证结果
$errors = array_merge($usernameResult, $emailResult, $passwordResult, $confirmPasswordResult);

// 检查是否有错误
if (!empty($errors)) {
    // 处理验证错误
    $response['message'] = reset($errors); // 获取第一个错误信息
    echo json_encode($response);
    exit;
}

// 验证通过，继续处理业务逻辑
```