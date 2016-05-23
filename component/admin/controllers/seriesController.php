<?php

/**
 * Description of series
 *
 * @author jfalkenstein
 */
class seriesController extends BaseController {
    
    public function pullAll(){
        /* @var $model SeriesModel */
        $model = $this->getNamedModel('series');
        $this->configArray['response'] = $model->PullAllSeries();
        $view = $this->getNamedView('sendResponse');
        $view->display();
    }
    
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
