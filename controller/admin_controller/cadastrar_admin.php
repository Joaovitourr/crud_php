<?php 


require_once("../../model/model.php");

$Admin = new SuperAdmin();

if(!isset($_POST['usuario']) || !isset($_POST['senha'])){
  echo "Erro";
  header("Location: ../../view/dash.php");
  die();
   
} else{
 
  $nome = $_POST['usuario'];
  $senha = $_POST['senha'];
  $nivel = $_POST['nivel'];
$Admin->cadastrarAdmin($nome, $senha, $nivel);
header("Location: ../../view/dash.php");
}


?>
