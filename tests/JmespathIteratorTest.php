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
        $iterator = new JmespathIterator(['foo', 'bar', 'baz']);
        $this->assertEquals(3, count($iterator));

    }

    /**
     * @test
     */
    public function it_can_set_an_array_value()
    {
        $iterator = new JmespathIterator(['foo', 'bar', 'baz']);
        $iterator[] = 'qux';
        $this->assertEquals(4, count($iterator));
    }

    /**
     * @test
     */
    public function it_can_retrieve_an_array_value_by_index()
    {
        $iterator = new JmespathIterator(['foo', 'bar', 'baz']);
        $iterator[] = 'qux';
        $this->assertEquals($iterator['3'], 'qux');
    }

    /**
     * @test
     */
    public function it_can_retrieve_an_array_value_by_key()
    {
        $iterator = new JmespathIterator(['foo' => 'bar']);

        $this->assertEquals($iterator['foo'], 'bar');
    }

    /**
     * @test
     */
    public function it_can_retrieve_an_array_value_by_jmespath_expression()
    {
        $iterator = new JmespathIterator(['foo' => [
            'bar' => 'baz',
        ]]);

        $this->assertEquals($iterator['foo.bar'], 'baz');
    }

    /**
     * @test
     */
    public function it_returns_all_values_when_star_is_passed()
    {
        $iterator = new JmespathIterator(['foo', 'bar', 'baz', 'qux', 'quxx']);


        $this->assertEquals(5, count($iterator['*']));
        $this->assertEquals(5, count($iterator['[*]']));
        $this->assertEquals($iterator['*'], $iterator['[*]']);
    }

    /**
     * @test
     */
    public function it_can_slice_an_array()
    {
        $iterator = new JmespathIterator(['foo', 'bar', 'baz', 'qux', 'quxx']);


        $this->assertEquals(2, count($iterator['0:2']));
        $this->assertEquals(2, count($iterator['[0:2]']));
    }

    /**
     * @test
     */
    public function it_can_slice_an_array_when_start_is_omitted()
    {
        $iterator = new JmespathIterator(['foo', 'bar', 'baz', 'qux', 'quxx']);


        $this->assertEquals(2, count($iterator[':2']));
        $this->assertEquals(2, count($iterator['[:2]']));
        $this->assertEquals($iterator[':2'], $iterator['[:2]']);
    }

    /**
     * @test
     */
    public function it_can_slice_an_array_when_stop_is_omitted()
    {
        $iterator = new JmespathIterator(['foo', 'bar', 'baz', 'qux', 'quxx']);

        $this->assertEquals(3, count($iterator['2:']));
        $this->assertEquals(3, count($iterator['[2:]']));
        $this->assertEquals($iterator['2:'], $iterator['[2:]']);
    }

    /**
     * @test
     */
    public function it_can_slice_an_array_when_start_and_stop_are_omitted()
    {
        $iterator = new JmespathIterator(['foo', 'bar', 'baz', 'qux', 'quxx']);

        $this->assertEquals(3, count($iterator['::2']));
        $this->assertEquals(3, count($iterator['[::2]']));

        $this->assertEquals($iterator['::2'], $iterator['[::2]']);
    }

    /**
     * @test
     */
    public function it_can_slice_an_array_when_start_and_stop_are_step_are_included()
    {
        $iterator = new JmespathIterator(['foo', 'bar', 'baz', 'qux', 'quxx']);

        $this->assertEquals(2, count($iterator['1:6:2']));
        $this->assertEquals(2, count($iterator['[1:6:2]']));

        $this->assertEquals($iterator['1:6:2'], $iterator['[1:6:2]']);
    }

    /**
     * @test
     */
    public function it_can_slice_an_array_when_start_are_step_are_included()
    {
        $iterator = new JmespathIterator(['foo', 'bar', 'baz', 'qux', 'quxx']);

        $this->assertEquals(3, count($iterator['0::2']));
        $this->assertEquals(3, count($iterator['[0::2]']));

        $this->assertEquals($iterator['0::2'], $iterator['[0::2]']);
    }
}