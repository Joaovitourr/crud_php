<?php 

include_once("../../model/model.php");
include_once("../../controller/admin_controller/verify_session.php");

$admin = new SuperAdmin();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="../style/lista_usuario.css">
</head>

<body>



<div class="caixa_usuarios">


<h1> Usuarios</h1>

<?Php $admin->getTodosOsDados(); ?> 

</div>
    
</body>
</html>