<?php


session_start();

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "model/Musica.php";
require_once "configs/utils.php";
require_once "configs/methods.php";

if (!parametrosValidos($_SESSION, ["idUsuario"])) {
    responder(401, ["status" => "Você deve estar logado para acessar esta página! ;)"]);
}



if (isMetodo("POST")) {
    if (parametrosValidos($_POST, ["nome", "cantor_banda", "estilo"])) {
        $nome = $_POST["nome"];
        $cantor_banda = $_POST["cantor_banda"];
        $estilo = $_POST["estilo"];

        $idUsuario = $_SESSION["idUsuario"];

        if (!Musica::existeMusica($nome, $cantor_banda)){
            if (Musica::cadastrar($nome, $cantor_banda, $estilo, $idUsuario)) {
                responder(201, ["status" => "Música cadastrada com sucesso! :)"]);
            } else {
                 responder(500, ["status" => "Música não pode ser cadastrada! :("]);
            }
        }else{
            $msg = ["status" => "Música já existe! ;)"];
            responder(400, $msg);
        }
        
       
    }

}

if (isMetodo("GET")) {
    //lista todas as playlists que possuem essa música (as playlists do usuário logado)
    if (parametrosValidos($_GET, ["idMusica"])){
        $idMusica = $_GET["idMusica"];
        $idUsuario = $_SESSION["idUsuario"];

        if(Musica::existeMusicaPlaylist($idMusica, $idUsuario)){
            $resultado = Musica::retornaPlaylistsMusica($idUsuario, $idMusica);
            responder(200, $resultado);
        } else{
            $msg = ["status" => "Música não existe em nenhuma playlist sua :("];
            responder(400, $msg);
        }
    }


    //lista todas as músicas
    $resultado = Musica::retornaMusicas();
    responder(200, $resultado);
    
}


if(isMetodo("DELETE")){
    if(parametrosValidos($_DELETE,["idMusica"])){
        $idUsuario=$_SESSION["idUsuario"];
        $idMusica =$_DELETE["idMusica"];
        Playlist::deletar_playlist_idmusica($idMusica);
        $resultado=Musica::deletar_musica_id($idUsuario,$idMusica);
        if($resultado){
            responder(200,["status"=>"Deu Certo"]);
        }else{
            responder(400,["status"=>"Deu Certo"]);
        }

    }
    responder(400,["status"=>"Erro de operação"]);

}

if (isMetodo("PUT")) {
    if (parametrosValidos($_PUT, ["estilo", "idMusica", "nome", "cantor_banda"])) {
        $idusuario = $_SESSION["idUsuario"]; 
        $idMusica = $_PUT["idMusica"];
        $estilo = $_PUT["estilo"];
        $nome = $_PUT["nome"]; 
        $cantor_banda = $_PUT["cantor_banda"];

        if (Musica::existeId($idMusica, $cantor_banda, $nome)) {
            if (Musica::editar_musica($estilo, $idMusica, $nome, $cantor_banda, $idusuario)) {
                $msg = ["status" => "Música editada com sucesso!"];
                responder(200, $msg);
            } else {
                $msg = ["status" => "Não foi possível realizar edição da Música!"];
                responder(400, $msg);
            }
        } else {
            $msg = ["status" => "Não existe a Música"];
            responder(400, $msg);
        }
    }
}

