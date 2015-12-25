<?php
use Igorw\Silex\ConfigServiceProvider;

$configFilePattern = sprintf("%s/config/%s.yml", SRC_DIR, '%s');
$app->register(new ConfigServiceProvider(sprintf($configFilePattern, 'default')));
$app->register(new ConfigServiceProvider(sprintf($configFilePattern, $env)));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
    'http_cache.cache_dir' => ROOT_DIR . '/cache'
));

$app->register(new Moust\Silex\Provider\CacheServiceProvider(), array(
    'cache.options' => array(
        'driver' => 'file',
        'cache_dir' => ROOT_DIR . '/cache/app',
    ),
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.options' => array(
        'cache' => isset($app['twig.options.cache']) ? $app['twig.options.cache'] : false,
        'strict_variables' => true
    ),
    'twig.path' => array(SRC_DIR . '/views')
));

$app->register(new Silex\Provider\SwiftmailerServiceProvider(), array(
    'swiftmailer.use_spool' => false,
    'swiftmailer.options' => array(
        'host' => $app['mail.host'],
        'transport' => $app['mail.transport'],
        'username' => $app['mail.username'],
        'password' => $app['mail.password'],
        'encryption' => 'tls',
        'auth_mode' => 'login',
    ),
));

return $app;