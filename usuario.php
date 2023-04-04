<?php

session_start();

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "model/Usuario.php";
require_once "configs/utils.php";
require_once "configs/methods.php";
require_once "model/Playlist.php";
require_once "model/Musica.php";


if (isMetodo("POST")) {

    //verifica se está logado
    if (parametrosValidos($_SESSION, ["idUsuario"])) {
        responder(400, ["status" => "Você já está logado! ;)"]);
    }

    //realiza login de usuário
    if (parametrosValidos($_POST, ["email", "senha", "login"])) {
        $email= $_POST["email"];
        $senha = $_POST["senha"];

        if (!Usuario::existeUsuarioEmail($email)) {
            responder(400, ["status" => "Usuário não existe! :("]);
        }

       
        $resultado = Usuario::login($email, $senha);
        if (!$resultado) {
            
            responder(401, ["status" => "Usuário ou senha inválidos! :("]);
        }
       
        $_SESSION["idUsuario"] = $resultado;
        responder(200, ["status" => "Seja bem-vindo! :)"]);
    }

    //cadastra um novo usuário
    if (parametrosValidos($_POST, ["nome", "email", "senha"])) {
        $nome = $_POST["nome"];
        $email= $_POST["email"];
        $senha = $_POST["senha"];

        if (!Usuario::existeUsuarioEmail($email)) {
           
            if (Usuario::cadastrar($nome, $email, $senha)) {
                $msg = ["status" => "Cadastro de usuário com sucesso! :)"];
                responder(201, $msg);
            } else {
                $msg = ["status" => "Cadastro não pode ser realizado! :("];
                responder(500, $msg);
            }
        } else {
            $msg = ["status" => "Usuário já existe! ;)"];
            responder(400, $msg);
        }
    }

    responder(400, ["status" => "Erro de operação"]);
}

if (isMetodo("GET")) {
    //realiza logout
    if (parametrosValidos($_GET, ["logout"])) {
        session_destroy();
        responder(200, ["status" => "Logout efetuado com sucesso, até mais! :)"]);
    }

    //lista todos os usuários
    $resultado = Usuario::getUsuarios();
    responder(200, $resultado);

}

if(isMetodo("DELETE")){


        $idusuario = $_SESSION["idUsuario"];
        

        Playlist::deletar_playlist_idusuario($idusuario);
        Playlist::deletar_todas_playlists($idusuario);
        Musica::deletar_musica($idusuario);
        $resultado = Usuario::deletar_usuario($idusuario);
        if($resultado) {
            session_destroy();
            responder(200,["status" => "Deu Certo"]);
        } else {
            responder(500,["status" => "Erro "]);
           
    
    }
    responder(400, ["status" => "Erro de operação"]);
    

} 

if (isMetodo("PUT")) {
    if (parametrosValidos($_PUT, ["nome", "senha"])) {
        $idUsuario = $_SESSION["idUsuario"]; 
        $nome = $_PUT["nome"];
        $senha = $_PUT["senha"];

        if (Usuario::existeId($idUsuario)) {
            if (Usuario::editar_usuario($nome, $senha, $idUsuario)) {
                $msg = ["status" => "Usuario editado com sucesso!"];
                responder(200, $msg);
            } else {
                $msg = ["status" => "Não foi possível realizar a edição do Usuário!"];
                responder(400, $msg);
            }
        } else {
            $msg = ["status" => "Não existe o Usuário"];
            responder(400, $msg);
        }
    }
}
