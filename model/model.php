<?php 


class Usuario{

    private $username = "joao";
    private $password = "1234";
    private $usuario;
    private $senha;
    
    protected $conn;

    public function __construct()
    {

    
       try{  
        $this->conn = new PDO('mysql:host=localhost;dbname=db_user', $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

       }catch(PDOException $erro){
        echo "Erro". $erro->getMessage();
       }
         

        
    }


    // Funcao para cadastrar o usuario
    // Primeiro verifica se ja existe no banco de dados
   // se existir informa que ja existe
   // Se nao existir cadastra.

    private function cadastrar_Usuario($usuario, $senha){

          

        try{

            $sqlVerificar = "SELECT usuario FROM usuario WHERE usuario = :usuario";
            $stmtVerificar = $this->conn->prepare($sqlVerificar);
            $stmtVerificar->bindParam(":usuario", $this->usuario, PDO::PARAM_STR);
            $stmtVerificar->execute();
            $resposta = $stmtVerificar->fetch(PDO::FETCH_ASSOC); 

   
            if($resposta !== false){
              
                echo json_encode(array("usuario_existe" => "Usuario ja existe"));
                exit();
               
            } else {
           
                $senhaHash = password_hash($this->senha, PASSWORD_DEFAULT);
                $sqlVerificar = "INSERT INTO usuario (usuario, senha) VALUES(:usuario, :senha)";
                $stmtVerificar = $this->conn->prepare($sqlVerificar);
                $stmtVerificar->bindParam(":usuario", $this->usuario, PDO::PARAM_STR);
                $stmtVerificar->bindParam(":senha", $senhaHash, PDO::PARAM_STR);
                $stmtVerificar->execute();
                echo json_encode(array("usuario_cadastrado" => "Usuario Cadastrado"));

            }


        }catch(PDOException $erro){
        
            echo "Erro ao cadastrar Usuario" . $erro->getMessage();
        }

    }

    public function cadastrar($nome, $senha){
        $this->cadastrar_Usuario($nome, $senha);
    }



    // Validando o login do usuario
    // Detalhe que se o usuario estiver com o presence (parte do banimento)
    // em "block", o acesso é negado
    private function logar($usuario, $senha) {
        
        try { 
       $usuario = htmlspecialchars($usuario);
         $sql = "SELECT id, usuario, senha, presence  FROM usuario WHERE usuario = :usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
            $stmt->execute();
    
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if($res && $res['usuario'] === $usuario && password_verify($senha, $res['senha'])) {
               

                if($res['presence'] === "block"){
                    echo json_encode(array("block" => "Seu acesso foi bloqueado"));
                    exit();
                } else {
                    session_start();
                    $_SESSION['usuario'] = $usuario;
                     $_SESSION['logged'] = true;
                     $this->setUsuarioOnline($res['id']);
                    echo json_encode(array(
                    "access" => "Acesso liberado", "user" => $usuario));

                }
                

            } else {
                echo json_encode(array("denied" => "Acesso negado"));
                exit();
            }
        } catch(PDOException $erro) {
            echo "Erro: " . $erro->getMessage();
        }
    }
    
    public function validarLogin($usuario, $senha) {
        $this->logar($usuario, $senha);
    }

    
    // Fechar a sessao e informar que o usuario esta offline
    public function desconectar($usuario){


        session_destroy();
        $this->setUsuarioOffline($usuario);
       echo json_encode(array("destroy" => "Destruindo"));   
      
    }

    // Funcao para verificar se o usuario esta online
    private function usuarioOnline($id){
        try{

            $id = filter_var($id, FILTER_VALIDATE_INT);
            $sql = "UPDATE usuario set user_status = :user_status 
            WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->bindValue("user_status", "online");
            $stmt->execute();

        }catch(PDOException $erro){
            echo "Erro" . $erro->getMessage();
        }
    }
   
    public function setUsuarioOnline($id){
        $this->usuarioOnline($id);
    }

    private function usuarioOffline($usuarioOffline){

        try{
         
            $sql = "UPDATE usuario set user_status = :user_status 
            WHERE usuario = :usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":usuario", $usuarioOffline, PDO::PARAM_STR);
            $stmt->bindValue(":user_status", "offline");
            $stmt->execute();

        }catch(PDOException $erro){
            echo "Erro" . $erro->getMessage();
        }
    }

public function setUsuarioOffline($id){
    $this->usuarioOffline($id);
}

    

}

