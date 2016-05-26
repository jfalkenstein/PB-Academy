<?php
require_once 'Requires/repos.php';
/**
 * This Registry creates a repository registry to map actual repositories to their
 * respective interfaces. This allows for easier abstraction and less reliance upon
 * Joomla's db api.
 * 
 * NOTE: THIS NEEDS TO BE CONVERTED TO AN IOC CONTAINER
 * 
 * @author jfalkenstein
 * @property RepoMapRegistry $instance
 * @property ILessonsRepository $Lessons
 * @property IContentTypesRepository $ContentTypes
 * @property ISeriesRepository $Series 
 * @property ICategoriesRepository $Categories
 */
class RepoMapRegistry {
    private static $instance;
    public $Lessons;
    public $ContentTypes;
    public $Series;
    public $Categories;
    
    public static function getRegistry(){
        if(!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        require 'Requires/reposMap.php';
    }
}
