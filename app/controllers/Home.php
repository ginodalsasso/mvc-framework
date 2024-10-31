<?php 

    class Home extends Controller {

        public function index() {

            $model = new Model;
            $arr['name'] = 'Mary';
            
            $result = $model->update(2, $arr);
            show($result);

            $this->view("home");
        }

    }