class Admin extends Usuario{

    private $nome;
    private $senha;

    public function __construct()
    {
        parent::__construct();
    }


    // Validando o acesso do administrador
    private function AdminAcesso($nome, $senha){

    

        try{
            $nome = mb_strtolower($nome);
            $senha = htmlspecialchars($senha);
            $sql = "SELECT id, nome, senha, nivel, adm_block FROM administrador WHERE nome
             = :nome AND senha  = :senha";
        $stmt =  $this->conn->prepare($sql);
        $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
        $stmt->bindParam(":senha", $senha, PDO::PARAM_STR);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if($res && $res['nome'] === $nome && $res['senha'] === $senha){

            session_start();
            $_SESSION['admin'] = true; 
            $_SESSION['admin_name'] = $nome;
            $_SESSION['info_blocked'] = $res['adm_block'];
            $usuarioNivel = $res['nivel'];
            $this->setAdminOnline($nome);

     
         
           
            $cargos = [
                0 => "Admin suspenso",
                1 => "Admin iniciante",
                2 => "Admin intermediario",
                3 => "Admin Supremo",
              ];
              
              $_SESSION['admin_cargo'] = $cargos[$usuarioNivel];
              $_SESSION['admin_nivel'] = $usuarioNivel;
              
              if ($usuarioNivel === 3) {
                $_SESSION['id'] = $res['id'];
              }

            echo json_encode(array("success" => "sucesso"));
 



        }  else {
            echo json_encode(array("error" => "Usuario/Senha invalidos"));
        }



        }catch(PDOException $erro){
            
            echo json_encode(array("erro" => $erro->getMessage()));
        }
                

    }

