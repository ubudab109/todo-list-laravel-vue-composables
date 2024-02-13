<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $array1 = [
            'test' => 'test',
        ];
        $array2 = [
            'test' => 'test',
        ];
        $this->assertEqualsCanonicalizing($array2, $array1);
    }
}
