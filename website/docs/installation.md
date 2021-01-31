# Installation

- [Requirements](#requirements)
- [Installation](#installation-steps)

<a name="requirements"></a>
## Requirements

- PHP 7.3 or higher
- Laravel 7.14 or higher

<a name="installation-steps"></a>
## Installation
```bash
composer require mertasan/laravel-menu
```

This registers the package with Laravel and creates an alias called `Menu`.


To use your own settings, publish config.
```bash
php artisan vendor:publish --provider="Mertasan\Menu\MenuServiceProvider"
```
