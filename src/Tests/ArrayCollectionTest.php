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
    protected $exampleStore1 = [
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

    protected $exampleStore2 = [
        'key' => 'new value',
        'nested' => [
            'key' => 'new value'
        ],
        'list' => [
            'four'
        ]
    ];

    protected $mergedExampleStore1And2 = [
        'key' => 'new value',
        'nested' => [
            'key' => 'new value'
        ],
        'list' => [
            'four'
        ]
    ];

    protected $mergedExampleStore1And2AtNew = [
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


    /**
     * @since 0.1.0
     * @test
     * @coversNothing
     */
    public function shouldImplementCollectionInterface()
    {
        $this->assertArrayHasKey('Framework\Collection\CollectionInterface', class_implements(ArrayCollection::class));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::__construct
     * @covers ::all
     */
    public function shouldProvideDefaultEmptyStore()
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
    public function shouldAcceptIndexedStore()
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
    public function shouldAcceptAssocStore()
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
    public function shouldAddItemsToTheCollection()
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
    public function shouldSetItemsInTheCollection()
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
    public function shouldClearTheCollection()
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
    public function shouldReturnAValidIterator()
    {
        $store = [
            'one',
            'two',
            'three' => 3
        ];
        $collection = new ArrayCollection($store);

        foreach ($collection as $key => $value) {
            $this->assertEquals($store[$key], $value);
        }
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::count
     */
    public function shouldReturnTheCorrectCount()
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
    public function shouldTakeCorrectAmountOfItems()
    {
        $store = [
            'one' => 1,
            'two',
            'three'
        ];
        $collection = new ArrayCollection($store);

        $one = $collection->take(1);
        $this->assertEquals(['one' => 1], $one);
        $this->assertEquals($store, $collection->take(3));
        $this->assertEmpty($collection->take(0));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::slice
     */
    public function shouldSliceCorrectly()
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
    public function shouldReturnTheCollectionKeys()
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
    public function shouldReturnTheCollectionValues()
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
    public function shouldReturnTheFirstItemValueInTheCollection()
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
    public function shouldDetermineIfAKeyExists()
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
    public function shouldDetermineIfAKeyDoesNotExists()
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
    public function shouldDetermineIfAValueExists()
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
    public function shouldReturnTheValueAtTheGivenKey()
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
    public function shouldReturnNullIfKeyIsNotFound()
    {
        $this->assertNull((new ArrayCollection)->get('three'));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::get
     */
    public function shouldReturnDefaultValueIfGiven()
    {
        $this->assertEquals(3, (new ArrayCollection)->get('three', 3));
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::remove
     */
    public function shouldRemoveTheGivenValue()
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
    public function shouldMergeTheGivenCollection()
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
    public function shouldMergeTheGivenCollectionAtTheGivenKey()
    {
        $collection1 = new ArrayCollection($this->exampleStore1);
        $collection2 = new ArrayCollection($this->exampleStore2);

        $collection1->merge($collection2, 'new');

        $this->assertEquals($this->mergedExampleStore1And2AtNew, $collection1->all());
    }
}
