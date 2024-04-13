<?php 


include_once("../../model/model.php");


$Admin = new SuperAdmin();
$nivelAdminDesbloqueado = 1;


if(!isset($_GET['id'])){
    header("Location: ../../view/dashboard.php");
} else{
    $idAdmin = $_GET['id'];
    $Admin->setDesbloquearAdministrador($idAdmin, $nivelAdminDesbloqueado);
    header("Location: ../../view/dashboard.php");

}

