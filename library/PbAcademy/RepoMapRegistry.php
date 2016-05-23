<?php
require_once 'Requires/repos.php';
/**
 * Description of RepoMapRegistry
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
