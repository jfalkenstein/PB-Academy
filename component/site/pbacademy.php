<?php
error_reporting(E_ALL);
defined('_JEXEC') or die("Restricted Access");

define('PB_ACADEMY_LIB', JPATH_LIBRARIES . '/pbacademylib/PbAcademy');

require_once 'views/sharedViewResources/BaseViewMaster.php';
require_once 'models/BaseModel.php';
JHtml::_('bootstrap.framework');

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$doc->addStyleSheet('/components/com_pbacademy/css/pbacademy.css');
$doc->addScript('/components/com_pbacademy/js/pbacademy.js');
$doc->addStyleSheet('/media/jui/css/icomoon.css');
$input = $app->input;
$controller = JControllerLegacy::getInstance('PbAcademy');
$controller->execute($input->getCmd('section','home'));
$controller->redirect();