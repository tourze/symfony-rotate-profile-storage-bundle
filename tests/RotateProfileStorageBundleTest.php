<?php

declare(strict_types=1);

namespace Tourze\RotateProfileStorageBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use Tourze\RotateProfileStorageBundle\RotateProfileStorageBundle;

/**
 * @internal
 */
#[CoversClass(RotateProfileStorageBundle::class)]
#[RunTestsInSeparateProcesses]
final class RotateProfileStorageBundleTest extends AbstractBundleTestCase
{
}
