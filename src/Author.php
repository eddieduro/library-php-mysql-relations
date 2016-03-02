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


        function getBooks(){
            $books = $GLOBALS['DB']->query("SELECT books.* FROM author
                JOIN books_authors ON (author.id = books_authors.author_id)
                JOIN books ON (books_authors.book_id = books.id)
                WHERE author.id = {$this->getId()};");

            $returned_books = array();
                foreach($books as $book){
                    $title = $book['title'];
                    $genre = $book['genre'];

                    $new_book = new Book($title, $genre);
                    array_push($returned_books, $new_book);
                }
            return $returned_books;
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
