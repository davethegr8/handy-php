<?php


use PHPUnit\Framework\TestCase;

/**
 */
class lowerTest extends TestCase
{
    public function testLowerCase()
    {
        $this->assertEquals(lower('LOWER'), 'lower');
    }
}

