<?php

/**
 * The view for the PB Academy homepage.
 *
 * @author jfalkenstein
 */
class PbAcademyViewHome extends BaseViewMaster implements IRecentLessonsView, IAllCategoriesView
{
    
    public function setCategories() {
        $this->Categories = $this->get('Categories','home');
    }
    public function setPageTitle() {
        $this->PageTitle = 'P&B Academy Home';
    }
    public function setSeries() {
        $this->AllSeries = $this->get('Series','home');
    }
    public function GetRecentLessons() {
        return $this->get('RecentLessons','home');
    }
    public function GetRecentLessonTitle() {
        return 'Latest Lessons:';
    }
    public function ShowDescriptions() {
        return false;
    }
    
}
