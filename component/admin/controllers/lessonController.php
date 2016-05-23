<?php

/**
 * This class handles the AJAX requests for Lessons.
 *
 * @author jfalkenstein
 * @property PBAcademyManager $PBAcademyManager Description
 */
class lessonController extends BaseController{

    public function pullAll(){
        /* @var $model LessonModel */
        $model = $this->getNamedModel('lesson');        
        $this->configArray['response'] = $model->PullAllLessons();
        $view = $this->getNamedView('sendResponse');
        $view->display();
    }
    
    public function pullForSeries(){
        $input = JFactory::getApplication()->input;
        $id = $input->getInt('id');
        /* @var $model LessonModel */
        $model = $this->getNamedModel('lesson');
        $this->configArray['response'] = $model->PullLessonsForSeries($id);
        $view = $this->getNamedView('sendResponse');
        $view->display();
    }
    public function pullForCategory(){
        $input = JFactory::getApplication()->input;
        $id = $input->getInt('id');
        /* @var $model LessonModel */
        $model = $this->getNamedModel('lesson');
        $this->configArray['response'] = $model->PullLessonsForCategory($id);
        $view = $this->getNamedView('sendResponse');
        $view->display();
    }

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
           
    private function sendDebug($object){
        var_dump($object);
        JFactory::getApplication()->close();
    }
}
