<?php

/**
 * Copyright (c) 2017 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Solid\Collection;

use ArrayIterator;
use ReflectionFunction;
use ReflectionMethod;
use Solid\Support\Arr;
use Traversable;

/**
 * @package Solid\Collection
 * @author Martin Pettersson <martin@solid-framework.com>
 */
class ArrayCollection implements CollectionInterface
{
    /**
     * @var array
     */
    protected $store;

    /**
     * @param array $store
     */
    public function __construct(array $store = [])
    {
        $this->store = $store;
    }

    /**
     * @param mixed $item
     */
    public function add($item): void
    {
        $this->store[] = $item;
    }

    /**
     * @param int|string $key
     * @param mixed $value
     */
    public function set($key, $value): void
    {
        $this->store[$key] = $value;
    }

    /**
     * @param \Solid\Collection\ReadableCollectionInterface $collection
     * @param int|string                                    $key
     * @param boolean                                       $mergeIndexedArrays
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
     * @param int|string $key
     */
    public function remove($key): void
    {
        unset($this->store[$key]);
    }

    public function clear(): void
    {
        $this->store = [];
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->store);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->store;
    }

    /**
     * @param int $amount
     * @return array
     */
    public function take(int $amount): array
    {
        return $this->slice(0, $amount);
    }

    /**
     * @param int $start
     * @param int $amount
     * @return array
     */
    public function slice(int $start, int $amount): array
    {
        return array_slice($this->store, $start, $amount);
    }

    /**
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->store);
    }

    /**
     * @return array
     */
    public function values(): array
    {
        return array_values($this->store);
    }

    /**
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
     * @param int|string $key
     * @return bool
     */
    public function has($key): bool
    {
        return array_key_exists($key, $this->store);
    }

    /**
     * @param mixed $value
     * @param bool  $strict
     * @return bool
     */
    public function contains($value, bool $strict = false): bool
    {
        return array_search($value, $this->values(), $strict) !== false;
    }

    /**
     * @param int|string $key
     * @param mixed      $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->store) ? $this->store[$key] : $default;
    }

    /**
     * @param callable $callback
     * @return \Solid\Collection\ReadableCollectionInterface
     */
    public function map(callable $callback): ReadableCollectionInterface
    {
        $store = [];
        $numberOfParameters = $this->getParameterCount($callback, 2);

        foreach ($this->store as $key => $value) {
            $store[] = $callback(...array_slice([$value, $key], 0, $numberOfParameters));
        }

        return new static($store);
    }

    /**
     * @param callable $callback
     * @return \Solid\Collection\ReadableCollectionInterface
     */
    public function filter(callable $callback): ReadableCollectionInterface
    {
        $store = [];
        $numberOfParameters = $this->getParameterCount($callback, 2);

        foreach ($this->store as $key => $value) {
            if ($callback(...array_slice([$value, $key], 0, $numberOfParameters))) {
                $store[is_numeric($key) ? count($store) : $key] = $value;
            }
        }

        return new static($store);
    }

    /**
     * @param callable $callback
     * @param mixed    $initialValue
     * @return mixed
     */
    public function reduce(callable $callback, $initialValue = null)
    {
        $result = $initialValue;
        $numberOfParameters = $this->getParameterCount($callback, 4);

        foreach ($this->store as $key => $value) {
            $result = $callback(...array_slice([$result, $value, $key, $this], 0, $numberOfParameters));
        }

        return $result;
    }

    /**
     * @param string $glue
     * @return string
     */
    public function join(string $glue): string
    {
        return implode($glue, $this->store);
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->store);
    }

    /**
     * @param callable $callback
     * @param int      $max
     * @return int
     */
    protected function getParameterCount(callable $callback, int $max): int
    {
        $reflection = is_array($callback) ?
            new ReflectionMethod(reset($callback), end($callback)) :
            new ReflectionFunction($callback);
        $numberOfParameters = $reflection->getNumberOfParameters();

        if ($numberOfParameters === 0 || $reflection->isVariadic()) {
            return $max;
        }

        return $numberOfParameters;
    }
}
