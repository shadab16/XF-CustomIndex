<?php

/**
 * New route-prefix class for handling the forum-index.
 *
 * @author Shadab Ansari
 * @package GeekPoint_CustomIndex
 */
class GeekPoint_CustomIndex_Route_Prefix_Forum implements XenForo_Route_Interface
{
	/**
	 * @see XenForo_Route_Interface::match()
	 */
	public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router)
	{
		return $router->getRouteMatch('XenForo_ControllerPublic_Index', $routePath, 'forums');
	}
}