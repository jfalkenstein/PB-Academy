<?php

/**
 * Description of view
 *
 * @author jfalkenstein
 */
class PbAcademyViewAllSeries extends BaseViewMaster implements IAllSeriesView, INavBarView{
     public function display($tpl = null) {
        parent::display($tpl);
    }
    public function setCategories() {
        $this->Categories = $this->get('Categories','allseries');
    }
    public function setPageTitle() {
        $this->PageTitle = 'Lesson Series';
    }
    public function setSeries() {
        $this->AllSeries = $this->get('Series','allseries');
    }
    public function ShowDescriptions() {
        return true;
    }
    public function SetNavBar() {
        $this->NavBar = new NavBar();
    }
    public function PrintNavBar() {
        print $this->NavBar->MakeNavBar();
    }
}
