<?php

declare(strict_types=1);

namespace Tourze\RotateProfileStorageBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Symfony\Component\HttpKernel\Profiler\ProfilerStorageInterface;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\RotateProfileStorageBundle\Service\RotateFileProfilerStorage;

/**
 * @internal
 */
#[CoversClass(RotateFileProfilerStorage::class)]
#[RunTestsInSeparateProcesses]
final class RotateFileProfilerStorageTest extends AbstractIntegrationTestCase
{
    private MockProfilerStorage $mockStorage;

    protected function onSetUp(): void
    {
        // 创建模拟存储服务
        $this->mockStorage = new MockProfilerStorage();

        // 在容器初始化之前设置模拟服务
        self::getContainer()->set('profiler.storage', $this->mockStorage);
    }

    /**
     * 测试装饰器是否正确实现接口
     */
    public function testImplementsInterface(): void
    {
        $storage = self::getService(RotateFileProfilerStorage::class);

        // Storage 实现了 ProfilerStorageInterface 接口
        $this->assertInstanceOf(ProfilerStorageInterface::class, $storage);
    }

    /**
     * 测试read方法是否委托给内部存储
     */
    public function testReadDelegation(): void
    {
        $profile = new Profile('test_token');

        // 设置期望的返回值
        $this->mockStorage->expectedCalls['read'] = $profile;

        $storage = self::getService(RotateFileProfilerStorage::class);
        $result = $storage->read('test_token');

        $this->assertSame($profile, $result);

        // 验证方法被调用
        $this->assertCount(1, $this->mockStorage->callsReceived);
        $this->assertEquals(['read', ['test_token']], $this->mockStorage->callsReceived[0]);
    }

    /**
     * 测试find方法是否委托给内部存储
     */
    public function testFindDelegation(): void
    {
        $expectedResult = [['token' => 'test']];

        // 设置期望的返回值
        $this->mockStorage->expectedCalls['find'] = $expectedResult;

        $storage = self::getService(RotateFileProfilerStorage::class);
        $result = $storage->find('127.0.0.1', '/test', 10, 'GET', 100, 200);

        $this->assertSame($expectedResult, $result);

        // 验证方法被调用
        $this->assertCount(1, $this->mockStorage->callsReceived);
        $this->assertEquals(['find', ['127.0.0.1', '/test', 10, 'GET', 100, 200, null, null]], $this->mockStorage->callsReceived[0]);
    }

    /**
     * 测试write方法的基本功能
     */
    public function testWriteDelegation(): void
    {
        $profile = new Profile('test_token');

        // 设置期望的返回值
        $this->mockStorage->expectedCalls['write'] = true;

        $storage = self::getService(RotateFileProfilerStorage::class);
        $result = $storage->write($profile);

        $this->assertTrue($result);

        // 验证方法被调用
        $this->assertCount(1, $this->mockStorage->callsReceived);
        $this->assertEquals(['write', [$profile]], $this->mockStorage->callsReceived[0]);
    }

    /**
     * 测试purge方法是否委托给内部存储
     */
    public function testPurge(): void
    {
        $storage = self::getService(RotateFileProfilerStorage::class);
        $storage->purge();

        // 验证方法被调用
        $this->assertCount(1, $this->mockStorage->callsReceived);
        $this->assertEquals(['purge', []], $this->mockStorage->callsReceived[0]);
    }
}
