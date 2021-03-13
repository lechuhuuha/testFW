<?php

namespace Ijdb\Entity;

class Joke
{
    public $id;
    public $joketest;
    public $jokedate;
    public $authorid;
    private $authorsTable;
    private $emailsTable;
    private $author;
    private $jokeCategoriesTable;
    private $authorEmailsTable;


    public function __construct(
        \Lchh\DatabaseTable $authorsTable,
        \Lchh\DatabaseTable $emailsTable,
        \Lchh\DatabaseTable $jokeCategoriesTable,
        \Lchh\DatabaseTable $authorEmailsTable


    ) {
        $this->authorsTable = $authorsTable;
        $this->emailsTable = $emailsTable;
        $this->jokeCategoriesTable = $jokeCategoriesTable;
        $this->authorEmailsTable = $authorEmailsTable;
    }
    public function getAuthor()
    {
        if (empty($this->author)) {

            $this->author = $this->authorsTable->findById($this->authorid);
        }
        return $this->author;
    }
    public  function getEmail()
    {
        $authorEmails = $this->authorEmailsTable->find('authorid', $this->authorid);
        $emails = [];
        foreach ($authorEmails as $authorEmail) {
            $email = $this->emailsTable->findById($authorEmail->emailid);
            if ($email) {
                $emails[] = $email;
            }
        }
        return $emails;
    }
    public function addCategory($categoryid)
    {
        $jokeCat = ['jokeid' => $this->id, 'categoryid' => $categoryid];
        $this->jokeCategoriesTable->save($jokeCat);
    }
    public function hasCategory($categoryid)
    {
        $jokeCategories = $this->jokeCategoriesTable->find('jokeid', $this->id);
        foreach ($jokeCategories as $jokeCategory) {
            if ($jokeCategory->categoryid == $categoryid) {
                return true;
            }
        }
    }
    public function clearCategories()
    {
        $this->jokeCategoriesTable->deleteWhere('jokeid', $this->id);
    }
}
