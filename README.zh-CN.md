# Symfony Rotate Profile Storage Bundle

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](https://github.com/tourze/symfony-rotate-profile-storage-bundle/actions)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg)](https://github.com/tourze/symfony-rotate-profile-storage-bundle)

[English](README.md) | [中文](README.zh-CN.md)

一个 Symfony 包，为 Symfony Profiler 存储文件提供自动轮换和清理功能，防止磁盘空间问题。

## 安装

```bash
composer require tourze/symfony-rotate-profile-storage-bundle
```

## 快速开始

这个包会自动装饰默认的 Symfony profiler 存储，并在 profile 数量超过可配置限制时提供自动清理功能。

### 基本用法

1. 通过 Composer 安装包
2. 包会自动在您的 Symfony 应用程序中注册
3. 配置轮换设置（可选）

### 配置

您可以使用环境变量配置轮换行为：

```bash
# 设置要保留的最大 profile 数量（默认值：1000）
ROTATE_PROFILE_STORAGE_KEEP_SIZE=1000
```

## 功能特性

- **自动轮换**：当达到限制时自动清理旧的 profiler 文件
- **可配置限制**：为要保留的 profile 数量设置自定义限制
- **透明集成**：与现有的 Symfony profiler 功能无缝协作
- **错误处理**：优雅地处理清理错误，不影响应用程序性能

## 示例

```php
// 包安装后自动工作
// 无需手动配置

// 可选：设置环境变量以自定义限制
$_ENV['ROTATE_PROFILE_STORAGE_KEEP_SIZE'] = 500;
```

## 工作原理

该包使用装饰器模式包装默认的 Symfony profiler 存储：

1. 当写入新的 profile 时，它会检查当前存储的 profile 数量
2. 如果数量超过配置的限制，它会触发清理
3. 清理过程会删除旧的 profiler 文件以释放磁盘空间
4. 所有操作都委托给原始存储实现

## 系统要求

- PHP 8.1 或更高版本
- Symfony 7.3 或更高版本

## 许可证

此包在 MIT 许可证下发布。详情请参阅 [LICENSE](LICENSE) 文件。
