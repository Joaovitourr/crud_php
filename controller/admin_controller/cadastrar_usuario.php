<?php 

require_once("../../model/model.php");
header("Content-type: application/json");

if($_SERVER['REQUEST_METHOD'] === "POST"){
    
    $json = json_decode(file_get_contents("php://input"), true);
    $usuario = $json["usuario"];
    $password = $json["senha"];
       
 
    $user = new Usuario();
    $user->cadastrar($usuario, $password);


}


    
    
    




