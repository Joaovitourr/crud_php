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
    <title>Usuarios Bloqueados</title>
    <link rel="stylesheet" href="../style/lista_usuario_bloqueado.css">
</head>
<body>


<?php $admin->setMostrarTodosUsuariosBloqueados(); ?>
    
</body>
</html>