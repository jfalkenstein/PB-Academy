<?php

class PbAcademyController extends JControllerLegacy
{           
    private $configArray;
    
    public function home() {
        $this->configArray['viewName'] = 'home';
        $homeView = $this->getView('home','html','',$this->configArray);
        $homeView->setModel($this->getNamedModel('home'));
        $homeView->display();
    }
    
    public function category(){
        $input = JFactory::getApplication()->input;
        $catID = $input->getInt('c_id');
        $pageNumber = $input->getInt('page');
        $this->configArray['catID'] = $catID;
        $this->configArray['page'] = $pageNumber;
        if($catID){
            $this->configArray['viewName'] = 'category';
            $catView = $this->getView('category','html','',$this->configArray);
            $catView->setModel($this->getNamedModel('category'));
            $catView->display();
        }else{
            $this->configArray['viewName'] = 'categories';
            $catsView = $this->getView('categories','html','',$this->configArray);
            $catsView->setModel($this->getNamedModel('categories'));
            $catsView->display();
        }
    }
    
    public function series(){
        $input = JFactory::getApplication()->input;
        $serId = $input->getInt('s_id');
        $pageNumber = $input->getInt('page');
        $this->configArray['page'] = $pageNumber;
        $this->configArray['seriesID'] = $serId;
        if($serId){
            $this->configArray['viewName'] = 'series';
            $serView = $this->getView('series','html','',$this->configArray);
            $serView->setModel($this->getNamedModel('series'));
            $serView->display();
        }else{
            $this->configArray['viewName'] = 'allseries';
            $serView = $this->getView('allseries','html','',$this->configArray);
            $serView->setModel($this->getNamedModel('allseries'));
            $serView->display();
        }   
    }
    
    public function lesson(){
        $input = JFactory::getApplication()->input;
        $lessonId = $input->getInt('l_id');
        if($lessonId){
            $this->configArray['lessonId'] = $lessonId;
            $this->configArray['viewName'] = 'lesson';
            $lessonView = $this->getView('lesson', 'html','',$this->configArray);
            $lessonView->setModel($this->getNamedModel('lesson'));
            $lessonView->display();
        }else{
            echo 'Going to lesson index page.';
        }
    }
    
    public function getNamedModel($modelName, $className = null){
        require_once 'models/' . $modelName . '.php';
        $class = (($className) ? $className : 'PbAcademyModel' . ucfirst($modelName));
        return new $class($this->configArray);
    }
    
}