<?php

session_start();

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "model/Usuario.php";
require_once "model/Musica.php";
require_once "model/Playlist.php";
require_once "configs/utils.php";
require_once "configs/methods.php";

if (!parametrosValidos($_SESSION, ["idUsuario"])) {
    responder(401, ["status" => "Você deve estar logado para acessar esta página! ;)"]);
}

if (isMetodo("POST")) {
    if (parametrosValidos($_POST, ["nome", "idMusica"])) {
        $nome = $_POST["nome"];
        $idMusica= $_POST["idMusica"];

        $idUsuario = $_SESSION["idUsuario"];

        if (Usuario::existeUsuarioId($idUsuario) && Musica::existeMusicaId($idMusica)) {
            if (!Playlist::existePlaylist($idUsuario, $idMusica, $nome)) {
                if (Playlist::cadastrar($idUsuario, $idMusica, $nome)) {
                    responder(201, ["status" => "Musica adicionada na playlist com sucesso! :)"]);
                } else {
                    responder(500, ["status" => "Musica não pode ser adicionada na Playlist! :("]);
                }
            }else{
                $msg = ["status" => "Essa música já foi adicionada a essa playlist ;)"];
            responder(400, $msg);
            }
        } else {
            $teste= Musica::existeMusicaId($idMusica);
            if($teste){
                responder(200,["status"=> "Musica existe"]);
            }else{
                responder(404, ["status" => "Musica não existe! :("]);
            }
            
        }
    } else {
        responder(400, ["status" => "Parâmetro 'nome' não foi encontrado para realizar o cadastro! :("]);
    }

}

if(isMetodo("GET")){
    //listar todas as músicas de uma playlist desse usuário (que esteja logado)
    if(parametrosValidos($_GET, ["playlist"])){
        $playlist = $_GET["playlist"];
        $idusuario = $_SESSION["idUsuario"];

        if(Playlist::existeNomePlaylist($idusuario, $playlist)){
            $resultado = Playlist::retornaPlaylistUsuario($idusuario, $playlist);
            responder(200, $resultado);

        } else {
            responder(404, ["status" => "Playlist não existe! :("]);
        }
    }


    //listar todas as playlists desse usuário (que esteja logado)
    if(parametrosValidos($_GET, ["listarplaylists"])){
        
        $idusuario = $_SESSION["idUsuario"];

        $resultado = Playlist::retornaPlaylists($idusuario);
        responder(201, $resultado);
        
    }if(parametrosValidos($_GET, ["mais_ouvida"])){
        $resultado = Playlist::getMaisOuvida();
        responder(200, $resultado);
    } 
    

    //lista todas as playlists com seus respectivos donos
    $resultado = Playlist::retornaPlaylistsDonos();
    responder(201, $resultado);
 
} 

    

if(isMetodo("DELETE")){

if(parametrosValidos($_DELETE, ["idMusica","nome"])){

    $idusuario = $_SESSION["idUsuario"];
    $idMusica = $_DELETE["idMusica"];
    $nome = $_DELETE["nome"];

    $resultado = Playlist::deletar_playlist($idusuario,$idMusica,$nome);
    if($resultado) {
        responder(200,["status" => "Deu Certo"]);
    } else {
        responder(500,["status" => "Erro "]);
    }
    
} if(parametrosValidos($_DELETE,["nome"])){
    $idUsuario=$_SESSION["idUsuario"];
    $nome= $_DELETE["nome"];

    $resultado= Playlist::deletar_playlist_nome_idusuario($idUsuario,$nome);
    if($resultado) {
        responder(200,["status" => "Deu Certo"]);
    } else {
        responder(500,["status" => "Erro "]);
    }

}else  {
    $idusuario = $_SESSION["idUsuario"];
    $resultado = Playlist::deletar_todas_playlists($idusuario);
    if($resultado) {
        responder(200,["status" => "Deu Certo, todas as playlists apagadas"]);
    } else {
        responder(500,["status" => "Erro "]);
    }



}
}


