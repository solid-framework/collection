<?php

/**
 * Copyright (c) 2017 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Solid\Collection\Tests;

use Solid\Collection\ArrayCollection;
use Solid\Collection\ReadableCollectionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @package Solid\Collection\Tests
 * @author Martin Pettersson <martin@solid-framework.com>
 * @coversDefaultClass Solid\Collection\ArrayCollection
 */
class ArrayCollectionTest extends TestCase
{
    /**
     * @var array
     */
    protected $exampleStore1;

    /**
     * @var array
     */
    protected $exampleStore2;

    /**
     * @var array
     */
    protected $mergedExampleStore1And2;

    /**
     * @var array
     */
    protected $mergedExampleStore1And2Indexed;

    /**
     * @var array
     */
    protected $mergedExampleStore1And2AtNew;

    /**
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
     * @test
     * @coversNothing
     */
    public function shouldImplementCollectionInterface(): void
    {
        $this->assertArrayHasKey('Solid\Collection\CollectionInterface', class_implements(ArrayCollection::class));
    }

    /**
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
     * @test
     * @covers ::get
     */
    public function shouldReturnNullIfKeyIsNotFound(): void
    {
        $this->assertNull((new ArrayCollection)->get('three'));
    }

    /**
     * @test
     * @covers ::get
     */
    public function shouldReturnDefaultValueIfGiven(): void
    {
        $this->assertEquals(3, (new ArrayCollection)->get('three', 3));
    }

    /**
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

    /**
     * @test
     * @covers ::map
     * @covers ::getParameterCount
     */
    public function mapShouldPassCorrectParametersToCallback(): void
    {
        $collection = new ArrayCollection(['item-1', 'item-2', 'item' => 3]);

        $mapMock = $this->getMockBuilder('stdClass')
            ->setMethods(['map'])
            ->getMock();

        $mapMock->expects($this->exactly(3))
            ->method('map')
            ->withConsecutive(
                [$this->equalTo('item-1'), $this->equalTo(0)],
                [$this->equalTo('item-2'), $this->equalTo(1)],
                [$this->equalTo(3), $this->equalTo('item')]
            );

        $collection->map([$mapMock, 'map']);
    }

    /**
     * @test
     * @covers ::map
     * @covers ::getParameterCount
     */
    public function mapShouldAllowCallbacksWithSingleParameter(): void
    {
        $collection = new ArrayCollection(['item-1']);

        $collection->map(function ($item) {
            $this->assertSame('item-1', $item);
        });
    }

    /**
     * @test
     * @covers ::map
     * @covers ::getParameterCount
     */
    public function mapShouldAllowVariadicCallbacks(): void
    {
        $collection = new ArrayCollection(['item-1']);

        $collection->map(function (...$parameters) {
            $this->assertEquals(['item-1', 0], $parameters);
        });

        $collection->map(function ($item, ...$parameters) {
            $this->assertSame('item-1', $item);
            $this->assertEquals([0], $parameters);
        });
    }

    /**
     * @test
     * @covers ::map
     * @covers ::getParameterCount
     */
    public function mapShouldAllowCallbacksWithNoParameters(): void
    {
        $collection = new ArrayCollection(['item-1']);

        $collection->map(function () {
            $this->assertEquals(['item-1', 0], func_get_args());
        });
    }

    /**
     * @test
     * @covers ::map
     * @covers ::getParameterCount
     */
    public function mapShouldReturnNewModifiedCollection(): void
    {
        $collection = new ArrayCollection(['Item 1', 'Item 2', 'Item 3']);

        $this->assertEquals(['item 1', 'item 2', 'item 3'], $collection->map('strtolower')->all());
    }

    /**
     * @test
     * @covers ::map
     * @covers ::getParameterCount
     */
    public function mapShouldNotMutateTheOriginalCollection(): void
    {
        $store = ['Item 1', 'Item 2', 'Item 3'];
        $collection = new ArrayCollection($store);
        $collection->map('strtolower');

        $this->assertEquals($store, $collection->all());
    }

    /**
     * @test
     * @covers ::filter
     * @covers ::getParameterCount
     */
    public function filterShouldPassCorrectParametersToCallback(): void
    {
        $collection = new ArrayCollection(['item-1', 'item-2', 'item' => 3]);

        $filterMock = $this->getMockBuilder('stdClass')
            ->setMethods(['filter'])
            ->getMock();

        $filterMock->expects($this->exactly(3))
            ->method('filter')
            ->withConsecutive(
                [$this->equalTo('item-1'), $this->equalTo(0)],
                [$this->equalTo('item-2'), $this->equalTo(1)],
                [$this->equalTo(3), $this->equalTo('item')]
            );

        $collection->filter([$filterMock, 'filter']);
    }

    /**
     * @test
     * @covers ::filter
     * @covers ::getParameterCount
     */
    public function filterShouldAllowCallbacksWithSingleParameter(): void
    {
        $collection = new ArrayCollection(['item-1']);

        $collection->filter(function ($item) {
            $this->assertSame('item-1', $item);
        });
    }

