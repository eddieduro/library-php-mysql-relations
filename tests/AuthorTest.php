<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once 'src/Author.php';

    $server = 'mysql:host=localhost;dbname=library_test';
    $user = 'root';
    $password = 'root';
    $DB = new PDO($server, $user, $password);

    class AuthorTest extends PHPUnit_Framework_TestCase
    {
        protected function teardown(){
            Author::deleteAll();
        }

        function test_getName(){

            // Arrange
            $name = "Bob";
            $new_author = new Author($name);

            // Act
            $result = $new_author->getName();

            // Assert
            $this->assertEquals("Bob", $result);
        }

        function test_deleteAll(){
            // Arrange
            $name1 = "Bob";
            $new_author = new Author($name1);
            $new_author->save();
            // Act
            Author::deleteAll();

            // Assert
            $result = Author::getAll();
            $this->assertEquals([], $result);
        }

        function test_getAll(){
            // Arrange
            $name1 = "Harry Potter";
            $new_author1 = new Author($name1);
            $new_author1->save();


            $name2 = "Harry Potter2";
            $new_author2 = new Author($name2);
            $new_author2->save();

            // Act
            $result = Author::getAll();

            // Assert

            $this->assertEquals([$new_author1, $new_author2], $result);
        }

        function test_save(){
            // Arrange
            $name = "Bob";
            $new_author = new Author($name);
            $new_author->save();

            // Act


            // Assert
            $result = Author::getAll();
            $this->assertEquals($new_author, $result[0]);
        }

    }

?>
