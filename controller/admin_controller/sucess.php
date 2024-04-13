<?php 

include_once('../../model/model.php');


$alterarDadosAdmin = new SuperAdmin();

print_r($_GET['id']);

if(isset($_GET['id']) && isset($_GET['admin'])){

    $id = $_GET['id'];
    $admin = $_GET['admin'];
    $senha = $_GET['password'];
    $alterarDadosAdmin->alterarDadosAdministradores($id, $admin, $senha);

} else {
    echo "Erro"; 
}