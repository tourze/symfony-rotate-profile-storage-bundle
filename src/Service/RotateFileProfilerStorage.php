<?php

namespace Tourze\RotateProfileStorageBundle\Service;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Symfony\Component\HttpKernel\Profiler\ProfilerStorageInterface;

/**
 * 原有的Profile不会自动清除旧的历史文件，只能自己做一次了
 */
#[AsDecorator(decorates: 'profiler.storage')]
class RotateFileProfilerStorage implements ProfilerStorageInterface
{
    public function __construct(
        #[AutowireDecorated] private readonly ProfilerStorageInterface $inner,
    )
    {
    }

    public function write(Profile $profile): bool
    {
        $reflection = new \ReflectionClass($this->inner);
        $indexFile = $reflection->getMethod('getIndexFilename')->invoke($this->inner);

        $lines = is_file($indexFile) ? file($indexFile) : [];

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

    public function find(?string $ip, ?string $url, ?int $limit, ?string $method, ?int $start = null, ?int $end = null): array
    {
        return $this->inner->find($ip, $url, $limit, $method, $start, $end);
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
