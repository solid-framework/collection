<?php

/**
 * Copyright (c) 2017 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Framework\Collection;

use Framework\Support\Arr;

/**
 * @package Framework\Collection
 * @author Martin Pettersson <martin@framework.com>
 * @since 0.1.0
 */
class ConfigurationCollection extends ArrayCollection
{
    /**
     * @api
     * @since 0.1.0
     * @param mixed $key
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
     * @api
     * @since 0.1.0
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
     * @api
     * @since 0.1.0
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
