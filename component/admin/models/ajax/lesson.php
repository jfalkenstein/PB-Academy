<?php

/**
 * This is the model for ajax calls related to Lessons.
 * @author jfalkenstein
 */
class LessonModel extends BaseAjaxAdminModel {
    
    /**
     * Returns an array of flattened Lessons representing all lessons in the DB.
     * @return object[]
     */
    public function PullAllLessons(){
        $lessons = $this->PBAcademyManager->GetAllLessons(false);      
        return $this->flattenLessonsArray($lessons);
    }
    
    /**
     * Returns flattened array of lessons for the specified series.
     * @param type $seriesId
     * @return object[]
     */
    public function PullLessonsForSeries($seriesId){
        $series = $this->PBAcademyManager->GetSeries($seriesId);
        $lessons = $series->GetLessons(false);
        return $this->flattenLessonsArray($lessons);
    }
    
    /**
     * Returns flattened array of lessons for the specified category.
     * @param type $catId
     * @return type
     */
    public function PullLessonsForCategory($catId){
        $cat = $this->PBAcademyManager->GetCategoryById($catId);
        $lessons = $cat->GetLessons(false);
        return $this->flattenLessonsArray($lessons);
    }
    
    /**
     * Receives an array of Lessons and will flatten those lessons into an array
     * of stdClass objects.
     * @param Lesson[] $lessons
     * @return \stdClass[]
     */
    private function flattenLessonsArray(array $lessons)
    {
        $arrayToSend = [];
        foreach($lessons as $item){
            $lesson = new stdClass();
                $lesson->Title = $item->Title;
                $lesson->Id = $item->Id;
                $lesson->Date = $item->DatePublished();
                $lesson->CategoryName = $item->Category->Name;
                $series = $item->Series;
                $lesson->SeriesName = ($series) ? $series->SeriesName : "";
                $lesson->EditLink = AdminUrlMaker::AddEditLesson($item->Id);
                $lesson->DeleteLink = AdminUrlMaker::AjaxDeleteLesson();
                $lesson->TruePosition = $item->TrueSeriesPosition();
                $lesson->Published = $item->Published;
                $arrayToSend[] = $lesson;
        }
        return $arrayToSend;
    }
    
    /**
     * This will add or update a lesson using the rawData passed in. It will return
     * a stdClass object with the rawData passed in and a success boolean.
     * @param stdClass $rawData
     * @return \stdClass
     */
    public function AddUpdate($rawData){
        $success = $this->createLesson($rawData);
        $response = new stdClass();
        $response->success = $success;
        $response->values = $rawData;
        return $response;
    }
    
    /**
     * Deletes the lesson specified by the id. Returns an object with a success
     * boolean
     * @param type $idToDelete
     * @return \stdClass
     */
    public function Delete($idToDelete){
        $response = new stdClass();
        $response->success = false;
        try{
            $success = $this->PBAcademyManager->DeleteLesson($idToDelete);
            $response->success = $success;
        } catch (Exception $ex) {
        }
        return $response;
    }
    
    /**
     * Creates a new lesson using the stdClass object passed in. Returns a success
     * boolean.
     * @param stdClass $object
     * @return boolean
     */
    private function createLesson($object){
        $mgr = $this->PBAcademyManager;
        try{
            if(!$object->update){ //If the lesson is new...
                $ct = $mgr->GetContentTypeById($object->contentTypeId); //Get the content type...
                $cat = $mgr->GetCategoryById($object->categoryId); //Get the category....
                $ser = null;
                if($object->seriesDrop){//If a series is specified...
                    $ser = $mgr->GetSeries($object->seriesDrop); //Get the series.
                }        
                $success = $mgr->CreateLession( //Create the lesson.
                    $ct,
                    $object->Title, 
                    $cat, 
                    date('Y-m-d'), 
                    $object->Description,
                    $object->content, 
                    $ser,
                    $object->imagePath,
                    $object->SourceCredit,
                    $object->seriesPosition,
                    $object->Published);
                return $success;
            }else{ //If it is an update to an existing lesson...
                $success = $mgr->UpdateLession(
                        $object->lessonId, 
                        $object->contentTypeId, 
                        $object->categoryId, 
                        (($object->seriesDrop) ? $object->seriesDrop : null), 
                        $object->Title, 
                        $object->Description, 
                        $object->content, 
                        $object->imagePath, 
                        $object->SourceCredit,
                        $object->seriesPosition,
                        $object->Published);
                return $success;
            }
        } catch (Exception $ex) {
            return false;
        }
    }
    
    
}
