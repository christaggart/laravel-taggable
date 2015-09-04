# Laravel Taggable

Based on [eloquent-taggable](https://github.com/cviebrock/eloquent-taggable) by [cviebrock](https://github.com/cviebrock).

## New Features

- Only allow the use already existing tags **(Can be set per model)**
- Symfony Code-Style **(PHP-CS-Fixer)**

## Installation

First, pull in the package through Composer.

```js
composer require draperstudio/laravel-taggable:1.0.*@dev
```

And then, if using Laravel 5, include the service provider within `app/config/app.php`.

```php
'providers' => [
    // ... Illuminate Providers
    // ... App Providers
    DraperStudio\Taggable\ServiceProvider::class
];
```

## Migration

To get started, you'll need to publish all vendor assets:

```bash
$ php artisan vendor:publish --provider="DraperStudio\Taggable\ServiceProvider"
```

And then run the migrations to setup the database table.

```bash
$ php artisan migrate
```

## Configuration

Taggable supports optional configuration.

To get started, you'll need to publish all vendor assets:

```bash
$ php artisan vendor:publish --provider="DraperStudio\Taggable\ServiceProvider"
```

This will create a `config/taggable.php` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package between releases.

## Usage

##### Setup a Model

```php
<?php

namespace App;

use DraperStudio\Taggable\Contracts\Taggable;
use DraperStudio\Taggable\Traits\Taggable as TaggableTrait;
use Illuminate\Database\Eloquent\Model;

class Post extends Model implements Taggable
{
    use TaggableTrait;

    protected $onlyUseExistingTags = false;
}
```

##### Add a tag to a model

```php
$model->tag('Apple,Banana,Cherry');
$model->tag(['Apple', 'Banana', 'Cherry']);
```

##### Remove specific tags

```php
$model->untag('Banana');
```

##### Remove all tags

```php
$model->detag();
```

##### Remove all assigned tags and assign the new ones

```php
$model->retag('Etrog,Fig,Grape');
```

## To-Do

- [ ] Simplify code more
- [ ] Remove Util class
- [ ] Three-way tagging (e.g. per-user tagging of models)

## License

Laravel Taggable is licensed under [The MIT License (MIT)](LICENSE).
