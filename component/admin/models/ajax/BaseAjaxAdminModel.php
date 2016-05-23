<?php

/**
 * Description of BaseAjaxAdminModel
 * @property PBAcademyManager $PBAcademyManager
 * @author jfalkenstein
 */
class BaseAjaxAdminModel extends JModelLegacy {
    protected $PBAcademyManager;
    
    public function __construct($config = array()) {
        $this->PBAcademyManager = PBAcademyManager::GetInstance();
        parent::__construct($config);
    }
    
}
