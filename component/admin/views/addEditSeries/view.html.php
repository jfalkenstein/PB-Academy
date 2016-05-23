<?php

/**
 * Description of view
 * @property LessonSeries $Series
 * @author jfalkenstein
 */
class PbAcademyViewAddEditSeries extends BaseAdminViewMaster {
    
    public $Series;
    
    public function display($tpl = null) {
        $problem = false;
        try{
            $this->Series = $this->get('Series','series');
        } catch (Exception $ex) {
            $problem = true;
        }
        parent::displayModel($problem);
    }
    
    protected function setPageTitle() {
        if(is_null($this->Series)){
            $this->PageTitle = "Add New Lesson Series";
        }else{
            $this->PageTitle = "Update Lesson Series: " . $this->Series->SeriesName . ' Lesson count: ' . $this->Series->LessonCount();
        }
    }
    
}
