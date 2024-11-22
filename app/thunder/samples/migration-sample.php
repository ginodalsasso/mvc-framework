<?php

    //$user = new Migration\{CLASSNAME};
    namespace Migration;

    defined('ROOTPATH') OR exit('Access Denied!');

    class {CLASSNAME} {

        use Migration; // 


        public function up() {  
            // Méthodes autorisées
            /*
            $this->addColumn();
            $this->addPrimaryKey();
            $this->addUniqueKey();
            // $this->addForeignKey();

            $this->addData();
            $this->insert();
            $this->createTable();
            */
        }


        public function down() {
            $this->dropTable('{classname}');
        }
        
    }