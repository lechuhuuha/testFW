<?php

namespace Ijdb\Entity;

use Lchh\DatabaseTable;

class Email
{
    public $id;
    public $email;
    public $authorid;
    private $authorsTable;
    public function __construct(DatabaseTable $authorsTable)
    {
        $this->authorsTable = $authorsTable;
    }
    public function getEmail()
    {
        $authorEmails = $this->authorEmailsTable->find('authorid', $this->id);
        $emails = [];
        foreach ($authorEmails as $authorEmail) {
            $email = $this->emailsTable->findById($authorEmail->id);
            if ($email) {
                $emails[] = $email;
            }
        }
        return $emails;
    }
}
