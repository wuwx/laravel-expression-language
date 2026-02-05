# Laravel Expression Language

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

将 Symfony ExpressionLanguage 组件集成到 Laravel 的扩展包，让你可以在 Laravel 应用中安全地执行动态表达式。

## 功能特性

- **简单易用** - 通过 Facade 或服务容器快速调用
- **安全执行** - 基于 Symfony 的表达式引擎，安全可控
- **Laravel 集成** - 自动服务注册，支持门面模式
- **广泛兼容** - 支持 Laravel 5.8 至 12.0
- **内置函数** - 预置 `array_get` 辅助函数

## 安装

```bash
composer require wuwx/laravel-expression-language
```

### Laravel 自动发现

该扩展包支持 Laravel 的自动发现功能，安装后即可使用。

### 手动注册（可选）

如果你需要手动注册，在 `config/app.php` 中添加：

```php
'providers' => [
    // ...
    Wuwx\LaravelExpressionLanguage\Providers\ExpressionLanguageServiceProvider::class,
],

'aliases' => [
    // ...
    'ExpressionLanguage' => Wuwx\LaravelExpressionLanguage\Facades\ExpressionLanguageFacade::class,
],
```

## 基本用法

### 使用门面（Facade）

```php
use ExpressionLanguage;

// 简单的数学表达式
$result = ExpressionLanguage::evaluate('1 + 1'); // 返回 2

// 使用变量
$result = ExpressionLanguage::evaluate('price * quantity', [
    'price' => 100,
    'quantity' => 3
]); // 返回 300

// 条件表达式
$result = ExpressionLanguage::evaluate('user.age >= 18', [
    'user' => ['age' => 20]
]); // 返回 true
```

### 使用服务容器

```php
// 通过 app 辅助函数
$result = app('expressionLanguage')->evaluate('1 + 1');

// 通过依赖注入
class OrderController extends Controller
{
    public function calculate($expressionLanguage)
    {
        $result = $expressionLanguage->evaluate('...');
        // ...
    }
}
```

## 表达式语法

### 基本操作

```php
// 数学运算
ExpressionLanguage::evaluate('10 + 5');      // 15
ExpressionLanguage::evaluate('10 - 5');    // 5
ExpressionLanguage::evaluate('10 * 5');    // 50
ExpressionLanguage::evaluate('10 / 5');    // 2
ExpressionLanguage::evaluate('10 % 3');    // 1
ExpressionLanguage::evaluate('10 ** 2');   // 100 (幂运算)
```

### 比较和逻辑

```php
// 比较
ExpressionLanguage::evaluate('a > b', ['a' => 10, 'b' => 5]);   // true
ExpressionLanguage::evaluate('a >= b', ['a' => 10, 'b' => 10]); // true
ExpressionLanguage::evaluate('a < b', ['a' => 5, 'b' => 10]);  // true
ExpressionLanguage::evaluate('a <= b', ['a' => 5, 'b' => 5]);  // true
ExpressionLanguage::evaluate('a == b', ['a' => 5, 'b' => 5]);  // true
ExpressionLanguage::evaluate('a != b', ['a' => 5, 'b' => 3]);  // true

// 逻辑运算
ExpressionLanguage::evaluate('a and b', ['a' => true, 'b' => false]);  // false
ExpressionLanguage::evaluate('a or b', ['a' => true, 'b' => false]);   // true
ExpressionLanguage::evaluate('not a', ['a' => false]);                   // true
ExpressionLanguage::evaluate('a ? b : c', ['a' => true, 'b' => 1, 'c' => 2]); // 1
```

### 三元运算符

```php
ExpressionLanguage::evaluate('status == "active" ? price * 0.9 : price', [
    'status' => 'active',
    'price' => 100
]); // 返回 90
```

### 数组操作

```php
// 数组访问
$data = [
    'user' => [
        'name' => '张三',
        'profile' => [
            'age' => 25
        ]
    ]
];

ExpressionLanguage::evaluate('user.name', $data);           // '张三'
ExpressionLanguage::evaluate('user.profile.age', $data);    // 25

// 使用内置 array_get 函数
ExpressionLanguage::evaluate('array_get(user, "profile.age")', $data); // 25
```

