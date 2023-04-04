<?php
require_once __DIR__ . "/../configs/BancoDados.php";

class Usuario
{


    public static function getUsuarios()
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM usuario");
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function cadastrar($nome, $email, $senha)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?,?,?)");

            $senha = password_hash($senha, PASSWORD_BCRYPT);
            $stmt->execute([$nome, $email, $senha]);

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

    public static function existeUsuarioEmail($email)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM usuario WHERE email = ?");
            $stmt->execute([$email]);

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

    public static function existeUsuarioId($id)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT COUNT(*) FROM usuario WHERE id = ?");
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

    public static function login($email, $senha)
    {
        try {
            $conexao = Conexao::getConexao();
            $stmt = $conexao->prepare("SELECT * FROM usuario WHERE email = ?");
            $stmt->execute([$email]);
            $resultado = $stmt->fetchAll();

            if (count($resultado) != 1) {
                return false;
            }

            if (password_verify($senha, $resultado[0]["senha"])) {
                return $resultado[0]["id"];
            } else {
                return False;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function deletar_usuario($idUsuario){
        try{
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("DELETE  FROM usuario WHERE id = ?");
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
 
    public static function editar_usuario($nome, $senha, $id){
        try{
            $conexao = Conexao::getConexao(); 
            $sql = $conexao->prepare("UPDATE usuario set nome=?, senha=? where id=?");
           
            $senha = password_hash($senha, PASSWORD_BCRYPT);
            $sql->execute([$nome, $senha, $id]);

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

    public static function existeId($id){
        {
            try {
                $conexao = Conexao::getConexao(); 
                $sql = $conexao->prepare("SELECT count(*) from usuario where id = ?");
                $sql->execute([$id]);
        
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