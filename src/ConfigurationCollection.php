<?php

/**
 * Copyright (c) 2017 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Solid\Collection;

use Solid\Support\Arr;

/**
 * @package Solid\Collection
 * @author Martin Pettersson <martin@solid-framework.com>
 */
class ConfigurationCollection extends ArrayCollection
{
    /**
     * @param int|string $key
     * @param mixed $value
     */
    public function set($key, $value): void
    {
        $partialStore = &$this->store;

        foreach (explode('.', $key) as $field) {
            if (!Arr::isAssociative($partialStore)) {
                $partialStore = [];
            }

            $partialStore = &$partialStore[$field];
        }

        $partialStore = $value;
    }

    /**
     * @param int|string $key
     * @return bool
     */
    public function has($key): bool
    {
        $partialStore = $this->store;

        foreach (explode('.', $key) as $field) {
            if (!is_array($partialStore) || !array_key_exists($field, $partialStore)) {
                return false;
            }

            $partialStore = $partialStore[$field];
        }

        return true;
    }

    /**
     * @param int|string $key
     * @param mixed      $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $partialStore = $this->store;

        foreach (explode('.', $key) as $field) {
            if (!is_array($partialStore) || !array_key_exists($field, $partialStore)) {
                return $default;
            }

            $partialStore = $partialStore[$field];
        }

        return $partialStore;
    }
}