    /**
     * @test
     * @covers ::filter
     * @covers ::getParameterCount
     */
    public function filterShouldAllowVariadicCallbacks(): void
    {
        $collection = new ArrayCollection(['item-1']);

        $collection->filter(function (...$parameters) {
            $this->assertEquals(['item-1', 0], $parameters);
        });

        $collection->filter(function ($item, ...$parameters) {
            $this->assertSame('item-1', $item);
            $this->assertEquals([0], $parameters);
        });
    }

    /**
     * @test
     * @covers ::filter
     * @covers ::getParameterCount
     */
    public function filterShouldAllowCallbacksWithNoParameters(): void
    {
        $collection = new ArrayCollection(['item-1']);

        $collection->filter(function () {
            $this->assertEquals(['item-1', 0], func_get_args());
        });
    }

    /**
     * @test
     * @covers ::filter
     * @covers ::getParameterCount
     */
    public function filterShouldReturnNewModifiedCollection(): void
    {
        $collection = new ArrayCollection(['one', '2', 'three', '4']);

        $this->assertEquals(['2', '4'], $collection->filter('is_numeric')->all());
        $this->assertEmpty($collection->filter('is_array')->all());
    }

    /**
     * @test
     * @covers ::filter
     * @covers ::getParameterCount
     */
    public function filterShouldNotMutateTheOriginalCollection(): void
    {
        $store = ['Item 1', 'Item 2', 'Item 3'];
        $collection = new ArrayCollection($store);
        $collection->filter('is_array');

        $this->assertEquals($store, $collection->all());
    }

    /**
     * @test
     * @covers ::reduce
     * @covers ::getParameterCount
     */
    public function reduceShouldPassCorrectParametersToCallback(): void
    {
        $collection = new ArrayCollection(['item-1', 'item-2', 'item' => 3]);

        $reduceMock = $this->getMockBuilder('stdClass')
            ->setMethods(['reduce'])
            ->getMock();

        $reduceMock->expects($this->exactly(3))
            ->method('reduce')
            ->withConsecutive(
                [$this->equalTo(null), $this->equalTo('item-1'), $this->equalTo(0), $this->equalTo($collection)],
                [$this->equalTo(null), $this->equalTo('item-2'), $this->equalTo(1), $this->equalTo($collection)],
                [$this->equalTo(null), $this->equalTo(3), $this->equalTo('item'), $this->equalTo($collection)]
            );

        $collection->reduce([$reduceMock, 'reduce']);
    }

    /**
     * @test
     * @covers ::reduce
     * @covers ::getParameterCount
     */
    public function reduceShouldAllowCallbacksWithSingleParameter(): void
    {
        $collection = new ArrayCollection(['item-1']);

        $collection->reduce('is_array');

        // Succeeds if no errors are thrown.
        $this->assertTrue(true);
    }

    /**
     * @test
     * @covers ::reduce
     * @covers ::getParameterCount
     */
    public function reduceShouldAllowVariadicCallbacks(): void
    {
        $collection = new ArrayCollection(['item-1']);

        $collection->reduce(function (...$parameters) use ($collection) {
            $this->assertEquals([null, 'item-1', 0, $collection], $parameters);
        });

        $collection->reduce(function ($accumulator, $item, ...$parameters) use ($collection) {
            $this->assertNull($accumulator);
            $this->assertSame('item-1', $item);
            $this->assertEquals([0, $collection], $parameters);
        });
    }

    /**
     * @test
     * @covers ::reduce
     * @covers ::getParameterCount
     */
    public function reduceShouldAllowCallbacksWithNoParameters(): void
    {
        $collection = new ArrayCollection(['item-1']);

        $collection->reduce(function () use ($collection) {
            $this->assertEquals([null, 'item-1', 0, $collection], func_get_args());
        });
    }

    /**
     * @test
     * @covers ::reduce
     * @covers ::getParameterCount
     */
    public function reduceShouldReturnAccumulatedResult(): void
    {
        $collection = new ArrayCollection(['one', 'two', 'three']);

        $this->assertFalse($collection->reduce('is_array'));
        $this->assertEquals(
            'one, two and three',
            $collection->reduce(function (
                string $sentence,
                string $word,
                int $i,
                ReadableCollectionInterface $collection
            ) {
                if ($i === 0) {
                    $sentence .= $word;
                } elseif ($i === $collection->count() - 1) {
                    $sentence .= " and {$word}";
                } else {
                    $sentence .= ", {$word}";
                }

                return $sentence;
            }, '')
        );
    }

    /**
     * @test
     * @covers ::reduce
     * @covers ::getParameterCount
     */
    public function reduceShouldNotMutateTheOriginalCollection(): void
    {
        $store = ['one', 'two', 'three'];
        $collection = new ArrayCollection($store);
        $collection->reduce('is_array');

        $this->assertEquals($store, $collection->all());
    }

    /**
     * @test
     * @covers ::join
     */
    public function shouldReturnJoinedString(): void
    {
        $collection = new ArrayCollection(['one', 'two', 'three']);

        $this->assertSame('one, two, three', $collection->join(', '));
    }
}
