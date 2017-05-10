<?php

/**
 * Copyright (c) 2017 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Framework\Collection\Tests;

use Framework\Collection\ConfigurationCollection;
use PHPUnit\Framework\TestCase;

/**
 * @package Framework\Collection\Tests
 * @author Martin Pettersson <martin@framework.com>
 * @since 0.1.0
 * @coversDefaultClass Framework\Collection\ConfigurationCollection
 */
class ConfigurationCollectionTest extends TestCase
{
    /**
     * @since 0.1.0
     * @test
     * @coversNothing
     */
    public function shouldImplementCollectionInterface(): void
    {
        $this->assertArrayHasKey('Framework\Collection\CollectionInterface', class_implements(ConfigurationCollection::class));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::set
     */
    public function shouldSetTheGivenNestedKey(): void
    {
        $collection = new ConfigurationCollection;

        $collection->set('key', 'value');
        $collection->set('nested.key', 'value');
        $collection->set('deeply.nested.key', 'value');

        $resultingStore = [
            'key' => 'value',
            'nested' => [
                'key' => 'value'
            ],
            'deeply' => [
                'nested' => [
                    'key' => 'value'
                ]
            ]
        ];

        $this->assertEquals($resultingStore, $collection->all());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::has
     */
    public function shouldBeAbleToTellIfANestedKeyExists(): void
    {
        $collection = new ConfigurationCollection([
            'key' => 'value',
            'nested' => [
                'key' => 'value'
            ],
            'deeply' => [
                'nested' => [
                    'key' => 'value'
                ]
            ]
        ]);

        $this->assertTrue($collection->has('key'));
        $this->assertTrue($collection->has('nested.key'));
        $this->assertTrue($collection->has('deeply.nested.key'));

        $this->assertFalse($collection->has('nokey'));
        $this->assertFalse($collection->has('nested.nokey'));
        $this->assertFalse($collection->has('deeply.nested.nokey'));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::get
     */
    public function shouldReturnNestedValue(): void
    {
        $collection = new ConfigurationCollection([
            'key' => 'value',
            'nested' => [
                'key' => 'value'
            ],
            'deeply' => [
                'nested' => [
                    'key' => 'value'
                ]
            ]
        ]);

        $this->assertEquals('value', $collection->get('key'));
        $this->assertEquals('value', $collection->get('nested.key'));
        $this->assertEquals('value', $collection->get('deeply.nested.key'));
        $this->assertEquals(['key' => 'value'], $collection->get('nested'));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::get
     */
    public function shouldReturnNullIfKeyIsNotFound(): void
    {
        $collection = new ConfigurationCollection;

        $this->assertNull($collection->get('nokey'));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::get
     */
    public function shouldReturnGivenDefaultValueIfKeyIsNotFound(): void
    {
        $collection = new ConfigurationCollection;

        $this->assertEquals('value', $collection->get('nokey', 'value'));
    }
}
