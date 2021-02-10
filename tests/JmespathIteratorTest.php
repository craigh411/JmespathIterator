<?php

use Humps\Jmespath\JmespathIterator;
use PHPUnit\Framework\TestCase;

class JmespathIteratorTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_count_the_array()
    {
        $iterator = new JmespathIterator(['foo','bar','baz']);
        $this->assertEquals(3, count($iterator));

    }
}