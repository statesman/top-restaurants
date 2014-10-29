<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

// Twig to render responses
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views'
));

// ConfigServiceProvider for environment settings
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__.'/env.json'));

// Routes ...
$app->get($app['urlprefix'].'map/', function() use($app) {
  $data = file_get_contents(__DIR__."/../data/data.json");
  $data = json_decode($data);

  return $app['twig']->render('map.twig', array(
    'resourceurl' => $app['urlprefix'],
    'locations' => (array) $data->top
  ));
});

$app->get($app['urlprefix'].'{id}/', function($id) use($app) {
    $data = file_get_contents(__DIR__."/../data/data.json");
    $data = json_decode($data);

    if($id != 0) {
      $prev = $data->top[$id - 1];
      $prev->id = $id - 1;
    }

    if($id != count($data->top) - 1) {
      $next = $data->top[$id + 1];
      $next->id = $id + 1;
    }

    return $app['twig']->render('restaurant.twig', array(
      'resourceurl' => $app['urlprefix'],
      'previous' => $prev,
      'current' => $data->top[$id],
      'next' => $next
    ));
});

$app->get($app['urlprefix'], function() use($app) {
    $data = file_get_contents(__DIR__."/../data/data.json");
    $data = json_decode($data);
    $data->resourceurl = $app['urlprefix'];

    return $app['twig']->render('index.twig', (array) $data);
});

$app->run();
