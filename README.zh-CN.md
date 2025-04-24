# Symfony AOP Lock Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![最新版本](https://img.shields.io/packagist/v/tourze/symfony-aop-lock-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-aop-lock-bundle)
[![构建状态](https://img.shields.io/travis/tourze/symfony-aop-lock-bundle/master.svg?style=flat-square)](https://travis-ci.org/tourze/symfony-aop-lock-bundle)
[![质量评分](https://img.shields.io/scrutinizer/g/tourze/symfony-aop-lock-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/symfony-aop-lock-bundle)
[![下载次数](https://img.shields.io/packagist/dt/tourze/symfony-aop-lock-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-aop-lock-bundle)

一个轻量级的 Symfony Bundle，基于 AOP（面向切面编程）提供声明式分布式锁能力。开发者只需通过属性（注解）即可为方法添加锁定逻辑，轻松实现分布式环境下的并发安全控制。

## 功能特性

- 通过 `#[Lockable]` 属性声明式加锁
- 支持使用 Twig 模板自定义锁定键
- 自动获取/释放锁（即使发生异常也能保证释放）
- 与 Symfony Lock 组件无缝集成
- 适用于分布式/多实例部署场景

## 安装说明

**环境要求：**
- PHP >= 8.1
- Symfony >= 6.4
- 依赖 symfony/lock、twig/twig、tourze/symfony-aop-bundle

使用 Composer 安装：

```bash
composer require tourze/symfony-aop-lock-bundle
```

如未自动注册，请在 `config/bundles.php` 中手动注册：

```php
Tourze\Symfony\AopLockBundle\AopLockBundle::class => ['all' => true],
```

## 快速开始

为方法添加 `#[Lockable]` 注解：

```php
use Tourze\Symfony\AopLockBundle\Attribute\Lockable;

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

- 锁定键可通过方法参数和 Twig 语法自定义，例如 `order_{{ orderId }}`。
- 支持的 Twig 变量：方法参数、`joinPoint.method`、`joinPoint.class` 等。

## 详细文档

- [API 文档](#) <!-- 如有实际链接请补充 -->
- **配置说明：**
  - 默认锁定超时时间：30 秒
  - 所有加锁/解锁逻辑均由切面自动处理
- **高级用法：**
  - 通过继承切面或实现 LockService 接口自定义锁服务
  - 继承 `LockAspect` 可自定义锁定行为

## 性能与最佳实践

- 仅在必要场景使用锁，避免无谓的网络开销
- 尽量缩小锁定范围，提升性能
- 使用细粒度锁定键，提高并发能力
- 分布式部署时需确保各节点时钟同步
- 监控锁定超时与失败情况

## 贡献指南

- 欢迎 Issue 和 PR！
- 遵循 PSR 代码规范
- 新特性需补充测试
- 静态分析请运行：`composer phpstan`

## 开源协议

MIT License © tourze

## 更新日志

详见 [Releases](https://packagist.org/packages/tourze/symfony-aop-lock-bundle#releases)
