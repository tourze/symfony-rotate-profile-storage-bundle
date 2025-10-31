<?php

namespace Tourze\RotateProfileStorageBundle\Service;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Symfony\Component\HttpKernel\Profiler\ProfilerStorageInterface;

/**
 * 原有的Profile不会自动清除旧的历史文件，只能自己做一次了
 */
#[AsDecorator(decorates: 'profiler.storage', onInvalid: ContainerInterface::IGNORE_ON_INVALID_REFERENCE)]
readonly class RotateFileProfilerStorage implements ProfilerStorageInterface
{
    public function __construct(
        #[AutowireDecorated] private ProfilerStorageInterface $inner,
    ) {
    }

    public function write(Profile $profile): bool
    {
        $reflection = new \ReflectionClass($this->inner);
        /** @var string */
        $indexFile = $reflection->getMethod('getIndexFilename')->invoke($this->inner);

        $lines = is_file($indexFile) ? file($indexFile) : [];
        if (false === $lines) {
            $lines = [];
        }

        // 检查下是否条数超出限制
        if (count($lines) > ($_ENV['ROTATE_PROFILE_STORAGE_KEEP_SIZE'] ?? 1000)) {
            try {
                $this->purge();
            } catch (\Throwable $exception) {
                // 有可能发生错误的，我们不进行处理。如 RecursiveDirectoryIterator::__construct(/var/cache/dev/profiler/54/ec): Failed to open directory: No such file or directory
            }
        }

        return $this->inner->write($profile);
    }

    /**
     * @return array<int, array{token: string, ip: string, method: string, url: string, time: int, parent: string|null, status_code: string, virtual_type: string}>
     */
    public function find(?string $ip, ?string $url, ?int $limit, ?string $method, ?int $start = null, ?int $end = null, ?string $statusCode = null, ?\Closure $filter = null): array
    {
        /** @var array<int, array{token: string, ip: string, method: string, url: string, time: int, parent: string|null, status_code: string, virtual_type: string}> */
        return $this->inner->find($ip, $url, $limit, $method, $start, $end, $statusCode, $filter);
    }

    public function read(string $token): ?Profile
    {
        return $this->inner->read($token);
    }

    public function purge(): void
    {
        $this->inner->purge();
    }
}
