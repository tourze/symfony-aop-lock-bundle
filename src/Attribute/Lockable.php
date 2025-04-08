<?php

namespace Tourze\Symfony\AopLockBundle\Attribute;

/**
 * 添加这个注解到方法上，可以快速为这个方法添加上锁逻辑
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class Lockable
{
    public function __construct(
        public ?string $key = null,
    )
    {
    }
}
