<?php

/**
 * Description of view
 * @property Category $Category
 * @author jfalkenstein
 */
class PbAcademyViewAddEditCategory extends BaseAdminViewMaster {
    
    public $Category;
    
    public function display($tpl = null) {
        $problem = false;
        try{
            $this->Category = $this->get('Category','category');
        } catch (Exception $ex) {
            $problem = true;
        }
        parent::displayModel($problem);
    }
    
    
    protected function setPageTitle() {
        if(is_null($this->Category)){
            $this->PageTitle = "Add New School";
        }else{
            $this->PageTitle = "Update School: " . $this->Category->Name . ' Lesson count: ' . $this->Category->LessonCount();
        }
    }
}
