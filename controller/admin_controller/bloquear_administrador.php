<?php 

session_start();

include_once("../../model/model.php");


$Admin = new SuperAdmin();
$nivelAdminBloqueado = 0;
$adminSessao = $_SESSION['admin_name'];





if(!isset($_GET['id'])){
   header("Location: ../../view/dashboard.php");
   

} else{
    $idAdmin = $_GET['id'];
    $Admin->setBloquearAdministrador($idAdmin, $nivelAdminBloqueado, $adminSessao);;
    header("Location: ../../view/dashboard.php");
    


}

