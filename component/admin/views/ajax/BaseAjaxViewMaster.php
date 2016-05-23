<?php

/**
 * Description of BaseAjaxViewMaster
 *
 * @author jfalkenstein
 */
class BaseAjaxViewMaster extends JViewLegacy{
    public function __construct($config = array()) {
        parent::__construct($config);
    }
    protected function sendJsonResponse($data){
        $doc = JFactory::getDocument();
        $doc->setMimeEncoding('application/json');
        JResponse::setHeader('Content-Disposition','attachment;filename="result.json"');
        echo json_encode($data);
        JFactory::getApplication()->close();
    }
}