    public function autorizarAdmin($nome, $senha){
    $this->AdminAcesso($nome, $senha);
 
    }
 
    
    private function adminOnline($nome) {
        try {
            $sql = "UPDATE Administrador SET user_status = :user_status WHERE nome = :nome";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":user_status", "online");
            $stmt->bindParam(":nome", $nome);
            $stmt->execute();
        } catch (PDOException $erro) {
            echo "Erro: " . $erro->getMessage();
        }
    }

    public function setAdminOnline($nome) {
        $this->adminOnline($nome);
    }

    private function adminOffline($nomeUsuario) {



        try {
            $sql = "UPDATE Administrador SET user_status = :user_status WHERE nome = :nome";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nome", $nomeUsuario);
            $stmt->bindValue(":user_status", "offline");
            $stmt->execute();
        } catch (PDOException $erro) {
            echo "Erro: " . $erro->getMessage();
        }
    }

    public function setAdminOffline($nomeUsuario){
        $this->adminOffline($nomeUsuario);
    }

  

    private function editarUsuario($id, $usuarioNome){


        try{

            $id = filter_var($id, FILTER_VALIDATE_INT);
            $usuarioNome= htmlspecialchars($usuarioNome);
            $sql = "UPDATE usuario SET usuario = :usuario WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":usuario", $usuarioNome, PDO::PARAM_STR);
            $stmt->execute();
            echo "Alteração bem sucedida";

        }catch(PDOException $erro){
           echo "erro". $erro->getMessage();
        }

    }

    public function setEditarUsuario($id, $nome){
        $this->editarUsuario($id, $nome);
    }

    // Somente administradores nivel 1 podem solicitar que seu nivel seja
    // alterado para ter mais previlegios.
    private function administradorSolicitaAlteracaoDeNivel($nome,  $nivelSolicitado, $mensagem){

        try{

          $sqlVerificar = "SELECT nome from admin_solicitacao where nome =:nome";
          $stmtVerificar = $this->conn->prepare($sqlVerificar);
          $stmtVerificar->bindParam(":nome", $nome);
          $stmtVerificar->execute();
          $resultadoVerificacao = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

            
          if($resultadoVerificacao !== false){
            echo "Voce ja tem uma solicitação em aberto";
            header("Location: ../../view/dash.php");
            exit();

          }else {
 
            
            $nome = htmlspecialchars($nome);
            $mensagem = htmlspecialchars($mensagem);
            $nivelSolicitado = filter_var($nivelSolicitado, FILTER_VALIDATE_INT);
        
       $sql = "INSERT INTO admin_solicitacao (nome, mensagem, nivel_solicitado)
        VALUES (:nome, :mensagem, :nivel_solicitado);
       "; 
       $stmt = $this->conn->prepare($sql);
       $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
       $stmt->bindParam(":mensagem", $mensagem, PDO::PARAM_STR);
       $stmt->bindParam(":nivel_solicitado", $nivelSolicitado, PDO::PARAM_INT);
       $stmt->execute();
       header("Location: ../../view/dash.php");


          }


        
     
                   
           
       
       
        }catch(PDOException $erro){
            echo "erro" . $erro->getMessage();
        }


    }
    
    public function setAdministradorSolicitaAlteracaoDeNivel($nome,  $nivelSolicitado, $mensagem){
        $this->administradorSolicitaAlteracaoDeNivel($nome,  $nivelSolicitado, $mensagem);
    }
    
   
}



class AdminIntermediario extends Admin {
    
     
    private function MostrarDadosAdmIntermediarios(){

        try{
            $sql = "SELECT id, usuario FROM usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach($res as $resultado){
                $id = $resultado['id'];
                $usuarioNome = $resultado['usuario'];
                echo $usuarioNome;
                
                echo "<div class='users_crud'>    
             
               <div class='user_edit'>  
               <a href='../controller/editar_usuario.php?id=$id'>
                Editar </a> </div>
               
                </div>";
            }
        
            }catch(PDOException $erro){
          echo "erro" . $erro->getMessage();
            }
    }

    public function AdminIntermediario(){
        $this->MostrarDadosAdmIntermediarios();
    }


  


}


class SuperAdmin extends Admin{

    

    // funcao para mostrar todos os administradores
 
    private function MostrarTodosUsuarios(){

        try{

           $sql = "SELECT id, usuario, senha, presence FROM usuario";
           $stmt = $this->conn->prepare($sql);
           $stmt->execute();
           $resposta = $stmt->fetchAll(PDO::FETCH_ASSOC);

           echo "<div class='itens'>";


           foreach($resposta as $usuarios){
            $id = $usuarios["id"];
            $usuarioNome = $usuarios['usuario'];

            echo "<div class='users_box'> 
            <p class='usuario_nome'> $usuarioNome </p>
            <div class='user_edit'>  
                <a href='../../controller/admin_controller/editar_usuario.php?id=$id&nome=$usuarioNome'> 
                    Editar 
                </a>
            </div> 
             
            <div class='user_remove'> 
                <a href='../../controller/admin_controller/admin_excluir_usuario.php?id=$id'> 
                    Apagar 
                </a>
            </div>";

        if ($usuarios['presence'] === "block") {
            echo "<div class='user_status'> 
                    <a href='../../controller/admin_controller/admin_lista_desbloquear.php?id=$id'> 
                        Desbloquear 
                    </a> 
                </div>";
        } else {
            echo "<div class='user_block'> 
                    <a href='../../controller/admin_controller/admin_bloquear_usuario.php?id=$id'> 
                        Bloquear 
                    </a> 
                </div>";
        }

        echo "</div>";

    


            
        }

                   
            
           echo "  <button><a href='../../view/dash.php'> Voltar </a> </button>'";
           echo "</div>";
         
       
        
        
        }  catch(PDOException $erro){
             echo "Erro" . $erro->getMessage();
         }

    }
    
