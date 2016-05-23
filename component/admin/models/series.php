<?php

/**
 * Description of series
 *
 * @author jfalkenstein
 */
class PbAcademyModelSeries extends BaseAdminModel {
    public $Id;
    
    
    public function __construct($config = array()) {
        if(isset($config['id'])){
            $this->Id = $config['id'];
        }
        parent::__construct($config);
    }
    
    
    public function getSeries(){
        if(isset($this->Id)){
            return $this->pbAcademyManager->GetSeries($this->Id);
        }
        return null;
    }
    
    
    
}
