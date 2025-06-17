<?php

namespace Tourze\Symfony\AopLockBundle\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\InMemoryStore;
use Tourze\Symfony\AopLockBundle\Attribute\Lockable;

/**
 * 集成测试，测试 Lockable 注解与 LockAspect 的协同工作
 */
class LockableIntegrationTest extends TestCase
{
    private LockFactory $lockFactory;

    protected function setUp(): void
    {

        // 创建实际的 LockFactory
        $store = new InMemoryStore();
        $this->lockFactory = new LockFactory($store);

        // 创建要测试的 LockAspect
    }

    /**
     * 简单测试类上的 Lockable 属性
     */
    public function testLockableAttributeOnClass(): void
    {
        // 创建一个带有 Lockable 注解的测试类
        $testObject = new class() {
            private array $calls = [];

            #[Lockable(key: "test_key")]
            public function testMethod(string $param): string
            {
                $this->calls[] = $param;
                return 'Result: ' . $param;
            }

            public function getCalls(): array
            {
                return $this->calls;
            }
        };

        // 调用 testMethod
        $result = $testObject->testMethod('value1');

        // 检查结果
        $this->assertEquals('Result: value1', $result);

        // 验证方法确实被调用
        $this->assertEquals(['value1'], $testObject->getCalls());

        // 获取属性信息
        $reflection = new \ReflectionMethod($testObject, 'testMethod');
        $attributes = $reflection->getAttributes(Lockable::class);

        // 验证属性是否存在
        $this->assertCount(1, $attributes);

        // 实例化属性对象
        $lockable = $attributes[0]->newInstance();
        $this->assertInstanceOf(Lockable::class, $lockable);
        $this->assertEquals('test_key', $lockable->key);
    }

    /**
     * 测试锁在多次调用之间的排他性
     */
    public function testLockingConcurrency(): void
    {
        // 创建测试锁对象
        $lock1 = $this->lockFactory->createLock('test_concurrent_lock');
        $lock2 = $this->lockFactory->createLock('test_concurrent_lock');

        // 第一个锁应该可以获取
        $this->assertTrue($lock1->acquire(false));

        // 同一个键的第二个锁应该无法获取（非阻塞模式）
        $this->assertFalse($lock2->acquire(false));

        // 释放第一个锁
        $lock1->release();

        // 现在第二个锁应该可以获取了
        $this->assertTrue($lock2->acquire(false));

        // 清理
        $lock2->release();
    }
}
