<?php

/**
 * Description of view
 *
 * @author jfalkenstein
 */
class PbAcademyViewCategories extends BaseViewMaster implements IAllCategoriesView, INavBarView {
    
    public function display($tpl = null) {
        parent::display($tpl);
    }
    public function setCategories() {
        $this->Categories = $this->get('Categories','categories');
    }
    public function setPageTitle() {
        $this->PageTitle = 'Schools';
    }
    public function setSeries() {
        $this->AllSeries = $this->get('Series','categories');
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
