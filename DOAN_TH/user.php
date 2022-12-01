<?php

include("./connection.php");
$conn = getConnection();

$method = $_SERVER['REQUEST_METHOD'];

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Header: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");
include "ProductDTO.php";
$conn = getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$message = ["message" => "false"];
$action = "you need seen action for me";
switch ($method) {
    case 'GET':
        if (!isset($_GET["action"]) || !$_GET["action"] != "") {
            echo json_encode($message);
            die;
        }

        try {
            $action = $_GET["action"];
            switch ($action) {
                case 'findAll':
                    
                    $sql = "SELECT * FROM USER";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
              
                    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
                    break;
                default:
                    echo json_encode($message);
                    die;
                    break;
            }
        } catch (Exception $e) {
            $pdo->rollback();
            $message = ["message" => $e];
            echo json_encode($message);
            throw $e;
        }
        break;
    case 'POST':


        // check action
        if (!isset($_POST["action"]) || !$_POST["action"] != "") {
            echo json_encode($message);
            die;
        }

        try {
            $action = $_POST["action"];
            if (!isset($_POST["username"]) && !isset($_POST["password"]) ) {
                echo json_encode($message);
                die;
            }
            $username = $_POST["username"];
            $password = $_POST["password"];
			
            switch ($action) {
                case 'register':
                    $role = "USER";
					
                    if (isset($_POST["role"])) $role = $_POST["role"];

					if(!isset($_POST["password2"])){
						 echo json_encode($message);
						die;
					}

					$password2 = $_POST["password2"];
					if($password != $password2){
					 echo json_encode($message);
						die;
					}
                    $sql = "INSERT INTO USER(USERNAME,PASSWORD,ROLE) VALUES(:username,:password,:role)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(array("username" => $username, "password" => $password, "role" => $role));
                    if ($stmt->rowCount() > 0) {
                        $message["message"] = "true";
                    }
                    echo json_encode($message);
                    break;
                case 'login':

                    $sql = "SELECT username,password,role FROM user where username = :username and password = :password";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(array(":username" => $username, ":password" => $password));
                    if ($stmt->rowCount())
                        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
                    else {
                        $message["message"] = "not found";
                        echo json_encode($message);
                    }
                    break;
                default:
                    echo json_encode($message);
                    die;
                    break;
            }
        } catch (Exception $e) {
            $pdo->rollback();
            $message = ["message" => $e];
            echo json_encode($message);
            throw $e;
        }
        break;
    case 'DELETE': // de delete

        break;
    case 'PUT': // de sua

        break;
}
