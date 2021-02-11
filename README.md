# JmespathIterator

JmespathIterator allows you to access a PHP array using [Jmespath](https://jmespath.org/) just like you would a standard array.

## Install

You can install JmespathIterator via comopser:

`composer require craigh/jmespath-iterator`

## Usage

Create a new JmespathIterator object, then pass the Jmespath expression as the array key

### Basic Example

```php

use Humps\Jmespath\JmespathIterator;

$iterator = new JmespathIterator([
  'foo' => [
    'bar' => [
      'baz' => 'qux',
    ],
  ],
]);

echo $iterator['foo.bar.baz']; // output: 'qux'
```

### List Projection Example

```php
use Humps\Jmespath\JmespathIterator;

$iterator = new JmespathIterator([
  'people' =>
    [
      [
        'first' => 'James',
        'last' => 'd',
      ],
      [
        'first' => 'Jacob',
        'last' => 'e',
      ],
      [
        'first' => 'Jayden',
        'last' => 'f',
      ],
      [
        'missing' => 'different',
       ],
    ],
    'foo' =>
      [
        'bar' => 'baz',
      ],
]);

var_dump($iterator['people[*].first']); // output: ["James", "Jacob", "Jayden"]
        
```

### Every Level is a JmespathIterator

JmespathIterator always returns arrays as JmespathIterator objects, which means you can query nested arrays using Jmespath:


```php
$iterator = new JmespathIterator([
  [
    'foo' => [
      'bar' => 'qux',
    ],
  ],
]);

echo $iterator[0]['foo.bar']; // output: 'qux'
```

And iterate over it just like a standard array

```php
use Humps\Jmespath\JmespathIterator;

$iterator = new JmespathIterator([
  [
    'bar' => [
      'baz' => 'qux',
    ],
  ],
  [
    'bar' => [
      'baz' => 'qux',
    ],
  ],
]);

if(count($iterator)){
  foreach ($iterator as $value) {
    echo $value['bar.baz'];
  }
}
```

### Add Items like An Array

Jmespath implements the `ArrayAccess` interface, so you can add array values just as you usually would:

```php
use Humps\Jmespath\JmespathIterator;

$iterator = new JmespathIterator();
$iterator[] = 'foo';
$iterator[] = 'bar';

echo $iterator[1] // output: 'bar';
```

### Slicing

To make things cleaner, you don't need to wrap slice expressions in square brackets, but you can if you want:

```
use Humps\Jmespath\JmespathIterator;

$iterator = new JmespathIterator(['foo','bar','baz','qux', 'qux']);

var_dump($iterator['0::2']); // outputs: ['foo', 'baz', 'qux']
var_dump($iterator['[0::2]']); // outputs: ['foo', 'baz', 'qux']
```

### Remember: It's Not Actually An Array!


While a JmespathIterator object might feel like an array, it's not; but if you need it to be you can use the `toArray()` method

```
use Humps\Jmespath\JmespathIterator;

$iterator = new JmespathIterator(['foo','bar','baz','qux', 'quxx']);
$array = $iterator->toArray();
natsort($array);
$newIterator = new JmespathIterator($array); 
var_dump($newIterator); // outputs: ['bar', 'baz','foo', 'qux', 'quxx']
```