    public function getTodosOsDados(){
        $this->MostrarTodosUsuarios();
    } 

   
   
    // funcao para cadastrar adminstradores

      private function cadastrarAdministrador($nome, $senha, $nivel){


        try{

            $nome = htmlspecialchars($nome);
            $nivel = filter_var($nivel, FILTER_VALIDATE_INT);

            if($nome === false || $nivel === false){
                throw new Exception("Erro de cadastro");
                exit();
            }
   

            $nameLower = $nome; 
            $sql = "INSERT INTO administrador (nome, senha, nivel) 
            VALUES (:nome, :senha, :nivel)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nome", $nameLower, PDO::PARAM_STR);
            $stmt->bindParam(":senha", $senha, PDO::PARAM_STR);
            $stmt->bindParam(":nivel", $nivel, PDO::PARAM_INT);
            $stmt->execute();
       
            
        }catch(PDOException $erro){
               echo "Erro" . $erro->getMessage();
        }
      } 




      public function cadastrarAdmin($nome, $senha, $nivel)
      {
        $this->cadastrarAdministrador($nome, $senha, $nivel);
        
      }

      // Funcao que altera as permissoes de administradores

      public function alterarDadosAdministradores(int $id, string $nome, string $senha){

        try{
             $id = filter_var($id, FILTER_VALIDATE_INT);
             $nome = htmlspecialchars($nome);
             $senha = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "UPDATE administrador SET nome = :nome, senha = :senha WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
            $stmt->bindParam(":senha", $senha, PDO::PARAM_STR);
            $stmt->execute();
            echo "Usuario Alterado";

        }catch(PDOException $e){
            echo "Erro" . $e->getMessage();

        }catch(Throwable $e){
            echo "Exception" . $e->getMessage();
        }

      }

      private function BloquearAcessoAdministrador($nome) {
        try {
            // Verifica se o administrador logado não é 'joao'
            $adminSessaologado = $_SESSION['admin_name'];
            if ($adminSessaologado !== 'sjoao') {
                // Verifica o status do administrador especificado
                $sql = "SELECT user_status FROM Administrador WHERE nome = :nome";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(":nome", $nome);
                $stmt->execute();
                $result = $stmt->fetch();
                
                
                if ($result) {
                    $this->setAdminOffline($nome);
                    session_destroy(); 
                    header("Location: login.php");
                    exit();
                }
            }
        } catch (PDOException $erro) {
            echo "Erro: " . $erro->getMessage();
        }
    }

    public function setBloquearAcessoAdministrador($nome){
        $this->BloquearAcessoAdministrador($nome);
    }


    // Funcao para desbloquear acesso do administrador
      
    public function desbloquearAcessoAdministrador($nome){

        try{

            $sql = "UPDATE administrador set user_status = :user_status
             where nome = :nome";
             $stmt = $this->conn->prepare($sql);
             $stmt->bindParam(":nome", $nome);
             $stmt->bindValue(":user_status", NULL);
             $stmt->execute();

        }catch(PDOException $erro){
            echo "Erro". $erro->getMessage();
        }
    } 





    
      private function excluirUsuario($id){

        try{

            $sql = "DELETE FROM usuario where id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            echo "Usuario Excluido";
 
        }catch(PDOException $erro){
            echo "Erro" . $erro->getMessage();
        }

      }




      public function setExcluirUsuario($id){
        $this->excluirUsuario($id);
      }




      // Funcao para bloquear administrador
      // o parametro $adminquebloqueou é o administrador que baniu 
      // bloqueou o adminstrador (somente admins nivel 3 podem bloquear acesso)


