# Oihana PHP - Robots

![Oihana PHP Robots](https://raw.githubusercontent.com/BcommeBois/oihana-php-robots/main/assets/images/oihana-php-robots-logo-inline-512x160.png)

A PHP library to create and manage `robots.txt` files, built on top of the oihana-php-commands library.

[![Latest Version](https://img.shields.io/packagist/v/oihana/php-robots.svg?style=flat-square)](https://packagist.org/packages/oihana/php-robots)  
[![Total Downloads](https://img.shields.io/packagist/dt/oihana/php-robots.svg?style=flat-square)](https://packagist.org/packages/oihana/php-robots)  
[![License](https://img.shields.io/packagist/l/oihana/php-robots.svg?style=flat-square)](LICENSE)

## ✨ Features
- 
- Create or remove a project's robots.txt from the CLI
- Optional custom path via -f|--file (absolute or relative)
- Clear console support via -c|--clear
- Config-driven defaults (config.toml)

A robots.txt file is a simple text file placed on a website’s root directory to give instructions to web crawlers (like search engine bots) about which pages or sections of the site should or shouldn’t be crawled and indexed. 
It helps control search engine access and manage site visibility.

## 📦 Installation

> Requires PHP 8.4+

Install via Composer:

```bash
composer require oihana/php-robots
```

## 🚀 Usage

The package provides a Symfony Console command named command:robots with actions:

- create: Generate a `robots.txt` file
- remove: Delete a `robots.txt` file

Examples:

Create a `robots.txt` using defaults
```shell
 bin/console command:robots create
```

Create a `robots.txt` at a custom path
```shell
bin/console command:robots create --path /var/www/my-website/htdocs/robots.txt
```
or
```shell
bin/console command:robots create --p /var/www/my-website/htdocs/robots.txt
```

Remove the default `robots.txt`
```shell
bin/console command:robots remove
```

Remove a robots.txt at a custom path
```shell
bin/console command:robots remove --path /var/www/my-website/htdocs/robots.txt
```

Clear the console before running
```shell
bin/console command:robots create --clear
```

### Options
| Option  | ShortCut | Description                        |
|---------|----------|------------------------------------|
| --clear | -c       | Clear the console before running   |
| --path  | -p       | The directory path of 'robots.txt' |


**Notes:**
- If `--path` is a relative path, it is resolved against the current working directory.
- On creation/removal, parent directory existence and permissions are validated.

## ⚙️ Configuration

You can set defaults in config/config.toml under the [robots] section:

```toml
[robots]
path        = "/path/to/your/project/htdocs"
overwrite   = true
permissions = 0o644
owner       = "www-data"
group       = "www-data"
content     = '''
User-agent: *
Disallow: /
'''
```

## 🧪 Programmatic usage

You can instantiate and configure the command in PHP if needed:

```php
use DI\Container;
use oihana\robots\commands\RobotsCommand;

$container = new Container();
$command = new RobotsCommand
(
    null,        // let kernel resolve the name
    $container,
    [
        'robots' => [
            'path'    => '/var/www/my-website/htdocs',
            'content' => "User-agent: *\nDisallow: /private/"
        ]
    ]
);
```

## 🔚 Exit codes
- 0 Success
- 1 Failure (invalid action, IO failure, etc.)

## ❓ Troubleshooting
- Ensure the parent directory for the `robots.txt` is writable (especially when using a custom `--path` option).
- When using relative paths with `--path`, they are resolved from the current working directory (pwd).
- For more details, see the inline documentation in [RobotsCommand.php](https://github.com/BcommeBois/oihana-php-robots/blob/main/src/oihana/robots/commands/RobotsCommand.php).

## ✅ Running Unit Tests

To run all tests:

```bash
composer test
```

## 🧾 License

This project is licensed under the Mozilla Public License 2.0 (MPL-2.0).

## 👤 About the author
- Author: Marc ALCARAZ (aka eKameleon)
- Mail: marc@ooop.fr
- Website: http://www.ooop.fr