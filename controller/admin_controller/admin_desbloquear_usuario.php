<?php 

require_once("../../model/model.php");
$admin = new SuperAdmin();


if(!isset($_GET['id'])){
    echo "Erro";
    header("Location: ../../view/admin/lista_bloqueados.php");
    exit();
} else {
    $id = $_GET['id'];
    $admin->setDesbloquearUsuario($id);
    header("Location: ../../view/admin/lista_bloqueados.php");


}

