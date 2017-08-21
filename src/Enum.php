<?php

/**
 * Copyright (c) 2017 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Solid\Collection;

use InvalidArgumentException;
use ReflectionClass;

/**
 * @package Solid\Collection
 * @author Martin Pettersson <martin@solid-framework.com>
 * @since 0.1.0
 */
abstract class Enum
{
    /**
     * @since 0.1.0
     * @var mixed
     */
    protected $value;

    /**
     * @since 0.1.0
     * @var array
     */
    protected $strings = [];

    /**
     * @api
     * @since 0.1.0
     * @param mixed $value
     */
    public function __construct($value)
    {
        if (!in_array($value, static::values())) {
            throw new InvalidArgumentException('Invalid value for enum ' . static::class . ": {$value}");
        }

        $this->value = self::getConstantFromValue($value);
    }

    /**
     * @api
     * @since 0.1.0
     * @return string
     */
    public function __toString(): string
    {
        return (string)array_key_exists($this->getValue(), $this->strings) ?
            $this->strings[$this->getValue()] :
            $this->value;
    }

    /**
     * @api
     * @since 0.1.0
     * @return mixed
     */
    public function getValue()
    {
        return constant("static::{$this->value}");
    }

    /**
     * @api
     * @since 0.1.0
     * @return array
     */
    public static function enum(): array
    {
        return array_keys((new ReflectionClass(static::class))->getConstants());
    }

    /**
     * @api
     * @since 0.1.0
     * @return array
     */
    public static function values(): array
    {
        return array_values((new ReflectionClass(static::class))->getConstants());
    }

    /**
     * @api
     * @since 0.1.0
     * @param mixed $value
     * @return string
     */
    private static function getConstantFromValue($value): string
    {
        return array_flip((new ReflectionClass(static::class))->getConstants())[$value];
    }
}
