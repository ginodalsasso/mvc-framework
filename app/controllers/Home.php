<?php 

    class Home extends Controller {

        public function index() {
            echo "This is the index method of the Home controller";
            $this->view("home");
        }

    }
