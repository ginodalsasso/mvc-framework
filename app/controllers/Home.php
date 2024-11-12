<?php 

    namespace Controller;

    defined('ROOTPATH') OR exit("Access Denied!");

    
    class Home {

        use MainController;

        public function index() {
            
            // Définit le nom de l'utilisateur connecté
            $data['username'] = empty($_SESSION['USER']) ? "User" : $_SESSION['USER']->email;

            $this->view("home");
        }

    }
