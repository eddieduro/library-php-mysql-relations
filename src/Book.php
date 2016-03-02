<?php

    require_once 'src/Author.php';
    require_once 'src/Copy.php';

    class Book
    {
        private $title;
        private $genre;
        private $id;

        function __construct($title, $genre, $id = null){
            $this->title = $title;
            $this->genre = $genre;
            $this->id = $id;
        }

        function getTitle(){
            return $this->title;
        }

        function setTitle($new_title){
            $this->title = (string) $new_title;
        }


        function getGenre(){
            return $this->genre;
        }

        function setGenre($new_genre){
            $this->genre = (string) $new_genre;
        }

        function getId(){
            return $this->id;
        }

        function save(){
            $GLOBALS['DB']->exec("INSERT INTO books (title, genre) VALUES ('{$this->getTitle()}', '{$this->getGenre()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function addAuthor($author){
            $GLOBALS['DB']->exec("INSERT INTO books_authors (book_id, author_id) VALUES ({$this->getId()}, {$author->getId()});");
        }

        function getAuthors(){
            $authors = $GLOBALS['DB']->query("SELECT author.* FROM books
                JOIN books_authors ON (books_authors.book_id = books.id)
                JOIN author ON (author.id = books_authors.author_id)
                WHERE books.id = {$this->getId()};");

            $authors = $authors->fetchAll();

            $returned_authors = array();
                foreach($authors as $author){
                    $name = $author['name'];
                    $id = $author['id'];
                    $new_author = new Author($name, $id);
                    array_push($returned_authors, $new_author);
                }
            return $returned_authors;
        }

        function updateTitle($new_title){
            $GLOBALS['DB']->exec("UPDATE books SET title = '{$new_title}' WHERE id = {$this->getId()};");
            $this->setTitle($new_title);
        }

        function updateGenre($new_genre){
            $GLOBALS['DB']->exec("UPDATE books SET genre = '{$new_genre}' WHERE id = {$this->getId()};");
            $this->setGenre($new_genre);
        }

        function deleteBook(){
            $GLOBALS['DB']->exec("DELETE FROM books WHERE id = {$this->getId()};");
        }

        function addCopy($copy){
            $GLOBALS['DB']->exec("INSERT INTO copies (books_id) VALUES ('{$copy->getId()}');");
        }

        function getCopies(){
            $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies WHERE books_id = {$this->getId()}");
            $copies = array();
            foreach($returned_copies as $copy){
                $id = $copy['id'];
                $book_id = $copy['books_id'];
                $new_copy = new Copy($book_id, $id);
                array_push($copies, $new_copy);
            }
            return $copies;
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
                $id = $book['id'];
                $new_book = new Book($title, $genre, $id);
                array_push($books, $new_book);
            }
            return $books;
        }
    }
?>
