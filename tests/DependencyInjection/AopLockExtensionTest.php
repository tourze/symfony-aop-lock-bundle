<?php

namespace Tourze\Symfony\AopLockBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\Symfony\AopLockBundle\DependencyInjection\AopLockExtension;

class AopLockExtensionTest extends TestCase
{
    public function testLoadExtension(): void
    {
        // 创建容器构建器
        $container = new ContainerBuilder();

        // 创建扩展实例
        $extension = new AopLockExtension();

        // 执行 load 方法
        $extension->load([], $container);

        // 验证服务是否已注册（Aspect 服务应该已自动注册）
        $this->assertTrue($container->hasDefinition('Tourze\Symfony\AopLockBundle\Aspect\LockAspect')
            || $container->hasParameter('Tourze\Symfony\AopLockBundle\Aspect\LockAspect'));
    }
}
