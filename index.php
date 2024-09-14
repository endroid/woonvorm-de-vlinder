<?php

use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

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

$routing = Yaml::parseFile(__DIR__.'/config/routes.yaml');
$twig = new Environment(new FilesystemLoader(__DIR__.'/templates'));

$contents = [];
foreach ($routing as $route) {
    $contents[$route['template']] = $twig->render('pages/'.$route['template'].'.html.twig', [
        'title' => $route['title'],
        'routing' => $routing,
    ]);

    file_put_contents(__DIR__.'/docs/'.$route['template'].'.html', $contents[$route['template']]);
}

$currentRoute = $routing[$requestUri];

echo $contents[$currentRoute['template']];