      private function BloquearAdministrador($id, $nivel, $adminQueBloqueou){

        try{

            $id = filter_var($id, FILTER_VALIDATE_INT);
            $nivel = filter_var($nivel, FILTER_VALIDATE_INT);
            $adminQueBloqueou = htmlspecialchars($adminQueBloqueou);

            if($id === false || $nivel === false || $adminQueBloqueou === false){
                throw new Exception("Dados invalidos");
                exit();
            }

            $sql = "UPDATE administrador SET nivel = :nivel, adm_block = :adm_block WHERE id = :id";    
             $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":nivel", $nivel, PDO::PARAM_INT);
            $stmt->bindParam(":adm_block", $adminQueBloqueou, PDO::PARAM_STR);
            $stmt->execute();
            

        }catch (PDOException $e) {
           echo "Erro" . $e->getMessage();
        }
      }

 
      public function setBloquearAdministrador($id, $nivel, $adminQueBloqueou){
        $this->BloquearAdministrador($id, $nivel, $adminQueBloqueou);
      } 





      // Somente admins nivel 3 podem desbloquear outros administradores.

      private function DesbloquearAdministrador($idAdminstrador, $nivelAdminstrador){

        try{

            $idAdminstrador = filter_var($idAdminstrador, FILTER_VALIDATE_INT);
          $nivelAdminstrador = filter_var($nivelAdminstrador, FILTER_VALIDATE_INT);

          if ($idAdminstrador === false || $nivelAdminstrador === false) {
            throw new Exception("ID de administrador ou nível inválido.");
        }


            $sql = "UPDATE administrador set nivel = :nivel, adm_block 
            = :adm_block WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $idAdminstrador, PDO::PARAM_INT);
            $stmt->bindParam(":nivel", $nivelAdminstrador, PDO::PARAM_INT);
            $stmt->bindValue(":adm_block", NULL);
            $stmt->execute();
            

        }catch (PDOException $e) {
           echo "Erro" . $e->getMessage();
        } 
    }





    public function setDesbloquearAdministrador($idAdminstrador, $nivelAdminstrador){
        $this->DesbloquearAdministrador($idAdminstrador, $nivelAdminstrador);
    }


    // somente admins nivel 3 podem desbloquear usuarios.
    private function desbloquearUsuario(int $id){


        try{

            if($id === false){
                throw new Exception("Ocorreu um erro");
                exit();
            }

            $sql = "UPDATE usuario set presence = :presence WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindValue(":presence", "free");
            $stmt->execute();
            
            
        }catch(PDOException $erro){
            echo "Erro" . $erro->getMessage();
        }
    }

    public function setDesbloquearUsuario($id){
        $this->desbloquearUsuario($id);
    }



    // Funcao para mostrar lista de admins bloqueados (somente nivel 3 
    //tem acesso)

    private function listaAdminsBloqueados(){

        try{

            $sql = "SELECT nome, adm_block, nivel FROM 
            administrador where nivel = :nivel";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":nivel", 0);
            $stmt->execute();
            $usuariosBloqueados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<div class='usuarios_bloqueados'>"; 
            

            if (!$usuariosBloqueados) {
                echo "<p>Nenhum usuário Bloqueado</p>";
            } else {
                foreach ($usuariosBloqueados as $user) {
                    echo "<div class='box_blocked'>";
                    $userNomeBloqueado = $user['nome'];
                    $adminQueDeuBloqueio = $user['adm_block'];
                
                    echo "<p class='usuario_block_nome'>Usuario:$userNomeBloqueado</p>";
                    echo "<p class='admin_block_nome'>Admin:$adminQueDeuBloqueio</p>";
                    echo "</div>";
                }
            }
            
            echo "</div>";
            
        }catch(PDOException $erro){

            echo "Erro" . $erro->getMessage();
 

        }

      
    }

    public function setListaAdminsBloqueado(){
        $this->listaAdminsBloqueados();
    }

  
