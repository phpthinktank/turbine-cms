# Setup

## Bootstrap

We recommend to use default bootstrap code from `public/index.php`

Turbine Platform has convenient application factory.

```php
<?php

Turbine\Application\Http\Bootstrap::create(dirname(__DIR__));
```

### Error handling

If you want to control Error output you just need to add following lines before initialise your app:

```php
<?php

//activate or deactive error output
ini_set('display_errors', 1);
error_reporting(E_ALL);

//bootstrap code
```

If you activate `ini_set('display_errors', 1)` allowed errors from `error_reporting()` will be shown with filp\whoops error handler.
If you deactivate `ini_set('display_errors', 0)` errors, all allowed errors will be save to /res/log/system.log

Next: [Configuration](01_Config.md)

### Custom http application bootstrap

Create an application with custom bootstrap logic.
 
```php
<?php

use Turbine\Application\Http\Bootstrap;

//validate rootpath
$rootPath = realpath($rootPath);

//create loader
$loader = require_once $rootPath . '/vendor/autoload.php';

//create bootstrap
$bootstrap = new Bootstrap($rootPath);

//setup bootstrap
$bootstrap
    ->setRootPath($rootPath)
    ->setResources(new Resources($rootPath . '/res'))
    ->setLoader($loader)
    
//initialize application factory
$factory = $bootstrap->boot();

//create a new application
$application = $factory->createApplication(new Foundation());

//dispatch factory
$application->dispatch($bootstrap->getRequest(), $bootstrap->getResponse());
```

### Inject custom container

Sometimes you need to use a pre-defined container. You could easly inject this container while bootstrap setup, before 
calling `Bootstrap::boot`!

```php
<?php

use League\Container\Container;

$container = new Container();

// ... some lines of code and configuration

$bootstrap->setContainer($container);

// ... booting and dispatching

```

You are currently not able to use a different container. We will work for a solution that works for all 
container-interop implementations! 

### Inject custom event emitter

Sometimes you need to use a pre-defined event emitter. You could easly inject this emitter while bootstrap setup, before 
calling `Bootstrap::boot`!

```php
<?php

use League\Event\Emitter;

$emitter = new Emitter();

// ... some lines of code and configuration

$bootstrap->setEmitter($emitter);

// ... booting and dispatching

```