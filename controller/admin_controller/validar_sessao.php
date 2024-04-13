<?php 


session_start();
header("Content-type: application/json");

if(isset($_SESSION['usuario']) && isset($_SESSION['logged']) === true) {


 
    echo json_encode(array("success" => "Credenciais valida",
    "user" => $_SESSION['usuario']));


} else {
    echo json_encode(array("denied" => "Credenciais Invalida"));

}