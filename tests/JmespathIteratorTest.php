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
        $this->assertEquals($iterator[3], 'qux');
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
    public function it_returns_all_values_when_asterisk_is_passed()
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

    /**
     * @test
     */
    public function it_can_unset_a_value()
    {
        $iterator = new JmespathIterator(['foo', 'bar', 'baz', 'qux', 'quxx']);
        unset($iterator[0]);

        $this->assertEquals(4, count($iterator));

        $this->assertEquals('bar', array_values($iterator->toArray())[0]);
    }

    /**
     * @test
     */
    public function it_can_serialize_the_object()
    {
        $iterator = new JmespathIterator(['foo', 'bar', 'baz', 'qux', 'quxx']);
        $serialized = serialize($iterator);
        $this->assertEquals($iterator, unserialize($serialized));
    }

    /**
     * @test
     */
    public function it_can_be_iterated_over()
    {
        $iterator = new JmespathIterator(['foo', 'bar']);
        foreach ($iterator as $key => $value) {
            $this->assertEquals($iterator[$key], $value);
        }
    }

    /**
     * @test
     */
    public function it_returns_a_jmespath_iterator_when_value_is_an_array_when_accessed_by_key()
    {
        $iterator = new JmespathIterator(['foo' => [
            'bar' => [
                'baz' => 'qux',
            ],
        ]]);

        $this->assertInstanceOf(JmespathIterator::class, $iterator['foo']);
        $this->assertEquals('qux', $iterator['foo']['bar.baz']);
    }

    /**
     * @test
     */
    public function it_returns_a_jmespath_iterator_when_value_is_an_array_when_accessed_by_index()
    {
        $iterator = new JmespathIterator([
            [
                'bar' => [
                    'baz' => 'qux',
                ],
            ],
        ]);

        $this->assertInstanceOf(JmespathIterator::class, $iterator[0]);
        $this->assertEquals('qux', $iterator[0]['bar.baz']);
    }

    /**
     * @test
     */
    public function it_can_check_that_a_numeric_offset_exists()
    {
        $iterator = new JmespathIterator(['foo', 'bar', 'baz']);

        $this->assertTrue(isset($iterator[0]));
        $this->assertFalse(isset($iterator[3]));
    }

    /**
     * @test
     */
    public function it_can_check_that_a_non_numeric_offset_exists()
    {
        $iterator = new JmespathIterator(['foo' => 'bar']);

        $this->assertTrue(isset($iterator['foo']));
        $this->assertFalse(isset($iterator['bar']));
    }

    /**
     * @test
     */
    public function it_can_check_that_a_jmespath_expression_returns_a_result()
    {
        $iterator = new JmespathIterator(['foo' => [
            'bar' => 'baz',
            ],
        ]);

        $this->assertTrue(isset($iterator['foo.bar']));
        $this->assertFalse(isset($iterator['foo.bar.baz']));
    }
}