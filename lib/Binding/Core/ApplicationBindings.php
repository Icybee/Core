<?php

namespace Icybee\Binding\Core;

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
