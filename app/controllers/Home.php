<?php 

    namespace Controller;

    defined('ROOTPATH') OR exit("Access Denied!");

    
    class Home extends AbstractController {

        public function index() {
            
            // Définit le nom de l'utilisateur connecté
            // $data['username'] = empty($_SESSION['USER']) ? "User" : $_SESSION['USER']->email;
            
            $session = new \Core\Session;
                if(!$session->is_logged_in()) // Si l'utilisateur n'est pas connecté
                    redirect("login");

            $this->view("home");
        }

    }
