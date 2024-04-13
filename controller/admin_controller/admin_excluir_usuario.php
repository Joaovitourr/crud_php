<?php 

include_once("../../model/model.php");

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
var_dump($id);

if(empty($id)){
    $_SESSION['msg'] = "<p> Nada encontrado </p>";
    header("Location: ../../view/admin/lista_usuarios.php");
    exit();
} else {
    
$admin = new SuperAdmin();
$admin->setExcluirUsuario($id);
header("Location: ../../view/admin/lista_usuarios.php");
}
