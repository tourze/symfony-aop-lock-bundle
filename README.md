# Symfony AOP Lock Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/symfony-aop-lock-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-aop-lock-bundle)
[![PHP Version](https://img.shields.io/packagist/php-v/tourze/symfony-aop-lock-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-aop-lock-bundle)
[![License](https://img.shields.io/packagist/l/tourze/symfony-aop-lock-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-aop-lock-bundle)
[![Build Status](https://img.shields.io/travis/tourze/symfony-aop-lock-bundle/master.svg?style=flat-square)](https://travis-ci.org/tourze/symfony-aop-lock-bundle)
[![Quality Score](https://img.shields.io/scrutinizer/g/tourze/symfony-aop-lock-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/symfony-aop-lock-bundle)
[![Coverage Status](https://img.shields.io/codecov/c/github/tourze/symfony-aop-lock-bundle.svg?style=flat-square)](https://codecov.io/gh/tourze/symfony-aop-lock-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/symfony-aop-lock-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-aop-lock-bundle)

A lightweight Symfony bundle providing declarative distributed locking based on AOP (Aspect-Oriented Programming). Easily add locking logic to your methods using attributes, enabling safe concurrency control in distributed environments.

## Features

- Declarative locking with the `#[Lockable]` attribute
- Customizable lock key with Twig template syntax
- Automatic lock acquire/release (even on exception)
- Seamless integration with Symfony Lock component
- Suitable for distributed/multi-instance deployments

## Installation

**Requirements:**
- PHP >= 8.1
- Symfony >= 6.4
- Required dependencies: symfony/lock, twig/twig, tourze/symfony-aop-bundle

Install via Composer:

```bash
composer require tourze/symfony-aop-lock-bundle
```

Register the bundle in your `config/bundles.php` if not auto-registered:

```php
return [
    // ... other bundles
    Tourze\Symfony\AopLockBundle\AopLockBundle::class => ['all' => true],
];
```

## Quick Start

Annotate your methods with `#[Lockable]`:

```php
use Tourze\Symfony\AopLockBundle\Attribute\Lockable;

class OrderService
{
    #[Lockable]
    public function processOrder(string $orderId): void
    {
        // business logic
    }

    #[Lockable(key: "user_{{ userId }}_update")]
    public function updateUserProfile(int $userId, array $data): void
    {
        // business logic
    }
}
```

- The lock key can be customized using method parameters and Twig syntax, e.g. `order_{{ orderId }}`.
- Supported Twig variables: method parameters, `joinPoint.method`, `joinPoint.class`, etc.

## Documentation

- [API Reference](#) <!-- TODO: Add real link if available -->
- **Configuration:**
  - Default lock timeout: 30 seconds
  - All lock/unlock logic is handled automatically by the aspect
- **Advanced:**
  - Implement your own lock service by extending the aspect or LockService interface
  - Customize lock behavior by overriding `LockAspect`

## Performance & Best Practices

- Only use locking where necessary, as it introduces network overhead
- Keep lock scope as small as possible
- Use fine-grained keys to maximize concurrency
- Ensure all nodes have synchronized clocks in distributed setups
- Monitor for lock timeouts and failures

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

Issues and PRs are welcome!
- Follow PSR coding standards
- Add tests for new features
- Run static analysis: `composer phpstan`

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Changelog

See [Releases](https://packagist.org/packages/tourze/symfony-aop-lock-bundle#releases) for version history.
