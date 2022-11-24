<?php

    namespace MF\Controller;
    use MF\Model\Container;

    abstract class TweetExpansion{
        public function validAuth(){
            session_start();

            if(
                !isset($_SESSION['id']) || !isset($_SESSION['nome'])
                || empty($_SESSION['id']) || empty($_SESSION['nome'])
            ){
                header('location: /?erro=acessRequired');
            }
        }        

        public function getTweets(){
            $tweet = Container::getModel('Tweet');
            $tweet->__set('id_usuario', $_SESSION['id']);
            return $tweet->getAll();
        }

        public function getMyTweets(){

            $tweet = Container::getModel('Tweet');
            $tweet->__set('id_usuario', $_SESSION['id']);
            return $tweet->getMyAll();
        }
        

        public function tweetMenu(){

            $usuarioSeguir = Container::getModel('UsuarioSeguir');
            $usuarioSeguir->__set('id_usuario', $_SESSION['id']);

            $_SESSION['tweets'] = count($this->getMyTweets());
            $_SESSION['seguidores'] = $usuarioSeguir->getSeguidores();
            $_SESSION['seguindo'] = $usuarioSeguir->getSeguindo();
        }
        
    }