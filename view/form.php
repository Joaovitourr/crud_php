
<form class="cadastrar" method="POST" action="../controller/admin_controller/cadastrar_admin.php"> 

<h1> Cadastrar</h1>

<label>Usuario:</label>
<input type="text" name="usuario" class="input_cad"> 

<label>Senha:</label>
<input type="text" name="senha" class="input_cad"> 

<label>Nivel Admin:</label>
<select name="select"> 
    <option value="1">1 - Comum</option>
    <option value="1">2 - intermediario </option>
    <option value="1">3 - Supremo </option>
</select>

<button> Cadastrar</button>


</form>