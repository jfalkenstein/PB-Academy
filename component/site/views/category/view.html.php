<?php

/**
 * @property Category $ThisCategory
 * @property []Lesson $Lessons
 */
class PbAcademyViewCategory extends BaseViewMaster implements INavBarView, IListLessonsView
{
    
    public $ThisCategory;
    public $Lessons;

    public function display($tpl = null) {
        $problem;
        $this->ThisCategory = $this->get('Category', 'category');
        if(is_null($this->ThisCategory)){
            $problem = true;
        }else{
            $problem = false;
        }
        parent::displayModel($problem);
    }    
    public function setCategories() {
        $this->Categories = $this->get('Categories','category');
    }
    public function setPageTitle() {
        $this->PageTitle = $this->ThisCategory->Name;
    }
    public function setSeries() {
        $this->AllSeries = $this->get('Series','category');
    }
    public function SetNavBar() {
        $this->NavBar = new NavBar(null, $this->ThisCategory);
    }
    public function PrintNavBar() {
        print $this->NavBar->MakeNavBar();
    }
    public function GetLessons() {
        return $this->ThisCategory->GetLessions();
    }
    public function ShowSeriesPositionInTitle() {
        return false;
    }
    public function GetLink() {
        return $this->ThisCategory->GetLink();
    }

}