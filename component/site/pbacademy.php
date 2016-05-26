<?php
//error_reporting(E_ALL);

/**
 * This the application root for the joomla component. It is called by Joomla
 * whenever our page is visited. It's primary purpose is to find the method to call
 * on the controller object. 
 */
defined('_JEXEC') or die("Restricted Access");

//Define the location of the PB library as a constant.
define('PB_ACADEMY_LIB', JPATH_LIBRARIES . '/pbacademylib/PbAcademy');

//Load the base view and model.
require_once 'views/sharedViewResources/BaseViewMaster.php';
require_once 'models/BaseModel.php';

//Force joomla to load jQuery and Bootstrap NOW rather than later, so jQuery is available
//whenever the component first loads.
JHtml::_('bootstrap.framework');

$app = JFactory::getApplication();
$doc = JFactory::getDocument();

//Add the stylesheets and scripts used by PB Academy.
$doc->addStyleSheet('/components/com_pbacademy/css/pbacademy.css');
$doc->addScript('/components/com_pbacademy/js/pbacademy.js');
$doc->addStyleSheet('/media/jui/css/icomoon.css');

$input = $app->input;
//Get the controller for this component.
$controller = JControllerLegacy::getInstance('PbAcademy');
//Get the section (i.e. controller method to call)
$controller->execute($input->getCmd('section','home'));

//This isn't used by the PB Academy, but it's included for future use, if desired.
$controller->redirect();