<?php 

require_once("../../model/model.php");
session_start();

$admin = new SuperAdmin();

if(isset($_GET['id'])){
    
  $id =  $_GET['id'];
$admin->setBloquearAcessoAdministrador($id);
header("Location: ../../view/dashboard.php");

} else {
    echo "Algum erro ocorreu";
}