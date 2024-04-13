<?php 

include_once("../../model/model.php");
$admin = new SuperAdmin();

if(isset($_GET['id']) && isset($_GET['nivel'])){
    $adminNome = $_GET['id'];
    $nivel = $_GET['nivel'];
 
    $admin->setConcederPrevilegioAdministrador($adminNome, $nivel);
    exit();
    

} 