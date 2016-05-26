<?php

//Load the various dependencies for the Views to function.
require_once __DIR__ . '/ViewInterfaces/IRecentLessonsView.php';
require_once __DIR__ . '/ViewInterfaces/INavBarView.php';
require_once __DIR__ . '/ViewInterfaces/IAllCategoriesView.php';
require_once __DIR__ . '/ViewInterfaces/IAllSeriesView.php';
require_once __DIR__ . '/ViewInterfaces/IListLessonsView.php';
require_once __DIR__ . '/../../ViewClasses/NavBar.php';
require_once PB_ACADEMY_LIB . '/UrlMaker.php';
/**
 * The BaseViewMaster extends the Joomla view class, but abstracts it away from the
 * rest of the views so that the desired behavior can be overridden. It provides
 * a layer of functionality shared by most all the views.
 * 
 * @author jfalkenstein
 * 
 * @property NavBar $NavBar If the view has a NavBar, this is where it is stored.
 */
abstract class BaseViewMaster extends JViewLegacy {
    public $MenuTitle;
    public $MainBodyInclude;
    protected $viewName;
    public $MasterTemplate;
    public $Categories;
    public $AllSeries;
    public $PageTitle;
    public $NavBar;
    public $PageNumber;
    const SHARED_TEMPLATES_INCLUDE = '/../../sharedViewResources/tmpl/';
    
    public function __construct($config = array()) {
        //Pull the view name from the controller, to be used in setting the mainBodyInclude.
        $this->viewName = $config['viewName'];
        //Set the left menu title --> This could potentially be overridden by a view.
        $this->MenuTitle = 'Schools';
        //Set the main body include. This could be potentially overridden by a view.
        $this->MainBodyInclude = __DIR__ . '/../' . $this->viewName . '/tmpl/MainBodyInclude.php';
        //Set the main PB Academy template.
        $this->MasterTemplate = 'tmpl/PbAcademyMaster.php';
        
        //Pagination is used by categories and series that list potentially large
        //numbers of lessons.
        if(isset($config['page'])){
            $this->PageNumber = $config['page'];
        }else{
            $this->PageNumber = 1;
        }
        parent::__construct($config);
    }
    
    //Overrides the default Joomla display method; This is a simple implementation
    //that wouldn't have a problem.
    public function display($tpl = null) {
        $this->displayModel(false);
    }
    
    /**
     * This is ultimately called by all views. It will report a problem and change
     * the main body include to a problem report if the given model cannot be located,
     * such as in the case of an invalid id provided in the query string.
     * @param type $problem
     */
    final function displayModel($problem) {
        $this->setCategories();
        $this->setSeries();
        if($problem){
            $this->NavBar = new NavBar();
            $this->PageTitle = 'Sorry!';
            $this->MainBodyInclude = __DIR__ . '/tmpl/Problem.php';
        }else{
            $this->setPageTitle();
            if(is_a($this, 'INavBarView')){
                $this->SetNavBar();
            }
        }
        include $this->MasterTemplate;
    }
    
    abstract protected function setCategories();
    abstract protected function setSeries();
    abstract protected function setPageTitle();
}
