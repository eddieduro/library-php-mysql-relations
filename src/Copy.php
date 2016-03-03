<?php
    require_once 'src/Book.php';
    require_once 'src/Author.php';

        class Copy
        {
            private $id;
            private $book_id;
            private $due_date;
            private $available;

            function __construct($book_id, $id = null, $due_date, $available = null){
                $this->book_id = $book_id;
                $this->id = $id;
                $this->due_date = $due_date;
                $this->available = $available;
            }

            function getBookId(){
                return $this->book_id;
            }

            function getId(){
                return $this->id;
            }

            function getDueDate(){
                return $this->due_date;
            }

            function getAvailable(){
                return $this->available;
            }

            function setAvailable($curr_available){
                $this->available = $curr_available;
            }

            function setDueDate($new_due_date){
                $this->due_date = $new_due_date;
            }

            function save(){
                $GLOBALS['DB']->exec("INSERT INTO copies (books_id, due_date, available) VALUES ({$this->getBookId()}, '{$this->getDueDate()}', {$this->getAvailable()});");
                $this->id = $GLOBALS['DB']->lastInsertId();
            }

            function findBooks($searched_title){
                $found_books = null;
                $books = Book::getAll();

                foreach($books as $book){
                    $books_title = $book->getTitle();
                    if($books_title == $searched_title){
                        $found_books = $book;
                    }
                }
                return $found_books;
            }

            function findAuthors($searched_author){
                $found_authors = null;
                $authors = Author::getAll();

                foreach($authors as $author){
                    $authors_name = $author->getName();
                    if($authors_name == $searched_author){
                        $found_authors = $author;
                    }
                }
                return $found_authors;
            }

            function checkOut($new_due_date){
                $GLOBALS['DB']->exec("UPDATE copies SET available = 0, due_date = '{$new_due_date}' WHERE id = {$this->getId()};");
                $this->setAvailable(0);
                $this->setDueDate($new_due_date);
            }

            function checkIn(){
                $GLOBALS['DB']->exec("UPDATE copies SET available = 1, due_date = '1970-01-01' WHERE id = {$this->getId()};");
                $this->setAvailable(1);
                $this->setDueDate('1970-01-01');
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
                    $due_date = $copy['due_date'];
                    $available = $copy['available'];
                    $new_copy = new Copy($book_id, $id, $due_date, $available);
                    array_push($copies, $new_copy);
                }
                return $copies;
            }

        }
    ?>
