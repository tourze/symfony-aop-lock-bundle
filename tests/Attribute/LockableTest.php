<?php

namespace Tourze\Symfony\AopLockBundle\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use Tourze\Symfony\AopLockBundle\Attribute\Lockable;

class LockableTest extends TestCase
{
    public function testConstructor(): void
    {
        // 测试默认构造函数
        $lockable = new Lockable();
        $this->assertNull($lockable->key);

        // 测试指定key的构造函数
        $key = 'test_key';
        $lockable = new Lockable($key);
        $this->assertSame($key, $lockable->key);
    }

    public function testAttributeTarget(): void
    {
        // 测试属性注解的目标是方法
        $reflectionClass = new \ReflectionClass(Lockable::class);
        $attributes = $reflectionClass->getAttributes();

        $this->assertCount(1, $attributes);
        $this->assertSame(\Attribute::class, $attributes[0]->getName());

        $attributeInstance = $attributes[0]->newInstance();
        $this->assertSame(\Attribute::TARGET_METHOD, $attributeInstance->flags);
    }
}
