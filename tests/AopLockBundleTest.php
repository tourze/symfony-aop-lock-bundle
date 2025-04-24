<?php

namespace Tourze\Symfony\AopLockBundle\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\Symfony\AopLockBundle\AopLockBundle;

class AopLockBundleTest extends TestCase
{
    public function testBundleCreation(): void
    {
        // 测试能否创建 Bundle 实例
        $bundle = new AopLockBundle();
        $this->assertInstanceOf(AopLockBundle::class, $bundle);
    }
}
