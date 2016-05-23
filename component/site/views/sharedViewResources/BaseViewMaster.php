<?php

require_once __DIR__ . '/ViewInterfaces/IRecentLessonsView.php';
require_once __DIR__ . '/ViewInterfaces/INavBarView.php';
require_once __DIR__ . '/ViewInterfaces/IAllCategoriesView.php';
require_once __DIR__ . '/ViewInterfaces/IAllSeriesView.php';
require_once __DIR__ . '/ViewInterfaces/IListLessonsView.php';
require_once __DIR__ . '/../../ViewClasses/NavBar.php';
require_once PB_ACADEMY_LIB . '/UrlMaker.php';
/**
 * Description of BaseViewMaster
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
        $this->viewName = $config['viewName'];
        $this->MenuTitle = 'Schools';
        $this->MainBodyInclude = __DIR__ . '/../' . $this->viewName . '/tmpl/MainBodyInclude.php';
        $this->MasterTemplate = 'tmpl/PbAcademyMaster.php';
        if(isset($config['page'])){
            $this->PageNumber = $config['page'];
        }else{
            $this->PageNumber = 1;
        }
        parent::__construct($config);
    }
    
    public function display($tpl = null) {
        $this->displayModel(false);
    }
    
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
