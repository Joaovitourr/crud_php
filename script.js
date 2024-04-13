const criarUsuario = () => {
      
    const btn = document.querySelector("button");
    const regex = /[!@#$%^&*()_+{}|:"<>?[\]\;',./\\`~]/;


    btn.addEventListener("click", (e) => {

        e.preventDefault();

        const nome = document.getElementById("username").value;
        const password = document.getElementById("password").value;
  
        if(nome === "" || password === ""){
            alert("Preencha corretamente");
            return;
        } 
        if(password < 5){
            alert("Senha curta")
            return;
        }

        if(regex.test(nome)){
            alert("Caracteres especiais nao sao permitidos");
            return;
        }
 
        let dados = {
            usuario: nome, 
            senha: password
        }


        // fetch("./controller/cadastrar_usuario.php",  {
        fetch("../login/controller/admin_controller/cadastrar_usuario.php",  {
            method: "POST",
            headers: {"Content-type": "application/json"},
            body: JSON.stringify(dados)
        }).then(response => {
            if(!response.ok){
                throw new Error("Erro");
            } 
            return response.json();
        }).then(data => {
            console.log(data);
        })

})}

criarUsuario();
