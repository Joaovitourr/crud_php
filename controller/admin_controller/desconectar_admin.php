<?php 

include_once("../../model/model.php");


$admin = new SuperAdmin();

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    $admin->desconectarAdministrador($id);
    // print_r($_SESSION['admin_nivel']);


} else {
    echo "Erro";
}