<?php

    class Patron
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
            $GLOBALS['DB']->exec("INSERT INTO patron (name) VALUES ('{$this->getName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }


        static function deleteAll(){
            $GLOBALS['DB']->exec("DELETE FROM patron");
        }

        static function getAll(){
            $returned_patrons = $GLOBALS['DB']->query("SELECT * FROM patron;");
            $patrons = array();
            foreach($returned_patrons as $patron){
                $name = $patron['name'];
                $id = $patron['id'];
                $new_patron = new Patron($name, $id);
                array_push($patrons, $new_patron);
            }
            return $patrons;
        }
    }

?>
