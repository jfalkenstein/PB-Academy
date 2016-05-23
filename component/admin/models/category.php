<?php

/**
 * Description of category
 *
 * @author jfalkenstein
 */
class PbAcademyModelCategory extends BaseAdminModel {
    
    public $Id;
    
    public function __construct($config = array()) {
        if(isset($config['id'])){
            $this->Id = $config['id'];
        }
        parent::__construct($config);
    }
    
    public function getCategory(){
        if(isset($this->Id)){
            return $this->pbAcademyManager->GetCategoryById($this->Id);
        }
        return null;
    }
    
}
