<?php

/**
 * Copyright (c) 2017 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Framework\Collection\Tests;

use Framework\Collection\ArrayCollection;
use PHPUnit\Framework\TestCase;

/**
 * @package Framework\Collection\Tests
 * @author Martin Pettersson <martin@framework.com>
 * @since 0.1.0
 * @coversDefaultClass Framework\Collection\ArrayCollection
 */
class ArrayCollectionTest extends TestCase
{
    /**
     * @since 0.1.0
     * @var array
     */
    protected $exampleStore1;

    /**
     * @since 0.1.0
     * @var array
     */
    protected $exampleStore2;

    /**
     * @since 0.1.0
     * @var array
     */
    protected $mergedExampleStore1And2;

    /**
     * @since 0.1.0
     * @var array
     */
    protected $mergedExampleStore1And2Indexed;

    /**
     * @since 0.1.0
     * @var array
     */
    protected $mergedExampleStore1And2AtNew;

    /**
     * @since 0.1.0
     * @before
     */
    public function setup(): void
    {
        $this->exampleStore1 = [
            'key' => 'value',
            'nested' => [
                'key' => 'value'
            ],
            'list' => [
                'one',
                'two',
                'three'
            ]
        ];

        $this->exampleStore2 = [
            'key' => 'new value',
            'nested' => [
                'key' => 'new value'
            ],
            'list' => [
                'four'
            ]
        ];

        $this->mergedExampleStore1And2 = [
            'key' => 'new value',
            'nested' => [
                'key' => 'new value'
            ],
            'list' => [
                'four'
            ]
        ];

        $this->mergedExampleStore1And2Indexed = [
            'key' => 'new value',
            'nested' => [
                'key' => 'new value'
            ],
            'list' => [
                'one',
                'two',
                'three',
                'four'
            ]
        ];

        $this->mergedExampleStore1And2AtNew = [
            'key' => 'value',
            'nested' => [
                'key' => 'value'
            ],
            'list' => [
                'one',
                'two',
                'three'
            ],
            'new' => [
                'key' => 'new value',
                'nested' => [
                    'key' => 'new value'
                ],
                'list' => [
                    'four'
                ]
            ]
        ];
    }

