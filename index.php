<?php

use Gajus\Dindent\Indenter;
use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use voku\helper\HtmlMin;

include 'vendor/autoload.php';

$docsPath = __DIR__.'/docs';
$requestUri = $_SERVER['REQUEST_URI'] ?? $_SERVER['argv'][1];

if (is_file($docsPath.$requestUri)) {
    $types = ['css' => 'text/css', 'js' => 'text/javascript'];
    $extension = pathinfo($docsPath.$requestUri, PATHINFO_EXTENSION);
    header('Content-Type: '.($types[$extension] ?? mime_content_type($docsPath.$requestUri)));
    readfile($docsPath.$requestUri);
    die;
}

$routes = Yaml::parseFile(__DIR__.'/config/routes.yaml');
$twig = new Environment(new FilesystemLoader(__DIR__.'/templates'));

$indenter = new Indenter();

$contents = [];
foreach ($routes as $route) {
    $contents[$route['template']] = $indenter->indent($twig->render('pages/'.$route['template'].'.html.twig', [
        'currentRoute' => $route,
        'routes' => $routes,
    ]));
    file_put_contents(__DIR__.'/docs/'.$route['template'].'.html', $contents[$route['template']]);
}

file_put_contents(__DIR__.'/docs/sitemap.xml', $indenter->indent($twig->render('sitemap.xml.twig', [
    'routes' => $routes,
])));

$currentRoute = $routes[$requestUri];

echo $contents[$currentRoute['template']];