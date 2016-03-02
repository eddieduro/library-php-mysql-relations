<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once 'src/Copy.php';
    require_once 'src/Book.php';
    require_once 'src/Author.php';

    $server = 'mysql:host=localhost;dbname=library_test';
    $user = 'root';
    $password = 'root';
    $DB = new PDO($server, $user, $password);

    class CopyTest extends PHPUnit_Framework_TestCase
    {
        protected function teardown(){
            Copy::deleteAll();
        }

        function test_getBookId(){

            // Arrange
            $book_id = null;
            $id = null;
            $new_copy = new Copy($book_id, $id);

            // Act
            $result = $new_copy->getBookId();

            // Assert
            $this->assertEquals($book_id, $result);
        }

        function test_deleteAll(){
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
            Copy::deleteAll();

            // Assert
            $result = Copy::getAll();
            $this->assertEquals([], $result);
        }

        function test_getAll(){
            // book
            $title1 = "Space";
            $genre = "Sci-fi";
            $id = null;
            $new_book1 = new Book($title1, $genre);
            $new_book1->save();
            $book_id1 = $new_book1->getId();

            // book
            $title2 = "Space2";
            $genre = "Sci-fi";
            $id = null;
            $new_book2 = new Book($title2, $genre);
            $new_book2->save();
            $book_id2 = $new_book2->getId();

            //Author
            $name = "Bob";
            $test_author = new Author($name);
            $test_author->save();

            // Act
            $new_book1->addAuthor($test_author);
            $new_book2->addAuthor($test_author);

            $new_copy1 = new Copy($book_id1, $id);
            $new_copy1->save();
            $new_copy2 = new Copy($book_id2, $id);
            $new_copy2->save();

            // Assert
            $result = Copy::getAll();
            $this->assertEquals([$new_copy1, $new_copy2], $result);
        }

        function test_save(){
            // book
            $title1 = "Space";
            $genre = "Sci-fi";
            $id = null;
            $new_book1 = new Book($title1, $genre);
            $new_book1->save();
            $book_id1 = $new_book1->getId();


            //Author
            $name = "Bob";
            $test_author = new Author($name);
            $test_author->save();

            // Act
            $new_book1->addAuthor($test_author);

            $new_copy1 = new Copy($book_id1, $id);
            $new_copy1->save();

            // Assert
            $result = Copy::getAll();
            $this->assertEquals($new_copy1, $result[0]);
        }

    }

?>
