<?php

/**
 * Copyright (c) 2017 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Solid\Collection\Tests;

use Solid\Collection\Tests\Fixtures\Status;
use PHPUnit\Framework\TestCase;

/**
 * @package Solid\Collection\Tests
 * @author Martin Pettersson <martin@solid-framework.com>
 * @since 0.1.0
 * @coversDefaultClass Solid\Collection\Enum
 */
class EnumTest extends TestCase
{
    /**
     * @since 0.1.0
     * @test
     * @covers ::__construct
     * @covers ::values
     * @covers ::getConstantFromValue
     */
    public function shouldBeInstantiableWithValidValues(): void
    {
        new Status(Status::SUCCESS);
        new Status(Status::WARNING);
        new Status(Status::ERROR);

        // @note The test passes if it doesn't throw any exceptions.
        $this->assertTrue(true);
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::__construct
     * @expectedException \InvalidArgumentException
     */
    public function shouldThrowExceptionIfInvalidValueIsGiven(): void
    {
        new Status(9);
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::getValue
     */
    public function shouldReturnTheConstantValue(): void
    {
        $statusSuccess = new Status(Status::SUCCESS);
        $statusError = new Status(Status::ERROR);

        $this->assertEquals('SUCCESS', $statusSuccess->getValue());
        $this->assertEquals(1, $statusError->getValue());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::enum
     */
    public function shouldReturnAllConstantNames(): void
    {
        $this->assertEquals(['SUCCESS', 'WARNING', 'ERROR'], Status::enum());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::values
     */
    public function shouldReturnAllConstantValues(): void
    {
        $this->assertEquals(['SUCCESS', 'WARNING', 1], Status::values());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::__toString
     */
    public function shouldReturnConstantNameWhenCastToString(): void
    {
        $statusError = new Status(Status::ERROR);
        $this->assertEquals('ERROR', (string) $statusError);
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::__toString
     */
    public function shouldReturnStringValueIfExistsWhenCastToString(): void
    {
        $statusWarning = new Status(Status::WARNING);
        $this->assertEquals('This is just a warning!', (string) $statusWarning);
    }
}
