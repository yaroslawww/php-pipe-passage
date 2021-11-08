# Alternative implementation 'Chain of Responsibility' pattern, using pipe.

[![Packagist License](https://img.shields.io/packagist/l/yaroslawww/php-pipe-passage?color=%234dc71f)](https://github.com/yaroslawww/php-pipe-passage/blob/master/LICENSE.md)
[![Packagist Version](https://img.shields.io/packagist/v/yaroslawww/php-pipe-passage)](https://packagist.org/packages/yaroslawww/php-pipe-passage)
[![Build Status](https://scrutinizer-ci.com/g/yaroslawww/php-pipe-passage/badges/build.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/php-pipe-passage/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/yaroslawww/php-pipe-passage/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/php-pipe-passage/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yaroslawww/php-pipe-passage/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/php-pipe-passage/?branch=master)



## Installation

Install the package via composer:

```bash
composer require yaroslawww/php-pipe-passage
```

## Usage

```injectablephp
 $pipe = Pipe::make([
    function ($entity, \Closure $next) {
        $entity->test = 'test value';

        return $next($entity);
    },
    function ($entity, \Closure $next) {
        $entity->test2 = 'second test value';

        return $next($entity);
    },
])
            ->next(SetNameHandler::class)
            ->next(new SetCompanyHandler('web company'))
            ->next(function ($entity, \Closure $next) {
                $entity->test3 = 'third test value';

                return $next($entity);
            });

$entityObject = $pipe->pass(new \stdClass());

$this->assertEquals('test value', $entityObject->test);
$this->assertEquals('second test value', $entityObject->test2);
$this->assertEquals('third test value', $entityObject->test3);
$this->assertEquals('default', $entityObject->name);
$this->assertEquals('web company', $entityObject->company);
```

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/)
