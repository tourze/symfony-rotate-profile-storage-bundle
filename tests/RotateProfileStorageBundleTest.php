<?php

namespace Tourze\RotateProfileStorageBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\RotateProfileStorageBundle\RotateProfileStorageBundle;

class RotateProfileStorageBundleTest extends TestCase
{
    /**
     * 测试Bundle类是否正确扩展了Symfony的Bundle基类
     */
    public function testBundleInheritsBundle(): void
    {
        $bundle = new RotateProfileStorageBundle();
        $this->assertInstanceOf(Bundle::class, $bundle);
    }
}
