<?php 

    namespace App\Controllers;
    use MF\Controller\Action;
    use MF\Model\Container;

    class IndexController extends Action{

        public function index(){

            $this->view->login = isset($_GET['login']) ? $_GET['login'] : false;
            $this->view->erro = isset($_GET['erro']) ? $_GET['erro'] : false;

            $this->render('index');
        }

        public function inscreverSe(){  
            
            $this->view->usuario = [
                'nome' => '',
                'email' => '',
                'senha' => ''
            ]; 

            $this->view->erro = [false];
            $this->render('inscreverse');

        }

        public function registrar(){   
                        
            if($_POST['senha'] == $_POST['reSenha']){

                $usuario = Container::getModel('Usuario');

                $usuario->__set('nome', $_POST['nome']);
                $usuario->__set('email', $_POST['email']);
                $usuario->__set('senha', md5($_POST['senha']));

                if($usuario->validarCadastro() && count($usuario->getUserFromEmail()) == 0){

                    $usuario->salvar();
                    $this->render('cadastro');

                }else{
                    
                    $this->view->usuario = [
                        'nome' => $_POST['nome'],
                        'email' => $_POST['email'],
                        'senha' => $_POST['senha']
                    ];

                    $this->view->erro = [true,'erro' => 0];
                    $this->render('inscreverse');
                    
                }


            }else{
                $this->view->usuario = [
                    'nome' => $_POST['nome'],
                    'email' => $_POST['email'],
                    'senha' => $_POST['senha']
                ];

                $this->view->erro = [true,'erro' => 1];
                $this->render('inscreverse');
            }
        }

    }