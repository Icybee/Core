<?php

/*
 * This file is part of the Icybee package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Icybee\Routing;

use ICanBoogie\HTTP\Request;

/**
 * Makes admin route definitions.
 */
class RouteMaker extends \ICanBoogie\Routing\RouteMaker
{
	const ACTION_CONFIRM_DELETE = 'confirm-delete';
	const ACTION_CONFIG = 'config';

	const ADMIN_PREFIX = 'admin:';

	static public function admin($module_id, $controller, array $options = [])
	{
		$options = static::normalize_options($options);
		$actions = array_merge(static::get_admin_actions(), $options['actions']);
		$actions = static::filter_actions($actions, $options);

		$routes = [];

		foreach (static::actions($module_id, $controller, $actions, $options) as $id => $route)
		{
			$as = self::ADMIN_PREFIX . $route['as'];

			$route['pattern'] = '/admin' . $route['pattern'];
			$route['as'] = $as;
			$route['module'] = $module_id;

			$routes[$as] = $route;
		}

		return $routes;
	}

	static protected function get_admin_actions()
	{
		return array_merge(static::get_resource_actions(), [

			self::ACTION_CONFIRM_DELETE => [ '/{name}/{id}/delete', Request::METHOD_GET ],
			self::ACTION_CONFIG => [ '/{name}/config', Request::METHOD_GET ]

		]);
	}
}
