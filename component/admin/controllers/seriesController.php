<?php

/**
 * This is the subcontroller responsible for dealing with ajax calls relating to
 * lesson series.
 *
 * @author jfalkenstein
 */
class seriesController extends BaseController {
    
    /**
     * Returns all lesson series in a json object.
     */
    public function pullAll(){
        /* @var $model SeriesModel */
        $model = $this->getNamedModel('series');
        $this->configArray['response'] = $model->PullAllSeries();
        $view = $this->getNamedView('sendResponse');
        $view->display();
    }
    
    /**
     * Adds/updates Lesson series. Will die without valid token. Returns successful
     * boolean in json response. Raw data is provided by json string.
     */
    public function AddUpdate(){
        JSession::checkToken() or die('Invalid Token');
        $input = JFactory::getApplication()->input;
        $data = $input->get('values',null,'raw');
        $rawData = json_decode($data);
        /* @var $model SeriesModel */
        $model = $this->getNamedModel('series');
        $this->configArray['response'] = $model->AddUpdate($rawData);        
        $view = $this->getNamedView('sendResponse');
        $view->display();
    }
    
    
    /**
     * Deletes lesson series. Will die without valid token. returns successful
     * boolean in json response.
     */
    public function Delete(){
        JSession::checkToken() or die('Invalid Token');
        $input = JFactory::getApplication()->input;
        $idToDelete = $input->post->getInt('IdToDelete');
        /* @var $model SeriesModel */
        $model = $this->getNamedModel('series');
        $this->configArray['response'] = $model->Delete($idToDelete);
        $view = $this->getNamedView('sendResponse');
        $view->display();
    }
    
}
