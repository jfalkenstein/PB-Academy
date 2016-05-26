<?php

/*
 * This script is the application root for the PB Academy admin interface. It's primary
 * function is to determine the action to call in the controller from the URL.
 * 
 * This app root overrides default Joomla implementation for AJAX calls, instead selecting
 * a "subcontroller," if a controller is specified within the url.
 */

//error_reporting(E_ALL);

//Prevent direct file access outside the Joomla app root.
defined('_JEXEC') or die("Restricted Access");

//Define the location of the PB academy library as a constant.
define('PB_ACADEMY_LIB', JPATH_LIBRARIES . '/pbacademylib/PbAcademy');

//Require the necessary base classes to be used later in the execution
require_once 'views/sharedViewResources/BaseAdminViewMaster.php';
require_once 'views/ajax/BaseAjaxViewMaster.php';
require_once 'models/ajax/BaseAjaxAdminModel.php';
require_once 'models/BaseAdminModel.php';
require_once 'controllers/BaseController.php';

/* Force Joomla to bootstrap the javascript frameworks NOW rather than later. This
 * allows Bootstrap and jQuery to be called on load; else, they are not available at load
 * time. */
JHtml::_('bootstrap.framework');

$app = JFactory::getApplication();
$input = $app->input;
$subController = getSubConroller($input);

if(is_null($subController)){//If there is no subcontroller, use default controller selection.
    //This gets the controller in controller.php
    $controller = JControllerLegacy::getInstance('PbAcademy');
    //Calls the method specified by the 'section' value in the url string. If no
    //section is specified, call the adminHome method.
    $controller->execute($input->getCmd('section','adminHome'));
    //This isn't used in the PB Academy admin, but recommended by Joomla in the event it is used.
    $controller->redirect();
}else{ //If there IS a subcontroller specified...
    //Calls the method specified by the 'action' value in the url string.
    $subController->execute($input->getCmd('action'));
    $subController->redirect();
}

/**
 * This will return a subcontroller specified by GET or POST data, if it exists.
 * @param JInput $input
 * @return \class
 */
function getSubConroller(&$input){
    //Attempt to get a controller specifed in the input.
    $name = $input->getCmd('controller',null);
    if($name){ //If a controller was found...
        //Create the path for the require.
        $path = __DIR__ . '/controllers/' . $name . 'Controller.php';
        if(file_exists($path)){ //If that path actually exists...
            //Require the file.
            require_once $path;
            //Create the name of the class that should be in the controller.
            $class = $name . 'Controller';
            if(class_exists($class)){ //If that class actually exists...
                //Return instance of that class
                return new $class();     
        }   
    }
    //If the controller file and/or class doesn't exist, return null.
    return null;
    }
}