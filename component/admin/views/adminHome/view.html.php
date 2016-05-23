<?php

/**
 * Description of view
 *
 * @author jfalkenstein
 */
class PbAcademyViewAdminHome extends BaseAdminViewMaster{
    
    public $AllLessons;
    public $WhichTableEnum = ["Lessons" => 0, "Schools" => 1, "Series" => 2];
    
    public function display($tpl = null) {
        parent::display($tpl);
    }
    
    public function setPageTitle() {
        $this->PageTitle = 'P&B Academy Back End';
    }
    
    public function getManageSection($addEditLink, $whichTableEnumValue,array $sortFieldNames)
    {
        include 'tmpl/manageSection.php';
    }
    
    
}
