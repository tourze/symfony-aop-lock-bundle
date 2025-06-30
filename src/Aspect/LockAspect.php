<?php

namespace Tourze\Symfony\AopLockBundle\Aspect;

use Symfony\Component\Lock\LockFactory;
use Tourze\Symfony\Aop\Attribute\After;
use Tourze\Symfony\Aop\Attribute\Aspect;
use Tourze\Symfony\Aop\Attribute\Before;
use Tourze\Symfony\Aop\Model\JoinPoint;
use Tourze\Symfony\AopLockBundle\Attribute\Lockable;
use Twig\Environment;

/**
 * 提供快速的上锁能力，开发者通过使用注解即可为指定方法快速加锁
 */
#[Aspect]
class LockAspect
{
    /**
     * JoinPoint和lock key的映射关系
     */
    private \WeakMap $lockKeys;

    public function __construct(
        private readonly LockFactory $lockFactory,
        private readonly Environment $twig,
    ) {
        $this->lockKeys = new \WeakMap();
    }

    private function getAttribute(JoinPoint $joinPoint): ?Lockable
    {
        $method = new \ReflectionMethod($joinPoint->getInstance(), $joinPoint->getMethod());
        /** @var array<\ReflectionAttribute<Lockable>> $attributes */
        $attributes = $method->getAttributes(Lockable::class);
        if (empty($attributes)) {
            // 这里返回null，则不进行缓存处理
            return null;
        }

        return $attributes[0]->newInstance();
    }

    /**
     * 缓存key
     */
    private function buildKey(JoinPoint $joinPoint): ?string
    {
        $attribute = $this->getAttribute($joinPoint);
        if ($attribute === null) {
            return null;
        }
        // 如果没声明缓存key的话，我们根据方法名/参数自动生成一个
        $key = !empty($attribute->key) ? $attribute->key : $joinPoint->getUniqueId();

        $template = $this->twig->createTemplate($key);
        return 'lock_' . trim($template->render([
            ...$joinPoint->getParams(),
            'joinPoint' => $joinPoint,
        ]));
    }

    #[Before(methodAttribute: Lockable::class)]
    public function acquireLock(JoinPoint $joinPoint): void
    {
        $key = $this->buildKey($joinPoint);
        if ($key === null) {
            return;
        }

        $lock = $this->lockFactory->createLock($key);
        $lock->acquire(true);
        $this->lockKeys[$joinPoint] = $lock;
    }

    #[After(methodAttribute: Lockable::class)]
    public function releaseLock(JoinPoint $joinPoint): void
    {
        if (!isset($this->lockKeys[$joinPoint])) {
            return;
        }

        $lock = $this->lockKeys[$joinPoint];
        $lock->release();
        unset($this->lockKeys[$joinPoint]);
    }
}
