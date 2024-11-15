<?php 

    namespace Controller;

    defined('ROOTPATH') OR exit("Access Denied!");

    // use Model\User; // Importe le modèle User pour l'instancier $user = new User;

    class Login {

        use MainController;

        public function index() {

            $data['user'] = new \Model\User; // Instancie le modèle User sans le use
            $request = new \Core\Request;

            if($request->posted()){
                $data['user']->login($_POST);
            }
            $this->view("login", $data);
        }

    }
