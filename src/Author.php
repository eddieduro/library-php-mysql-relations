<?php

    class Author
    {
        private $name;
        private $id;

        function __construct($name, $id = null){
            $this->name = $name;
            $this->id = $id;
        }

        function getName(){
            return $this->name;
        }

        function getId(){
            return $this->id;
        }

        function save(){
            $GLOBALS['DB']->exec("INSERT INTO author (name) VALUES ('{$this->getName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }


        // function getBooks(){
        //     $books = $GLOBALS['DB']->query("SELECT books.* FROM author
        //         JOIN books_authors ON (books_authors.book_id = books.id)
        //         JOIN books ON (author.id = books_authors.author_id)
        //         WHERE author.id = {$this->getId()};");
        //
        //     $returned_books = array();
        //         foreach($books as $book){
        //             $title = $book['title'];
        //             $genre = $book['genre'];
        //             $id = $book['id'];
        //             $new_book = new Book($title, $genre, $id);
        //             array_push($returned_books, $new_book);
        //         }
        //     return $returned_books;
        // }
        function deleteAuthor(){
            $GLOBALS['DB']->exec("DELETE FROM author WHERE id = {$this->getId()};");
        }
        static function find($search_id){
            $found_books = null;
            $authors = Author::getAll();

            foreach($authors as $author){
                $id = $author->getId();
                if($search_id == $id){
                    $found_authors = $author;
                }
            }
            return $found_authors;
        }

        static function deleteAll(){
            $GLOBALS['DB']->exec("DELETE FROM author");
        }

        static function getAll(){
            $returned_authors = $GLOBALS['DB']->query("SELECT * FROM author;");
            $authors = array();
            foreach($returned_authors as $author){
                $name = $author['name'];
                $id = $author['id'];
                $new_author = new Author($name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }
    }
?>
