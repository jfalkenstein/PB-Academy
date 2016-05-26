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
    
    /**
     * This is called on the adminHome page to include with specific variables the manage
     * sections. 
     * @param type $addEditLink The link to edit the unit that section represents.
     * @param type $whichTableEnumValue The table that section represents, represented by the whichTableEnum value (set in JavaScript);
     * @param array $sortFieldNames The fields in the table that will be sortable with buttons using list.js.
     */
    public function getManageSection($addEditLink, $whichTableEnumValue,array $sortFieldNames)
    {
        include 'tmpl/manageSection.php';
    }
    
    
}
