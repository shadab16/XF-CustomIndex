<?php

class GeekPoint_CustomIndex_Addon
{
	public static function initDependencies(XenForo_Dependencies_Abstract $dependencies, array $data)
	{
		if (!$dependencies instanceof XenForo_Dependencies_Public)
		{
			return;
		}

		/*$config = new Zend_Config(array(
			'routePrefix'     => 'pages',
			'controllerClass' => 'XenForo_ControllerPublic_Page',
			'params'          => array('node_name' => 'lectus-pretium-consequat'),
		));*/

		/*$config = new Zend_Config(array(
			'routePrefix'     => 'portal',
			'controllerClass' => 'EWRporta_ControllerPublic_Portal',
			'majorSection'    => 'portal'
		));*/

		$config = new Zend_Config(array(
			'routePrefix'     => 'recent-activity',
			'controllerClass' => 'XenForo_ControllerPublic_RecentActivity',
			'majorSection'    => 'home'
		));

		/*$config = new Zend_Config(array(
			'routePrefix'     => 'threads',
			'controllerClass' => 'XenForo_ControllerPublic_Thread',
			'majorSection'    => 'home',
			'params'          => array('thread_id' => 4)
		));*/

		GeekPoint_CustomIndex_Helper::setDefaultRoute($config, $data);
	}
}