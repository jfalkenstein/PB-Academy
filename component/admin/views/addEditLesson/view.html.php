<?php

/**
 * Description of view
 * @property []LessonSeries $AllSeries 
 * @property []Category $AllCategories 
 * @property []ContentType $ContentTypes
 * @property Lesson $Lesson
 * @author jfalkenstein
 */
class PbAcademyViewAddEditLesson extends BaseAdminViewMaster {
    
    public $AllSeries;
    public $AllCategories;
    public $Lesson;
    public $ContentTypes;
    
    public function display($tpl = null) {
        $problem = false;
        try{
            $this->AllCategories = $this->get('AllCategories','lesson');
            $this->AllSeries = $this->get('AllSeries','lesson');
            $this->Lesson = $this->get('Lesson','lesson');
            $this->ContentTypes = $this->get('AllContentTypes','lesson');
        } catch (Exception $ex) {
            $problem = true;
        }
        parent::displayModel($problem);
    }
    
    protected function setPageTitle() {
        if(is_null($this->Lesson)){
            $this->PageTitle = 'Add New Lesson';
        }else{
            $this->PageTitle = 'Update Lesson: ' . $this->Lesson->Title;
        }
    }
}
