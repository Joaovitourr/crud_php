<?php 


$id = $_GET['id'];
$nomeUsuario = $_GET['nome'];
 
if(!isset($id) || !isset($nomeUsuario)){
    echo "Erro de valores";
    header("Location: ../../view/admin/lista_usuarios.php");
    exit();
      
} else {


    echo "<form class='form' method='GET' action='alterar_usuario.php'> 
    
    <h1> Usuario Atual </h1>
    <label> Nome Anterior: </label>;
    <input type='text'  value='$nomeUsuario' disabled> 
    <label> Nome Atual: </label>;
    <input type='text' name='usuario' maxlength='7'>
    <input type='hidden' name='id' value='$id'>

    <button> Enviar </button>
    ";
 
}
?>