<?php 

    class Home extends Controller {

        public function index() {

            $user = new User;
            
            $result = $user->findAll();

            show($result);

            $this->view("home");
        }

    }
