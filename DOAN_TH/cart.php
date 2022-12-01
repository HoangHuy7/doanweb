<?php
    
include("./connection.php");
include("./detailCart.php");
include("./ProductDTO.php");
$conn = getConnection();

$method = $_SERVER['REQUEST_METHOD'];

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Header: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");
session_start();
switch ($method) {
    case 'GET':
         if (isset($_SESSION["cartMain"])) {
			 $data = $_SESSION["cartMain"];
            echo "[" . json_encode($data) . "]";
		 }else{
			echo "[]";

		 }
        break;
    case 'POST': // them vao gio hang`
        try {
            
            $productId = $_POST["productId"];
            $quantity = $_POST["quantity"];
            // echo "id= ".$productId."quantity = ".$quantity; 
            if (isset($_SESSION["cartMain"])) {
                $cartMain = $_SESSION["cartMain"];
                if (array_key_exists($productId, $cartMain)) {

                    $cartMain[$productId]->__set("quantity", $cartMain[$productId]->__get("quantity") + $quantity);
                } else {
                    $sql = "SELECT * FROM PRODUCT WHERE id = :id";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(array("id" => $productId));
                    $result =  $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($result != null) {

                        $prodDTO = new ProductDTO();
                        $prodDTO->id = $result["id"];
                        $prodDTO->name = $result["name"];
                        $prodDTO->price = $result["price"];
                        $prodDTO->content = $result["content"];
                        $prodDTO->image = $result["image"];


                        $cartMain = new detailCart($quantity, $prodDTO);
                        $cartArray = $_SESSION["cartMain"];

                        $cartArray[$productId] =  $cartMain;
                        $_SESSION["cartMain"] = $cartArray;
                    }
                }
            } else {
               
                $sql = "SELECT * FROM PRODUCT WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute(array("id" => $_POST["productId"]));
                $result =  $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result != null) {
                    $prodDTO = new ProductDTO();
                    $prodDTO->id = $result["id"];
                    $prodDTO->name = $result["name"];
                    $prodDTO->price = $result["price"];
                    $prodDTO->content = $result["content"];
                    $prodDTO->image = $result["image"];
                    // print_r( $prodDTO);

                    $cartMain = new detailCart($quantity, $prodDTO);
                    $_SESSION["cartMain"] = array($productId => $cartMain);
                }
            }
            if (!isset($_SESSION["cartMain"])) {
                $_SESSION["cartMain"] = [];
               
            }
            $data = $_SESSION["cartMain"];
            echo "[" . json_encode($data) . "]";
        } catch (Exception $e) {
        }
        break;
    case 'DELETE': // de delete

        break;
    case 'PUT': // de sua

        break;
}