    /**
     * @since 0.1.0
     * @test
     * @coversNothing
     */
    public function shouldImplementCollectionInterface(): void
    {
        $this->assertArrayHasKey('Framework\Collection\CollectionInterface', class_implements(ArrayCollection::class));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::__construct
     * @covers ::all
     */
    public function shouldProvideDefaultEmptyStore(): void
    {
        $collection = new ArrayCollection;

        $this->assertEmpty($collection->all());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::__construct
     * @covers ::all
     */
    public function shouldAcceptIndexedStore(): void
    {
        $store = ['one', 'two', 'three'];
        $collection = new ArrayCollection($store);

        $this->assertEquals($store, $collection->all());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::__construct
     * @covers ::all
     */
    public function shouldAcceptAssocStore(): void
    {
        $store = [
            'one' => 1,
            'two' => 2,
            'three' => 3
        ];
        $collection = new ArrayCollection($store);

        $this->assertEquals($store, $collection->all());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::add
     * @covers ::all
     */
    public function shouldAddItemsToTheCollection(): void
    {
        $collection = new ArrayCollection;

        $this->assertEmpty($collection->all());

        // Indexed values
        $collection->add('item 1');
        $this->assertCount(1, $collection->all());
        $this->assertContains('item 1', $collection->all());

        $collection->add('item 2');
        $this->assertCount(2, $collection->all());
        $this->assertContains('item 2', $collection->all());

        // Associative values
        $collection->add(['assoc' => 'assoc-item']);
        $this->assertCount(3, $collection->all());
        $this->assertContains(['assoc' => 'assoc-item'], $collection->all());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::set
     * @covers ::all
     */
    public function shouldSetItemsInTheCollection(): void
    {
        $collection = new ArrayCollection([
            'one',
            'two',
            'three' => 3
        ]);

        $collection->set(0, 1);
        $this->assertCount(3, $collection->all());
        $this->assertContains(1, $collection->all());

        $collection->set('three', 'three');
        $this->assertCount(3, $collection->all());
        $this->assertContains('three', $collection->all());

        $collection->set('four', 'four');
        $this->assertCount(4, $collection->all());
        $this->assertContains('four', $collection->all());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::clear
     * @covers ::all
     */
    public function shouldClearTheCollection(): void
    {
        $collection = new ArrayCollection(['item']);

        $this->assertNotEmpty($collection->all());

        $collection->clear();

        $this->assertEmpty($collection->all());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::getIterator
     */
    public function shouldReturnAValidIterator(): void
    {
        $store = [
            'one',
            'two',
            'three' => 3
        ];
        $collection = new ArrayCollection($store);
        $result = [];

        foreach ($collection as $key => $value) {
            $result[$key] = $value;
        }

        $this->assertEquals($store, $result);
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::count
     */
    public function shouldReturnTheCorrectCount(): void
    {
        $store = [
            'one',
            'two',
            'three' => 3
        ];
        $collection = new ArrayCollection($store);

        $this->assertEquals(3, $collection->count());
        $this->assertEquals(0, (new ArrayCollection)->count());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::take
     * @covers ::slice
     */
    public function shouldTakeCorrectAmountOfItems(): void
    {
        $store = [
            'one' => 1,
            'two',
            'three'
        ];
        $collection = new ArrayCollection($store);

        $this->assertEquals(['one' => 1], $collection->take(1));
        $this->assertEquals($store, $collection->take(3));
        $this->assertEmpty($collection->take(0));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::slice
     */
    public function shouldSliceCorrectly(): void
    {
        $store = [
            'one' => 1,
            'two',
            'three'
        ];
        $collection = new ArrayCollection($store);

        $this->assertEquals(['one' => 1], $collection->slice(0, 1));
        $this->assertEquals(['two'], $collection->slice(1, 1));
        $this->assertEquals(['three'], $collection->slice(2, 3));
        $this->assertEquals($store, $collection->slice(0, 5));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::keys
     */
    public function shouldReturnTheCollectionKeys(): void
    {
        $store = [
            'one' => 1,
            'two',
            'three'
        ];
        $collection = new ArrayCollection($store);

        $this->assertEquals(array_keys($store), $collection->keys());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::values
     */
    public function shouldReturnTheCollectionValues(): void
    {
        $store = [
            'one' => 1,
            'two',
            'three'
        ];
        $collection = new ArrayCollection($store);

        $this->assertEquals(array_values($store), $collection->values());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::first
     */
    public function shouldReturnTheFirstItemValueInTheCollection(): void
    {
        $collection = new ArrayCollection([
            'one' => 1,
            'two'
        ]);

        $this->assertEquals(1, $collection->first());
        $this->assertNull((new ArrayCollection)->first());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::has
     */
    public function shouldDetermineIfAKeyExists(): void
    {
        $collection = new ArrayCollection([
            'one' => 1,
            'two'
        ]);

        $this->assertTrue($collection->has('one'));
        $this->assertTrue($collection->has(0));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::has
     */
    public function shouldDetermineIfAKeyDoesNotExists(): void
    {
        $collection = new ArrayCollection([
            'one' => 1,
            'two'
        ]);

        $this->assertFalse($collection->has('two'));
        $this->assertFalse($collection->has(2));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::contains
     */
    public function shouldDetermineIfAValueExists(): void
    {
        $collection = new ArrayCollection([
            'one' => 1,
            'two'
        ]);

        $this->assertTrue($collection->contains(1));
        $this->assertTrue($collection->contains('two'));
        $this->assertFalse($collection->contains('one'));

        // @note If we don't compare with the strict flag we get true here, is it a bug?
        //       This is true for in_array and array_search (without strict flag).
        //       ex: in_array(0, ['some string']) === true
        $this->assertFalse($collection->contains(0, true));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::get
     */
    public function shouldReturnTheValueAtTheGivenKey(): void
    {
        $store = [
            'one' => 1,
            'two'
        ];

        $collection = new ArrayCollection($store);

        $this->assertEquals(1, $collection->get('one'));
        $this->assertEquals('two', $collection->get(0));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::get
     */
    public function shouldReturnNullIfKeyIsNotFound(): void
    {
        $this->assertNull((new ArrayCollection)->get('three'));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::get
     */
    public function shouldReturnDefaultValueIfGiven(): void
    {
        $this->assertEquals(3, (new ArrayCollection)->get('three', 3));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::remove
     */
    public function shouldRemoveTheGivenValue(): void
    {
        $collection = new ArrayCollection(['key' => 'value']);
        $collection->remove('key');

        $this->assertNull($collection->get('key'));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::merge
     */
    public function shouldMergeTheGivenCollection(): void
    {
        $collection1 = new ArrayCollection($this->exampleStore1);
        $collection2 = new ArrayCollection($this->exampleStore2);

        $collection1->merge($collection2);

        $this->assertEquals($this->mergedExampleStore1And2, $collection1->all());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::merge
     */
    public function shouldMergeIndexedArrays(): void
    {
        $collection1 = new ArrayCollection($this->exampleStore1);
        $collection2 = new ArrayCollection($this->exampleStore2);

        $collection1->merge($collection2, null, true);

        $this->assertEquals($this->mergedExampleStore1And2Indexed, $collection1->all());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::merge
     */
    public function shouldMergeTheGivenCollectionAtTheGivenKey(): void
    {
        $collection1 = new ArrayCollection($this->exampleStore1);
        $collection2 = new ArrayCollection($this->exampleStore2);

        $collection1->merge($collection2, 'new');

        $this->assertEquals($this->mergedExampleStore1And2AtNew, $collection1->all());
    }
}
