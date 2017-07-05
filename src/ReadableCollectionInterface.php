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
 * @since 0.1.0
 */
interface ReadableCollectionInterface extends IteratorAggregate
{
    /**
     * @api
     * @since 0.1.0
     * @return int
     */
    public function count(): int;

    /**
     * @api
     * @since 0.1.0
     * @return array
     */
    public function all(): array;

    /**
     * @api
     * @since 0.1.0
     * @param int $amount
     * @return array
     */
    public function take(int $amount): array;

    /**
     * @api
     * @since 0.1.0
     * @param int $start
     * @param int $amount
     * @return array
     */
    public function slice(int $start, int $amount): array;

    /**
     * @api
     * @since 0.1.0
     * @return array
     */
    public function keys(): array;

    /**
     * @api
     * @since 0.1.0
     * @return array
     */
    public function values(): array;

    /**
     * @api
     * @since 0.1.0
     * @return mixed
     */
    public function first();

    /**
     * @api
     * @since 0.1.0
     * @param int|string $key
     * @return bool
     */
    public function has($key): bool;

    /**
     * @api
     * @since 0.1.0
     * @param mixed $value
     * @param bool  $strict
     * @return bool
     */
    public function contains($value, bool $strict): bool;

    /**
     * @api
     * @since 0.1.0
     * @param callable $callback
     * @return ReadableCollectionInterface
     */
    public function map(callable $callback): ReadableCollectionInterface;

    /**
     * @api
     * @since 0.1.0
     * @param callable $callback
     * @return ReadableCollectionInterface
     */
    public function filter(callable $callback): ReadableCollectionInterface;

    /**
     * @api
     * @since 0.1.0
     * @param callable $callback
     * @param mixed    $initialValue
     * @return mixed
     */
    public function reduce(callable $callback, $initialValue);

    /**
     * @api
     * @since 0.1.0
     * @param string $glue
     * @return string
     */
    public function join(string $glue): string;

    /**
     * @api
     * @since 0.1.0
     * @param int|string $key
     * @param mixed      $default
     * @return mixed
     */
    public function get($key, $default);
}
