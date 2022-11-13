<?php
header("Access-Control-Allow-Origin: *"); //add this CORS header to enable any domain to send HTTP requests to these endpoints:
include "ProductDTO.php";




$id = "";
$sql = "";
$conn = new PDO(
    'mysql:host=sql.freedb.tech;dbname=freedb_doanweb',
    "freedb_doanweb",
    "bckKv#&nUTjtW76",
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
);
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        $action = "findAll";
        if (isset($_GET["action"])) {
            $action = $_GET["action"];
        }

        if ($action == "findAll") {
            $sql = "SELECT * FROM product";
            $stmt = $conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "ProductDTO");
            $stmt->execute($_GET);
            if (!$id) echo '[';
            $t = 0;
            while ($row = $stmt->fetch()) {
    
                echo ($t > 0 ? ',' : '') . $row;
                $t++;
            }
            if (!$id) echo ']';
        }else if ($action == "findOne") {
            $movieId = null;
            $sql = "SELECT* FROM product where `product`.`id` = :productId";
            if (isset($_GET["productId"])) {
                $movieId = $_GET["productId"];
            }else {
				echo '{"message":"movieId dont find"}';
				die;
			}
            $stmt = $conn->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "ProductDTO");
            $stmt->bindParam(':productId', $movieId, PDO::PARAM_INT);
            $stmt->execute();
            if (!$id) echo '[';
            $t = 0;
            while ($row = $stmt->fetch()) {
    
                echo ($t > 0 ? ',' : '') . $row;
                $t++;
            }
            if (!$id) echo ']';
        }
       
        break;
    case 'POST':
        $message = ["message" => "false"];
        try {

            $sql = "INSERT INTO product ( name, price, content, image) VALUES ( :name, :price, :content, :image);";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute($_POST)) $message = ["message" => "success"];
            echo json_encode($message);
        } catch (Exception $e) {
            $pdo->rollback();
            $message = ["message" => $e];
            echo json_encode($message);
            throw $e;
        }
        break;
    case 'DELETE': // de delete
        $message = ["message" => "false"];
        try {
            $sql = "SELECT * FROM `product` WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $_REQUEST["id"], PDO::PARAM_INT);
            $stmt->execute();
            while ($stmt->fetch()) {
                $sql = "DELETE FROM `product` WHERE `product`.`id` = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $_REQUEST["id"], PDO::PARAM_INT);
                if ($stmt->execute()) {
                    $message = ["message" => "true"];
                }
            }
            echo json_encode($message);
        } catch (Exception $e) {
            $pdo->rollback();
            echo $e;
            throw $e;
        }
        break;
    case 'PUT': // de sua
        $message = ["message" => "false"];
        try {
            $sql = "UPDATE product SET name = :name, price = :price, content = :content, image = :image WHERE id = :id;";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute($_REQUEST))  $message = ["message" => "true"];
            echo json_encode($message);
           
        } catch (Exception $e) {
            $pdo->rollback();
            echo $e;
            throw $e;
        }
        break;
}
