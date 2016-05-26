<?php

/**
 * This is the base ajax view. It provides the functionality of sending json responses
 * back from ajax calls.
 * @author jfalkenstein
 */
class BaseAjaxViewMaster extends JViewLegacy{
    public function __construct($config = array()) {
        parent::__construct($config);
    }
    
    /**
     * This receives a single object, encodes it in json, then echos it out immediately.
     * It will then close joomla so that nothing else is printed to the output.
     * @param object $data
     */
    protected function sendJsonResponse($data){
        $doc = JFactory::getDocument();
        $doc->setMimeEncoding('application/json');
        JResponse::setHeader('Content-Disposition','attachment;filename="result.json"');
        echo json_encode($data);
        JFactory::getApplication()->close();
    }
}
