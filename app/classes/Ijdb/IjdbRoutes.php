<?php

namespace Ijdb;

class IjdbRoutes implements \Lchh\Routes
{
    private $authorsTable;
    private $jokesTable;
    private $authentication;
    private $emailsTable;
    private $categoriesTable;
    private $jokeCategoriesTable;
    private $authorEmailsTable;
    public function __construct()
    {
        include __DIR__ . '/../../includes/DatabaseConnection.php';
        $this->jokesTable = new \Lchh\DatabaseTable($pdo, 'joke', 'id', '\Ijdb\Entity\Joke', [&$this->authorsTable, &$this->emailsTable, &$this->jokeCategoriesTable, &$this->authorEmailsTable]);
        $this->authorsTable = new \Lchh\DatabaseTable($pdo, 'author', 'id', '\Ijdb\Entity\Author', [&$this->jokesTable, &$this->emailsTable, &$this->authorEmailsTable]);
        $this->emailsTable = new \Lchh\DatabaseTable($pdo, 'email', 'id');
        $this->authentication = new \Lchh\Authentication($this->authorsTable, 'name', 'password');
        $this->jokeCategoriesTable = new \Lchh\DatabaseTable($pdo, 'jokecategory', 'categoryid');
        $this->categoriesTable = new \Lchh\DatabaseTable($pdo, 'category', 'id', '\Ijdb\Entity\Category', [&$this->jokesTable, &$this->jokeCategoriesTable]);
        $this->authorEmailsTable = new \Lchh\DatabaseTable($pdo, 'authoremail', 'authorid');
    }
    public function getRoutes(): array
    {
        $jokeController = new \Ijdb\Controllers\Joke(
            $this->jokesTable,
            $this->authorsTable,
            $this->emailsTable,
            $this->categoriesTable,
            $this->authentication
        );
        $authorController = new \Ijdb\Controllers\Register(
            $this->authorsTable,
            $this->emailsTable
        );
        $categoryController = new \Ijdb\Controllers\Category($this->categoriesTable);
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
            'category/edit' => [
                'POST' => [
                    'controller' => $categoryController,
                    'action' => 'saveEdit'
                ],
                'GET' => [
                    'controller' => $categoryController,
                    'action' => 'edit'
                ],
                'login' => true,
                'permissions' => \Ijdb\Entity\Author::EDIT_CATEGORIES

            ],
            'category/list' => [
                'GET' => [
                    'controller' => $categoryController,
                    'action' => 'list'
                ],
                'login' => true,
                'permissions' => \Ijdb\Entity\Author::LIST_CATEGORIES

            ],
            'category/delete' => [
                'POST' => [
                    'controller' => $categoryController,
                    'action' => 'delete'
                ],
                'login' => true,
                'permissions' => \Ijdb\Entity\Author::DELETE_JOKE
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
            ],
            'author/permissions' => [
                'GET' => [
                    'controller' => $authorController,
                    'action' => 'permissions'
                ],
                'POST' => [
                    'controller' => $authorController,
                    'action' => 'savePermissions'
                ],
                'login' => true
            ],
            'author/list' => [
                'GET' => [
                    'controller' => $authorController,
                    'action' => 'list'
                ],
                'login' => true
            ]
        ];

        return $routes;
    }
    public function getAuthentication(): \Lchh\Authentication
    {
        return $this->authentication;
    }
    public function checkPermission($permission): bool
    {
        $user = $this->authentication->getUser();
        if ($user && $user->hasPermission($permission)) {
            return true;
        } else {
            return false;
        }
    }
}
