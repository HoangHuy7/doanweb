<?php 

    function getConnection(){
        try {
            $conn = new PDO(
                'mysql:host=localhost;dbname=buoi7',
                "root",
                "",
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            return $conn;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>