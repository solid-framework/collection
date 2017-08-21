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
 */
interface WritableCollectionInterface
{
    /**
     * @param mixed $item
     */
    public function add($item): void;

    /**
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key, $value): void;

    /**
     * @param \Solid\Collection\ReadableCollectionInterface $collection
     * @param int|string                                    $key
     * @param bool                                          $mergeIndexed
     */
    public function merge(ReadableCollectionInterface $collection, $key = null, bool $mergeIndexed = false): void;

    /**
     * @param int|string $key
     */
    public function remove($key): void;

    public function clear(): void;
}
