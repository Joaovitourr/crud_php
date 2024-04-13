<?php 

require_once('../../model/model.php');

$Usuario = $_SESSION['admin_name'];

$adminStatus = new SuperAdmin(); 
$adminStatus->setAdminOnline($usuario, "online");