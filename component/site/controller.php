<?php
/**
 * This is the main controller for the PB Academy Front end. It is required by
 * the Joomla APA. It is handled at the app root (pbacademy.php). Controllers are
 * responsible for selecting the appropriate model and view, connecting them, and then
 * displaying the view.
 */
class PbAcademyController extends JControllerLegacy
{           
    private $configArray;
    
    /**
     * The home controller. No tricky business here.
     */
    public function home() {
        $this->configArray['viewName'] = 'home';
        $homeView = $this->getView('home','html','',$this->configArray);
        $homeView->setModel($this->getNamedModel('home'));
        $homeView->display();
    }
    
    /**
     * The category controller.
     */
    public function category(){
        $input = JFactory::getApplication()->input;
        //Try to get a category id from the input.
        $catID = $input->getInt('c_id');
        //Try to get a page number from the input.
        $pageNumber = $input->getInt('page');
        //Stick these values into the config array for the model & view.
        $this->configArray['catID'] = $catID;
        $this->configArray['page'] = $pageNumber;
        if($catID){ //If there is an id...
            //Select the category view & model.
            $this->configArray['viewName'] = 'category';
            $catView = $this->getView('category','html','',$this->configArray);
            $catView->setModel($this->getNamedModel('category'));
            $catView->display();
        }else{ //If there is NO id...
            //Select the categories view & model.
            $this->configArray['viewName'] = 'categories';
            $catsView = $this->getView('categories','html','',$this->configArray);
            $catsView->setModel($this->getNamedModel('categories'));
            $catsView->display();
        }
    }
    /**
     * The series controller. It functions exactly like the category controller,
     * so see comments in it for explanation.
     */
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
    
    /**
     * The lesson controller.
     */
    public function lesson(){
        $input = JFactory::getApplication()->input;
        $lessonId = $input->getInt('l_id');
        $this->configArray['lessonId'] = $lessonId;
        $this->configArray['viewName'] = 'lesson';
        $lessonView = $this->getView('lesson', 'html','',$this->configArray);
        $lessonView->setModel($this->getNamedModel('lesson'));
        $lessonView->display();
    }
    
    /**
     * This overrides the "GetModel" method within the Joomla api. This, instead,
     * will return a model based upon my own convention. This was much easier to
     * get to work instead of relying upon Joomla's mysterious conventions.
     * @param string $modelName
     * @param string $className
     * @return BaseModel
     */
    public function getNamedModel($modelName, $className = null){
        require_once 'models/' . $modelName . '.php';
        $class = (($className) ? $className : 'PbAcademyModel' . ucfirst($modelName));
        return new $class($this->configArray);
    }
    
}