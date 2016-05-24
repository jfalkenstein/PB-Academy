<?php

/**
 * This controllers handles the category-related AJAX calls.
 *
 * @author jfalkenstein
 */
class categoryController extends BaseController {
    
    /**
     * This is the controller that pulls all categories and returns them in a 
     * json response.
     */
    public function pullAll(){
        /* @var $model CategoryModel */
        $model = $this->getNamedModel('category');
        $this->configArray['response'] = $model->PullAllCategories();
        $view = $this->getNamedView('sendResponse');
        $view->display();
    }
    
    /**
     * This is the controller that adds/updates categories. It will die if not
     * accompanied by an anti-forgery token. It will send back in JSON
     * whether or not the add/update was successful.
     */
    public function AddUpdate(){
        JSession::checkToken() or die('Invalid Token');
        $input = JFactory::getApplication()->input;
        $data = $input->get('values',null,'raw');
        $rawData = json_decode($data);
        /* @var $model SeriesModel */
        $model = $this->getNamedModel('category');
        $this->configArray['response'] = $model->AddUpdate($rawData);        
        $view = $this->getNamedView('sendResponse');
        $view->display();
    }
    
    /**
     * This is the controller that deletes categories. It will die if not accompanied
     * by an anti-forgery token. It will send back in JSON
     * whether or not the delete was successful.
     */
    public function Delete(){
        JSession::checkToken() or die('Invalid Token');
        $input = JFactory::getApplication()->input;
        $idToDelete = $input->post->getInt('IdToDelete');
        /* @var $model CategoryModel */
        $model = $this->getNamedModel('category');
        $this->configArray['response'] = $model->Delete($idToDelete);
        $view = $this->getNamedView('sendResponse');
        $view->display();
    }
}
