<?php

    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/../src/Author.php';
    require_once __DIR__.'/../src/Book.php';
    require_once __DIR__.'/../src/Copy.php';
    require_once __DIR__.'/../src/Patron.php';

    $server = 'mysql:host=localhost;dbname=library';
    $user = 'root';
    $password = 'root';
    $DB = new PDO($server, $user, $password);

    $app = new Silex\Application();

    $app->register(new Silex\Provider\TwigServiceProvider, array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get("/", function () use ($app) {
        return $app['twig']->render("index.html", array(
            'books' => Book::getAll(),
            'copies' => Copy::getAll(),
            'patron' => Patron::getAll()
        ));
    });

    return $app;
?>
