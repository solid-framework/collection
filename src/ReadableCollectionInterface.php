<?php

/**
 * Copyright (c) 2017 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Solid\Collection;

use IteratorAggregate;

/**
 * @package Solid\Collection
 * @author Martin Pettersson <martin@solid-framework.com>
 */
interface ReadableCollectionInterface extends IteratorAggregate
{
    /**
     * @return int
     */
    public function count(): int;

    /**
     * @return array
     */
    public function all(): array;

    /**
     * @param int $amount
     * @return array
     */
    public function take(int $amount): array;

    /**
     * @param int $start
     * @param int $amount
     * @return array
     */
    public function slice(int $start, int $amount): array;

    /**
     * @return array
     */
    public function keys(): array;

    /**
     * @return array
     */
    public function values(): array;

    /**
     * @return mixed
     */
    public function first();

    /**
     * @param int|string $key
     * @return bool
     */
    public function has($key): bool;

    /**
     * @param mixed $value
     * @param bool  $strict
     * @return bool
     */
    public function contains($value, bool $strict): bool;

    /**
     * @param callable $callback
     * @return \Solid\Collection\ReadableCollectionInterface
     */
    public function map(callable $callback): ReadableCollectionInterface;

    /**
     * @param callable $callback
     * @return \Solid\Collection\ReadableCollectionInterface
     */
    public function filter(callable $callback): ReadableCollectionInterface;

    /**
     * @param callable $callback
     * @param mixed    $initialValue
     * @return mixed
     */
    public function reduce(callable $callback, $initialValue);

    /**
     * @param string $glue
     * @return string
     */
    public function join(string $glue): string;

    /**
     * @param int|string $key
     * @param mixed      $default
     * @return mixed
     */
    public function get($key, $default);
}
