
<?php 
include_once("../model/model.php");

session_start();

$adminNome = $_SESSION['admin_name'];
$adminNivel = $_SESSION['admin_nivel'];
$admin = new SuperAdmin();

if(!isset($adminNome)){
    echo "Nao esta";
    header("Location: ../restrito.html");
    exit();
    
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
</head>
<body>

<?php if ($adminNivel === 1): ?>
    <?php include_once("./admin_view/body_simples.php"); ?>
<?php elseif ($adminNivel === 2): ?>
    <?php include_once("./admin_view/body_intermediario.php"); ?>
<?php elseif ($adminNivel === 3): ?>
    <?php include_once("./admin_view/body_supremo.php"); ?>
<?php endif; ?>



  
</body>
</html>