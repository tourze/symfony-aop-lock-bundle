<?php

namespace Tourze\Symfony\AopLockBundle\Tests\Aspect;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;
use Tourze\Symfony\Aop\Model\JoinPoint;
use Tourze\Symfony\AopLockBundle\Aspect\LockAspect;
use Twig\Environment;

/**
 * @internal
 */
#[CoversClass(LockAspect::class)]
final class LockAspectTest extends TestCase
{
    private LockFactory|MockObject $lockFactory;

    private Environment|MockObject $twig;

    private JoinPoint $joinPoint;

    private LockInterface|MockObject $lock;

    private LockAspect $lockAspect;

    protected function setUp(): void
    {
        parent::setUp();

        // 创建模拟对象
        $this->lockFactory = $this->createMock(LockFactory::class);

        /*
         * Mock Twig\Environment 具体类的说明：
         * 1) 为什么必须使用具体类：LockAspect 需要调用 Environment::createTemplate() 方法来解析锁键模板，
         *    而 Twig 没有提供相应的接口抽象，必须使用具体的 Environment 类
         * 2) 使用合理性：这是合理的，因为我们需要测试 LockAspect 与 Twig 模板引擎的交互行为
         * 3) 替代方案：理想情况下应该有 TemplateEngineInterface，但由于是第三方库限制，当前方案是最佳选择
         */
        $this->twig = $this->createMock(Environment::class);

        /*
         * 创建真实的 JoinPoint 实例进行测试
         * 因为 JoinPoint 是一个具体的数据模型类，包含复杂的内部状态
         * 使用 Mock 会破坏其内部逻辑，所以我们创建真实实例
         */
        $this->joinPoint = new JoinPoint();
        $this->joinPoint->setInstance(new class {});
        $this->joinPoint->setMethod('testMethod');
        $this->joinPoint->setParams([]);
        $this->lock = $this->createMock(LockInterface::class);

        // 创建被测试对象
        $this->lockAspect = new LockAspect($this->lockFactory, $this->twig);
    }

    public function testAcquireLockWithNoAttribute(): void
    {
        // 模拟 JoinPoint 对象，设置它没有 Lockable 属性
        $this->prepareJoinPointWithNoAttribute();

        // 设置期望：不应该调用 createLock
        $this->lockFactory->expects($this->never())->method('createLock');

        // 执行测试方法
        $this->lockAspect->acquireLock($this->joinPoint);
    }

    public function testReleaseLockWithNoLock(): void
    {
        // 测试没有获取锁的情况下释放锁
        $this->prepareJoinPointWithNoAttribute();

        // 设置期望：不应该调用 release
        $this->lock->expects($this->never())->method('release');

        // 执行测试方法
        $this->lockAspect->releaseLock($this->joinPoint);
    }

    private function prepareJoinPointWithNoAttribute(): void
    {
        // 设置 JoinPoint 对象没有 Lockable 属性
        $testObject = new class {
            public function testMethod(): void
            {
            }
        };

        $this->joinPoint->setInstance($testObject);
        $this->joinPoint->setMethod('testMethod');
        $this->joinPoint->setParams([]);
    }
}
