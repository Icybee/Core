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

use ICanBoogie\ActiveRecord;
use ICanBoogie\ActiveRecord\Model;
use ICanBoogie\ActiveRecord\ModelCollection;
use ICanBoogie\ActiveRecord\Query;
use ICanBoogie\ActiveRecord\RecordNotFound;
use Icybee\Binding\PrototypedBindings;

/**
 * This is the super class for all models using the _constructor_ model (currently "nodes" and "users").
 * It provides support for the `constructor` property whether it is for saving records or
 * filtering them through the `own` scope.
 */
abstract class ConstructorModel extends Model
{
	use PrototypedBindings;

	const CONSTRUCTOR = 'constructor';

	protected $constructor;

	/**
	 * @inheritdoc
	 *
	 * @throws \Exception if {@link CONSTRUCTOR} is not defined.
	 */
	public function __construct(ModelCollection $models, array $attributes)
	{
		if (empty($attributes[self::CONSTRUCTOR]))
		{
			throw new \Exception('The CONSTRUCTOR tag is required');
		}

		$this->constructor = $attributes[self::CONSTRUCTOR];

		parent::__construct($models, $attributes);
	}

	/**
	 * @inheritdoc
	 *
	 * Overwrites the `constructor` property of new records.
	 */
	public function save(array $properties, $key = null, array $options = [])
	{
		if (!$key && empty($properties[self::CONSTRUCTOR]))
		{
			$properties[self::CONSTRUCTOR] = $this->constructor;
		}

		return parent::save($properties, $key, $options);
	}

	/**
	 * @inheritdoc
	 *
	 * Makes sure that records are found using their true constructor.
	 */
	public function find($key)
	{
		$record = call_user_func_array('parent::' . __FUNCTION__, func_get_args());

		if ($record instanceof ActiveRecord)
		{
			$record_model = $this->models[$record->constructor];

			if ($this !== $record_model)
			{
				$record = $record_model[$key];
			}
		}

		return $record;
	}

	/**
	 * Finds records using their constructor.
	 *
	 * Unlike {@link find()} this method is designed to find records that where created by
	 * different constructors. The result is the same, bu where {@link find()} uses a new request
	 * for each record that is not created by the current model, this method only needs one query
	 * by constructor plus one extra query.
	 *
	 * @param array $keys
	 *
	 * @throws RecordNotFound If a record was not found.
	 *
	 * @return array
	 */
	public function find_using_constructor(array $keys)
	{
		if (!$keys)
		{
			return [];
		}

		$records = array_combine($keys, array_fill(0, count($keys), null));
		$missing = $records;

		$constructors = $this
		->select('constructor, {primary}')
		->where([ '{primary}' => $keys ])
		->all(\PDO::FETCH_COLUMN | \PDO::FETCH_GROUP);

		foreach ($constructors as $constructor => $constructor_keys)
		{
			try
			{
				$constructor_records = $this->models[$constructor]->find($constructor_keys);

				foreach ($constructor_records as $key => $record)
				{
					$records[$key] = $record;
					unset($missing[$key]);
				}
			}
			catch (RecordNotFound $e)
			{
				foreach ($e->records as $key => $record)
				{
					if ($record === null)
					{
						continue;
					}

					$records[$key] = $record;
					unset($missing[$key]);
				}
			}
		}

		if ($missing)
		{
			if (count($missing) > 1)
			{
				throw new RecordNotFound
				(
					"Records " . implode(', ', array_keys($missing)) . " do not exists.", $records
				);
			}
			else
			{
				$key = array_keys($missing);
				$key = array_shift($key);

				throw new RecordNotFound
				(
					"Record <q>{$key}</q> does not exists.", $records
				);
			}
		}

		return $records;
	}

	/**
	 * Adds the "constructor = <constructor>" condition to the query.
	 *
	 * @param Query $query The query to alter.
	 *
	 * @return Query
	 */
	protected function scope_own(Query $query)
	{
		return $query->filter_by_constructor($this->constructor);
	}
}
