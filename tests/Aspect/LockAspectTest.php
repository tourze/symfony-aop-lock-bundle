<?php

namespace Tourze\Symfony\AopLockBundle\Tests\Aspect;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;
use Tourze\Symfony\Aop\Model\JoinPoint;
use Tourze\Symfony\AopLockBundle\Aspect\LockAspect;
use Twig\Environment;

class LockAspectTest extends TestCase
{
    /**
     * @var LockFactory&MockObject
     */
    private LockFactory|MockObject $lockFactory;

    /**
     * @var Environment&MockObject
     */
    private Environment|MockObject $twig;

    private JoinPoint|MockObject $joinPoint;
    private LockInterface|MockObject $lock;
    private LockAspect $lockAspect;

    protected function setUp(): void
    {
        // 创建模拟对象
        $this->lockFactory = $this->createMock(LockFactory::class);
        $this->twig = $this->createMock(Environment::class);
        $this->joinPoint = $this->createMock(JoinPoint::class);
        $this->lock = $this->createMock(LockInterface::class);

        // 创建被测试对象
        $this->lockAspect = new LockAspect($this->lockFactory, $this->twig);
    }

    public function testAcquireLockWithNoAttribute(): void
    {
        // 模拟 JoinPoint 对象，设置它没有 Lockable 属性
        $this->prepareJoinPointWithNoAttribute();

        // 执行测试方法
        $this->lockAspect->acquireLock($this->joinPoint);

        // 验证锁没有被获取（因为没有属性）
        $this->lockFactory->expects($this->never())->method('createLock');
    }


    public function testReleaseLockWithNoLock(): void
    {
        // 测试没有获取锁的情况下释放锁
        $this->prepareJoinPointWithNoAttribute();

        // 执行测试方法
        $this->lockAspect->releaseLock($this->joinPoint);

        // 没有锁被释放
        $this->lock->expects($this->never())->method('release');
    }


    private function prepareJoinPointWithNoAttribute(): void
    {
        // 设置 JoinPoint 模拟对象没有 Lockable 属性
        $testObject = new class() {
            public function testMethod(): void
            {
            }
        };

        $this->joinPoint->method('getInstance')->willReturn($testObject);
        $this->joinPoint->method('getMethod')->willReturn('testMethod');
    }
}
