<?php 

    namespace Controller;

    defined('ROOTPATH') OR exit("Access Denied!");

    /**
     * {CLASSNAME} Controller
     */
    class {CLASSNAME} {

        use MainController;

        public function index() {
            
            $this->view("{classname}");
        }

    }
