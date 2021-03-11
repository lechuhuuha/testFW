<?php

namespace Ijdb;

class IjdbRoutes implements \Lchh\Routes
{
    private $authorsTable;
    private $jokesTable;
    private $authentication;
    private $emailsTable;
    public function __construct()
    {
        include __DIR__ . '/../../includes/DatabaseConnection.php';
        $this->jokesTable = new \Lchh\DatabaseTable($pdo, 'joke', 'id', '\Ijdb\Entity\Joke', [&$this->authorsTable]);
        $this->authorsTable = new \Lchh\DatabaseTable($pdo, 'author', 'id', '\Ijdb\Entity\Author', [&$this->jokesTable, &$this->emailsTable]);
        $this->emailsTable = new \Lchh\DatabaseTable($pdo, 'email', 'id');
        $this->authentication = new \Lchh\Authentication($this->authorsTable, 'name', 'password');
    }
    public function getRoutes(): array
    {
        $jokeController = new \Ijdb\Controllers\Joke(
            $this->jokesTable,
            $this->authorsTable,
            $this->emailsTable,
            $this->authentication
        );
        $authorController = new \Ijdb\Controllers\Register(
            $this->authorsTable,
            $this->emailsTable
        );
        $loginController = new \Ijdb\Controllers\Login($this->authentication);
        $routes = [
            'joke/edit' => [
                'POST' => [
                    'controller' => $jokeController,
                    'action' => 'saveEdit'
                ],
                'GET' => [
                    'controller' => $jokeController,
                    'action' => 'edit'
                ],
                'login' => true
            ],
            'joke/delete' => [
                'POST' => [
                    'controller' => $jokeController,
                    'action' => 'delete'
                ],
                'login' => true
            ],
            'joke/list' => [
                'GET' => [
                    'controller' => $jokeController,
                    'action' => 'list'
                ]
            ],
            '' => [
                'GET' => [
                    'controller' => $jokeController,
                    'action' => 'home'
                ]
            ],
            'author/register' => [
                'GET' => [
                    'controller' => $authorController,
                    'action' => 'registrationForm'
                ],
                'POST' => [
                    'controller' => $authorController,
                    'action' => 'registerUser'
                ]
            ],
            'author/success' => [
                'GET' => [
                    'controller' => $authorController,
                    'action' => 'success'
                ]
            ],
            'login/error' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'error'
                ]
            ],
            'login' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'loginForm'
                ],
                'POST' => [
                    'controller' => $loginController,
                    'action' => 'processLogin'
                ]
            ],
            'login/success' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'success'
                ],
                'login' => true
            ],
            'logout' => [
                'GET' => [
                    'controller' => $loginController,
                    'action' => 'logout'
                ]
            ]
        ];

        return $routes;
    }
    public function getAuthentication(): \Lchh\Authentication
    {
        return $this->authentication;
    }
}
