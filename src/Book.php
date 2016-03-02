<?php

    class Book
    {
        private $title;
        private $genre;
        private $author_id;
        private $id;

        function __construct($title, $genre, $author_id, $id = null){
            $this->title = $title;
            $this->genre = $genre;
            $this->author_id = $author_id;
            $this->id = $id;
        }

        function getTitle(){
            return $this->title;
        }

        function getGenre(){
            return $this->genre;
        }

        function getAuthorId(){
            return $this->author_id;
        }

        function getId(){
            return $this->id;
        }

        function save(){
            $GLOBALS['DB']->exec("INSERT INTO books (title, genre, author_id) VALUES ('{$this->getTitle()}', '{$this->getGenre()}', '{$this->getAuthorId()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function deleteAll(){
            $GLOBALS['DB']->exec("DELETE FROM books");
        }

        static function getAll(){
            $returned_books = $GLOBALS['DB']->query("SELECT * FROM books;");
            $books = array();
            foreach($returned_books as $book){
                $title = $book['title'];
                $genre = $book['genre'];
                $author_id = $book['author_id'];
                $id = $book['id'];
                $new_book = new Book($title, $genre, $author_id, $id);
                array_push($books, $new_book);
            }
            return $books;
        }
    }
?>
