<?php

    namespace Thunder;

    defined('ROOTPATH') OR exit('Access Denied!');

    class {CLASSNAME} extends Migration {

        use Migration; // 


        public function up() {  
            // Méthodes autorisées
            /*
            $this->addColumn();
            $this->addPrimaryKey();
            $this->addUniqueKey();
            // $this->addForeignKey();

            $this->addData();
            $this->insertData();
            $this->createTable();
            */

            /** Creer une table **/
            $this->addColumn('id int(11) NOT NULL AUTO_INCREMENT');
            $this->addColumn('date_created datetime NULL');
            $this->addColumn('date_updated datetime NULL');
            $this->addPrimaryKey('id');

            $this->createTable('{classname}');

            /** insert data **/
            $this->addData('date_created',date("Y-m-d H:i:s"));
            $this->addData('date_updated',date("Y-m-d H:i:s"));

            $this->insertData('{classname}');
        }


        public function down() {
            $this->dropTable('{classname}');
        }
        
    }