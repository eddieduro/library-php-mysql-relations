<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once 'src/Book.php';

    $server = 'mysql:host=localhost;dbname=library_test';
    $user = 'root';
    $password = 'root';
    $DB = new PDO($server, $user, $password);

    class BookTest extends PHPUnit_Framework_TestCase
    {
        protected function teardown(){
            Book::deleteAll();
        }

        function test_getTitle(){

            // Arrange
            $title = "Harry Potter";
            $genre = "Sci-fi";
            $author_id = 1;
            $id = 1;
            $new_book = new Book($title, $genre, $author_id, $id);

            // Act
            $result = $new_book->getTitle();

            // Assert
            $this->assertEquals("Harry Potter", $result);
        }

        function test_deleteAll(){
            // Arrange
            $title1 = "Harry Potter";
            $genre = "Sci-fi";
            $author_id1 = 1;
            $id = null;
            $new_book1 = new Book($title1, $genre, $author_id1, $id);
            $new_book1->save();
            // Act
            BOok::deleteAll();

            // Assert
            $result = Book::getAll();
            $this->assertEquals([], $result);
        }

        function test_getAll(){
            // Arrange
            $title1 = "Harry Potter";
            $genre = "Sci-fi";
            $new_book1 = new Book($title1, $genre);
            $new_book1->save();
    

            $title2 = "Harry Potter2";
            $new_book2 = new Book($title2, $genre);
            $new_book2->save();
    
            // Act
            $result = Book::getAll();

            // Assert

            $this->assertEquals([$new_book1, $new_book2], $result);
        }

        function test_save(){
            // Arrange
            $title = "Harry Potter";
            $genre = "Sci-fi";
            $new_book = new Book($title, $genre);
            $new_book->save();

            // Act


            // Assert
            $result = Book::getAll();
            $this->assertEquals($new_book, $result[0]);
        }

        function test_addAuthor(){
            // Arrange
            // book
            $title = "Space";
            $genre = "Sci-fi";
            $new_book = new Book($title, $genre);
            $new_book->save();

            //Author
            $name = "Bob";
            $test_author = new Author($name);
            $test_author->save();

            // Act
            $new_book->addAuthor($test_author);
            var_dump($new_book->getAuthor());
            // Assert
            $this->assertEquals($new_book->getAuthor(), [$test_author]);
        }
    }

?>