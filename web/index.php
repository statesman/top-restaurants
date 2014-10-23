<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->get('/hello/{name}/{day}', function($name, $day) use($app) {
  $name = strtoupper($name);

    return $app['twig']->render('hello.twig', array(
        'firstname' => $name,
        'day' => $day
    ));
});

$app->get('/hello/wynne', function() use($app) {
    return 'Hello, Wynne!';
});

$app->get('/goodbye/{name}', function($name) use($app) {
    return 'Goodbye ' . $name;
});


$app->run();
