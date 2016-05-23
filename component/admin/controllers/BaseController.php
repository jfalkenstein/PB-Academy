<?php

/**
 * Description of BaseController
 *
 * @author jfalkenstein
 */
class BaseController extends JControllerLegacy {
    
    protected $configArray = [];
    public function __construct($config = array()) {
        $this->configArray = $config;
        parent::__construct($config);
    }
    
    /**
     * This function replaces Joomla's "getModel" function. This is more specific to
     * the present design patterna dn doesn't rely on Joomla's mysterious conventions.
     * @param string $modelName
     * @param string $className
     * @return JModelLegacy
     */
    public function getNamedModel($modelName, $className = null, $ajax= true){
        require_once __DIR__ . '/../models/' . (($ajax) ? 'ajax/' : '') . $modelName . '.php';
        $class = (($className) ? $className :  ucfirst($modelName) . 'Model');
        return new $class($this->configArray);
    }
    
    /**
     * This function replaces Joomla's "getView" function. This is more specific to
     * the present design pattern and doesn't rely on Joomla's mysterious conventions.
     * @param string $viewName
     * @param string $className
     * @return JViewLegacy
     */
    public function getNamedView($viewName, $className = null, $ajax = true, $format = 'json'){
        require_once __DIR__ . '/../views/' . (($ajax) ? 'ajax/' : '') . $viewName . '/view.' . $format . '.php';
        $class = (($className) ? $className : ucfirst($viewName) . 'View');
        return new $class($this->configArray);
    }
}
