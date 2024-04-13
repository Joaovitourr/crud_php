<?php 

header("Content-type: appplication/json");

require_once("../../model/model.php");

$json = json_decode(file_get_contents("php://input"), true);
$usuario = $json['user'];
$senha = $json['senha'];


$user = new Admin();
$user->autorizarAdmin($usuario, $senha);


?>
