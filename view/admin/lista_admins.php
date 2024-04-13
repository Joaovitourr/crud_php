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
    <title>Lista Admins</title>
    <link rel="stylesheet" href="../style/lista_admins.css">
</head>
<body>

<h1> Lista Admins </h1>

<div class="container_lista">


<?php  
$admin->setMostrarTodosAdmins(); ?>

</div>

</div>


    


 
</body>
</html>


