<?php

/**
 * Description of category
 *
 * @author jfalkenstein
 */
class CategoryModel extends BaseAjaxAdminModel {
    
    public function PullAllCategories(){
        $cats = $this->PBAcademyManager->GetAllCategories();
        $arrayToSend = [];
        foreach ($cats as $cat){
            /* @var $cat Category */
            $c = new stdClass();
            $c->Name = $cat->Name;
            $c->Id = $cat->Id;
            $c->LessonCount = $cat->LessonCount(false);
            $c->ViewLessonsLink = AdminUrlMaker::AjaxLessonsForCategory($cat->Id);
            $c->EditLink = AdminUrlMaker::AddEditCategory($cat->Id);
            $c->DeleteLink = AdminUrlMaker::AjaxDeleteCategory($cat->Id);
            $arrayToSend[] = $c;
        }
        return $arrayToSend;
    }
    
    public function AddUpdate($rawData){
        $success = $this->createCategory($rawData);
        $response = new stdClass();
        $response->success = $success;
        $response->values = $rawData;
        return $response;
    }
    
    private function createCategory($rawData){
        $mgr = $this->PBAcademyManager;
        try{
            if(!$rawData->update){
                $success = $mgr->CreateCategory(
                        $rawData->categoryName, 
                        $rawData->Description, 
                        $rawData->imagePath);
                return $success;
            }else{
                $success = $mgr->UpdateCategory(
                        $rawData->categoryId, 
                        $rawData->categoryName, 
                        $rawData->Description, 
                        $rawData->imagePath);
                return $success;
            }
        } catch (Exception $ex) {
            return false;
        }
    }
    
    public function Delete($idToDelete){
        $response = new stdClass();
        $response->success = false;
        try{
            $success = $this->PBAcademyManager->DeleteCategory($idToDelete);
            $response->success = $success;
        } catch (Exception $ex) {
        }
        return $response;
    }
}
