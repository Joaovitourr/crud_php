<?php 

include_once("../../model/model.php");

$admin = new SuperAdmin();

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $admin->setbanirUsuario($id);
    header("Location: ../../view/admin/lista_usuarios.php");
}
