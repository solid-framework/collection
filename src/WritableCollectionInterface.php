<?php

/**
 * Copyright (c) 2017 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Solid\Collection;

/**
 * @package Solid\Collection
 * @author Martin Pettersson <martin@solid-framework.com>
 * @since 0.1.0
 */
interface WritableCollectionInterface
{
    /**
     * @api
     * @since 0.1.0
     * @param mixed $item
     */
    public function add($item): void;

    /**
     * @api
     * @since 0.1.0
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key, $value): void;

    /**
     * @api
     * @since 0.1.0
     * @param ReadableCollectionInterface $collection
     * @param int|string                  $key
     * @param bool                        $mergeIndexed
     */
    public function merge(ReadableCollectionInterface $collection, $key = null, bool $mergeIndexed = false): void;

    /**
     * @api
     * @since 0.1.0
     * @param int|string $key
     */
    public function remove($key): void;

    /**
     * @api
     * @since 0.1.0
     */
    public function clear(): void;
}
