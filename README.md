# Icybee's Core

[![Release](https://img.shields.io/packagist/v/icybee/core.svg)](https://github.com/Icybee/Core/releases)
[![Build Status](https://img.shields.io/travis/Icybee/Core/master.svg)](http://travis-ci.org/Icybee/Core)
[![HHVM](https://img.shields.io/hhvm/icybee/core.svg)](http://hhvm.h4cc.de/package/icybee/core)
[![Code Quality](https://img.shields.io/scrutinizer/g/Icybee/Core/master.svg)](https://scrutinizer-ci.com/g/Icybee/Core)
[![Code Coverage](https://img.shields.io/coveralls/Icybee/Core/master.svg)](https://coveralls.io/r/Icybee/Core)
[![Packagist](https://img.shields.io/packagist/dt/icybee/core.svg)](https://packagist.org/packages/icybee/core)

Icybee's core classes.





----------





## Requirements

The package requires PHP 5.5 or later.





## Installation

The recommended way to install this package is through [Composer](http://getcomposer.org/).
Create a `composer.json` file and run `php composer.phar install` command to install it:

```
$ composer require icybee/core
```





### Cloning the repository

The package is [available on GitHub](https://github.com/Icybee/Core), its repository can
be cloned with the following command line:

	$ git clone https://github.com/Icybee/Core.git





## Documentation

The package is documented as part of the [Icybee](http://icybee.org/) CMS
[documentation](http://icybee.org/docs/). The documentation for the package and its
dependencies can be generated with the `make doc` command. The documentation is generated in
the `docs` directory using [ApiGen](http://apigen.org/). The package directory can later by
cleaned with the `make clean` command.





## Testing

The test suite is ran with the `make test` command. [PHPUnit](https://phpunit.de/) and [Composer](http://getcomposer.org/) need to be globally available to run the suite. The command installs dependencies as required. The `make test-coverage` command runs test suite and also creates an HTML coverage report in "build/coverage". The directory can later be cleaned with the `make clean` command.

The package is continuously tested by [Travis CI](http://about.travis-ci.org/).

[![Build Status](https://img.shields.io/travis/Icybee/Core/master.svg)](https://travis-ci.org/Icybee/Core)
[![Code Coverage](https://img.shields.io/coveralls/Icybee/Core/master.svg)](https://coveralls.io/r/Icybee/Core)





## License

**Icybee/Core** is licensed under the New BSD License - See the LICENSE file for details.
