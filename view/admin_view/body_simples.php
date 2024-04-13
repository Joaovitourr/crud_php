<?php 

$admin = $_SESSION['admin_name'];




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Normal</title>
    <link rel="stylesheet" href="./style/dash_simples.css">
</head>
<body>

<h1> Ola <?=$admin ?>. Para mais funcionalidades, Solicite abaixo.
</h1>

<div class="container">
   
   <h2>Solicitar Nivel</h2>

   <form method="POST" action="../../login/controller/admin_controller/admin_alteracao.php"> 
    
   <select name='nivel_solicitacao'>
     <option value='2'> Admin Intermediario </option>
     <option value='3'> Admin Supremo </option>
  </select>

  <input type="text" hidden value="<?= $admin ?>" name="usuario">

  <label> Mensagem (opcional): </label>
  <textarea rows="5" cols="20" name="mensagem"> </textarea>

  <button> Enviar</button>
   </div>


   <form>

  

</div>
    
</body>
</html>