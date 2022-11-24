<?php 

    namespace App\Models;
    use MF\Model\Model;

    class Usuario extends Model{
        private $id;
        private $nome;
        private $email;
        private $senha;

        public function __get($attr){
            return $this->$attr;
        }

        public function __set($attr, $value){
            return $this->$attr = $value;
        }

        public function salvar(){
            $query = "INSERT INTO usuarios (nome, email, senha) VALUES (?,?,?)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1,$this->__get('nome'));
            $stmt->bindValue(2,$this->__get('email'));
            $stmt->bindValue(3,$this->__get('senha'));
            $stmt->execute();

            return $this;
        }

        public function validarCadastro(){
            $valid = true;

            if(strlen($this->__get('nome')) < 3){
                $valid = false;
            }

            if(strlen($this->__get('email')) < 3){
                $valid = false;
            }

            if(strlen($this->__get('senha')) < 3){
                $valid = false;
            }

            return $valid;
        }

        public function getUserFromEmail(){
            $query = "SELECT nome, email FROM usuarios WHERE email ='" . $this->__get('email') . "'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();       
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ;    
        }

        public function autenticar(){
            $query = "SELECT id, nome, email, senha FROM usuarios WHERE email = ? AND senha = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1,$this->__get('email'));
            $stmt->bindValue(2,$this->__get('senha'));
            $stmt->execute();
            $retorno = $stmt->fetch(\PDO::FETCH_ASSOC);

            if(!empty($retorno['id']) && !empty($retorno['nome'])){
                $this->__set('id', $retorno['id']);
                $this->__set('nome', $retorno['nome']);
                return $this;
            }
        }

        public function getAll($getAll = false){
            if($getAll){
                $query =  "SELECT u.id, u.nome, u.email,(SELECT COUNT(*) FROM seguidores as seg WHERE seg.id_usuario = ? AND seg.id_seguindo = u.id) as seguidores_sn FROM usuarios as u WHERE u.id != ? AND (SELECT COUNT(*) FROM seguidores as seg WHERE seg.id_usuario = ? AND seg.id_seguindo = u.id) = 0 
                "; 

                $stmt = $this->db->prepare($query);

                $stmt->bindValue(1, $this->__get('id'));
                $stmt->bindValue(2, $this->__get('id'));
                $stmt->bindValue(3, $this->__get('id'));
            }else{

                $query =  "SELECT u.id, u.nome, u.email,(SELECT COUNT(*) FROM seguidores as seg WHERE seg.id_usuario = ? AND seg.id_seguindo = u.id) as seguidores_sn FROM usuarios as u WHERE u.nome LIKE ? AND u.id != ?";   

                $stmt = $this->db->prepare($query); 

                $stmt->bindValue(1, $this->__get('id'));
                $stmt->bindValue(2, '%'.$this->__get('nome').'%');
                $stmt->bindValue(3, $this->__get('id'));
            }
            
            
            
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
    }

?>