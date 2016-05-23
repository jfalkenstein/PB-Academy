<?php
//error_reporting(E_ALL);
defined('_JEXEC') or die("Restricted Access");

define('PB_ACADEMY_LIB', JPATH_LIBRARIES . '/pbacademylib/PbAcademy');
require_once 'views/sharedViewResources/BaseAdminViewMaster.php';
require_once 'views/ajax/BaseAjaxViewMaster.php';
require_once 'models/ajax/BaseAjaxAdminModel.php';
require_once 'models/BaseAdminModel.php';
require_once 'controllers/BaseController.php';

JHtml::_('bootstrap.framework');

$app = JFactory::getApplication();

$input = $app->input;
$subController = getSubConroller($input);
if(is_null($subController)){
    $controller = JControllerLegacy::getInstance('PbAcademy');
    $controller->execute($input->getCmd('section','adminHome'));
    $controller->redirect();
}else{
    /* @var $subController JControllerLegacy */
    $subController->execute($input->getCmd('action'));
    $subController->redirect();
}


function getSubConroller(&$input){
    $name = $input->getCmd('controller',null);
    if($name){
        $path = __DIR__ . '/controllers/' . $name . 'Controller.php'; 
        if(file_exists($path)){
            require_once $path;
            $class = $name . 'Controller';
            if(class_exists($class)){
                return new $class();     
        }   
    }
    return null;
    }
}