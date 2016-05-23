<?php

/**
 * Description of lesson
 *
 * @author jfalkenstein
 */
class LessonModel extends BaseAjaxAdminModel {
    
    public function PullAllLessons(){
        $lessons = $this->PBAcademyManager->GetAllLessons(false);      
        return $this->flattenLessonsArray($lessons);
    }
    
    public function PullLessonsForSeries($seriesId){
        $series = $this->PBAcademyManager->GetSeries($seriesId);
        $lessons = $series->GetLessons(false);
        return $this->flattenLessonsArray($lessons);
    }
    
    public function PullLessonsForCategory($catId){
        $cat = $this->PBAcademyManager->GetCategoryById($catId);
        $lessons = $cat->GetLessions(false);
        return $this->flattenLessonsArray($lessons);
    }
    
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
    
    
    public function AddUpdate($rawData){
        $success = $this->createLesson($rawData);
        $response = new stdClass();
        $response->success = $success;
        $response->values = $rawData;
        return $response;
    }
    
    public function Delete($idToDelete){
        $response = new stdClass();
        $response->success = false;
        try{
            $success = $this->PBAcademyManager->DeleteLession($idToDelete);
            $response->success = $success;
        } catch (Exception $ex) {
        }
        return $response;
    }
    
    private function createLesson($object){
        $mgr = $this->PBAcademyManager;
        try{
            if(!$object->update){
                $ct = $mgr->GetContentTypeById($object->contentTypeId);
                $cat = $mgr->GetCategoryById($object->categoryId);
                $ser = null;
                if($object->seriesDrop){
                    $ser = $mgr->GetSeries($object->seriesDrop);
                }        
                $success = $mgr->CreateLession(
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
            }else{
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
