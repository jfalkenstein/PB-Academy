<?php

/**
 * This is the main  admin controller, required by the Joomla API.
 * The function of the controller is to select the appropriate model and view
 * based upon GET and POST data. Then it will connect the model to said view,
 * then display the view.
 *
 * @author jfalkenstein
 */
class PbAcademyController extends JControllerLegacy
{
    private $configArray;
    
    public function adminHome(){
        $this->configArray['viewName'] = 'adminHome';
        $adminHome = $this->getNamedView('adminHome');
        $adminHome->setModel($this->getNamedModel('adminHome'));
        $adminHome->display();
    }
    
    public function lesson(){
        $input = JFactory::getApplication()->input;
        $this->configArray['viewName'] = 'addEditLesson';
        $id = $input->getInt('id');
        if(isset($id)){
            $this->configArray['id'] = $id;
        }
        $lesView = $this->getNamedView('addEditLesson');
        $lesView->setModel($this->getNamedModel('lesson'));
        $lesView->display();
    }
    
    public function series(){
        $input = JFactory::getApplication()->input;
        $this->configArray['viewName'] = 'addEditSeries';
        $id = $input->getInt('id');
        if(isset($id)) {
            $this->configArray['id'] = $id;
        }
        $serView = $this->getNamedView('addEditSeries');
        $serView->setModel($this->getNamedModel('series'));
        $serView->display();
    }
    
    public function category(){
        $input = JFactory::getApplication()->input;
        $this->configArray['viewName'] = 'addEditCategory';
        $id = $input->getInt('id');
        if(isset($id)) {
            $this->configArray['id'] = $id;
        }
        $catView = $this->getNamedView('addEditCategory');
        $catView->setModel($this->getNamedModel('category'));
        $catView->display();
    } 
    /**
     * This function replaces Joomla's "getModel" function. This is more specific to
     * the present design patterna dn doesn't rely on Joomla's mysterious conventions.
     * @param string $modelName
     * @param string $className
     * @return JModelLegacy
     */
    public function getNamedModel($modelName, $className = null){
        require_once 'models/' . $modelName . '.php';
        $class = (($className) ? $className : 'PbAcademyModel' . ucfirst($modelName));
        return new $class($this->configArray);
    }
    
    /**
     * This function replaces Joomla's "getView" function. This is more specific to
     * the present design pattern and doesn't rely on Joomla's mysterious conventions.
     * @param string $viewName
     * @param string $className
     * @return JViewLegacy
     */
    public function getNamedView($viewName, $className = null){
        require_once 'views/' . $viewName . '/view.html.php';
        $class = (($className) ? $className : 'PbAcademyView' . ucfirst($viewName));
        return new $class($this->configArray);
    }
    
}
