<?php
require_once PB_ACADEMY_LIB . '/AdminUrlMaker.php';
/**
 * This is the base view for the various admin pages (not ajax calls). It overrides
 * some of Joomla's default display behavior (see below).
 * @author jfalkenstein
 */
abstract class BaseAdminViewMaster extends JViewLegacy {
    
    protected $viewName;
    public $MainBodyInclude;
    public $MasterTemplate;
    public $PageNumber;
    public $PageTitle;
    
    public function __construct($config = array()) {
        //Pull the name of the view from config array, set by the controller.
        $this->viewName = $config['viewName'];
        //Set the main body include with the view name; This COULD be overridden
        //by the view, but this is my own convention.
        $this->MainBodyInclude = __DIR__ . '/../' . $this->viewName . '/tmpl/MainBodyInclude.php';
        //Sets the base framework for all descendent views.
        $this->MasterTemplate = __DIR__ . '/tmpl/PbAcademyAdminMaster.php';
        parent::__construct($config);
    }
    
    /**
     * This overrides the parent display function. Instead of calling the parent,
     * it calls the displayModel function. This is used when there is a simple page
     * displayed that is not reliant upon a central model object being valid.
     * @param type $tpl This is not used.
     */
    public function display($tpl = null) {
        $this->displayModel(false);
    }
    
    /**
     * This is the main display function. If there is a $problem, it will change
     * the main body include to a basic error message. Otherwise, it will display
     * the main body include as set by the view.
     * @param type $problem
     */
    final function displayModel($problem){
        if($problem){
            $this->PageTitle = 'Sorry!';
            $this->MainBodyInclude = __DIR__ . '/tmpl/Problem.php';
        }else{
            $this->setPageTitle();
        }
        JToolBarHelper::title($this->PageTitle); //Uses Joomla's admin titlebar.
        include $this->MasterTemplate;
    }
    
    //This is required by all descendent views.
    abstract protected function setPageTitle();
}
