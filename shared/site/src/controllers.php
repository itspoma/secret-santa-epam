<?php
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// @route landing page
$app->match('/', function () use ($app) {
    return $app['twig']->render('landing/index.html.twig');
})
->bind('landing');

// @route registration page
$app->match('/play', function (Request $request) use ($app) {
    $email = $request->get('email');
    
    sleep(1);

    $database = file_get_contents($app['db.filename']);

    if (false !== strpos(strtolower($database), strtolower($email))) {
        return json_encode(array(
            'ok' => false,
            'error' => 'exists',
        ));
    }

    $data = array(
        date('Y-m-d H:i:s'),
        $_SERVER['REMOTE_ADDR'],
        $email,
    );

    $f = fopen($app['db.filename'], 'aw');
    fwrite($f, join("\t\t", $data)."\n");
    fclose($f);

    return json_encode(array(
        'ok' => true,
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