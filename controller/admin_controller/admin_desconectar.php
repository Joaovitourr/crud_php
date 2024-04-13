<?php 

include_once("../../model/model.php");

session_start();

$UsuarioDeslogar = $_SESSION['admin_name'];

if(!isset($UsuarioDeslogar)){
    echo "Erro";
    header("Location: ../../restrito.html");
    exit();
} else {
    $FecharSessao = new Admin();
    $FecharSessao->setAdminOffline($UsuarioDeslogar);
    $FecharSessao->desconectar($UsuarioDeslogar);
    header("Location: ../../restrito.html");
}



