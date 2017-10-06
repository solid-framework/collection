<?php

/**
 * Copyright (c) 2017 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Solid\Collection\Tests;

use Solid\Collection\ConfigurationCollection;
use PHPUnit\Framework\TestCase;

/**
 * @package Solid\Collection\Tests
 * @author Martin Pettersson <martin@solid-framework.com>
 * @coversDefaultClass Solid\Collection\ConfigurationCollection
 */
class ConfigurationCollectionTest extends TestCase
{
    /**
     * @test
     * @coversNothing
     */
    public function shouldImplementCollectionInterface(): void
    {
        $this->assertArrayHasKey(
            'Solid\Collection\CollectionInterface',
            class_implements(ConfigurationCollection::class)
        );
    }

    /**
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
     * @test
     * @covers ::get
     */
    public function shouldReturnNullIfKeyIsNotFound(): void
    {
        $collection = new ConfigurationCollection;

        $this->assertNull($collection->get('nokey'));
    }

    /**
     * @test
     * @covers ::get
     */
    public function shouldReturnGivenDefaultValueIfKeyIsNotFound(): void
    {
        $collection = new ConfigurationCollection;

        $this->assertEquals('value', $collection->get('nokey', 'value'));
    }
}
