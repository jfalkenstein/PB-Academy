<?php

/**
 * This class handles the AJAX requests for Lessons.
 *
 * @author jfalkenstein
 */
class lessonController extends BaseController{

    /**
     * Returns all lessons in JSON format.
     */
    public function pullAll(){
        /* @var $model LessonModel */
        $model = $this->getNamedModel('lesson');        
        $this->configArray['response'] = $model->PullAllLessons();
        $view = $this->getNamedView('sendResponse');
        $view->display();
    }
    
    /**
     * Returns all lessons within the series identified by the given ID in JSON
     * format.
     */
    public function pullForSeries(){
        $input = JFactory::getApplication()->input;
        $id = $input->getInt('id');
        /* @var $model LessonModel */
        $model = $this->getNamedModel('lesson');
        $this->configArray['response'] = $model->PullLessonsForSeries($id);
        $view = $this->getNamedView('sendResponse');
        $view->display();
    }
    
    /**
     * Returns all lessons within the category identified by the given ID in JSON
     * format.
     */
    public function pullForCategory(){
        $input = JFactory::getApplication()->input;
        $id = $input->getInt('id');
        /* @var $model LessonModel */
        $model = $this->getNamedModel('lesson');
        $this->configArray['response'] = $model->PullLessonsForCategory($id);
        $view = $this->getNamedView('sendResponse');
        $view->display();
    }
    
    /**
     * This will add or update a lesson through an AJAX call. 
     * It will die if a valid token is not present. It receives a JSON string
     * for the raw material for the add/update. It will send back in JSON
     * whether or not the add/update was successful.
     */
    public function AddUpdate(){
        JSession::checkToken() or die('Invalid Token');
        $input = JFactory::getApplication()->input;
        $data = $input->get('values',null,'raw');
        $rawData = json_decode($data);
        /* @var $model LessonModel */
        $model = $this->getNamedModel('lesson');
        $this->configArray['response'] = $model->AddUpdate($rawData);        
        $view = $this->getNamedView('sendResponse');
        $view->display();
    }
    
    
    /**
     * This is the controller to delete a lesson identified by the IdToDelete input
     * value. It will die if a valid token is not present. It will send back in JSON
     * whether or not the delete was successful.
     */
    public function Delete(){
        JSession::checkToken() or die('Invalid Token');
        $input = JFactory::getApplication()->input;
        $idToDelete = $input->post->getInt('IdToDelete');
        /* @var $model LessonModel */
        $model = $this->getNamedModel('lesson');
        $this->configArray['response'] = $model->Delete($idToDelete);
        $view = $this->getNamedView('sendResponse');
        $view->display();
    }
    
    /**
     * This will obtain the preview lesson embed code, given the content type,
     * content string value, and the imagePath. 
     */
    public function GetPreview(){
        $input = JFactory::getApplication()->input;
        $ctId = $input->getInt('ctId');
        $content = $input->getString('content');
        $imagePath = $input->getString('imagePath');
        $embedString = LessonEmbedder::GetPreview($ctId, $content, $imagePath);
        $preview = new stdClass();
        $preview->embedString = $embedString;
        $this->configArray['response'] = $preview;
        $view = $this->getNamedView('sendResponse');
        $view->display();
    }
}
