<?php

namespace Tourze\RotateProfileStorageBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use Tourze\RotateProfileStorageBundle\DependencyInjection\RotateProfileStorageExtension;
use Tourze\RotateProfileStorageBundle\Service\RotateFileProfilerStorage;

/**
 * @internal
 */
#[CoversClass(RotateProfileStorageExtension::class)]
final class RotateProfileStorageExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // 扩展测试不需要特殊的设置
    }

    /**
     * 测试扩展是否正确加载服务
     */
    public function testLoad(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.environment', 'test');

        $extension = new RotateProfileStorageExtension();
        $extension->load([], $container);

        // 验证服务是否已正确注册
        $this->assertTrue($container->has(RotateFileProfilerStorage::class));
    }
}
