<?php

namespace Tourze\RotateProfileStorageBundle\DependencyInjection;

use Tourze\SymfonyDependencyServiceLoader\AutoExtension;

class RotateProfileStorageExtension extends AutoExtension
{
    protected function getConfigDir(): string
    {
        return __DIR__ . '/../Resources/config';
    }
}
