<?php 

include_once("../../model/model.php");

$admin = new SuperAdmin();
$id = $_GET['id'];
$usuario = $_GET['usuario'];


if(!isset($id) || !isset($usuario)){
    
  echo "Erro de parametros";
//   header("Location: ../controller/admin_controller/editar_usuario.php");
  exit();
} else {
    $admin->setEditarUsuario($id, $usuario);
    header("Location: ../../view/admin/lista_usuarios.php");

}
