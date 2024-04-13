<?php 


if(isset($_GET['id'])){
  
    $id = $_GET['id'];
    
    
  echo "<div>
   
  <form action='sucess.php' method='GET'> 

<input type='hidden' placeholder='id' name='id' value='$id'>
<input type='text' placeholder='usuario' name='admin'>
<input type='text' placeholder='senha' name='password'>

<button> Enviar </button>

</form>
  
  </div>
  ";
 

};




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>


    
</body>
</html>