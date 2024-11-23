<?php 

    namespace Controller;

    defined('ROOTPATH') OR exit("Access Denied!");

    // use Model\User; // Importe le modèle User pour l'instancier $user = new User;

    class Register extends AbstractController {

        public function index() {

            $data['user'] = new \Model\User;

            $request = new \Core\Request;
            if($request->posted()){
                $data['user']->signup($_POST);
                
                redirect("login");
            }

            $this->view("register", $data);
        }

    }
