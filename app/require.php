<?php

try {
    // include __DIR__ . '/classes/EntryPoint.php';
    // include __DIR__ . '/classes/IjdbRoutes.php';
    include __DIR__ . '/includes/autoLoad.php';
    include __DIR__ . '/includes/Config.php';
    $route = ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
    $route = str_replace(APPNAME, '', $route);

    $entryPoint = new \Lchh\EntryPoint($route, $_SERVER['REQUEST_METHOD'], new \Ijdb\IjdbRoutes());


    $entryPoint->run();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage() . ' in '
        . $e->getFile() . ':' . $e->getLine();
}
