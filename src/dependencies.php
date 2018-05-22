<?php

use BCLib\AlmaPrinter\AlmaClient;
use BCLib\AlmaPrinter\ItemResponseParser;
use BCLib\AlmaPrinter\NullCache;
use BCLib\AlmaPrinter\RedisCache;

$container = $app->getContainer();

$container['view'] = function ($c) {
    $template_path = __DIR__ . '/../templates';
    $view_cache_path = $_ENV['SLIM_MODE'] === 'development' ? false : __DIR__ . '/../view-cache';
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

$container['alma_client'] = function ($c) {
    $cache = $_ENV['CACHE_ENGINE'] === 'redis' ? new RedisCache($_ENV['REDIS_HOST']) : new NullCache();
    $label_map = require __DIR__ . '/LabelMap.php';
    $response_parser = new ItemResponseParser($label_map);
    return new AlmaClient($_ENV['ALMA_API_KEY'], $cache, $response_parser);
};