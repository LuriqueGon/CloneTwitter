<?php 
    namespace App\Models;
    use MF\Model\Model;

    class UsuarioSeguir extends Model{

        private $id_usuario;
        private $id_seguindo;

        public function __get($attr){
            return $this->$attr;
        }

        public function __set($attr, $value){
            return $this->$attr = $value;
        }

        public function seguirUsuario(){
            $query = "INSERT INTO seguidores(id_usuario, id_seguindo) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $this->__get('id_usuario'));
            $stmt->bindValue(2, $this->__get('id_seguindo'));
            $stmt->execute();
            return true;
        }
        public function DeixarSeguirUsuario(){
            $query = "DELETE FROM seguidores WHERE id_usuario = ? AND id_seguindo = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $this->__get('id_usuario'));
            $stmt->bindValue(2, $this->__get('id_seguindo'));
            $stmt->execute();
            return true;
        }

        public function getSeguidores(){
            $query = "SELECT COUNT(*) as seguidores FROM seguidores WHERE id_seguindo = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $this->__get('id_usuario'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC)['seguidores'];
        }

        public function getSeguindo(){
            $query = "SELECT COUNT(*) as seguindo FROM seguidores WHERE id_usuario = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $this->__get('id_usuario'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC)['seguindo'];
        }
        

    }
    


?>