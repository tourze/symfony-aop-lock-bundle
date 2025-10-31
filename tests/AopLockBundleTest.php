<?php

declare(strict_types=1);

namespace Tourze\Symfony\AopLockBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use Tourze\Symfony\AopLockBundle\AopLockBundle;

/**
 * @internal
 */
#[CoversClass(AopLockBundle::class)]
#[RunTestsInSeparateProcesses]
final class AopLockBundleTest extends AbstractBundleTestCase
{
}
