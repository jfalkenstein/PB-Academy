<?php

/**
 * The model for the addEditSeries view.
 *
 * @author jfalkenstein
 */
class PbAcademyModelSeries extends BaseAdminModel {
    public $Id;
    
    /**
     * Obtains the id for the series, on construction, if available, from the
     * config array (set by the controller).
     * @param type $config
     */
    public function __construct($config = array()) {
        if(isset($config['id'])){
            $this->Id = $config['id'];
        }
        parent::__construct($config);
    }
    
    /**
     * Gets the series identified by the ID, if it exists. If not, it returns null.
     * @return type
     */
    public function getSeries(){
        if(isset($this->Id)){
            return $this->pbAcademyManager->GetSeries($this->Id);
        }
        return null;
    }
    
    
    
}
