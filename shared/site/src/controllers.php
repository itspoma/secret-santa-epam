<?php
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// 
$app->before(function (Request $request, Application $app) {
    // if (time() - strtotime($app['phase1.end']) >= 0) {
    //     var_dump('phase1 end');
    // }

    // var_dump(date('Y-m-d H:i:s', time()), time() - strtotime($app['phase1.end']));die;

}, Application::EARLY_EVENT);

// @route landing page
$app->match('/', function () use ($app) {
    return $app['twig']->render('landing/index.html.twig');
})
->bind('landing');

// @route
$app->post('/play', function (Request $request) use ($app) {
    $data = array(
        date('Y-m-d H:i:s'),
        'landing',
        $request->get('email'),
    );

    $f = fopen('emails-registered.txt', 'aw');
    fwrite($f, join("\t\t", $data)."\n");
    fclose($f);

    sleep(2);

    return json_encode(array(
        'status' => 'ok',
    ));
});

//
$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    return $app['twig']->render('error/index.html.twig', array(
        'code' => $code,
        'message' => $e->getMessage(),
    ));
});

return $app;