### 字符串操作

```php
ExpressionLanguage::evaluate('name ~ " " ~ surname', [
    'name' => 'John',
    'surname' => 'Doe'
]); // 返回 'John Doe'（字符串连接）
```

## 高级用法

### 自定义函数

你可以通过直接访问 Symfony ExpressionLanguage 实例来添加自定义函数：

```php
use Symfony\Component\ExpressionLanguage\ExpressionFunction;

$expressionLanguage = app('expressionLanguage');

// 添加自定义函数
$expressionLanguage->expressionLanguage->addFunction(
    ExpressionFunction::fromPhp('strtoupper')
);

// 现在可以在表达式中使用
$result = $expressionLanguage->evaluate('strtoupper(name)', [
    'name' => 'hello'
]); // 返回 'HELLO'
```

### 表达式缓存

对于频繁使用的表达式，建议使用缓存：

```php
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

$cache = new ArrayAdapter();
$expressionLanguage = new ExpressionLanguage($cache);
```

### 错误处理

表达式执行过程中可能会抛出异常，建议进行错误处理：

```php
use Symfony\Component\ExpressionLanguage\SyntaxError;

try {
    $result = ExpressionLanguage::evaluate('invalid syntax...');
} catch (SyntaxError $e) {
    // 处理语法错误
    Log::error('表达式语法错误: ' . $e->getMessage());
} catch (\Exception $e) {
    // 处理其他错误
    Log::error('表达式执行错误: ' . $e->getMessage());
}
```

## 实际应用场景

### 1. 动态规则引擎

```php
// 定义业务规则
$rules = [
    'discount' => 'amount > 1000 and is_vip',
    'free_shipping' => 'amount > 500 or is_vip',
];

$context = [
    'amount' => 1500,
    'is_vip' => true,
];

foreach ($rules as $name => $expression) {
    $result = ExpressionLanguage::evaluate($expression, $context);
    echo "$name: " . ($result ? '适用' : '不适用') . "\n";
}
```

### 2. 动态表单验证

```php
$validationRules = [
    'age' => 'age >= 18 and age <= 120',
    'email' => 'email matches "/^[\w.-]+@[\w.-]+\.\w+$/"',
];

$data = ['age' => 25, 'email' => 'test@example.com'];

foreach ($validationRules as $field => $rule) {
    $isValid = ExpressionLanguage::evaluate($rule, $data);
}
```

### 3. 动态计算字段

```php
// 电商订单金额计算
$order = [
    'items' => [
        ['price' => 100, 'quantity' => 2],
        ['price' => 50, 'quantity' => 1],
    ],
    'coupon_discount' => 20,
    'is_vip' => true,
];

$formula = '(sum(items, "price * quantity") - coupon_discount) * (is_vip ? 0.95 : 1)';
$total = ExpressionLanguage::evaluate($formula, $order);
```

## 测试

运行测试套件：

```bash
composer install
vendor/bin/phpunit
```

## 兼容性

| Laravel 版本 | 支持状态 |
|-------------|---------|
| 5.8         | 支持 |
| 6.x         | 支持 |
| 7.x         | 支持 |
| 8.x         | 支持 |
| 9.x         | 支持 |
| 10.x        | 支持 |
| 11.x        | 支持 |
| 12.x        | 支持 |

## 依赖

- PHP >= 7.1
- illuminate/support: ^5.8|^6.0|^7.0|^8.0|^9.0|^10.0|^11.0|^12.0
- symfony/expression-language: ^3.3|^4.0|^5.0|^6.0|^7.0

## 相关资源

- [Symfony ExpressionLanguage 文档](https://symfony.com/doc/current/components/expression_language.html)
- [Laravel 官方文档](https://laravel.com/docs)

## 许可证

MIT 许可证 - 查看 [LICENSE](LICENSE) 文件了解详情。

## 贡献

欢迎提交 Issue 和 Pull Request！

---

**提示**: 该扩展包是对 Symfony ExpressionLanguage 的轻量级封装，更多高级用法请参考 [Symfony 官方文档](https://symfony.com/doc/current/components/expression_language.html)。