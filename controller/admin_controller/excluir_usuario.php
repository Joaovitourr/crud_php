<?php 


include_once("../../model/model.php");
session_start();
ob_start();


$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
var_dump($id);

if(empty($id)){
    $_SESSION['msg'] = "<p> Nada encontrado </p>";
    header("Location: ../view/dashboard.php");
    exit();
} else {
    
$admin = new SuperAdmin();
$admin->setExcluirUsuario($id);
header("Location: ../view/dashboard.php");
}




// $admin->mostrarUsers();