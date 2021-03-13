<?php

namespace  Ijdb\Controllers;

use \Lchh\DatabaseTable;
use \Lchh\Authentication;

class Joke
{
    private $authorsTable;
    private $jokesTable;
    private $emailsTable;
    private $categoriesTable;
    public function __construct(
        DatabaseTable $jokesTable,
        DatabaseTable $authorsTable,
        DatabaseTable $emailsTable,
        DatabaseTable $categoriesTable,
        Authentication $authentication
    ) {
        $this->jokesTable = $jokesTable;
        $this->authorsTable = $authorsTable;
        $this->emailsTable = $emailsTable;
        $this->categoriesTable = $categoriesTable;
        $this->authentication = $authentication;
    }
    public function list()
    {
        if (isset($_GET['category'])) {
            $category = $this->categoriesTable->findById($_GET['category']);
            $jokes = $category->getJokes();
        } else {
            $jokes = $this->jokesTable->findAll();
        }


        $author = $this->authentication->getUser();

        $totalJokes = $this->jokesTable->total();


        $title = 'Joke list';


        return [
            'template' => 'jokes.html.php',
            'title' => $title,
            'variables' => [
                'totalJokes' => $totalJokes,
                'jokes' => $jokes,
                'userId' => $author->id ?? null,
                'categories' => $this->categoriesTable->findAll() ?? null
            ]
        ];
    }
    public function home()
    {
        $title = 'Internet Joke Database';

        return ['template' => 'home.html.php', 'title' =>
        $title, 'variables' => [
            'joke' => $joke ?? null
        ]];
    }
    public function delete()
    {
        $author = $this->authentication->getUser();

        if (isset($_POST['id'])) {
            $joke = $this->jokesTable->findById($_POST['id']);
            // if ($joke['authorid'] != $author['id']) {
            if ($joke->authorid != $author->id) {

                return;
            }
        }
        $this->jokesTable->delete($_POST['id']);
        header('location: ' . URLROOT . 'joke/list');
    }
    public function loledit()
    {
        if (isset($_POST['joke'])) {
            $joke = $_POST['joke'];
            $joke['jokedate'] = new \DateTime();
            $joke['authorId'] = 1;
            $this->jokesTable->save($joke);
            header('location: ' . URLROOT . 'joke/list');
        } else {
            $title = 'Add joke';
            if (isset($_GET['id'])) {
                $joke = $this->jokesTable->findById($_GET['id']);
                $title = 'Edit joke';
            }
        }
        return [
            'template' => 'editjoke.html.php',
            'title' => $title,
            'variables' => [
                'joke' => $joke ?? null
            ]
        ];
    }
    public function saveEdit()
    {
        $author = $this->authentication->getUser();
        // $authorObject = new \Ijdb\Entity\Author($this->jokesTable);
        // $authorObject->id = $author['id'];
        // $authorObject->name = $author['name'];
        // $authorObject->email = $author['email'];
        // $authorObject->password = $author['password'];

        if (isset($_GET['id'])) {
            $joke = $this->jokesTable->findById($_GET['id']);
            // if ($joke['authorid'] != $author['id']) {
            if ($joke->authorid != $author->id) {

                return;
            }
        }
        $joke = $_POST['joke'];
        $joke['jokedate'] = new \DateTime();
        // $authorObject->addJoke($joke);
        $jokeEntity = $author->addJoke($joke);
        $jokeEntity->clearCategories();
        foreach ($_POST['category'] as $categoryid) {
            $jokeEntity->addCategory($categoryid);
        }
        header('location: ' . URLROOT . 'joke/list');
    }
    public function edit()
    {
        $author = $this->authentication->getUser();
        $categories = $this->categoriesTable->findAll();
        $title = 'Add joke';
        if (isset($_GET['id'])) {
            $joke = $this->jokesTable->findById($_GET['id']);
            $title = 'Edit joke';
        }
        return [
            'template' => 'editjoke.html.php',
            'title' => $title,
            'variables' => [
                'joke' => $joke ?? null,
                'userId' => $author->id ?? null,
                'categories' => $categories ?? null

            ]
        ];
    }
}
