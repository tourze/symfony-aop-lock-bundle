# AopLockBundle

AopLockBundle 是一个基于 Symfony 的分布式锁实现包,通过 AOP 技术提供声明式的锁定能力。它允许开发者使用简单的注解来为方法添加锁定逻辑,从而实现分布式环境下的并发控制。

## 核心功能

### 声明式锁定

使用 `#[Lockable]` 注解标记需要加锁的方法:

```php
#[Lockable]
public function processOrder(string $orderId): void 
{
    // 此方法在同一时间只能由一个进程执行
    // ...
}
```

### 自定义锁定键

可以通过 key 参数自定义锁定键,支持 Twig 模板语法:

```php
#[Lockable(key: "order_{{ orderId }}")]
public function processOrder(string $orderId): void 
{
    // 使用 orderId 作为锁定键
    // ...
}
```

### 自动锁定管理

- 在方法执行前自动获取锁
- 在方法执行后(无论是否发生异常)自动释放锁
- 支持分布式环境下的锁定

## 使用方法

1. 在方法上添加 `#[Lockable]` 注解:

```php
use AopLockBundle\Attribute\Lockable;

class OrderService 
{
    #[Lockable]
    public function processOrder(string $orderId): void 
    {
        // 业务逻辑
    }

    #[Lockable(key: "user_{{ userId }}_update")]
    public function updateUserProfile(int $userId, array $data): void 
    {
        // 业务逻辑
    }
}
```

2. 锁定键模板支持:
   - 访问方法参数: `{{ paramName }}`
   - 访问连接点信息: `{{ joinPoint.method }}`, `{{ joinPoint.class }}`
   - 支持所有 Twig 语法特性

## 重要说明

1. 性能考虑
   - 锁定会引入额外的网络开销,建议只在必要的方法上使用
   - 尽量缩小锁定范围,避免长时间持有锁
   - 考虑使用更细粒度的锁定键来提高并发性

2. 锁定超时
   - 默认的锁定超时时间为 30 秒
   - 如果方法执行时间可能超过此值,建议调整超时设置

3. 异常处理
   - 方法抛出异常时会自动释放锁
   - 建议在方法内部进行适当的异常处理

4. 分布式环境
   - 确保所有节点的时钟同步
   - 考虑网络延迟对锁定超时的影响

## 扩展开发

1. 自定义锁定服务
   - 实现 `LockService` 接口
   - 在服务配置中替换默认实现

2. 自定义切面
   - 继承 `LockAspect` 类
   - 重写相关方法以自定义锁定行为

## 调试建议

1. 开启日志记录以跟踪锁定行为
2. 使用 Symfony Profiler 查看锁定情况
3. 监控锁定失败和超时情况
