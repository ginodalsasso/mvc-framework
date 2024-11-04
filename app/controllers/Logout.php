<?php 
    defined('ROOTPATH') OR exit("Access Denied!");

    class Logout {

        use Controller;

        public function index() {
            if(isset($_SESSION['USER']))
                unset($_SESSION['USER']);
            
            redirect("home");
        }
    }
