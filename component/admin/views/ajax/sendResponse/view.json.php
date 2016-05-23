<?php

class SendResponseView extends BaseAjaxViewMaster
{
    private $response;
    public function __construct($config = array()) {
        $this->response = $config['response'];
        parent::__construct($config);
    }
    
    public function display($tpl = null) {
        $this->sendJsonResponse($this->response);
        parent::display($tpl);
    }
}