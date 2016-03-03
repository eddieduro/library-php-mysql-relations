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
            // book
            $title1 = "Space";
            $genre = "Sci-fi";
            $id = null;
            $new_book1 = new Book($title1, $genre);
            $new_book1->save();
            $book_id = $new_book1->getId();

            $due_date = "2016-03-03";
            $id = null;
            $available = 1;
            $new_copy = new Copy($book_id, $id, $due_date, $available);
            $new_copy->save();

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

            $due_date = "2016-03-03";
            $available = 1;
            $new_copy1 = new Copy($book_id1, $id, $due_date, $available);
            $new_copy1->save();
            $new_copy2 = new Copy($book_id2, $id, $due_date, $available);
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

            $due_date = "2016-03-03";
            $available = 1;
            $new_copy1 = new Copy($book_id1, $id, $due_date, $available);
            $new_copy1->save();

            // Assert
            $result = Copy::getAll();
            $this->assertEquals($new_copy1, $result[0]);
        }

        function test_findBook(){
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

            $new_book1->addAuthor($test_author);

            // Act
            $due_date = "2016-03-03";
            $available = 1;
            $new_copy1 = new Copy($book_id1, $id, $due_date, $available);
            $new_copy1->save();

            $searched_title = "Space";


            // Assert
            $result = $new_copy1->findBooks($searched_title);
            $this->assertEquals($new_book1, $result);
        }

        function test_findAuthor(){
            // book
            $title1 = "Space";
            $genre = "Sci-fi";
            $id = null;
            $new_book1 = new Book($title1, $genre);
            $new_book1->save();
            $book_id1 = $new_book1->getId();

            // book
            $title2 = "Katz";
            $genre = "Family";
            $id = null;
            $new_book2 = new Book($title2, $genre);
            $new_book2->save();
            $book_id2 = $new_book2->getId();


            //Author
            $name1 = "Bob";
            $test_author1 = new Author($name1);
            $test_author1->save();

            $new_book1->addAuthor($test_author1);

            $name2 = "Tom";
            $test_author2 = new Author($name2);
            $test_author2->save();

            $new_book2->addAuthor($test_author2);
            // Act
            $due_date = "2016-03-03";
            $available = 1;
            $new_copy1 = new Copy($book_id1, $id, $due_date, $available);
            $new_copy1->save();


            $searched_author = "Tom";

            // Assert
            $result = $new_copy1->findAuthors($searched_author);
            $this->assertEquals($test_author2, $result);
        }

        function test_setDueDate(){
            //arrange
            // book
            $title = "Space";
            $genre = "Sci-fi";
            $id = null;
            $new_book = new Book($title, $genre);
            $new_book->save();
            $book_id = $new_book->getId();


            $due_date = "2016-03-03";
            $available = 1;
            $new_copy = new Copy($book_id, $id, $due_date, $available);
            $new_copy->save();
            $new_due_date = '2020-12-20';
            //act
            $new_copy->setDueDate($new_due_date);
            $results = $new_copy->getDueDate();
            //assert
            $this->assertEquals('2020-12-20', $results);
        }

        function test_getDueDate(){
            //arrange
            // book
            $title = "Space";
            $genre = "Sci-fi";
            $id = null;
            $new_book = new Book($title, $genre);
            $new_book->save();
            $book_id = $new_book->getId();


            $due_date = "2016-03-03";
            $available = 1;
            $new_copy = new Copy($book_id, $id, $due_date, $available);
            $new_copy->save();
            //act
            $results = $new_copy->getDueDate();
            //assert
            $this->assertEquals("2016-03-03", $results);
        }

        function test_checkOut(){
            // book
            $title = "Space";
            $genre = "Sci-fi";
            $id = null;
            $new_book = new Book($title, $genre);
            $new_book->save();
            $book_id = $new_book->getId();


            $due_date = '1970-01-01';
            $available = 1;
            $new_copy = new Copy($book_id, $id, $due_date, $available);
            $new_copy->save();
            $new_due_date = "2016-04-03";

            //act
            $new_copy->checkOut($new_due_date);
            $result = Copy::getAll();

            //assert
            $this->assertEquals([$new_copy], $result);
        }

        function test_checkIn(){
            // book
            $title = "Space";
            $genre = "Sci-fi";
            $id = null;
            $new_book = new Book($title, $genre);
            $new_book->save();
            $book_id = $new_book->getId();


            $due_date = "1970-01-01";
            $available = 0;
            $new_copy = new Copy($book_id, $id, $due_date, $available);
            $new_copy->save();

            //act
            $new_copy->checkIn();
            $result = Copy::getAll();

            //assert
            $this->assertEquals([$new_copy], $result);
        }
    }

?>
