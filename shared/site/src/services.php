<?php
use app\models\Database as DatabaseModel;

// @service for Database
$app['database.model'] = $app->share(function () use ($app) {
    return new DatabaseModel($app);
});