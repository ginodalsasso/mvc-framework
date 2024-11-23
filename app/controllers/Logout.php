<?php 

    namespace Controller;

    defined('ROOTPATH') OR exit("Access Denied!");

    class Logout extends AbstractController {

        public function index() {

            $session = new \Core\Session;
            $session->logout();

            redirect("home");
        }
    }
