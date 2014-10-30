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
use Symfony\Component\HttpFoundation\Request;
$app->get($app['urlprefix'].'map/', function(Request $request) use($app) {
  $data = file_get_contents(__DIR__."/../data/data.json");
  $data = json_decode($data);

  return $app['twig']->render('map.twig', array(
    'debug' => $app['debug'],
    'current' => null,
    'resourceurl' => $app['urlprefix'],
    'locations' => (array) $data->top,
    'pagetitle' => "Austin's best restaurants",
    'domain' => $request->getHttpHost()
  ));
});

$app->get($app['urlprefix'].'{id}/', function(Request $request, $id) use($app) {
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

    $data->top[$id]->id = $id;

    return $app['twig']->render('restaurant.twig', array(
      'debug' => $app['debug'],
      'resourceurl' => $app['urlprefix'],
      'domain' => $request->getHttpHost(),
      'pagetitle' => $data->top[$id]->name . " review: No. " . $data->top[$id]->position . " on Matthew Odam's best Austin restaurant list",
      'previous' => $prev,
      'current' => $data->top[$id],
      'next' => $next
    ));
});

$app->get($app['urlprefix'], function(Request $request) use($app) {
    $data = file_get_contents(__DIR__."/../data/data.json");
    $data = json_decode($data);
    $data->resourceurl = $app['urlprefix'];
    $data->pagetitle = "Austin's best restaurants";
    $data->domain = $request->getHttpHost();
    $data->current = null;
    $data->debug = $app['debug'];

    return $app['twig']->render('index.twig', (array) $data);
});

$app->run();
