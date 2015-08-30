<?php

namespace Icybee\Binding\Core;

/**
 * {@link \ICanBoogie\Core} prototype bindings.
 *
 * This trait is really a collection of bindings defined by the dependencies.
 */
trait CoreBindings
{
	use \ICanBoogie\Module\CoreBindings;
	use \ICanBoogie\Binding\ActiveRecord\CoreBindings;
	use \ICanBoogie\Binding\CLDR\CoreBindings;
	use \ICanBoogie\Binding\I18n\CoreBindings;
}
