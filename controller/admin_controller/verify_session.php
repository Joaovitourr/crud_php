<?php 

include_once("../../model/model.php");

session_start();


if(!isset($_SESSION['admin_name']) || $_SESSION['admin_nivel'] !== 3){
    echo "Voce nao pode acessar essa pagina";
    header("Location: ../../restrito.html");
}