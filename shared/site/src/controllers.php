<?php
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// 
$app->before(function (Request $request, Application $app) {
    $url = $request->getUri();
    // $redirects = $app['redirects.model']->getAll();

    // foreach ($redirects as $redirect) {
    //     if (strpos($url, $redirect['search']) !== false) {
    //         return $app->redirect($redirect['redirect_to'], $redirect['code']);
    //     }
    // }
}, Application::EARLY_EVENT);

// @route landing page
$app->match('/', function () use ($app) {
    // $page = $app['pages.model']->findBy('page', 'landing');

    $params = array(
        // 
    );

    if (isset($_GET['debug']) && $app['debug']) {
        var_dump($params);
        die;
    }

    return $app['twig']->render('landing/index.html.twig', $params);
})
->bind('landing');

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