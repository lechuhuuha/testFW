<?php

namespace Ijdb\Controllers;

class Category
{
    private $categoriesTable;
    public function __construct(\Lchh\DatabaseTable $categoriesTable)
    {
        $this->categoriesTable = $categoriesTable;
    }
    public function list()
    {
        $categories = $this->categoriesTable->findAll();
        $title = 'Joke categories';
        return [
            'template' => 'categories.html.php',
            'title' => $title,
            'variables' => [
                'categories' => $categories
            ]
        ];
    }
    public function edit()
    {
        if (isset($_GET['id'])) {
            $category = $this->categoriesTable->findById($_GET['id']);
        }
        $title = 'Edit category';
        return [
            'template' => 'editcategory.html.php',
            'title' => $title,
            'variables' => [
                'category' => $category ?? null
            ]
        ];
    }
    public function saveEdit()
    {
        $category = $_POST['category'];
        $this->categoriesTable->save($category);
        header('location: ' . URLROOT . 'category/list');
    }
    public function delete()
    {
        $this->categoriesTable->delete($_POST['id']);
        header('location: ' . URLROOT . 'category/list');
    }
}
