<?php 

    function getConnection(){
        try {
            $conn = new PDO(
                'mysql:host=sql.freedb.tech;dbname=freedb_doanwebhuy',
                "freedb_doanannghia",
                "xSPuqUE?&2k3aZN",
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            return $conn;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
?>