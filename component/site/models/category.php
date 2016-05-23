<?php

/**
 * @property Category $ThisCategory The specific category assigned by the controller.
 */
class PbAcademyModelCategory extends BaseModel
{
    private $catId;
    private $ThisCategory;
    
    public function __construct($config = array()) {
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
