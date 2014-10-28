<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = TRUE;

$app['resourceurl'] = '/';

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->get('/map/', function() use($app) {
  $data = file_get_contents(__DIR__."/../data/data.json");
  $data = json_decode($data);

  return $app['twig']->render('map.twig', array(
    'resourceurl' => $app['resourceurl'],
    'locations' => (array) $data->top
  ));
});

$app->get('/{id}/', function($id) use($app) {
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
      'resourceurl' => $app['resourceurl'],
      'previous' => $prev,
      'current' => $data->top[$id],
      'next' => $next
    ));
});

$app->get('/', function() use($app) {
    $data = file_get_contents(__DIR__."/../data/data.json");
    $data = json_decode($data);
    $data->resourceurl = $app['resourceurl'];

    return $app['twig']->render('index.twig', (array) $data);
});

$app->run();
