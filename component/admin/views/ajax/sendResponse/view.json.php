<?php

/**
 * This class is a view class, specifically intended to send a single object in
 * a json response. This object is pulled from the configArray, set by the varous controllers.
 */
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