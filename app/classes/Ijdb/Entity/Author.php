<?php

namespace Ijdb\Entity;

class Author
{
    const EDIT_JOKES = 1;
    const DELETE_JOKE = 2;
    const LIST_CATEGORIES = 4;
    const EDIT_CATEGORIES = 8;
    const REMOVE_CATEGORIES = 16;
    const EDIT_USER_ACCESS = 32;
    public $id;
    public $name;
    public $email;
    public $password;
    private $jokesTable;
    private $emailsTable;
    private $authorEmailsTable;
    public function __construct(
        \Lchh\DatabaseTable $jokesTable,
        \Lchh\DatabaseTable $emailsTable,
        \Lchh\DatabaseTable $authorEmailsTable
    ) {
        $this->jokesTable = $jokesTable;
        $this->emailsTable = $emailsTable;
        $this->authorEmailsTable = $authorEmailsTable;
    }
    public function hasPermission($permission)
    {
        return $this->permissions & $permission;
    }
    public function getEmail()
    {
        $authorEmails = $this->authorEmailsTable->find('authorid', $this->id);
        $emails = [];
        foreach ($authorEmails as $authorEmail) {
            $email = $this->emailsTable->findById($authorEmail->emailid);
            if ($email) {
                $emails[] = $email;
            }
        }
        return $emails;
    }
    public function getJokes()
    {
        return $this->jokesTable->findById('authorid', $this->id);
    }
    public function addJoke($joke)
    {
        $joke['authorid'] = $this->id;
        return   $this->jokesTable->save($joke);
    }
}
