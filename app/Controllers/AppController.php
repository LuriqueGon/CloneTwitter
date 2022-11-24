<?php 

namespace App\Controllers;
use MF\Controller\Action;
use MF\Model\Container;

    class AppController extends action{

        public function timeline(){

            $this->validAuth();     

            $tweetMenu = $this->tweetMenu();
            $this->view->tweets = $this->getTweets();

            $this->render('timeline');

            
        }

        public function tweet(){
            
            $this->validAuth();              

            $tweet = Container::getModel('Tweet');
            $tweet->__set('tweet', $_POST['tweet']);
            $tweet->__set('id_usuario', $_SESSION['id']);
            $tweet->setTweet();

            header('location: /timeline');
        }

        public function deletarTweet(){
            $tweet = Container::getModel('Tweet');
            $tweet->__set('id', $_POST['tweetId']);
            $tweet->deletarTweet();

            header('location: /timeline');
        }

        public function quemSeguir(){
            $this->validAuth();   

            $pesquisarPor = isset($_GET['pesquisarPor']) ? strtolower($_GET['pesquisarPor']) : '';
            $usuarios = array();

            $usuario = Container::getModel('Usuario');
            $usuario->__set('nome', $pesquisarPor);
            $usuario->__set('id', $_SESSION['id']);

            if(!empty($pesquisarPor)){

                $this->view->usuarios = $usuario->getAll();
            }else{
                $this->view->usuarios = $usuario->getAll(true);
            }

            $this->view->pesquisa = ucfirst($pesquisarPor);
            
            $tweetMenu = $this->tweetMenu();

            $this->render('quemSeguir');
        }

        public function acao(){
            $this->validAuth(); 
            
            $action = isset($_GET['action']) ? $_GET['action'] : '';
            $id_seguindo = isset($_GET['id_usuario_seguir']) ? $_GET['id_usuario_seguir'] : '';

            $usuario = Container::getModel('UsuarioSeguir');
            $usuario->__set('id_seguindo', $id_seguindo);
            $usuario->__set('id_usuario', $_SESSION['id']);

            if($action== 'seguir'){
                if($usuario->seguirUsuario()){
                    header('location: /quem_seguir??pesquisarPor='.$_GET['pesquisarPor']);
                }
            }else if($action == 'deixarDeSeguir'){
                if($usuario->DeixarSeguirUsuario()){
                    header('location: /quem_seguir??pesquisarPor='.$_GET['pesquisarPor']);
                }
            }
        }

        

        
    }

?>