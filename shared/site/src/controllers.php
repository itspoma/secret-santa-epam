<?php
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->before(function (Request $request, Application $app) {
    $app['registration.isOpen'] = time() - strtotime($app['registration.close.date']) < 0;
}, Application::EARLY_EVENT);

// @route landing page
$app->match('/', function () use ($app) {
    return $app['twig']->render('landing/index.html.twig', array(
        'isOpenRegistration' => $app['registration.isOpen'],
    ));
})
->bind('landing');

// @route registration page
$app->match('/play', function (Request $request) use ($app) {
    $response = array(
        'ok' => false,
        'error' => '',
    );

    if (true !== $app['registration.isOpen']) {
        $response['error'] = 'closed';
        return json_encode($response);
    }

    sleep(1);

    $email = $request->get('email');    

    if ($app['database.model']->exists($email)) {
        $response['error'] = 'exists';
        return json_encode($response);
    }

    $response['ok'] = $app['database.model']->save($email);
    return json_encode($response);
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