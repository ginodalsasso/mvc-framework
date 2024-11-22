<?php 

    namespace Controller;

    defined('ROOTPATH') OR exit("Access Denied!");

    class Logout extends MainController {

        public function index() {

            $session = new \Core\Session;
            $session->logout();

            redirect("home");
        }
    }
