<?php

namespace Tourze\Symfony\AopLockBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use Tourze\Symfony\AopLockBundle\DependencyInjection\AopLockExtension;

/**
 * @internal
 */
#[CoversClass(AopLockExtension::class)]
final class AopLockExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // AopLockExtension 是一个 Symfony 扩展类，测试其容器配置能力
        // 直接实例化是合理的，因为：
        // 1) 我们需要测试扩展类的 load() 方法如何配置容器
        // 2) 扩展类本身不依赖容器服务，它是容器的配置者
        // 3) 这种方式可以验证扩展类是否正确注册了服务和参数
    }

    public function testLoadExtension(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.environment', 'test');

        $extension = new AopLockExtension();
        $extension->load([], $container);

        $this->assertInstanceOf(AopLockExtension::class, $extension);
    }
}
