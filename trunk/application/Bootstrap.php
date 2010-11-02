<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initRegistry()
	{
		if ($this->hasPluginResource('Log'))
			Zend_Registry::set('log', $this->getResource('Log'));
                    Zend_Registry::set('Config', $this->getOptions());
	}

	protected function _initView()
	{
		$this->bootstrap('layout');
		$view = $this->getResource('layout')->getView();
		$view->addHelperPath( 'Recipe/View/Helper/', 'Recipe_View_Helper' );
		// $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');

		//$container = new Zend_Navigation($config);
		//$view->navigation($container);
	}
	
	protected function _initAcl()
	{
		$frontController = Zend_Controller_Front::getInstance();
		require_once 'Recipe/Plugin/Acl.php';
		$frontController->registerPlugin(new Recipe_Plugin_Acl($acl));
	}

}

