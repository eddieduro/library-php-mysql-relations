<?php
    require_once 'src/Book.php';
    require_once 'src/Author.php';

        class Copy
        {
            private $id;
            private $book_id;

            function __construct($book_id, $id = null){
                $this->book_id = $book_id;
                $this->id = $id;
            }

            function getBookId(){
                return $this->book_id;
            }

            function getId(){
                return $this->id;
            }


            function save(){
                $GLOBALS['DB']->exec("INSERT INTO copies (books_id) VALUES ('{$this->getBookId()}');");
                $this->id = $GLOBALS['DB']->lastInsertId();
            }
            //
            function findBooks($searched_title, $searched_author){
                $returned_books = $GLOBALS['DB']->query("SELECT * FROM books WHERE id = {$this->getBookId()}");
                $books = array();
                foreach ($returned_books as $book) {
                    $title = $book['title'];
                    $genre = $book['genre'];
                    $new_book = new Book($title, $genre);
                }
            }

            static function deleteAll(){
                $GLOBALS['DB']->exec("DELETE FROM copies");
            }

            static function getAll(){
                $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies;");
                $copies = array();
                foreach($returned_copies as $copy){
                    $book_id = $copy['books_id'];
                    $id = $copy['id'];
                    $new_copy = new Copy($book_id, $id);
                    array_push($copies, $new_copy);
                }
                return $copies;

            }
        }
    ?>
