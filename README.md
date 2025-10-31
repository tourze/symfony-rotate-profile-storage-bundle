# Symfony Rotate Profile Storage Bundle

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](https://github.com/tourze/symfony-rotate-profile-storage-bundle/actions)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](https://github.com/tourze/symfony-rotate-profile-storage-bundle)

[English](README.md) | [中文](README.zh-CN.md)

A Symfony bundle that provides automatic rotation and cleanup for Symfony Profiler storage files to prevent disk space issues.

## Installation

```bash
composer require tourze/symfony-rotate-profile-storage-bundle
```

## Quick Start

This bundle automatically decorates the default Symfony profiler storage and provides automatic cleanup of old profiler files when the number of profiles exceeds a configurable limit.

### Basic Usage

1. Install the bundle via Composer
2. The bundle will automatically register itself in your Symfony application
3. Configure the rotation settings (optional)

### Configuration

You can configure the rotation behavior using environment variables:

```bash
# Set the maximum number of profiles to keep (default: 1000)
ROTATE_PROFILE_STORAGE_KEEP_SIZE=1000
```

## Features

- **Automatic Rotation**: Automatically cleans up old profiler files when the limit is reached
- **Configurable Limits**: Set custom limits for the number of profiles to keep
- **Transparent Integration**: Works seamlessly with existing Symfony profiler functionality
- **Error Handling**: Gracefully handles cleanup errors without affecting application performance

## Example

```php
// The bundle works automatically once installed
// No manual configuration required

// Optional: Set environment variable for custom limits
$_ENV['ROTATE_PROFILE_STORAGE_KEEP_SIZE'] = 500;
```

## How It Works

The bundle uses a decorator pattern to wrap the default Symfony profiler storage:

1. When a new profile is written, it checks the current number of stored profiles
2. If the count exceeds the configured limit, it triggers a cleanup
3. The cleanup process removes old profiler files to free up disk space
4. All operations are delegated to the original storage implementation

## Requirements

- PHP 8.1 or higher
- Symfony 7.3 or higher

## License

This bundle is released under the MIT License. See the [LICENSE](LICENSE) file for details.