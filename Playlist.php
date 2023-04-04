<?php
require_once __DIR__ . "/../configs/BancoDados.php";

class Playlist
{

    public static function cadastrar($idUsuario, $idMusica, $nome)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("INSERT INTO playlist (id_usuario, id_musica, nome) VALUES (?,?,?)");
            $stmt->execute([$idUsuario, $idMusica, $nome]);

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function existePlaylist($idUsuario, $idMusica, $nome)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM playlist WHERE id_usuario = ? and id_musica=? and nome=?");
            $stmt->execute([$idUsuario, $idMusica, $nome]);

            if ($stmt->fetchColumn() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function existeNomePlaylist($idUsuario, $nome)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM playlist WHERE id_usuario = ? and nome=?");
            $stmt->execute([$idUsuario, $nome]);

            if ($stmt->fetchColumn() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    //lista as músicas de uma playlist do usuario
    public static function retornaPlaylistUsuario($idUsuario, $nomePlaylist)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT m.* FROM musica m join playlist p WHERE p.id_usuario = ? and m.id = p.id_musica and p.nome = ?");
            $stmt->execute([$idUsuario, $nomePlaylist]);

            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    //lista as playlists de um usuário
    public static function retornaPlaylists($idUsuario)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT distinct nome FROM playlist WHERE id_usuario = ?");
            $stmt->execute([$idUsuario]);

            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    //lista todas as playlists com seus respectivos donos
    public static function retornaPlaylistsDonos()
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT distinct p.nome as playlist, u.nome as usuario FROM playlist p join usuario u on p.id_usuario = u.id");
            $stmt->execute([]);

            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function deletar_playlist($idUsuario, $idMusica, $nome){
        try{
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("DELETE  FROM playlist WHERE id_usuario = ? and id_musica = ? and nome = ?");
            $sql->execute([$idUsuario, $idMusica, $nome]);
            if($sql->rowCount() > 0) {
                return true;
            }else{
                return false;
            }

        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    public static function deletar_todas_playlists($idUsuario){
        try{
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("DELETE  FROM playlist WHERE id_usuario = ? ");
            $sql->execute([$idUsuario]);
            if($sql->rowCount() > 0) {
                return true;
            }else{
                return false;
            }

        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }
    public static function deletar_playlist_idusuario($idUsuario){
        try{
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("delete from playlist where id_musica = (select id from musica where id_usuario = ?)");
            $sql->execute([$idUsuario]);
            if($sql->rowCount() > 0) {
                return true;
            }else{
                return false;
            }

        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }
    public static function deletar_playlist_idmusica($idMusica){
        try{
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("delete from playlist where id_musica = ?");
            $sql->execute([$idMusica]);
            if($sql->rowCount() > 0) {
                return true;
            }else{
                return false;
            }

        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    public static function deletar_playlist_nome_idusuario($nome,$idUsuario){
        try{
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("delete from playlist where nome = ? and id_usuario=?");
            $sql->execute([$nome,$idUsuario]);
            if($sql->rowCount() > 0) {
                return true;
            }else{
                return false;
            }

        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }
   

 
}