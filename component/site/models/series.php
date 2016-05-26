<?php

/**
 * The model for the individual series view.
 * @property Category $ThisCategory The specific category assigned by the controller.
 */
class PbAcademyModelSeries extends BaseModel
{
    private $seriesId;
    private $ThisSeries;
    
    public function __construct($config = array()) {
        $this->seriesId = $config['seriesID'];
        parent::__construct($config);
    }
    
    public function getThisSeries(){
        if(!isset($this->ThisSeries)){
            $this->ThisSeries = $this->pbAcademyManager->GetSeries($this->seriesId);
        }
        return $this->ThisSeries;
    }
}
