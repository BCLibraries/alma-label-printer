<?php

use BCLib\AlmaPrinter\AlmaClient;
use BCLib\AlmaPrinter\NullCache;
use BCLib\AlmaPrinter\RedisCache;

$container = $app->getContainer();

$container['view'] = function ($c) {
    $template_path = __DIR__ . '/../templates';
    $view_cache_path = __DIR__ . '/../view-cache';
    $view_cache_path = false;

    $view = new \Slim\Views\Twig($template_path, ['cache' => $view_cache_path]);

    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['api_cache'] = function ($c) {
    return $_ENV['CACHE_ENGINE'] === 'redis' ? new RedisCache($_ENV['REDIS_HOST']) : new NullCache();
};

$container['alma_client'] = function ($c) {
    return new AlmaClient($_ENV['ALMA_API_KEY'], $c['api_cache']);
};