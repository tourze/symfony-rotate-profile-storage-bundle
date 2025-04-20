<?php

namespace Tourze\RotateProfileStorageBundle\Tests\Service;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Symfony\Component\HttpKernel\Profiler\ProfilerStorageInterface;
use Tourze\RotateProfileStorageBundle\Service\RotateFileProfilerStorage;

class RotateFileProfilerStorageTest extends TestCase
{
    /**
     * 测试装饰器是否正确实现接口
     */
    public function testImplementsInterface(): void
    {
        // phpcs:disable SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
        /** @var ProfilerStorageInterface&MockObject $innerStorage */
        $innerStorage = $this->createMock(ProfilerStorageInterface::class);
        // phpcs:enable

        $storage = new RotateFileProfilerStorage($innerStorage);

        $this->assertInstanceOf(ProfilerStorageInterface::class, $storage);
    }

    /**
     * 测试read方法是否委托给内部存储
     */
    public function testReadDelegation(): void
    {
        $profile = new Profile('test_token');

        // phpcs:disable SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
        /** @var ProfilerStorageInterface&MockObject $innerStorage */
        $innerStorage = $this->createMock(ProfilerStorageInterface::class);
        $innerStorage->expects($this->once())
            ->method('read')
            ->with('test_token')
            ->willReturn($profile);
        // phpcs:enable

        $storage = new RotateFileProfilerStorage($innerStorage);
        $result = $storage->read('test_token');

        $this->assertSame($profile, $result);
    }

    /**
     * 测试find方法是否委托给内部存储
     */
    public function testFindDelegation(): void
    {
        $expectedResult = [['token' => 'test']];

        // phpcs:disable SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
        /** @var ProfilerStorageInterface&MockObject $innerStorage */
        $innerStorage = $this->createMock(ProfilerStorageInterface::class);
        $innerStorage->expects($this->once())
            ->method('find')
            ->with('127.0.0.1', '/test', 10, 'GET', 100, 200)
            ->willReturn($expectedResult);
        // phpcs:enable

        $storage = new RotateFileProfilerStorage($innerStorage);
        $result = $storage->find('127.0.0.1', '/test', 10, 'GET', 100, 200);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * 测试purge方法是否委托给内部存储
     */
    public function testPurgeDelegation(): void
    {
        // phpcs:disable SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
        /** @var ProfilerStorageInterface&MockObject $innerStorage */
        $innerStorage = $this->createMock(ProfilerStorageInterface::class);
        $innerStorage->expects($this->once())
            ->method('purge');
        // phpcs:enable

        $storage = new RotateFileProfilerStorage($innerStorage);
        $storage->purge();
    }

    /**
     * 测试write方法的基本功能，但不测试反射逻辑
     */
    public function testWriteDelegation(): void
    {
        // 由于原方法中使用了反射调用getIndexFilename，我们不能直接测试
        // 此测试方法的目的是确认write方法能够正确委托给内部存储的write方法

        // 这里我们将使用一个适配器模式，创建一个模拟类实现ProfilerStorageInterface
        // 该类还提供getIndexFilename方法以满足反射需求

        $profile = new Profile('test_token');
        $indexFile = tempnam(sys_get_temp_dir(), 'profiler_test');

        // 创建一个实现所需方法的模拟存储类
        $innerStorage = new class($indexFile) implements ProfilerStorageInterface {
            private $indexFile;
            private $writeCalled = false;

            public function __construct(string $indexFile)
            {
                $this->indexFile = $indexFile;
            }

            public function getIndexFilename()
            {
                return $this->indexFile;
            }

            public function write(Profile $profile): bool
            {
                $this->writeCalled = true;
                return true;
            }

            public function find(?string $ip, ?string $url, ?int $limit, ?string $method, ?int $start = null, ?int $end = null): array
            {
                return [];
            }

            public function read(string $token): ?Profile
            {
                return null;
            }

            public function purge(): void
            {
            }

            public function wasWriteCalled(): bool
            {
                return $this->writeCalled;
            }
        };

        // 仅写入少量内容，避免触发清理逻辑
        file_put_contents($indexFile, "line1\nline2");

        // 环境变量设置，确保不会触发清理
        $_ENV['ROTATE_PROFILE_STORAGE_KEEP_SIZE'] = 1000;

        $storage = new RotateFileProfilerStorage($innerStorage);
        $result = $storage->write($profile);

        $this->assertTrue($result);
        $this->assertTrue($innerStorage->wasWriteCalled(), '内部存储的write方法应被调用');

        // 清理测试文件
        if (file_exists($indexFile)) {
            unlink($indexFile);
        }
    }
}
