<?php

declare(strict_types=1);

namespace Tourze\RotateProfileStorageBundle\Tests\Service;

use Symfony\Component\HttpKernel\Profiler\Profile;
use Symfony\Component\HttpKernel\Profiler\ProfilerStorageInterface;

/**
 * @internal
 */
class MockProfilerStorage implements ProfilerStorageInterface
{
    /** @var array<string, mixed> */
    public array $expectedCalls = [];

    /** @var array<int, array{string, array<mixed>}> */
    public array $callsReceived = [];

    public function reset(): void
    {
        $this->expectedCalls = [];
        $this->callsReceived = [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function find(?string $ip, ?string $url, ?int $limit, ?string $method, ?int $start = null, ?int $end = null, ?string $statusCode = null, ?\Closure $filter = null): array
    {
        $this->callsReceived[] = ['find', func_get_args()];

        /** @var array<int, array<string, mixed>> */
        return $this->expectedCalls['find'] ?? [];
    }

    public function read(string $token): ?Profile
    {
        $this->callsReceived[] = ['read', func_get_args()];

        /** @var Profile|null */
        return $this->expectedCalls['read'] ?? null;
    }

    public function write(Profile $profile): bool
    {
        $this->callsReceived[] = ['write', func_get_args()];

        /** @var bool */
        return $this->expectedCalls['write'] ?? true;
    }

    public function purge(): void
    {
        $this->callsReceived[] = ['purge', func_get_args()];
    }

    public function getIndexFilename(): string
    {
        // 返回一个不存在的文件路径，这样不会触发清理逻辑
        return '/nonexistent/path/index.csv';
    }
}
