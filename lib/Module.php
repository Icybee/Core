<?php

/*
 * This file is part of the Icybee package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Icybee;

use ICanBoogie\HTTP\AuthenticationRequired;
use ICanBoogie\HTTP\SecurityError;
use ICanBoogie\I18n;
use ICanBoogie\Module\Descriptor;

/**
 * Extends the Module class with the following features:
 *
 * - Special handling for the 'edit', 'new' and 'configure' blocks.
 * - Inter-users edit lock on records.
 *
 * @property-read \ICanBoogie\Core|\Icybee\Binding\CoreBindings $app
 */
class Module extends \ICanBoogie\Module
{
	const OPERATION_CONFIG = 'config';

	public function getBlock($name)
	{
		$args = func_get_args();

		$class_name = $this->resolve_block_class($name);

		if ($class_name)
		{
			array_shift($args);

			I18n::push_scope($this->flat_id);
			I18n::push_scope($this->flat_id . '.' . $name);

			try
			{
				$block = new $class_name($this, [], $args);

// 				$rendered_block = $block->render();
			}
			catch (SecurityError $e)
			{
				I18n::pop_scope();
				I18n::pop_scope();

				throw $e;
			}
			catch (\Exception $e)
			{
				$block = \ICanBoogie\Debug::format_alert($e);
			}

			I18n::pop_scope();
			I18n::pop_scope();

			return $block;
		}

// 		\ICanBoogie\log_info("Block class not found for <q>$name</q> falling to callbacks.");

		return call_user_func_array('parent::' . __FUNCTION__, $args);
	}

	protected function resolve_block_class($name)
	{
		$module = $this;
		$class_name = \ICanBoogie\camelize(\ICanBoogie\underscore($name)) . 'Block';

		while ($module)
		{
			$try = $module->descriptor[Descriptor::NS] . '\\' . $class_name;

			if (class_exists($try, true))
			{
				return $try;
			}

			$module = $module->parent;
		}
	}

	private function create_activerecord_lock_name($key)
	{
		return "activerecord_locks.$this->flat_id.$key";
	}

	/**
	 * Locks an activerecord.
	 *
	 * @param int $key
	 * @param null $lock
	 *
	 * @return array|false
	 *
	 * @throws AuthenticationRequired
	 */
	public function lock_entry($key, &$lock = null)
	{
		$user_id = $this->app->user_id;

		if (!$user_id)
		{
			throw new AuthenticationRequired;
		}

		if (!$key)
		{
			throw new \InvalidArgumentException('The record key is required.');
		}

		#
		# is the node already locked by another user ?
		#
		$registry = $this->app->registry;

		$lock_name = $this->create_activerecord_lock_name($key);
		$lock = json_decode($registry[$lock_name], true);
		$lock_uid = $user_id;
		$lock_until = null;

		$now = time();
		$until = date('Y-m-d H:i:s', $now + 2 * 60);

		if ($lock)
		{
			$lock_uid = $lock['uid'];
			$lock_until = $lock['until'];

			if ($now > strtotime($lock_until))
			{
				#
				# Because the lock has expired we can claim it.
				#

				$lock_uid = $user_id;
			}
			else if ($lock_uid != $user_id)
			{
				return false;
			}
		}

		$lock = [

			'uid' => $lock_uid,
			'until' => $until

		];

		$registry[$lock_name] = json_encode($lock);

		return true;
	}

	public function unlock_entry($key)
	{
		$registry = $this->app->registry;

		$lock_name = $this->create_activerecord_lock_name($key);
		$lock = json_decode($registry[$lock_name], true);

		if (!$lock)
		{
			return null;
		}

		if ($lock['uid'] != $this->app->user_id)
		{
			return false;
		}

		unset($registry[$lock_name]);

		return true;
	}
}
