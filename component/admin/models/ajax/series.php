<?php

/**
 * This is the model for LessonSeries ajax calls.
 * @author jfalkenstein
 */
class SeriesModel extends BaseAjaxAdminModel {
    
    /**
     * Pulls all series from the DB and then returns a flattened array of stdClass objects
     * with additional properties of LessonCount, ViewLessonsLink, EditLink, and DeleteLink.
     * @return \stdClass[]
     */
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
    
    /**
     * Adds/updates a series based upon the raw data passed in. Returns a stdClass object
     * with the raw data and the success boolean.
     * @param type $rawData
     * @return \stdClass
     */
    public function AddUpdate($rawData){
        $success = $this->createSeries($rawData);
        $response = new stdClass();
        $response->success = $success;
        $response->values = $rawData;
        return $response;
    }
    
    /**
     * Deletes the series specified by the Id. Returns an  object with a success
     * boolean.
     * @param type $idToDelete
     * @return \stdClass
     */
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
    
    /**
     * Creates a series with the raw data. Returns a success boolean.
     * @param type $rawdata
     * @return boolean
     */
    private function createSeries($rawdata){
        $mgr = $this->PBAcademyManager;
        try{
            if(!$rawdata->update){ //If it's a new series...
                $success = $mgr->CreateSeries(
                        $rawdata->SeriesName, 
                        $rawdata->Description,
                        $rawdata->imagePath);
                return $success;
            }else{ //If it's an old series...
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
