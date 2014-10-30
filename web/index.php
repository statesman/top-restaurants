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

// Forward Uchiko to Uchi
$app->get($app['urlprefix'].'3/', function(Request $request) use ($app) {
  return $app->redirect($app['urlprefix'].'2');
});

// Special Uchi/Uchiko handler that combines the two
$app->get($app['urlprefix'].'2/', function(Request $request) use ($app) {
  $data = file_get_contents(__DIR__."/../data/data.json");
  $data = json_decode($data);

  $prev = $data->top[1];
  $prev->id = 1;
  $next = $data->top[4];
  $next->id = 4;

  $uchi = $data->top[2];
  $uchi->id = 2;
  $uchiko = $data->top[3];

  return $app['twig']->render('uchis.twig', array(
    'debug' => $app['debug'],
    'resourceurl' => $app['urlprefix'],
    'domain' => $request->getHttpHost(),
    'pagetitle' => "Uchi / Uchiko: No. 3 on Matthew Odam's best Austin restaurant list",
    'previous' => $prev,
    'current' => $uchi,
    'uchi' => $uchi,
    'uchiko' => $uchiko,
    'next' => $next
  ));
});

$app->get($app['urlprefix'].'{id}/', function(Request $request, $id) use($app) {
    $data = file_get_contents(__DIR__."/../data/data.json");
    $data = json_decode($data);

    if($id != 0) {
      $prev = $data->top[$id - 1];
      $prev->id = $id - 1;
      if($prev->id = 3) {
        $prev->name = "Uchi / Uchiko";
      }
    }
    else {
      $prev = null;
    }

    if($id != count($data->top) - 1) {
      $next = $data->top[$id + 1];
      $next->id = $id + 1;
      if($next->id == 2) {
        $next->name = "Uchi / Uchiko";
      }
    }
    else {
      $next = null;
    }

    $data->top[$id]->id = $id;

    return $app['twig']->render('restaurant.twig', array(
      'debug' => $app['debug'],
      'resourceurl' => $app['urlprefix'],
      'domain' => $request->getHttpHost(),
      'pagetitle' => $data->top[$id]->name . ": No. " . $data->top[$id]->position . " on Matthew Odam's best Austin restaurant list",
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
