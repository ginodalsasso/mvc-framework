<?php
/**
 * Classe de migration pour {CLASSNAME}.
 *
 * Cette classe gère la création et la suppression de la table `{classname}` dans la base de données.
 * 
 * Méthodes :
 * - up() : Méthode appelée lors de la migration pour créer la table et insérer des données.
 * - down() : Méthode appelée lors de la rétrogradation pour supprimer la table.
 *
 * Exemple d'utilisation :
 * $this->addColumn('id int(11) NOT NULL AUTO_INCREMENT');
 * $this->addColumn('date_created datetime NULL');
 * $this->addColumn('date_updated datetime NULL');
 * $this->addPrimaryKeys('id');
 * $this->createTable('{classname}');
 * $this->addData('date_created', date("Y-m-d H:i:s"));
 * $this->addData('date_updated', date("Y-m-d H:i:s"));
 * $this->insertData('{classname}');
 */

    namespace Thunder;

    defined('ROOTPATH') OR exit('Access Denied!');

    class {CLASSNAME} extends Migration {

        public function up() {  
            // Méthodes autorisées
            $this->addColumn(); // Ajouter une colonne à la table.
            $this->addPrimaryKey(); // Définir une clé primaire pour la table. 
            $this->addUniqueKey(); //  Définir une clé unique pour la table.
            $this->addForeignKey(); // 

            $this->addData(); // Ajouter des données à insérer dans la table.
            $this->insertData(); // Insérer des données dans la table.
            $this->createTable(); // Créer la table.
            
        }


        public function down() {
            $this->dropTable('{classname}'); // Supprimer la table.
        }
        
    }