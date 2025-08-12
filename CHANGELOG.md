# Oihana PHP Robots library - Change Log

All notable changes to this project will be documented in this file.

This project adheres to [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)  
and follows [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

---

## [1.0.0] - 2025-08-12

### Added
- Initial release of the Oihana PHP Robots library.
- Symfony Console command `command:robots` to manage a project's `robots.txt` file.
  - Actions:
    - `create`: generate a robots.txt file.
    - `remove`: delete a robots.txt file.
  - Options:
    - `--path`, `-p`: path to the `robots.txt` file.
    - `--content`: inline content to use when creating the file.
    - `--clear`, `-c`: clear the console before running (global option).
- Config-driven defaults via `config/config.toml` under the `[robots]` section (e.g., `path`, `overwrite`, `permissions`, `owner`, `group`, `content`).
- Programmatic API:
  - `RobotsCommand` for DI/container registration and execution.
  - `RobotsTrait` with methods: `createRobots()`, `deleteRobots()`, `initializeRobotsOptions()`.
  - `RobotsOptions` value object (`append`, `force`, `lock`, `overwrite`, `permissions`, `path`, `owner`, `group`, `content`) with helper `getFilePath()`.
  - `RobotsAction` enum with `create` and `remove` values.
- Composer scripts for quick usage: `composer robots` (alias to run `command:robots`), `composer console`, and `composer test`.
- Requires PHP 8.4+ and is licensed under MPL-2.0.
- Unit test scaffolding with PHPUnit 12 and vfsStream.


