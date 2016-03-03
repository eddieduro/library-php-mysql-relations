<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once 'src/Patron.php';
    require_once 'src/Book.php';
    require_once 'src/Author.php';
    require_once 'src/Patron.php';

    $server = 'mysql:host=localhost;dbname=library_test';
    $user = 'root';
    $password = 'root';
    $DB = new PDO($server, $user, $password);

    class PatronTest extends PHPUnit_Framework_TestCase
    {
        protected function teardown(){
            Patron::deleteAll();
        }

        function test_getName(){

            // Arrange
            $name = 'timmy';
            $new_patron = new Patron($name);
            $new_patron->save();

            // Act
            $result = $new_patron->getName();

            // Assert
            $this->assertEquals('timmy', $result);
        }

        function test_deleteAll(){
            // Arrange
            $name1 = "Bob";
            $new_patron = new Patron($name1);
            $new_patron->save();
            // Act
            Patron::deleteAll();

            // Assert
            $result = Patron::getAll();
            $this->assertEquals([], $result);
        }

        function test_getAll(){
            // Arrange
            $name1 = "Harry Potter";
            $new_patron1 = new Patron($name1);
            $new_patron1->save();


            $name2 = "Harry Potter2";
            $new_patron2 = new Patron($name2);
            $new_patron2->save();

            // Act
            $result = Patron::getAll();

            // Assert

            $this->assertEquals([$new_patron1, $new_patron2], $result);
        }

        function test_save(){
            // Arrange
            $name = "Bob";
            $new_patron = new Patron($name);
            $new_patron->save();

            // Act


            // Assert
            $result = Patron::getAll();
            $this->assertEquals($new_patron, $result[0]);
        }




    }

?>
