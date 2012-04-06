<?php

/**
 * Helper class to set a custom home route, by shifting
 * the forum listing to a dedicated route-prefix: /forum/.
 *
 * @author Shadab Ansari
 * @package GeekPoint_CustomIndex
 */
class GeekPoint_CustomIndex_Helper
{
	/**
	 * Sets the default route according to the configuration data provided in $config.
	 * Two settings are required: the route prefix (key: routePrefix),
	 * and the controller class-name (key: controllerClass).
	 *
	 * Three optional settings are supported as well: major section (key: majorSection),
	 * minor section (key: minorSection) and request parameters (key: params);
	 * which might be used during the routing process.
	 *
	 * @param Zend_Config $config Configuration data
	 * @param array $dependencyData Data array supplied by the "init_dependencies" event
	 * @throws XenForo_Exception
	 */
	public static function setDefaultRoute(Zend_Config $config, array $dependencyData)
	{
		$routesPublic = $dependencyData['routesPublic'];

		if (!$config->routePrefix || !$config->controllerClass)
		{
			// Debugging message. No need for phrasing.
			throw new XenForo_Exception('Missing route-prefix and/or controller class-name.');
		}

		if ($config->readOnly())
		{
			// A read-only object was passed. Arghh!
			$newConfig = new Zend_Config(
				array('routeClass' => $routesPublic[$config->routePrefix]['route_class']),
				true
			);

			$config = $newConfig->merge($config);
		}
		else
		{
			$config->routeClass = $routesPublic[$config->routePrefix]['route_class'];
		}

		self::_setCustomRoutePrefixes($config->routePrefix, $routesPublic);

		$config->setReadOnly();
		XenForo_Application::set('customIndex', $config);
	}

	/**
	 * Internal method for doing the actual swapping/shifting of route prefixes.
	 *
	 * @param string $defaultRoutePrefix
	 * @param array $routesPublic
	 */
	protected static function _setCustomRoutePrefixes($defaultRoutePrefix, array $routesPublic)
	{
		$routesPublic['index'] = array(
			'build_link'  => 'all',
			'route_class' => 'GeekPoint_CustomIndex_Route_Prefix_Index'
		);

		$routesPublic['forum'] = array(
			'build_link'  => 'none',
			'route_class' => 'GeekPoint_CustomIndex_Route_Prefix_Forum'
		);

		$routesPublic[$defaultRoutePrefix]['build_link'] = 'all';

		XenForo_Link::setHandlerInfoForGroup('public', $routesPublic);

		XenForo_CodeEvent::addListener('load_class_route_prefix', array(__CLASS__, 'loadClassRoutePrefix'));
	}

	/**
	 * Event listener for the "load_class_route_prefix" code-event
	 * to dynamically extend route-prefix classes.
	 *
	 * @param string $class
	 * @param array $extend
	 */
	public static function loadClassRoutePrefix($class, array &$extend)
	{
		switch ($class)
		{
			case XenForo_Application::get('customIndex')->routeClass:

				$extend[] = 'GeekPoint_CustomIndex_Route_Prefix_Custom';
				break;

			case 'XenForo_Route_Prefix_Categories':

				if (!XenForo_Application::get('options')->categoryOwnPage)
				{
					$extend[] = 'GeekPoint_CustomIndex_Route_Prefix_Categories';
				}
				break;

			default:
				break;
		}
	}
}