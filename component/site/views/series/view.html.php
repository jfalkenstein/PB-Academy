<?php

/**
 * The view for a single series.
 * @property LessonSeries $ThisSeries Description
 * @author jfalkenstein
 */
class PbAcademyViewSeries extends BaseViewMaster implements INavBarView, IListLessonsView{
    public $ThisSeries;   
    
    public function display($tpl = null) {
        $problem;
        $this->ThisSeries = $this->get('ThisSeries','series');
        if(is_null($this->ThisSeries)){
            $problem = true;
        }else{
            $problem = false;
        }        
        parent::displayModel($problem);
    }
    
    public function setPageTitle() {
        $this->PageTitle = $this->ThisSeries->SeriesName;
    }
    protected function setSeries() {
        $this->AllSeries = $this->get('Series','series');
    }
    protected function setCategories() {
        $this->Categories = $this->get('Categories', 'series');
    }
    public function SetNavBar() {
        $this->NavBar = new NavBar(null,null,$this->ThisSeries);
    }
    public function PrintNavBar() {
        print $this->NavBar->MakeNavBar();
    }
    public function ShowSeriesPositionInTitle() {
        return true;
    }
    public function GetLessons() {
        return $this->ThisSeries->GetLessons();
    }
    public function GetLink() {
        return $this->ThisSeries->GetLink();
    }
}
