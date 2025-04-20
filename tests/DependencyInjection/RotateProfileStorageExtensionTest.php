<?php

namespace Tourze\RotateProfileStorageBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\RotateProfileStorageBundle\DependencyInjection\RotateProfileStorageExtension;
use Tourze\RotateProfileStorageBundle\Service\RotateFileProfilerStorage;

class RotateProfileStorageExtensionTest extends TestCase
{
    /**
     * 测试扩展是否正确加载服务
     */
    public function testLoad(): void
    {
        $container = new ContainerBuilder();

        $extension = new RotateProfileStorageExtension();
        $extension->load([], $container);

        // 验证服务是否已正确注册
        $this->assertTrue($container->has(RotateFileProfilerStorage::class));

        // 注意：此测试并不完整，真实环境中我们应该检查更多的服务配置
        // 如autowire、autoconfigure等，但这需要更复杂的断言和测试夹具
    }
}
