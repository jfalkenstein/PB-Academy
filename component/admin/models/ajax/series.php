<?php

/**
 * Description of series
 *
 * @author jfalkenstein
 */
class SeriesModel extends BaseAjaxAdminModel {
    
    public function PullAllSeries(){
        $series = $this->PBAcademyManager->GetAllSeries();
        $arrayToSend = [];
        foreach($series as $ser){
            $s = new stdClass();
            $s->SeriesName = $ser->SeriesName;
            $s->Id = $ser->Id;
            $s->LessonCount = $ser->LessonCount(false);
            $s->ViewLessonsLink = AdminUrlMaker::AjaxLessonsForSeries($ser->Id);
            $s->EditLink = AdminUrlMaker::AddEditSeries($ser->Id);
            $s->DeleteLink = AdminUrlMaker::AjaxDeleteSeries();
            $arrayToSend[] = $s;
        }
        return $arrayToSend;        
    }
    
    public function AddUpdate($rawData){
        $success = $this->createSeries($rawData);
        $response = new stdClass();
        $response->success = $success;
        $response->values = $rawData;
        return $response;
    }
    
    public function Delete($idToDelete){
        $response = new stdClass();
        $response->success = false;
        try{
            $success = $this->PBAcademyManager->DeleteSeries($idToDelete);
            $response->success = $success;
        } catch (Exception $ex) {
        }
        return $response;
    }
    
    private function createSeries($rawdata){
        $mgr = $this->PBAcademyManager;
        try{
            if(!$rawdata->update){
                $success = $mgr->CreateSeries(
                        $rawdata->SeriesName, 
                        $rawdata->Description,
                        $rawdata->imagePath);
                return $success;
            }else{
                $success = $mgr->UpdateSeries(
                        $rawdata->seriesId, 
                        $rawdata->SeriesName, 
                        $rawdata->Description, 
                        $rawdata->imagePath);
                return $success;
            }
        } catch (Exception $ex) {
            return false;
        }
    }
    
}
