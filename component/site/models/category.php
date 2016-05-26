<?php

/**
 * This is the model for the single category page. 
 * @property Category $ThisCategory The specific category assigned by the controller.
 */
class PbAcademyModelCategory extends BaseModel
{
    private $catId;
    private $ThisCategory;
    
    public function __construct($config = array()) {
        //Pull category ID from the config array (set by the controller). 
        $this->catId = $config['catID'];
        parent::__construct($config);
    }
    
    public function getCategory(){
        if(!isset($this->ThisCategory)){
            $this->ThisCategory = $this->pbAcademyManager->GetCategoryById($this->catId);
        }
        return $this->ThisCategory;
    }
}
