<?php 

include_once("../../model/model.php");
include_once("../../controller/admin_controller/verify_session.php");

$adminSolicitacao =new SuperAdmin();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admins solicitação</title>
    <link rel="stylesheet" href="../style/lista_admins_solicitacao.css">
</head>
<body>

<p> <?php $adminSolicitacao->setMostrarPedidosAdminsCargo() ?> </p>
    
</body>
</html>