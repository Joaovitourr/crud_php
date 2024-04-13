<?php 


include_once("../model/model.php");

session_start();
$usuarioOffline = $_SESSION['usuario'];
$usuario = new Usuario();
$usuario->desconectar($usuarioOffline);