<?php
    /**
     * Pagination class
     */

        /** 
         * Instanciaition de la classe Pagination dans le namespace Core 
         * ex: $Pagination = new Core\Pagination();
         */
        namespace Core; 

        defined('ROOTPATH') OR exit("Access Denied!");

        class Pagination {

            public $links = array();           // Tableau pour stocker les liens de navigation (première page, page actuelle, page suivante)
            public $offset = 0;                // Offset calculé pour les requêtes SQL (commencement des enregistrements)
            public $page_number = 1;           // Numéro de la page actuelle
            public $start = 1;                 // Numéro de la première page visible dans la pagination
            public $end = 1;                   // Numéro de la dernière page visible dans la pagination
            public $limit = 10;                // Nombre d'éléments à afficher par page
            public $nav_class = '';            
            public $ul_class = 'pagination justify-content-center'; 
            public $li_class = 'page-item';   
            public $a_class = 'page-link';     
        
            /**
             * Constructeur de la classe Pagination.
             * @param int $limit Nombre d'éléments par page (par défaut : 10)
             * @param int $extras Nombre de pages supplémentaires à afficher autour de la page actuelle (par défaut : 1)
             */
            public function __construct($limit = 10, $extras = 1) {
                
                // Récupère le numéro de la page dans l'URL (paramètre GET "page"), ou utilise 1 par défaut
                $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
                // Si le numéro de la page est inférieur à 1, on le remplace par 1 (sécurité)
                $page_number = $page_number < 1 ? 1 : $page_number;
        
                // Définit les pages de début et de fin de la pagination en fonction du numéro de page et des extras
                $this->end = $page_number + $extras;
                $this->start = $page_number - $extras;
        
                // Si le début de la pagination est inférieur à 1, on le remet à 1
                if ($this->start < 1) {
                    $this->start = 1;
                }
        
                // Calcul de l'offset pour les requêtes SQL (nombre d'éléments à sauter pour chaque page)
                $this->offset = ($page_number - 1) * $limit;
        
                // Stocke le numéro de la page actuelle et la limite dans les propriétés de la classe
                $this->page_number = $page_number;
                $this->limit = $limit;
        
                // Récupère l'URL de la page actuelle pour créer les liens de pagination
                $url = isset($_GET['url']) ? $_GET['url'] : '';
        
                // Génère l'URL de la page courante en nettoyant les paramètres superflus et en ajoutant le numéro de page si absent
                $current_link = ROOT . "/" . $url . "?" . trim(str_replace("url=", "", str_replace($url, "", $_SERVER['QUERY_STRING'])), "&");
                $current_link = !strstr($current_link, "page=") ? $current_link . "&page=1" : $current_link;
        
                // Si aucun point d'interrogation n'est présent dans l'URL, ajuste pour ajouter les paramètres correctement
                if (!strstr($current_link, "?")) {
                    $current_link = str_replace("&page=", "?page=", $current_link);
                }
        
                // Crée le lien vers la première page en remplaçant le numéro de page actuel par 1
                $first_link = preg_replace("/page=[0-9]*/", "page=1", $current_link);
        
                // Crée le lien vers la page suivante en incrémentant le numéro de page
                $next_link = preg_replace("/page=[0-9]*/", "page=" . ($page_number + $extras + 1), $current_link);
        
                // Stocke les liens dans le tableau $links pour utilisation dans l'affichage
                $this->links['first'] = $first_link;
                $this->links['current'] = $current_link;
                $this->links['next'] = $next_link;
            }
        
            /**
             * Affiche le code HTML de la navigation de pagination.
             * @param int|null $record_count Nombre total d'éléments enregistrés (optionnel, par défaut : $limit)
             */
            public function display($record_count = null) {
                // Si le nombre d'enregistrements n'est pas fourni, utilise la limite par défaut
                if ($record_count == null) {
                    $record_count = $this->limit;
                }
        
                // Affiche la pagination uniquement s'il y a assez d'enregistrements pour paginer ou si on n'est pas sur la première page
                if ($record_count == $this->limit || $this->page_number > 1) {
                    ?>
                    <br class="clearfix">
                    <div>
                        <nav class="<?= $this->nav_class ?>">
                            <ul class="<?= $this->ul_class ?>">
                                <!-- Lien vers la première page -->
                                <li class="<?= $this->li_class ?>">
                                    <a class="<?= $this->a_class ?>" href="<?= $this->links['first'] ?>">First</a>
                                </li>
        
                                <!-- Boucle pour afficher les numéros de pages -->
                                <?php for ($x = $this->start; $x <= $this->end; $x++): ?>
                                    <li class="<?= $this->li_class ?><?= ($x == $this->page_number) ? ' active' : '' ?>">
                                        <!-- Lien vers une page spécifique -->
                                        <a class="<?= $this->a_class ?>" href="<?= preg_replace('/page=[0-9]+/', "page=" . $x, $this->links['current']) ?>"><?= $x ?></a>
                                    </li>
                                <?php endfor; ?>
        
                                <!-- Lien vers la page suivante -->
                                <li class="<?= $this->li_class ?>">
                                    <a class="<?= $this->a_class ?>" href="<?= $this->links['next'] ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <?php
                }
            }
        }