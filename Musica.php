<?php
require_once __DIR__ . "/../configs/BancoDados.php";

class Musica
{


    public static function getMusicas()
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM musica");
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function cadastrar($nome, $cantor_banda, $estilo, $idUsuario)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("INSERT INTO musica (nome, cantor_banda, estilo, id_usuario) VALUES (?,?,?,?)");
            $stmt->execute([$nome, $cantor_banda, $estilo, $idUsuario]);

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

    public static function existeMusica($nome, $cantor_banda)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM  musica WHERE nome = ? and cantor_banda=?");
            $stmt->execute([$nome, $cantor_banda]);

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

    public static function existeMusicaId($id)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM musica WHERE id = ?");
            $stmt->execute([$id]);

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

    //lista todas as músicas
    public static function retornaMusicas()
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * from musica;");
            $stmt->execute([]);

            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    //lista todas as playlists do usuário que possuem essa música
    public static function retornaPlaylistsMusica($idUsuario, $idMusica)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT distinct p.nome from playlist p join musica m on p.id_musica = m.id where p.id_usuario = ? and p.id_musica = ?");
            $stmt->execute([$idUsuario, $idMusica]);

            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function existeMusicaPlaylist($idMusica, $idUsuario)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM musica m join playlist p on m.id = p.id_musica WHERE m.id = ? and p.id_usuario = ?");
            $stmt->execute([$idMusica, $idUsuario]);

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

    public static function deletar_musica($idUsuario){
        try{
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("delete from musica where id_usuario = ?");
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

    public static function deletar_musica_id($idUsuario,$idMusica){
        try{
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("delete from musica where id_usuario = ? and id=?");
            $sql->execute([$idUsuario,$idMusica]);
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
    public static function editar_musica($estilo, $idMusica, $nome, $cantor_banda, $idUsuario){
        try{
            $conexao = Conexao::getConexao(); 
            $sql = $conexao->prepare("UPDATE musica set estilo=? where id = ? and nome=? and cantor_banda=? and id_usuario=?");
            $sql->execute([$estilo, $idMusica, $nome, $cantor_banda, $idUsuario]);

            if($sql->rowCount()>0){
                return true;
            }else{
                return false;
            }
            
        }catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
    public static function existeId($idMusica, $cantor_banda, $nome){
    {
        try {
            $conexao = Conexao::getConexao(); 
            $sql = $conexao->prepare("SELECT count(*) from musica where id = ? and cantor_banda=? and nome=?");
            $sql->execute([$idMusica, $cantor_banda, $nome]);
    
            $quantidade = $sql->fetchColumn();
            if ($quantidade > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
}
}