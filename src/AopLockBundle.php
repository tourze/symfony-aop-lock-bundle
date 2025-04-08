<?php

namespace Tourze\Symfony\AopLockBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;

class AopLockBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            \Tourze\Symfony\Aop\AopBundle::class => ['all' => true],
        ];
    }
}
