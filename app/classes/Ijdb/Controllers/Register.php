<?php

namespace Ijdb\Controllers;

use \Lchh\DatabaseTable;

class Register
{
    private $authorTable;
    private $emailTable;
    public function __construct(DatabaseTable $authorTable, DatabaseTable $emailTable)
    {
        $this->authorTable = $authorTable;
        $this->emailTable = $emailTable;
    }
    public function registrationForm()
    {
        $lastAuthor = $this->authorTable->lastRecord();
        return [
            'template' => 'register.html.php', 'title' => 'Register an account',
            'variables' => [
                'lastAuthor' => $lastAuthor,
            ]
        ];
    }
    public function success()
    {
        return ['template' => 'registersuccess.html.php', 'title' => 'Registration Successfull'];
    }
    public function registerUser()
    {
        $data = $_POST['author'];
        $email = (array_slice($data, -4, 2));
        $author = (array_slice($data, 2));
        $email['email'] = strtolower($email['email']);

        $valid = true;
        $errors = [];
        if (empty($email['email'])) {
            $valid = false;
            $errors[] = 'Email cannot be blank';
        } else if (filter_var($email['email'], FILTER_VALIDATE_EMAIL) == false) {
            $valid = false;
            $errors[] = 'Invalid email address';
        } elseif (count($this->emailTable->find('email', $email['email'])) > 0) {
            $valid = false;
            $errors[] = 'Email  has already taken';
        }
        if (empty($author['name'])) {
            $valid = false;
            $errors[] = 'Name can not be blank';
        }
        if (count($this->authorTable->find('name', $author['name'])) > 0) {
            $valid = false;
            $errors[] = 'Username has already taken';
        }
        if (empty($author['password'])) {
            $valid = false;
            $errors[] = 'Password cannot be blank';
        }
        if ($valid == true) {
            $author['password'] = password_hash($author['password'], PASSWORD_DEFAULT);
            $this->authorTable->save($author);
            $this->emailTable->save($email);
            header('location: ' . URLROOT . 'author/success');
        } else {
            $lastAuthor = $this->authorTable->lastRecord();

            return [
                'template' => 'register.html.php', 'title' => 'Check your an account',
                'variables' =>
                [
                    'lastAuthor' => $lastAuthor,
                    'errors' => $errors,
                    'email' => $email,
                    'author' => $author
                ]
            ];
        }
    }
    public function list()
    {
        $authors = $this->authorTable->findAll();
        return [
            'template' => 'authorlist.html.php',
            'title' => 'Author list',
            'variables' => [
                'authors' => $authors,
            ]
        ];
    }
    public function permissions()
    {

        $author = $this->authorTable->findById($_GET['id']);
        $reflected = new \ReflectionClass('\Ijdb\Entity\Author');
        $constants = $reflected->getConstants();
        return [
            'template' => 'permissions.html.php',
            'title' => 'User permissions',
            'variables' => [
                'author' => $author,
                'permissions' => $constants
            ]
        ];
    }
    public function savePermissions()
    {
        $author = [
            'id' => $_GET['id'],
            'permissions' => array_sum($_POST['permissions'])
        ];
        $this->authorTable->save($author);
        header('location:' . URLROOT . 'author/list');
    }
}
