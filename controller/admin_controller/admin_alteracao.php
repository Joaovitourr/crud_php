<?php 

require_once("../../model/model.php");
$admin = new Admin();


if(!isset($_POST['usuario']) || !isset($_POST['nivel_solicitacao'])){
    echo "Erro";
    exit();
 
} else {
    $usuario = $_POST['usuario'];
    $nivelSolicitado = $_POST['nivel_solicitacao'];
    $mensagem = $_POST['mensagem'];
    $admin->setAdministradorSolicitaAlteracaoDeNivel($usuario,$nivelSolicitado,  $mensagem);
    
    
}



