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
use ICanBoogie\Routing\Route;
use ICanBoogie\Routing\RouteMaker as Make;

/**
 * Makes admin route definitions.
 */
class RouteMaker extends \ICanBoogie\Routing\RouteMaker
{
	public const ACTION_CONFIRM_DELETE = 'confirm-delete';
	public const ACTION_CONFIG = 'config';

	public const ADMIN_PREFIX = 'admin:';

	public static function admin($module_id, $controller, array $options = [])
	{
		$options = static::normalize_options($options);
		$actions = array_merge(static::get_admin_actions(), $options['actions']);
		$actions = static::filter_actions($actions, $options);

		$routes = [];

		foreach (static::actions($module_id, $controller, $actions, $options) as $route)
		{
			$id = self::ADMIN_PREFIX . $route->id;

//			$route[RouteDefinition::ID] = $id;
//			$route[RouteDefinition::PATTERN] = '/admin' . $route[RouteDefinition::PATTERN];
//			$route[RouteDefinition::MODULE] = $module_id;

			$routes[$id] = new Route(pattern: '/admin' . $route->pattern, action: 'admin:' . $route->action);
		}

		return $routes;
	}

	private static function get_admin_actions()
	{
		return array_merge(static::get_resource_actions(), [

			#
			# FIXME: "POST" is added to NEW and EDIT methods because "save" operation is currently
			# posted to the same route. We'll be able to remove this once the operation
			# controller has been revised.
			#
			self::ACTION_NEW            => [ '/{name}/new', [ Request::METHOD_GET, Request::METHOD_POST ] ],
			self::ACTION_EDIT           => [ '/{name}/{id}/edit', [ Request::METHOD_GET, Request::METHOD_POST ] ],
			self::ACTION_CONFIRM_DELETE => [ '/{name}/{id}/delete', Request::METHOD_GET ],
			self::ACTION_CONFIG         => [ '/{name}/config', Request::METHOD_GET ]

		]);
	}
}
