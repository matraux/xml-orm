# MATRAUX XML ORM
[![Latest Version on Packagist](https://img.shields.io/packagist/v/matraux/xml-orm.svg?logo=packagist&logoColor=white)](https://packagist.org/packages/matraux/xml-orm)
[![Last release](https://img.shields.io/github/v/release/matraux/xml-orm?display_name=tag&logo=github&logoColor=white)](https://github.com/matraux/xml-orm/releases)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg?logo=open-source-initiative&logoColor=white)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.4+-blue.svg?logo=php&logoColor=white)](https://php.net)
[![Security Policy](https://img.shields.io/badge/Security-Policy-blue?logo=bitwarden&logoColor=white)](./.github/SECURITY.md)
[![Contributing](https://img.shields.io/badge/Contributing-Disabled-lightgrey?logo=github&logoColor=white)](CONTRIBUTING.md)
[![QA Status](https://img.shields.io/github/actions/workflow/status/matraux/xml-orm/qa.yml?label=Quality+Assurance&logo=checkmarx&logoColor=white)](https://github.com/matraux/xml-orm/actions/workflows/qa.yml)
[![Issues](https://img.shields.io/github/issues/matraux/xml-orm?logo=github&logoColor=white)](https://github.com/matraux/xml-orm/issues)
[![Last Commit](https://img.shields.io/github/last-commit/matraux/xml-orm?logo=git&logoColor=white)](https://github.com/matraux/xml-orm/commits)

<br>

## Introduction
A PHP 8.4+ library for converting XML data to typed entities and back, with support for lazy-loading collections, XML namespaces, and structured entity design.
Useful for parsing configuration files, processing structured XML APIs, and working with hierarchical XML data in an object-oriented way.


<br>

## Features
- Object-oriented XML mapping
- Conversion from XML to typed entities and back
- Lazy-loading collections for efficient memory usage
- Entity objects are mutable and can be freely modified after creation
- Mapping via PHP attributes (no separate config files needed)
- Strict type support with automatic casting
- Native support for nested structures and arrays
- Full support for XML namespaces
- Easy integration with configuration files or XML-based APIs

<br>

## Installation
```bash
composer require matraux/xml-orm
```

<br>

## Requirements
| version | PHP | Note |
|---|---|---|
| 1.0.0 | 7.4+ | Support PHP 7.4 |
| 2.0.0 | 8.3+ | Support PHP 8.3 |
| 3.0.0 | 8.4+ | Support PHP 8.4 |

<br>

## Examples
See [Definitions](./docs/Definitions.md)  for how to define your own entities and collections.

See [Read](./docs/Read.md) for full reading examples.
```php
use Matraux\XmlOrm\Xml\SimpleExplorer;

$explorer = SimpleExplorer::fromString($xml);
$main = MainEntity::fromExplorer($explorer);
echo $main->name;
```

See [Write](./docs/Write.md) for writing examples.
```php
$main = MainEntity::create();
$main->name = 'Main entity';

echo $main;
```

<br>

## Development
See [Development](./docs/Development.md) for debug, test instructions, static analysis, and coding standards.

<br>

## Support
For bug reports and feature requests, please use the [issue tracker](https://github.com/matraux/xml-orm/issues).