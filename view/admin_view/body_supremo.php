<link rel="stylesheet" href="./style/dash.css">


<div class="container">


<section class="menu-left"> 
    
    <div class="itens-left-content"> 

        <ul class="ul_list_left"> 
            <li><a href="./admin/lista_admins.php"> Admin  <img src="./icons/arrow.png" class="arrow-left"> </a> </li>
            <li><a href="./admin/lista_usuarios.php"> Usuarios <img src="./icons/arrow.png" class="arrow-left"> </a> </li>
            <li><a href="./admin/lista_online.php"> Onlines <img src="./icons/arrow.png" class="arrow-left">  </a> </li>
            <li><a href="./admin/lista_bloqueados.php"> Bloqueados <img src="./icons/arrow.png" class="arrow-left">  </a> </li>
            <li><a href="./admin/lista_admins_solicitacao.php"> Admin Solicitação <span class='color_invite'> <?php $admin = new SuperAdmin(); $admin->contadorPedidosAdminCargo() ?> </span> <img src="./icons/arrow.png" class="arrow-left">  </a> </li>
            <li><a href="../../login/controller/admin_controller/admin_desconectar.php"> Sair  <img src="./icons/arrow.png" class="arrow-left">  </a> </li>
        </ul>


    </div>


</section>



</div>


<section class="section_center"> 

<h1 class="painel_heading">Painel de informações </h1>

<div class="itens-center-content"> 



    <div class="itens-card-info"> 
        <div class="itens-card-content"> 
            <div class="img-icon"> 
                <img src="./icons/admin.png" class="icon_dash one">

            </div>

            <div class="description-card">       
                <p class="number_card text_one"><?php $admin->setListaTotalAdmin($adminNome)?> </p>
                <p class="info_text_card"> Admins</p>
            </div> <!-- Description card-->
        </div> <!-- itens card conten-->
    </div> <!-- itens card info-->

    <div class="itens-card-info"> 
        <div class="itens-card-content"> 
            <div class="img-icon"> 
                <img src="./icons/user.png" class="icon_dash two">
            </div>

            <div class="description-card">       
                <p class="number_card text_two"><?php $admin->setTotalUsuariosCadastrado();?> </p>
                <p class="info_text_card"> Usuarios cadastrado</p>
            </div> <!-- Description card-->
        </div> <!-- itens card conten-->
    </div> <!-- itens card info-->

    <div class="itens-card-info"> 
        <div class="itens-card-content"> 
            <div class="img-icon"> 
                <img src="./icons/form.png" class="icon_dash three">

            </div>

            <div class="description-card">       
                <p class="number_card text_three"> 15 </p>
                <p class="info_text_card"> Formularios Preenchidos</p>
            </div> <!-- Description card-->
        </div> <!-- itens card conten-->
    </div> <!-- itens card info-->

    <div class="itens-card-info"> 
        <div class="itens-card-content"> 
            <div class="img-icon"> 
                <img src="./icons/online.png" class="icon_dash four">

            </div>

            <div class="description-card">       
                <p class="number_card text_four"> <?php $admin->setVerTodosUsuariosOnline(); ?> </p>
                <p class="info_text_card">Admin(s) Online</p>
            </div> <!-- Description card-->
        </div> <!-- itens card conten-->
    </div> <!-- itens card info-->

    <div class="itens-card-info"> 
        <div class="itens-card-content"> 
            <div class="img-icon"> 
                <img src="./icons/offline.png" class="icon_dash five">
            </div>

            <div class="description-card">       
                <p class="number_card text_five"><?php $admin->setVerTodosUsuariosOffline(); ?> </p>
                <p class="info_text_card"> Admins(s) Offline</p>
            </div> <!-- Description card-->
        </div> <!-- itens card conten-->
    </div> <!-- itens card info-->

    <div class="itens-card-info"> 
        <div class="itens-card-content"> 
            <div class="img-icon"> 

                <img src="./icons/banned.png" class="icon_dash six">
            </div>

            <div class="description-card">       
                <p class="number_card text_six"><?php $admin->setUsuariosBanidos(); ?> </p>
                <p class="info_text_card"> Usuario(s) banido</p>
            </div> <!-- Description card-->
        </div> <!-- itens card conten-->
    </div> <!-- itens card info-->


  

</div> <!-- base itens center-->



</section>


<form action="../controller/admin_controller/cadastrar_admin.php" method="POST">

<h2> Cadastrar Admin</h2>

<div class="form-group">
  <label for="usuario">Nome:</label>
  <input type="text" id="usuario" name="usuario" required aria-labelledby="usuario">
</div>
<div class="form-group">
  <label for="senha">Senha:</label>
  <input type="password" id="senha" name="senha" required aria-labelledby="usuario">
</div>

<select name="nivel" class="select">
  <option value='1'>1- Nivel Normal</option>
  <option value='2'>2- Nivel Intermediario</option>
  <option value='3'>3- Nivel Supremo</option>

  </select>
<button class="cadastrar_admin">Cadastrar</button>


</form>
