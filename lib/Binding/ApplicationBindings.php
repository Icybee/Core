<?php

/*
 * This file is part of the Icybee package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Icybee\Binding;

/**
 * {@link \ICanBoogie\Core} prototype bindings.
 *
 * This trait is really a collection of bindings defined by the dependencies.
 */
trait ApplicationBindings
{
	use \ICanBoogie\Module\ApplicationBindings;
	use \ICanBoogie\Binding\Routing\ApplicationBindings;
	use \ICanBoogie\Binding\ActiveRecord\ApplicationBindings;
	use \ICanBoogie\Binding\CLDR\ApplicationBindings;
	use \ICanBoogie\Binding\I18n\ApplicationBindings;
}
