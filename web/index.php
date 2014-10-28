<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = TRUE;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->get('/{id}/', function($id) use($app) {
    $data = file_get_contents(__DIR__."/../data.json");
    $data = json_decode($data);

    return $app['twig']->render('restaurant.twig', (array) $data[$id]);
});

$app->get('/', function() use($app) {
    $data = file_get_contents(__DIR__."/../data.json");
    $data = json_decode($data);

    return $app['twig']->render('index.twig', array("data" => $data));
});

$app->run();
