<?php

namespace Lchh;

class EntryPoint
{
    private $route;
    private $routes;
    private $method;
    public function __construct($route, $method, \Lchh\Routes $routes)
    {
        $this->route = $route;
        $this->routes = $routes;
        $this->method = $method;
        $this->checkUrl();
    }
    public function checkUrl()
    {
        if ($this->route !== strtolower($this->route)) {
            http_response_code(301);
            header('location: ' . URLROOT . strtolower($this->route));
        }
    }
    private function loadTemplate($templateFileName, $variables = [])
    {
        extract($variables);
        ob_start();
        include __DIR__ . '/../../templates/' . $templateFileName;
        return ob_get_clean();
    }

    public function run()
    {
        $routes = $this->routes->getRoutes();
        $authentication = $this->routes->getAuthentication();

        if (
            isset($routes[$this->route]['login'])
            && !$authentication->isLoggedIn()
        ) {
            header('Location:' . URLROOT . 'login/error');
        } elseif (
            isset($routes[$this->route]['permissions'])
            && !$this->routes->checkPermission($routes[$this->route]['permissions'])
        ) {
            header('Location:' . URLROOT . 'login/error');
        }
        if (isset($routes[$this->route][$this->method]['controller']) && isset($routes[$this->route][$this->method]['action'])) {
            $controller = $routes[$this->route][$this->method]['controller'];
            $action = $routes[$this->route][$this->method]['action'];
        } else {
            header('Location:' . URLROOT . 'login/error');
        }

        $page =  $controller->$action();

        $title = $page['title'];

        if (isset($page['variables'])) {
            $output = $this->loadTemplate(
                $page['template'],
                $page['variables'],
            );
        } else {
            $output = $this->loadTemplate($page['template']);
        }
        echo $this->loadTemplate(
            'layout.html.php',
            [
                'loggedIn' => $authentication->isLoggedIn(),
                'output' => $output,
                'title' => $title
            ]
        );
    }
}
