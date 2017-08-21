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
 */
abstract class Enum
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var array
     */
    protected $strings = [];

    /**
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
     * @return string
     */
    public function __toString(): string
    {
        return (string)array_key_exists($this->getValue(), $this->strings) ?
            $this->strings[$this->getValue()] :
            $this->value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return constant("static::{$this->value}");
    }

    /**
     * @return array
     */
    public static function enum(): array
    {
        return array_keys((new ReflectionClass(static::class))->getConstants());
    }

    /**
     * @return array
     */
    public static function values(): array
    {
        return array_values((new ReflectionClass(static::class))->getConstants());
    }

    /**
     * @param mixed $value
     * @return string
     */
    private static function getConstantFromValue($value): string
    {
        return array_flip((new ReflectionClass(static::class))->getConstants())[$value];
    }
}
