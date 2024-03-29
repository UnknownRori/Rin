# Rin

* [Overview](https://github.com/UnknownRori/Rin#-overview)

* [Feature](https://github.com/UnknownRori/Rin#-feature)

* [Basic Usage](https://github.com/UnknownRori/Rin#-basic-usage)

* [Installation](https://github.com/UnknownRori/Rin#-installation)

    * [For Projects](https://github.com/UnknownRori/Rin#-for-projects)

    * [For Development](https://github.com/UnknownRori/Rin#%EF%B8%8F-for-development)
    
* [Contribution](https://github.com/UnknownRori/Rin#-contribution)


## 📔 Overview

Rin is a lightweight minimalistic framework written in php, it's feature SEO Friendly Routing, Middleware, and Dependency Injection. This framework is trying to solve problem when someone said [Laravel](https://laravel.com/) is overkill so this framework will be replacement for someone who not very familiar with [CodeIgniter](https://codeigniter.com/), although this framework is bare minimum and not for production because this framework is just for fun.

## 🚀 Feature

* Nearly identical with Laravel API

* SEO Friendly Routing & Middleware

* Dependency Injection

## 🚀 Basic Usage

Don't use this framework until the API is stable


```php
// index.php
<?php

require './vendor/autoload.php';

use UnknownRori\Rin\Application;
use UnknownRori\Rin\Http\Route;

Route::get('/', function () {
    echo "<h1>Hello, World!</h1>";
});

$app = new Application();

return $app->serve();
```

To start the server just type

`php -S 127.0.0.1:8000 -t ./your-public-directory ./index.php`

## 🛠️ Installation

* ### 📦 For Projects

    `composer require unknownrori/rin`

* ### 🛠️ For Development

    ``` bash
    # Clone the repository
    > git clone https://github.com/UnknownRori/Rin.git

    # Enter the directory and install the dependency
    > cd Rin
    > composer install
    ```

## 🌟 Contribution

Feel free to contribute and improve the codebase, send pull request or create an issues if something is wrong or need some additional feature we will review it.