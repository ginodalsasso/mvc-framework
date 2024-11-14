<?php 

    namespace Controller;

    defined('ROOTPATH') OR exit("Access Denied!");

    class Logout {

        use MainController;

        public function index() {

            $session = new \Core\Session;
            $session->logout();

            redirect("home");
        }
    }
