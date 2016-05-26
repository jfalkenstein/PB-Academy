<?php

/**
 * The model for the addEditCategory view.
 *
 * @author jfalkenstein
 */
class PbAcademyModelCategory extends BaseAdminModel {
    
    public $Id;
    
    /**
     * On construction, obtains the id (if available) for the present category.
     * @param type $config
     */
    public function __construct($config = array()) {
        if(isset($config['id'])){
            $this->Id = $config['id'];
        }
        parent::__construct($config);
    }
    
    /**
     * If there is a category for this particular view, this will obtain it. Otherwise,
     * it will return null.
     * @return Category
     */
    public function getCategory(){
        if(isset($this->Id)){
            return $this->pbAcademyManager->GetCategoryById($this->Id);
        }
        return null;
    }
    
}
