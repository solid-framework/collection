<?php

/**
 * Copyright (c) 2017 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Solid\Collection\Tests\Fixtures;

use Solid\Collection\Enum;

/**
 * @package Solid\Collection\Tests\Fixtures
 * @author Martin Pettersson <martin@solid-framework.com>
 */
class Status extends Enum
{
    const SUCCESS = 'SUCCESS';
    const WARNING = 'WARNING';
    const ERROR = 1;

    /**
     * @var array
     */
    protected $strings = [
        self::WARNING => 'This is just a warning!'
    ];
}
