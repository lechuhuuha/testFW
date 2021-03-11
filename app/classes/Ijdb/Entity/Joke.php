<?php

namespace Ijdb\Entity;

class Joke
{
    public $id;
    public $authorid;
    public $jokedate;
    public $joketest;
    private $authorsTable;
    private $emailsTable;

    public function __construct(
        \Lchh\DatabaseTable $authorsTable,
        \Lchh\DatabaseTable $emailsTable
    ) {
        $this->authorsTable = $authorsTable;
        $this->emailsTable = $emailsTable;
    }
    public function getAuthor()
    {
        return $this->authorsTable->findById($this->authorid);
    }
    public  function getEmail()
    {
        return $this->emailsTable->findById($this->authorid);
    }
}
