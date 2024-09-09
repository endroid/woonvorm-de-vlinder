<?php

use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

include 'vendor/autoload.php';

$routing = Yaml::parseFile('config/routing.yaml');
$twig = new Environment(new FilesystemLoader('templates'));

$contents = [];
foreach ($routing as $route) {
    $contents[$route['template']] = $twig->render('pages/'.$route['template'].'.html.twig', [
        'title' => $route['title'],
        'routing' => $routing,
    ]);

    file_put_contents('docs/'.$route['template'].'.html', $contents[$route['template']]);
}

$currentRoute = $routing[$_SERVER['REQUEST_URI'] ?? $_SERVER['argv'][1]];

echo $contents[$currentRoute['template']];