// Funcao para banir usuario
    private function banirUsuario($id){
        try{
            
            $sql = "UPDATE usuario set presence = :presence WHERE
            id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->bindValue(":presence", "block");
            $stmt->execute();
            

        }catch(PDOException $erro){
            echo " Erro" . $erro->getMessage();
        }
    }

    public function setbanirUsuario($id){
        $this->banirUsuario($id);
    }
   

    public function desconectarAdministrador($id) {
        try {
      
            session_start();
    
            if(isset($_SESSION['id']) && $_SESSION['admin_nivel'] === 3) {
                if($_SESSION['id'] !== $id) {
                    unset($_SESSION['admin_cargo']);
                    echo "Usuário desconectado com sucesso.";
                    return;
                }
            }
    
            echo "Erro: Você não tem permissão para desconectar administradores ou o ID fornecido é inválido.";
        } catch(PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    // Funcao para listar todos os administradores 
    // que nao sejam o que esta na sessao (eu)

    private function listaTotalAdmins($admin){

        try{

      
            $sql = "SELECT nome FROM administrador where nome != :nome";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nome", $admin, PDO::PARAM_STR);
            $stmt->execute();
            $resposta = count($stmt->fetchAll(PDO::FETCH_ASSOC));

            echo "<p class='number_card text_one'> $resposta </p>";


        }catch(PDOException $erro){
            echo "Nao foi possivel" . $erro->getMessage();
        }
    }

    public function setListaTotalAdmin($admin){
        $this->listaTotalAdmins($admin);
    }


    private function totalUsuariosCadastro(){
        try{
            $sql = "SELECT usuario, senha FROM usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $totalUsuarios = count($stmt->fetchAll(PDO::FETCH_ASSOC));

            if(!$totalUsuarios){
                echo "0";
            }
             
            echo $totalUsuarios;
        }catch(PDOException $erro){
          echo "Erro" . $erro->getMessage();

        }
    }

    public function setTotalUsuariosCadastrado(){
        $this->totalUsuariosCadastro();
    }


    // Lista de todos administradores bloqueado 
    // o nivel 0 significa que o admin em questao esta 
    // sem seus previlegios.
    private function TodosAdminsBloqueados(){

        try{

            $sql = "SELECT nivel FROM administrador where nivel = :nivel";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":nivel", 0);
            $stmt->execute();
            $totalAdminsBloqueado = count($stmt->fetchAll(PDO::FETCH_ASSOC));
            
            if(!$totalAdminsBloqueado){
                echo "0";
            } 

            echo $totalAdminsBloqueado;

        

        }catch(PDOException $erro){
            echo "erro" . $erro->getMessage();
        }
    }

    public function setTodosAdminsBloqueados(){
        $this->TodosAdminsBloqueados();
    }
    
    // Contador de Usuarios que estao Banidos 
    private function usuariosBanidos(){

        try{

            $sql = "SELECT presence FROM usuario WHERE presence = :presence";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":presence", "block");
            $stmt->execute();
            $usuariosBanidos = count($stmt->fetchAll(PDO::FETCH_ASSOC));

          

          if(!$usuariosBanidos){
            echo "0";
          } else {
            echo $usuariosBanidos;
          }

          
         

        }catch(PDOException $erro){
            echo " Erro" . $erro->getMessage();
        }
    }

    public function setUsuariosBanidos(){
        $this->usuariosBanidos();
    }

    private function verTodosUsuariosOnline(){


        try{
            $sql = "SELECT user_status FROM administrador WHERE 
            user_status = :user_status";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":user_status", "online");
            $stmt->execute();
            $todosUsuariosOnline = count($stmt->fetchAll(PDO::FETCH_ASSOC));
            echo $todosUsuariosOnline;



        }catch(PDOException $erro){
            echo " Erro" . $erro->getMessage();
        }
    }
    public function setVerTodosUsuariosOnline(){
 
         $this->verTodosUsuariosOnline();
    }

    private function verTodosUsuariosOffline(){

        try{
            $sql = "SELECT user_status FROM administrador WHERE 
            user_status = :user_status";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":user_status", "offline");
            $stmt->execute();
            $todosUsuariosOffline = count($stmt->fetchAll(PDO::FETCH_ASSOC));
            
            echo $todosUsuariosOffline;



        }catch(PDOException $erro){
            echo " Erro" . $erro->getMessage();
        }

    }

    public function setVerTodosUsuariosOffline(){
        $this->verTodosUsuariosOffline();
    }

    // Lista de todos administradores (inclusive voce)
    private function MostrarTodosAdmins(){

        try{

            $sql = "SELECT nome, nivel, adm_block, user_status, reason_block FROM 
            administrador";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
           

            echo "<div class='container_content'>"; 
           
    
            if(!$res){
                echo "<div class='user_name'> Nao existe admins </div>";
            } 

            foreach($res as $admin){
                $mensagemPadrao = "Acesso livre";
                $adminNome = $admin['nome'];
                $adminNivel = $admin['nivel'];
          
  
                echo "<div class='container_box'>"; 

                echo " 

                <div class='user_name'> $adminNome </div>
                <div class='user_nivel'>Nivel: $adminNivel </div>
            
                       
                ";
                echo "</div>";

            }

 
            echo "</div>";
     


        }catch(PDOException $erro){
            echo "Erro" . $erro->getmessage();
        }

    
    }

    public function setMostrarTodosAdmins(){
        $this->MostrarTodosAdmins();
    }

    // Lista de todos os usuarios online

    private function MostrarTodosUsuariosOnline(){

        try{

            $sql = "SELECT id, usuario, user_status FROM usuario 
            WHERE user_status = :user_status";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":user_status", "online");
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<div class='container_online'>";
            echo "<div class='user_box'>";

            if(!$res){
                echo "<p class='user_nome'> Nao existe usuarios online </p>";
            }

            foreach($res as $usuarios){
  
                $usuarioNome = $usuarios['usuario'];
                $usuarioStatus = $usuarios['user_status'];

                
                
            echo "
            <div class='user_content'>
            
            <p class='user_nome'> 
              $usuarioNome
            </p>

            <p class='user_status'> 
            $usuarioStatus

            </p>

        

            </div>
           ";

           
            }

            echo "</div>";


        }catch(PDOException $erro){
            echo "erro" . $erro->getMessage();
        }
    }

    public function setMostrarTodosUsuariosOnline(){
        $this->MostrarTodosUsuariosOnline();
    }

    private function mostrarTodosUsuariosBloqueados(){
        try{

            $sql = "SELECT id, usuario, presence FROM usuario WHERE 
            presence = :presence";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue("presence", "block");
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<div class='container_user_blocked'>";
    

            if(!$resultado){
                echo "<p class='user_nome'> Nao existe usuarios bloqueados </p>";
            }

            foreach($resultado as $usuariosBloqueado){
                $userBloqueado = $usuariosBloqueado['usuario'];
                $usuarioId = $usuariosBloqueado['id'];

                echo "<div class='container_content'>";
              echo "         
            <div class='user_nome'> 
            $userBloqueado

           </div>

           <div class='buttons'> 

           <button class='btn_desbloquear'><a href='../../controller/admin_controller/admin_desbloquear_usuario.php?id=$usuarioId'> 
           Desbloquear </a> </button>
           </div>

           </div>

           ";                

            }
   

             echo "<button><a href='../../view/dash.php'> Voltar </a> </button>";

             echo "</div>";
            echo "</div>";

        }catch(PDOException $erro){
            echo "erro" . $erro->getMessage();
        }
    }

    public function setMostrarTodosUsuariosBloqueados(){
     $this->mostrarTodosUsuariosBloqueados();
    }


    //Somente admins que sao nivel 1 podem solicitar o aumento de previlegio
    // quando o admin nivel 1 envia uma solicitacao
    // no painel do lado esquerdo (admin solicitacao) ira mostrar
    // a notificacao do admin que enviou a solicitacao.
    private function mostrarPedidosAdminsCargo(){
        try{

            $sql = "SELECT nome, nivel_solicitado, mensagem FROM admin_solicitacao";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $adminsSolicitacao = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<div class='container_solicitacao'>";
            echo "<div class='content_box'>";

            if(!$adminsSolicitacao){
                echo "<p class='admin_nome'> Nenhuma solicitacao </p>";
            } 

            foreach($adminsSolicitacao as $admin){
              $adminNome = $admin['nome'];
              $adminNivelSolicitado = $admin['nivel_solicitado'];
              $adminMensagem = $admin['mensagem'];

             echo "
              
              <div class='infos'> 

              <p class='admin_nome'>Usuario:  $adminNome  </p>
              <p class='admin_nivel'>Nivel Solicitado:  $adminNivelSolicitado  </p>

      
              <p class='admin_mensagem'>Mensagem:  $adminMensagem  </p>
       


              </div>


             
              <div class='buttons_radio'> 
 
             <button class='btn_aceitar'> <a href='../../controller/admin_controller/admin_aceitar_update_previlegio.php?id=$adminNome&nivel=$adminNivelSolicitado'> Aceitar </a> </button>
             <button class='btn_negar'><a href='../../controller/admin_controller/admin_negar_update_previlegio.php?id=$adminNome'> Negar </button>
              
              </div>
              ";



              echo "</div>";

            }

            echo "</div>";

        }catch(PDOException $erro){
            echo "erro". $erro->getMessage();
        }
    }

    public function setMostrarPedidosAdminsCargo(){
        $this->mostrarPedidosAdminsCargo();
    }

    // Aqui voce pode conceder o pedido do administrador para 
    // aumentar seu nivel (previlegio)
    private function concederPrevilegioAdministrador($admin, $nivel){
        try{

            $admin = htmlspecialchars($admin);
            $nivel = filter_var($nivel, FILTER_VALIDATE_INT);

            $sql = "UPDATE administrador set nivel = :nivel WHERE
            nome = :nome";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nome", $admin, PDO::PARAM_STR);
            $stmt->bindParam(":nivel", $nivel, PDO::PARAM_INT);
            $stmt->execute();
            $this->setRemoverSolicitaçãoAdministrador($admin);



        }catch(PDOException $erro){
            echo "erro" . $erro->getMessage();
        }
    }

    public function setConcederPrevilegioAdministrador($admin, $nivel){
        $this->concederPrevilegioAdministrador($admin, $nivel);
    }


    public function contadorPedidosAdminCargo(){

        try{

            $sql = "SELECT nome FROM admin_solicitacao";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $adminsSolicitacao = count($stmt->fetchAll(PDO::FETCH_ASSOC));

        
            if(!$adminsSolicitacao){
                echo "";
            } else {

                echo $adminsSolicitacao;
            }




            

        }catch(PDOException $erro){
            echo "erro" . $erro->getMessage();
        }
    }


    // Aqui voce nega a solicitacao do admin que quer aumentar seu nivel
    // excluindo sua solicitacao.
    private function removerSolicitaçãoAdministrador($nome){

        try{

            $nome = htmlspecialchars($nome);
            $sql = "DELETE FROM admin_solicitacao WHERE nome = :nome";
             $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
            $stmt->execute();
            
        }catch(PDOException $erro){
            echo "erro" . $erro->getMessage();
        }
    }


    public function setRemoverSolicitaçãoAdministrador($nome){
        $this->removerSolicitaçãoAdministrador($nome);
    }

 

    } 

   

    
