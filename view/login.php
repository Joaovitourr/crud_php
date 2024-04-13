<?php 

include_once("../model/model.php");

$json = json_decode(file_get_contents("php://input"), true);
$user = $json['usuario'];
$senha = $json['senha'];


$usuario = new Usuario();
$usuario->validarLogin($user, $senha);