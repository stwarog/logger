# Efficio/logger

Few implementations of commonly used Loggers in our system, compatible with [PSR-3](https://www.php-fig.org/psr/psr-3/).

Well documented details are attached in PSR documents.

Most of the implementations depends on Monolog.

## General

This package wraps 3 types of Loggers. Each of them implements simple factory interface, to build concrete
LoggerInterface (PSR-3) object with minimal setup.

## "External" (Sentry) logger
**Monolog** wrapper that sends all logs to the external Sentry system.

### Initialization

```php
# Config is optional

$config = [
    'level' => LogLevel::ERROR # Default value
]

$factory = new LoggerFactory('PASTE HERE SENTRY LINK', $config);

$logger = $factory->create();
```

### Capturing an Exception
To capture Exception, simply as Context argument, pass an Exception wrapped in parentheses e.g.

```php
$logger->error('custom message', ['exception' => new Exception()]);
```

It will produce detailed log in the app, like native sdk `capture_exception()` function.

### Adding custom logs
This logger uses under the hood `Normalizer` that will transform all common data to desired format.

```php
$logger->info('custom message', [
    'some primitive type field' => 123.12,
    'date' => new DateTime(),
    'jsonSerializable' => $someObject,
]);
```

## "Local" (File) logger
**Monolog** implementation that stores all logs in a file system.

### Initialization

```php
# Config is optional

$config = [ # Default values
    'path' => '{project_dir}/var/log/',
    'file_name' => '{date}-app.txt',
    'permission' => 0775,
    'level' => LogLevel::DEBUG 
]

$factory = new LoggerFactory($config);

$logger = $factory->create();
```

It will produce array representation of Exception with track.

### Capturing an Exception
To capture Exception, simply as Context argument, pass an Exception wrapped in parentheses e.g.

```php
$logger->error('custom message', ['exception' => new Exception()]);
```

### Adding custom logs
This logger uses under the hood `Normalizer` that will transform all common data to desired format.

```php
$logger->emergency('custom message', [
    'some primitive type field' => 123.12,
    'date' => new DateTime(),
    'jsonSerializable' => $someObject,
]);
```

## "Null" object logger
Implements [pattern Null Object](https://sourcemaking.com/design_patterns/null_object) to avoid "ifology".

```php
$factory = new LoggerFactory($config);

$logger = $factory->create();
```

## "Resolver" logger
This is another kind of logger that acts like a resolver. By provided environment name is able to
determine what concrete logger should be used.

### Copy & Paste full example

```php
use Efficio\Logger\{Environment, File, File\Config, NullObject, Resolver, Sentry};
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;


$container->nullLoggerFactory = fn(): LoggerFactory => new NullObject\LoggerFactory();

$container->sentryLoggerFactory = fn(): LoggerFactory => new Sentry\LoggerFactory(
    "getenv('SENTRY_DSN')",
    ['level' => LogLevel::ERROR]
);

$container->fileLoggerFactory = fn(): LoggerFactory => new File\LoggerFactory(
    new Config(
        __DIR__ . '/../../var/logs/',
        'app.txt',
        0777, # todo
        LogLevel::DEBUG,
    )
);

$container->loggerFactory = function () use ($container) {
    $defaultLogger = $container->nullLoggerFactory;

    return new Resolver\LoggerFactory(
        new LoggerEnvironment(getenv('ENVIRONMENT_NAME')),
        $defaultLogger,                  # default
        $container->nullLoggerFactory,   # null
        $container->sentryLoggerFactory, # external 
        $container->fileLoggerFactory    # local
    );
};

$container->logger = fn(): LoggerInterface => $container->loggerFactory->create();
```

### Using Container
There is also an alternative approach to initialize the Resolver Factory by using Container, but 4 predefined 
aliases for Factories must be defined:

```php
$container[Types::NULL] = NullLogger::class;
$container[Types::DEFAULT] = $container[Types::NULL];
$container[Types::EXTERNAL] = SentryLogger::class;
$container[Types::LOCAL] = FileLogger::class;

Resolver\LoggerFactory::createFrom($container);

$container->logger = fn(): LoggerInterface => $container->loggerFactory->create();
```
