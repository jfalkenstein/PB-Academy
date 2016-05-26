<?php

/**
 * The ajax model for categories (schools).
 * @author jfalkenstein
 */
class CategoryModel extends BaseAjaxAdminModel {
    
    /**
     * Returns an array of all categories in the db. To each category is added
     * a property for the LessonCount, the link to pull lessons for that category,
     * the link to edit that category, and the link to delete it.
     * @return stdClass[]
     */
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
            $c->DeleteLink = AdminUrlMaker::AjaxDeleteCategory();
            $arrayToSend[] = $c;
        }
        return $arrayToSend;
    }
    
    /**
     * This will add or update a lesson and return an object with the values received
     * and a boolean to indicate if the add/update succeeded.
     * @param type $rawData a stdClass object of raw data from the json sent in
     * javascript.
     * @return \stdClass
     */
    public function AddUpdate($rawData){
        $success = $this->createCategory($rawData);
        $response = new stdClass();
        $response->success = $success;
        $response->values = $rawData;
        return $response;
    }
    
    /**
     * Private helper method to create a category out of the raw data.
     * @param type $rawData
     * @return boolean Indicates whether or not the creation was a success.
     */
    private function createCategory($rawData){
        $mgr = $this->PBAcademyManager;
        try{
            if(!$rawData->update){ //If the rawData indicates its not an update (i.e. new category)...
                $success = $mgr->CreateCategory(
                        $rawData->categoryName, 
                        $rawData->Description, 
                        $rawData->imagePath);
                return $success;
            }else{ //It's an updated category
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
    
    /**
     * Deletes the category indicated by the id.
     * @param type $idToDelete
     * @return \stdClass object with boolean property to indicate success or failure.
     */
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
