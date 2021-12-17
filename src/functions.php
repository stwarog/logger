<?php

namespace Efficio\Logger;

use Efficio\Logger\Factory\File\Config;
use Efficio\Logger\Factory\File\LoggerFactory as MonologFactory;
use Efficio\Logger\Factory\NullLoggerFactory;
use Efficio\Logger\Factory\Resolver\LoggerFactory as ResolverFactory;
use Efficio\Logger\Factory\Sentry\LoggerFactory as SentryFactory;
use Psr\Container\ContainerInterface as Container;
use Psr\Log\LoggerInterface;
use RuntimeException;

# todo: it should be done in another repo, it's just a hackathon sample
function bootstrap(
    object $app,
    string $environment,
    string $sentryDsn,
    string $path,
    string $fileName = 'app.txt',
    int $permission = 0777, # todo
    string $level = 'DEBUG',
    bool $displayErrorDetails = true,
    bool $logErrors = true,
    bool $logErrorDetails = true
): void {
    // Verify

    if (!method_exists($app, 'addErrorMiddleware')) {
        throw new RuntimeException('App does not contains method "addErrorMiddleware"');
    }

    if (!method_exists($app, 'getContainer')) {
        throw new RuntimeException('App does not contains method "getContainer"');
    }

    /** @var Container $container */
    $container = $app->getContainer();

    /*
     *  Initialize default Logger factory if not set
     */
    if (!$container->has(LoggerFactory::class)) {
        $null = (new NullLoggerFactory())->create();
        $default = $null;
        $sentry = (new SentryFactory($sentryDsn))->create();
        $monologConfig = new Config($path, $fileName, $permission, $level);

        $monolog = (new MonologFactory($monologConfig))->create();

        /** @phpstan-ignore-next-line */
        $container[LoggerFactory::class] = fn(): LoggerFactory => new ResolverFactory(
            $environment,
            $default,
            $null,
            $sentry,
            $monolog
        );
    }

    /*
     *  Binds the result of Logger Factory to PSR logger interface in Container
     */
    if (!$container->has(LoggerInterface::class)) {
        /** @var LoggerFactory $factory */
        $factory = $container->get(LoggerFactory::class);
        /** @phpstan-ignore-next-line */
        $container[LoggerInterface::class] = $factory->create();
    }

    /*
     *  # todo addErrorMiddleware works with psr-4
     *  Enables Error logger
     */
    $app->addErrorMiddleware(
        $displayErrorDetails,
        $logErrors,
        $logErrorDetails,
        $container->get(LoggerInterface::class)
    );
}
