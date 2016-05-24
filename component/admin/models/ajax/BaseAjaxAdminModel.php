<?php

/**
 * This is the base model for the various ajax models. It provides basic access
 * to the PBAcademyManager.
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
