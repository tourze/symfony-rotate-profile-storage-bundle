<?php

declare(strict_types=1);

namespace Tourze\RotateProfileStorageBundle;

use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;

class RotateProfileStorageBundle extends Bundle implements BundleDependencyInterface
{
    /**
     * @return array<class-string<Bundle>, array<string, bool>>
     */
    public static function getBundleDependencies(): array
    {
        return [
            WebProfilerBundle::class => ['dev' => true, 'test' => true],
        ];
    }
}
