<?php

class UserDocuments 
{
    private $documents = array();

    function __construct(){}
    
    public function addDocument(Document $document, User $user)
    {
        array_push($this->documents, array('document' => $document, 'user' => $user));
    }

    public function getAllDocuments()
    {
        return $this->documents;
    }

    public function findDocumentsByUser($user)
    {
        $docs = array();
        foreach ($this->documents as $document) {
            if ($document['user']->giveName() == $user->giveName()) {
                $docs[] = $document['document'];
            }
        }

        return $docs;
    }
}

interface IDocument 
{
    public function getTitle();
    public function getContent();
    public function isNil();
}

class Document implements IDocument
{
    private $title;
    private $content;

    function __construct($_title, $_content = '')
    {
        if (strlen($_title) <= 5) {
            throw new Exception("The document title it's too short, must be at least 5 charactes");            
        }
        $this->title = $_title;
        $this->content = $_content;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function isNil()
    {
        return false;
    }
}

class DocumentNotFound implements IDocument
{

    function __construct() {}

    public function getTitle()
    {
        return 'Document not found';
    }

    public function getContent()
    {
        return '';
    }

    public function isNil()
    {
        return true;
    }
}

class Document_Mapper
{
    private $db;

    function __construct($db_adapter)
    {
        $this->db = $db_adapter;
    }

    public function getDocumentByTitle($title)
    {
        $row = $this->db->query('SELECT name, content FROM document WHERE name = "' . $title . '" LIMIT 1');
        
        if (count($row)) {
            $document = new Document($row[0], $row[1]);
        } else {
            $document = new DocumentNotFound();
        }

        return $document;
    }

    public function getDocumentByContent($content)
    {
        $row = $this->db->query('SELECT name, content FROM document WHERE content = "%' . $content . '%" LIMIT 1');
        
        if (count($row)) {
            $document = new Document($row[0], $row[1]);
        } else {
            $document = new DocumentNotFound();
        }

        return $document;
    }
}

class User 
{
    private $name;

    function __construct($_name)
    {
        $this->name = $_name;
    }

    public function giveName()
    {
        return $this->name;
    }
}
