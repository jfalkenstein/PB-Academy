<?php
require_once PB_ACADEMY_LIB . '/AdminUrlMaker.php';
/**
 * Description of BaseViewMaster
 *
 * @author jfalkenstein
 */
abstract class BaseAdminViewMaster extends JViewLegacy {
    
    protected $viewName;
    public $MainBodyInclude;
    public $MasterTemplate;
    public $PageNumber;
    public $PageTitle;
    
    public function __construct($config = array()) {
        $this->viewName = $config['viewName'];
        $this->MainBodyInclude = __DIR__ . '/../' . $this->viewName . '/tmpl/MainBodyInclude.php';
        $this->MasterTemplate = __DIR__ . '/tmpl/PbAcademyAdminMaster.php';
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
    
    final function displayModel($problem){
        if($problem){
            $this->PageTitle = 'Sorry!';
            $this->MainBodyInclude = __DIR__ . '/tmpl/Problem.php';
        }else{
            $this->setPageTitle();
        }
        JToolBarHelper::title($this->PageTitle);
        include $this->MasterTemplate;
    }
    
    abstract protected function setPageTitle();
}
