<?php

/**
 * Copyright (c) 2017 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Framework\Collection;

use ArrayIterator;
use Framework\Support\Arr;
use Traversable;

/**
 * @package Framework\Collection
 * @author Martin Pettersson <martin@framework.com>
 * @since 0.1.0
 */
class ArrayCollection implements CollectionInterface
{
    /**
     * @since 0.1.0
     * @var array
     */
    protected $store;

    /**
     * @api
     * @since 0.1.0
     * @param array $store
     */
    public function __construct(array $store = [])
    {
        $this->store = $store;
    }

    /**
     * @api
     * @since 0.1.0
     * @param mixed $item
     */
    public function add($item): void
    {
        $this->store[] = $item;
    }

    /**
     * @api
     * @since 0.1.0
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key, $value): void
    {
        $this->store[$key] = $value;
    }

    /**
     * @api
     * @since 0.1.0
     * @param ReadableCollectionInterface $collection
     * @param int|string                  $key
     * @param boolean                     $mergeIndexedArrays
     */
    public function merge(ReadableCollectionInterface $collection, $key = null, bool $mergeIndexedArrays = false): void
    {
        $mergeBase = !is_null($key) ? $this->get($key, []) : $this->all();
        $mergedArray = Arr::merge($mergeBase, $collection->all(), $mergeIndexedArrays);

        if (!is_null($key)) {
            $this->set($key, $mergedArray);
        } else {
            $this->store = $mergedArray;
        }
    }

    /**
     * @api
     * @since 0.1.0
     * @param int|string $key
     */
    public function remove($key): void
    {
        unset($this->store[$key]);
    }

    /**
     * @api
     * @since 0.1.0
     */
    public function clear(): void
    {
        $this->store = [];
    }

    /**
     * @api
     * @since 0.1.0
     * @return int
     */
    public function count(): int
    {
        return count($this->store);
    }

    /**
     * @api
     * @since 0.1.0
     * @return array
     */
    public function all(): array
    {
        return $this->store;
    }

    /**
     * @api
     * @since 0.1.0
     * @param int $amount
     * @return array
     */
    public function take(int $amount): array
    {
        return $this->slice(0, $amount);
    }

    /**
     * @api
     * @since 0.1.0
     * @param int $start
     * @param int $amount
     * @return array
     */
    public function slice(int $start, int $amount): array
    {
        return array_slice($this->store, $start, $amount);
    }

    /**
     * @api
     * @since 0.1.0
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->store);
    }

    /**
     * @api
     * @since 0.1.0
     * @return array
     */
    public function values(): array
    {
        return array_values($this->store);
    }

    /**
     * @api
     * @since 0.1.0
     * @return mixed
     */
    public function first()
    {
        if ($this->count() > 0) {
            return reset($this->store);
        }

        return null;
    }

    /**
     * @api
     * @since 0.1.0
     * @param int|string $key
     * @return bool
     */
    public function has($key): bool
    {
        return array_key_exists($key, $this->store);
    }

    /**
     * @api
     * @since 0.1.0
     * @param mixed $value
     * @param bool  $strict
     * @return bool
     */
    public function contains($value, bool $strict = false): bool
    {
        return array_search($value, $this->values(), $strict) !== false;
    }

    /**
     * @api
     * @since 0.1.0
     * @param int|string $key
     * @param mixed      $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->store) ? $this->store[$key] : $default;
    }

    /**
     * @api
     * @since 0.1.0
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->store);
    }
}
