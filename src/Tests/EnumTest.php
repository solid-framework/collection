<?php

/**
 * Copyright (c) 2017 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Framework\Collection\Tests;

use Framework\Collection\Tests\Fixtures\Status;
use PHPUnit\Framework\TestCase;

/**
 * @package Framework\Collection\Tests
 * @author Martin Pettersson <martin@framework.com>
 * @since 0.1.0
 * @coversDefaultClass Framework\Collection\Enum
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
    public function shouldBeInstantiableWithValidValues()
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
    public function shouldThrowExceptionIfInvalidValueIsGiven()
    {
        new Status(9);
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::getValue
     */
    public function shouldReturnTheConstantValue()
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
    public function shouldReturnAllConstantNames()
    {
        $this->assertEquals(['SUCCESS', 'WARNING', 'ERROR'], Status::enum());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::values
     */
    public function shouldReturnAllConstantValues()
    {
        $this->assertEquals(['SUCCESS', 'WARNING', 1], Status::values());
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::__toString
     */
    public function shouldReturnConstantNameWhenCastToString()
    {
        $statusError = new Status(Status::ERROR);
        $this->assertEquals('ERROR', (string) $statusError);
    }

    /**
     * @since 0.1.0
     * @test
     * @covers ::__toString
     */
    public function shouldReturnStringValueIfExistsWhenCastToString()
    {
        $statusWarning = new Status(Status::WARNING);
        $this->assertEquals('This is just a warning!', (string) $statusWarning);
    }
}
